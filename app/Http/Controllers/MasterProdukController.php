<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\MasterProduk;
use App\Models\ProductCatalog;

class MasterProdukController extends Controller
{
    public function bulkCreate(Request $request)
    {
        // Ambil catalog yang belum ada di master produk (opsional, tapi lebih baik)
        $existingCodes = MasterProduk::pluck('kode_produk')->toArray();
        $catalog = ProductCatalog::orderBy('nama')->get();
        
        return view('master-produk.bulk-create', compact('catalog', 'existingCodes'));
    }

    public function bulkStore(Request $request)
    {
        $request->validate([
            'kategori' => 'required|in:BB,ISIAN,GA',
            'target_role' => 'nullable|array',
            'target_role.*' => 'in:staff_admin,staff_produksi,staff_dapur,staff_pastry,mixing,all',
            'catalog_ids' => 'required|array',
            'catalog_ids.*' => 'exists:product_catalogs,id',
        ]);

        $selectedCatalogs = ProductCatalog::whereIn('id', $request->catalog_ids)->get();

        \DB::beginTransaction();
        try {
            foreach ($selectedCatalogs as $item) {
                $produk = MasterProduk::updateOrCreate(
                    ['kode_produk' => $item->kode],
                    [
                        'nama_produk' => $item->nama,
                        'satuan' => $item->satuan,
                        'kategori' => $request->kategori,
                        'target_role' => $request->target_role,
                    ]
                );
            }
            \DB::commit();
            return redirect()->route('master-produk.index')->with('success', count($selectedCatalogs) . ' produk berhasil ditambahkan secara batch.');
        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function index(Request $request)
    {
        $query = MasterProduk::query();
        
        // Filter Pencarian Teks (Kode atau Nama)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_produk', 'like', "%{$search}%")
                  ->orWhere('nama_produk', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('target_role')) {
            $query->where(function($q) use ($request) {
                $q->whereJsonContains('target_role', $request->target_role)
                  ->orWhere('target_role', $request->target_role);
            });
        }

        $produks = $query->latest()->paginate(30)->onEachSide(1)->appends($request->query());

        if ($request->ajax()) {
            return view('master-produk.partials.table', compact('produks'))->render();
        }

        return view('master-produk.index', compact('produks'));
    }

    public function create()
    {
        $catalog = ProductCatalog::orderBy('nama')->get();
        return view('master-produk.create', compact('catalog'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_produk' => 'required|unique:master_produks',
            'nama_produk' => 'required',
            'satuan' => 'required',
            'kategori' => 'required|in:BB,ISIAN,GA',
            'target_role' => 'nullable|array',
            'target_role.*' => 'in:staff_admin,staff_produksi,staff_dapur,staff_pastry,mixing,all',
        ]);

        $produk = MasterProduk::create($request->all());

        return redirect()->route('master-produk.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(MasterProduk $masterProduk)
    {
        $catalog = ProductCatalog::orderBy('nama')->get();
        return view('master-produk.edit', compact('masterProduk', 'catalog'));
    }

    public function update(Request $request, MasterProduk $masterProduk)
    {
        $request->validate([
            'kode_produk' => 'required|unique:master_produks,kode_produk,' . $masterProduk->id,
            'nama_produk' => 'required',
            'satuan' => 'required',
            'kategori' => 'required|in:BB,ISIAN,GA',
            'target_role' => 'nullable|array',
            'target_role.*' => 'in:staff_admin,staff_produksi,staff_dapur,staff_pastry,mixing,all',
        ]);

        $masterProduk->update($request->all());

        return redirect()->route('master-produk.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(MasterProduk $masterProduk)
    {
        $masterProduk->cabangs()->detach();
        $masterProduk->delete();
        return redirect()->route('master-produk.index')->with('success', 'Produk berhasil dihapus.');
    }

    public function batchDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:master_produks,id',
        ]);

        \DB::beginTransaction();
        try {
            $produks = MasterProduk::whereIn('id', $request->ids)->get();
            foreach ($produks as $produk) {
                $produk->cabangs()->detach();
                $produk->delete();
            }
            \DB::commit();
            return redirect()->route('master-produk.index')->with('success', count($request->ids) . ' produk berhasil dihapus.');
        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menghapus produk: ' . $e->getMessage());
        }
    }
}
