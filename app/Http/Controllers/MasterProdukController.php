<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\MasterProduk;

class MasterProdukController extends Controller
{
    public function index(Request $request)
    {
        $query = MasterProduk::query();
        
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
            $query->where('target_role', $request->target_role);
        }

        $produks = $query->latest()->paginate(30)->appends($request->query());
        return view('master-produk.index', compact('produks'));
    }

    public function create()
    {
        $cabangs = \App\Models\Cabang::all();
        return view('master-produk.create', compact('cabangs'));
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
        $masterProduk->load('cabangs');
        return view('master-produk.edit', compact('masterProduk', 'cabangs'));
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
