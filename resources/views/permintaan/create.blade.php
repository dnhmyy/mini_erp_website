<x-app-layout>
    <x-slot name="header">
        Buat Permintaan - {{ $kategori }}
    </x-slot>

    <div class="max-w-4xl bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden mx-auto">
        <div class="p-8">
            <div class="mb-6">
                <h3 class="text-lg font-bold text-slate-800">Formulir Permintaan {{ $kategori === 'BB' ? 'Bahan Baku' : ($kategori === 'ISIAN' ? 'Isian' : 'General Affair') }}</h3>
                <p class="text-sm text-slate-500">Silakan masukkan item dan jumlah yang dibutuhkan.</p>
            </div>

            <form action="{{ route('permintaan.store') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="kategori" value="{{ $kategori }}">

                @if(auth()->user()->isSuperUser())
                <div class="bg-amber-50 border border-amber-100 rounded-lg p-4 mb-6">
                    <label for="cabang_id" class="block text-sm font-bold text-amber-800 mb-1">Pilih Cabang Tujuan</label>
                    <select name="cabang_id" id="cabang_id" required class="block w-full rounded-lg border-amber-200 focus:border-brand-primary focus:ring-brand-primary sm:text-sm bg-white">
                        <option value="">-- Hubungkan Permintaan ke Cabang --</option>
                        @foreach($cabangs as $cabang)
                            <option value="{{ $cabang->id }}">{{ $cabang->nama }}</option>
                        @endforeach
                    </select>
                    @error('cabang_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    <p class="mt-1 text-xs text-amber-600">Sebagai Super Admin, Anda harus menentukan cabang mana yang meminta barang ini.</p>
                </div>
                @endif

                @if(in_array(auth()->user()->role, ['staff_dapur', 'staff_pastry', 'mixing']))
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-slate-50 p-6 rounded-xl border border-slate-200 mb-6">
                    <div>
                        <label for="gudang_asal" class="block text-sm font-bold text-slate-700 mb-2">Gudang Asal</label>
                        <select name="gudang_asal" id="gudang_asal" required class="block w-full rounded-lg border-slate-200 focus:border-brand-primary focus:ring-brand-primary sm:text-sm bg-white">
                            <option value="">-- Pilih Gudang Asal --</option>
                            <option value="GUDANG CENTRAL - GA">GUDANG CENTRAL - GA</option>
                            <option value="GUDANG CENTRAL - KECIL">GUDANG CENTRAL - KECIL</option>
                            <option value="GUDANG CENTRAL - ISIAN">GUDANG CENTRAL - ISIAN</option>
                            <option value="GUDANG CENTRAL - PREMIX">GUDANG CENTRAL - PREMIX</option>
                        </select>
                    </div>
                    <div>
                        <label for="gudang_tujuan" class="block text-sm font-bold text-slate-700 mb-2">Gudang Tujuan</label>
                        <select name="gudang_tujuan" id="gudang_tujuan" required class="block w-full rounded-lg border-slate-200 focus:border-brand-primary focus:ring-brand-primary sm:text-sm bg-white">
                            <option value="">-- Pilih Gudang Tujuan --</option>
                            <option value="Central Kitchen">Central Kitchen</option>
                            <option value="Mixing">Mixing</option>
                            <option value="Pastry">Pastry</option>
                        </select>
                    </div>
                </div>
                @endif
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h4 class="text-md font-bold text-slate-800">Daftar Barang</h4>
                        <button type="button" onclick="addRow()" class="inline-flex items-center px-3 py-1.5 bg-slate-100 border border-transparent rounded-lg font-semibold text-xs text-slate-700 uppercase tracking-widest hover:bg-slate-200 transition">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Tambah Baris
                        </button>
                    </div>

                    <div class="bg-slate-50 border border-slate-200 rounded-xl overflow-hidden">
                        <table class="w-full text-left" id="items-table">
                            <thead class="bg-slate-100 text-slate-600 text-xs uppercase font-semibold">
                                <tr>
                                    <th class="px-4 py-3">Nama Produk</th>
                                    <th class="px-2 py-3 w-20 sm:w-32 text-center">Qty</th>
                                    <th class="px-2 py-3 w-16 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @for($i = 0; $i < 10; $i++)
                                <tr class="item-row">
                                    <td class="px-4 py-3">
                                        <select name="items[{{ $i }}][produk_id]" {{ $i === 0 ? 'required' : '' }} class="block w-full border-transparent bg-transparent focus:ring-0 sm:text-sm">
                                            <option value="">-- Pilih Produk --</option>
                                            @foreach($produks as $produk)
                                                <option value="{{ $produk->id }}">{{ $produk->nama_produk }} ({{ $produk->satuan }})</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-2 py-3">
                                        <input type="number" name="items[{{ $i }}][qty]" min="1" {{ $i === 0 ? 'placeholder="1"' : '' }} class="block w-full border-transparent bg-transparent focus:ring-0 text-sm text-center px-0">
                                    </td>
                                    <td class="px-2 py-3 text-center">
                                        <button type="button" onclick="removeRow(this)" class="text-slate-400 hover:text-red-600 transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </td>
                                </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                    @error('items') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-end space-x-3 pt-6 border-t border-slate-100">
                    <a href="{{ route('permintaan.index', ['kategori' => $kategori]) }}" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 rounded-lg font-semibold text-xs text-slate-700 uppercase tracking-widest shadow-sm hover:bg-slate-50 transition">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-brand-primary border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-brand-secondary active:bg-brand-secondary focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2 transition ease-in-out duration-150">
                        Kirim Permintaan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let rowIndex = 10;
        function addRow() {
            const table = document.getElementById('items-table').getElementsByTagName('tbody')[0];
            const newRow = table.insertRow();
            newRow.classList.add('item-row');
            newRow.classList.add('divide-y');
            newRow.classList.add('divide-slate-200');
            
            newRow.innerHTML = `
                <td class="px-4 py-3">
                    <select name="items[${rowIndex}][produk_id]" required class="block w-full border-transparent bg-transparent focus:ring-0 sm:text-sm">
                        <option value="">-- Pilih Produk --</option>
                        @foreach($produks as $produk)
                            <option value="{{ $produk->id }}">{{ $produk->nama_produk }} ({{ $produk->satuan }})</option>
                        @endforeach
                    </select>
                </td>
                <td class="px-2 py-3">
                    <input type="number" name="items[${rowIndex}][qty]" min="1" value="1" required class="block w-full border-transparent bg-transparent focus:ring-0 text-sm text-center px-0">
                </td>
                <td class="px-2 py-3 text-center">
                    <button type="button" onclick="removeRow(this)" class="text-slate-400 hover:text-red-600 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </td>
            `;
            rowIndex++;
        }

        function removeRow(btn) {
            const rows = document.getElementsByClassName('item-row');
            if (rows.length > 1) {
                const row = btn.parentNode.parentNode;
                row.parentNode.removeChild(row);
            } else {
                alert('Minimal harus ada satu item.');
            }
        }
    </script>
</x-app-layout>
