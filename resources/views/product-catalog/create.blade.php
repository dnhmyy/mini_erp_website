<x-app-layout>
    <x-slot name="header">
        Tambah ke Katalog
    </x-slot>

    <div class="max-w-xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-8">
                <form action="{{ route('product-catalog.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label for="kode" class="block text-sm font-bold text-slate-700 mb-1">Kode Barang</label>
                        <input type="text" name="kode" id="kode" value="{{ old('kode') }}" required 
                               class="block w-full border border-slate-200 rounded-xl py-2.5 px-4 focus:ring-brand-primary focus:border-brand-primary transition">
                        @error('kode') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="nama" class="block text-sm font-bold text-slate-700 mb-1">Nama Barang</label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required 
                               class="block w-full border border-slate-200 rounded-xl py-2.5 px-4 focus:ring-brand-primary focus:border-brand-primary transition">
                        @error('nama') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="satuan" class="block text-sm font-bold text-slate-700 mb-1">Satuan</label>
                        <input type="text" name="satuan" id="satuan" value="{{ old('satuan', 'PCS') }}" required 
                               class="block w-full border border-slate-200 rounded-xl py-2.5 px-4 focus:ring-brand-primary focus:border-brand-primary transition" 
                               placeholder="Contoh: PCS, BOX, KG">
                        @error('satuan') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-4">
                        <a href="{{ route('product-catalog.index') }}" class="px-4 py-2 text-sm font-bold text-slate-500 hover:text-slate-800 transition">Batal</a>
                        <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-brand-primary text-white font-bold rounded-xl hover:bg-brand-secondary transition-all shadow-md active:scale-95">
                            Simpan ke Katalog
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
