<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\MasterProduk;
use App\Models\ProductCatalog;

class MasterProdukController extends Controller
{
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
        
        // Filter Dropdown Nama Produk (Opsi Pilihan)
        if ($request->filled('nama_produk')) {
            $query->where('nama_produk', $request->nama_produk);
        }
        
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('target_role')) {
            $query->where('target_role', $request->target_role);
        }

        $productNames = MasterProduk::distinct()->pluck('nama_produk')->sort();
        $produks = $query->latest()->paginate(30)->appends($request->query());

        if ($request->ajax()) {
            return view('master-produk.partials.table', compact('produks'))->render();
        }

        return view('master-produk.index', compact('produks', 'productNames'));
    }

    public function create()
    {
        $cabangs = \App\Models\Cabang::all();
        $catalog = ProductCatalog::orderBy('nama')->get();
        return view('master-produk.create', compact('cabangs', 'catalog'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_produk' => 'required|unique:master_produks',
            'nama_produk' => 'required',
            'satuan' => 'required',
            'kategori' => 'required|in:BB,ISIAN,GA',
            'target_role' => 'nullable|in:staff_admin,staff_produksi,staff_dapur,staff_pastry,all',
            'cabang_ids' => 'nullable|array',
            'cabang_ids.*' => 'exists:cabangs,id',
        ]);

        $produk = MasterProduk::create($request->all());

        if ($request->has('cabang_ids')) {
            $produk->cabangs()->sync($request->cabang_ids);
        }

        return redirect()->route('master-produk.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(MasterProduk $masterProduk)
    {
        $cabangs = \App\Models\Cabang::all();
        $catalog = ProductCatalog::orderBy('nama')->get();
        $masterProduk->load('cabangs');
        return view('master-produk.edit', compact('masterProduk', 'cabangs', 'catalog'));
    }

    public function update(Request $request, MasterProduk $masterProduk)
    {
        $request->validate([
            'kode_produk' => 'required|unique:master_produks,kode_produk,' . $masterProduk->id,
            'nama_produk' => 'required',
            'satuan' => 'required',
            'kategori' => 'required|in:BB,ISIAN,GA',
            'target_role' => 'nullable|in:staff_admin,staff_produksi,staff_dapur,staff_pastry,all',
            'cabang_ids' => 'nullable|array',
            'cabang_ids.*' => 'exists:cabangs,id',
        ]);

        $masterProduk->update($request->all());

        if ($request->has('cabang_ids')) {
            $masterProduk->cabangs()->sync($request->cabang_ids);
        } else {
            $masterProduk->cabangs()->detach();
        }

        return redirect()->route('master-produk.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(MasterProduk $masterProduk)
    {
        $masterProduk->delete();
        return redirect()->route('master-produk.index')->with('success', 'Produk berhasil dihapus.');
    }
}
