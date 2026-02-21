<?php

namespace App\Http\Controllers;

use App\Models\ProductCatalog;
use Illuminate\Http\Request;

class ProductCatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductCatalog::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%");
            });
        }

        $items = $query->orderBy('nama')->paginate(30)->appends($request->query());
        
        if ($request->ajax()) {
            return view('product-catalog.partials.table', compact('items'))->render();
        }

        return view('product-catalog.index', compact('items'));
    }

    public function create()
    {
        return view('product-catalog.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:product_catalogs',
            'nama' => 'required',
            'satuan' => 'required',
        ]);

        ProductCatalog::create($request->all());

        return redirect()->route('product-catalog.index')->with('success', 'Barang berhasil ditambahkan ke katalog.');
    }

    public function edit(ProductCatalog $productCatalog)
    {
        return view('product-catalog.edit', compact('productCatalog'));
    }

    public function update(Request $request, ProductCatalog $productCatalog)
    {
        $request->validate([
            'kode' => 'required|unique:product_catalogs,kode,' . $productCatalog->id,
            'nama' => 'required',
            'satuan' => 'required',
        ]);

        $productCatalog->update($request->all());

        return redirect()->route('product-catalog.index')->with('success', 'Katalog barang berhasil diperbarui.');
    }

    public function destroy(ProductCatalog $productCatalog)
    {
        $productCatalog->delete();
        return redirect()->route('product-catalog.index')->with('success', 'Barang dihapus dari katalog.');
    }
}
