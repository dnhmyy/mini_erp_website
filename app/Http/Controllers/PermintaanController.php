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
        $kategori = $request->query('kategori', 'BB');

        $query = Permintaan::with(['cabang', 'user'])
            ->where('kategori', $kategori)
            ->latest();

        // Branch-level roles only see their own branch's requests
        if (auth()->user()->isBranchLevel()) {
            $query->where('cabang_id', auth()->user()->cabang_id);
        }

        $requests = $query->paginate(30)->appends($request->query());
        return view('permintaan.index', compact('requests', 'kategori'));
    }

    public function create(Request $request)
    {
        $user = auth()->user();
        if (!$user->isBranchLevel() && !$user->isSuperUser()) {
            abort(403, 'Hanya Staff Cabang yang dapat membuat permintaan.');
        }

        $kategori = $request->query('kategori', 'BB');

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
            'driver' => 'required|string|max:255',
        ]);

        $permintaan->update([
            'status' => 'shipped',
            'driver' => $request->driver,
        ]);

        return redirect()->back()->with('success', 'Status diperbarui menjadi "Dikirim" oleh ' . $request->driver . '.');
    }

    public function receive(Permintaan $permintaan)
    {
        $user = auth()->user();
        if (!$user->isBranchLevel() && !$user->isSuperUser()) abort(403);
        if ($permintaan->cabang_id !== $user->cabang_id && !$user->isSuperUser()) abort(403);

        $permintaan->update(['status' => 'received']);
        return redirect()->back()->with('success', 'Barang dinyatakan telah diterima.');
    }
}
