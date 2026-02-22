<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Permintaan;
use App\Models\PermintaanDetail;
use App\Models\MasterProduk;
use App\Models\MasterDriver;
use App\Models\Cabang;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PermintaanController extends Controller
{
    public function index(Request $request)
    {
        $kategori = $request->query('kategori'); // Removed default 'BB'
        $query = Permintaan::with(['cabang', 'user']); // Kept Permintaan, assuming PermintaanBarang was a typo

        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_request', 'like', "%{$search}%")
                  ->orWhereHas('cabang', function($cq) use ($search) {
                      $cq->where('nama', 'like', "%{$search}%");
                  })
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Branch-level roles only see their own branch's requests
        if (auth()->user()->isBranchLevel()) {
            $query->where('cabang_id', auth()->user()->cabang_id);
        }

        $requests = $query->latest()->paginate(30)->appends($request->query());
        
        if ($request->ajax()) {
            return view('permintaan.partials.table', compact('requests', 'kategori'))->render();
        }

        return view('permintaan.index', compact('requests', 'kategori'));
    }

    public function create(Request $request)
    {
        $user = auth()->user();
        if (!$user->isBranchLevel() && !$user->isSuperUser()) {
            abort(403, 'Hanya Staff Cabang yang dapat membuat permintaan.');
        }

        $kategori = $request->query('kategori', 'BB');

        // Block ISIAN for special roles
        if ($kategori === 'ISIAN' && in_array($user->role, ['staff_dapur', 'staff_pastry', 'mixing'])) {
            abort(403, 'Akses ke kategori Isian dibatasi untuk role Anda.');
        }

        $query = MasterProduk::where('kategori', $kategori);

        // Filter by target_role for all categories
        $query->where(function($q) use ($user) {
            $q->where('target_role', $user->role)          // exact match for this role
              ->orWhere('target_role', 'all')               // 'all' means open to all branch staff
              ->orWhereNull('target_role');                 // null/empty means no restriction
        });

        // Filter by assigned branch if not superuser
        if (!$user->isSuperUser()) {
            $cabang_id = $user->cabang_id;
            $query->where(function($q) use ($cabang_id) {
                $q->whereHas('cabangs', function($sq) use ($cabang_id) {
                    $sq->where('cabangs.id', $cabang_id);
                })->orWhereDoesntHave('cabangs');
            });
        }

        $produks = $query->get();
        $cabangs = $user->isSuperUser() ? Cabang::all() : null;
        return view('permintaan.create', compact('produks', 'kategori', 'cabangs'));
    }

    public function store(Request $request)
    {
        // Filter out items that are not filled (empty produk_id)
        if ($request->has('items')) {
            $filteredItems = array_filter($request->items, function ($item) {
                return !empty($item['produk_id']);
            });
            $request->merge(['items' => $filteredItems]);
        }

        $rules = [
            'kategori' => 'required|in:BB,ISIAN,GA',
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:master_produks,id',
            'items.*.qty' => 'required|integer|min:1',
            'gudang_asal' => 'nullable|string|max:255',
            'gudang_tujuan' => 'nullable|string|max:255',
        ];

        if (auth()->user()->isSuperUser()) {
            $rules['cabang_id'] = 'required|exists:cabangs,id';
        }

        $request->validate($rules);

        return DB::transaction(function () use ($request) {
            $now = Carbon::now();
            $kat = $request->kategori;
            $count = Permintaan::where('kategori', $kat)->whereDate('tanggal', $now->toDateString())->count() + 1;

            $prefix = 'REQ-' . $kat;
            $no_request = $prefix . '-' . $now->format('Ymd') . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);

            $cabang_id = auth()->user()->isSuperUser() ? $request->cabang_id : auth()->user()->cabang_id;

            $permintaan = Permintaan::create([
                'no_request' => $no_request,
                'kategori' => $kat,
                'cabang_id' => $cabang_id,
                'user_id' => auth()->id(),
                'status' => 'pending',
                'tanggal' => $now,
                'gudang_asal' => $request->gudang_asal,
                'gudang_tujuan' => $request->gudang_tujuan,
            ]);

            foreach ($request->items as $item) {
                PermintaanDetail::create([
                    'permintaan_id' => $permintaan->id,
                    'produk_id' => $item['produk_id'],
                    'qty' => $item['qty'],
                ]);
            }

            return redirect()->route('permintaan.index', ['kategori' => $kat])->with('success', 'Permintaan barang berhasil dibuat.');
        });
    }

    public function show(Permintaan $permintaan)
    {
        $permintaan->load(['cabang', 'user', 'details.produk']);
        $drivers = MasterDriver::all();
        return view('permintaan.show', compact('permintaan', 'drivers'));
    }

    public function approve(Permintaan $permintaan)
    {
        if (auth()->user()->isBranchLevel()) abort(403);

        $permintaan->update(['status' => 'approved']);
        return redirect()->back()->with('success', 'Permintaan disetujui.');
    }

    public function reject(Permintaan $permintaan)
    {
        if (auth()->user()->isBranchLevel()) abort(403);

        $permintaan->update(['status' => 'rejected']);
        return redirect()->back()->with('success', 'Permintaan ditolak.');
    }

    public function ship(Request $request, Permintaan $permintaan)
    {
        if (auth()->user()->isBranchLevel()) abort(403);

        $request->validate([
            'driver' => 'nullable|string|max:255',
            'gudang_asal' => 'nullable|string|max:255',
            'gudang_tujuan' => 'nullable|string|max:255',
            'items' => 'required|array',
            'items.*.id' => 'required|exists:permintaan_details,id',
            'items.*.qty_dikirim' => 'required|integer|min:0',
        ]);

        return DB::transaction(function () use ($request, $permintaan) {
            foreach ($request->items as $item) {
                PermintaanDetail::where('id', $item['id'])
                    ->where('permintaan_id', $permintaan->id)
                    ->update(['qty_dikirim' => $item['qty_dikirim']]);
            }

            $updateData = [
                'status' => 'shipped',
                'driver' => $request->driver,
            ];

            if ($request->filled('gudang_asal')) {
                $updateData['gudang_asal'] = $request->gudang_asal;
            }
            if ($request->filled('gudang_tujuan')) {
                $updateData['gudang_tujuan'] = $request->gudang_tujuan;
            }

            $permintaan->update($updateData);

            return redirect()->back()->with('success', 'Status diperbarui menjadi "Dikirim" dengan rincian jumlah kirim.');
        });
    }

    public function receive(Request $request, Permintaan $permintaan)
    {
        $user = auth()->user();
        if (!$user->isBranchLevel()) abort(403);
        if ($permintaan->cabang_id !== $user->cabang_id && !$user->isSuperUser()) abort(403);
        if ($permintaan->status !== 'shipped') abort(403, 'Hanya barang dengan status "Dikirim" yang bisa dikonfirmasi.');

        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:permintaan_details,id',
            'items.*.qty_terima' => 'required|integer|min:0',
        ]);

        return DB::transaction(function () use ($request, $permintaan) {
            $allComplete = true;

            foreach ($request->items as $item) {
                $detail = PermintaanDetail::where('id', $item['id'])
                    ->where('permintaan_id', $permintaan->id)
                    ->firstOrFail();
                
                $qty_terima = $item['qty_terima'];
                $detail->update(['qty_terima' => $qty_terima]);

                // if any item received is less than what was shipped, it's partial
                if ($qty_terima < $detail->qty_dikirim) {
                    $allComplete = false;
                }
            }

            $status = $allComplete ? 'received_complete' : 'received_partial';
            $permintaan->update(['status' => $status]);

            return redirect()->back()->with('success', 'Penerimaan barang berhasil disimpan dengan status: ' . str_replace('_', ' ', strtoupper($status)));
        });
    }
}
