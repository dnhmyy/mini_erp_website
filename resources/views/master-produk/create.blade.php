<x-app-layout>
    <x-slot name="header">
        Tambah Produk
    </x-slot>

    <div class="max-w-2xl bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden mx-auto">
        <div class="p-8">
            <form action="{{ route('master-produk.store') }}" method="POST" class="space-y-6" x-data="{ 
                open: false, 
                search: '', 
                catalog: {{ $catalog->map(fn($item) => ['id' => $item->id, 'kode' => $item->kode, 'nama' => $item->nama, 'satuan' => $item->satuan])->toJson() }},
                selectedNama: '{{ old('nama_produk', '--- Pilih dari Katalog ---') }}',
                selectedKode: '{{ old('kode_produk') }}',
                selectedSatuan: '{{ old('satuan', 'Pcs') }}',
                get filteredCatalog() {
                    if (this.search === '') return this.catalog.slice(0, 100);
                    return this.catalog.filter(item => 
                        item.nama.toLowerCase().includes(this.search.toLowerCase()) || 
                        item.kode.toLowerCase().includes(this.search.toLowerCase())
                    ).slice(0, 50);
                },
                selectItem(item) {
                    this.selectedNama = item.nama;
                    this.selectedKode = item.kode;
                    this.selectedSatuan = item.satuan;
                    this.open = false;
                    this.search = '';
                }
            }">
                @csrf
                
                <!-- Searchable Select for Product Catalog -->
                <div class="relative">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Pilih Produk (Cari dari Katalog)</label>
                    <button type="button" @click="open = !open" class="relative w-full bg-white border border-slate-200 rounded-lg py-2.5 pl-3 pr-10 text-left cursor-default focus:outline-none focus:ring-1 focus:ring-brand-primary focus:border-brand-primary sm:text-sm transition">
                        <span class="block truncate" x-text="selectedNama"></span>
                        <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="none" stroke="currentColor">
                                <path d="M7 7l3-3 3 3m0 6l-3 3-3-3" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                    </button>

                    <div x-show="open" @click.away="open = false" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="absolute z-50 mt-1 w-full bg-white shadow-2xl max-h-80 rounded-xl py-2 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm">
                        
                        <div class="sticky top-0 z-10 bg-white px-3 py-2 border-b border-slate-100">
                            <input type="text" x-model="search" placeholder="Ketik nama atau kode produk..." 
                                   class="block w-full rounded-lg border-slate-200 focus:border-brand-primary focus:ring-brand-primary sm:text-sm">
                        </div>

                        <ul class="divide-y divide-slate-50">
                            <template x-for="item in filteredCatalog" :key="item.id">
                                <li @click="selectItem(item)" 
                                    class="text-slate-900 cursor-pointer select-none relative py-3 pl-3 pr-9 hover:bg-brand-primary hover:text-white transition-colors group">
                                    <div class="flex flex-col">
                                        <span class="font-bold block truncate" x-text="item.nama"></span>
                                        <span class="text-xs text-slate-500 group-hover:text-amber-100" x-text="'Kode: ' + item.kode + ' | Satuan: ' + item.satuan"></span>
                                    </div>
                                </li>
                            </template>
                            <li x-show="filteredCatalog.length === 0" class="py-10 text-center text-slate-500 italic">
                                Produk tidak ditemukan...
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Hidden inputs for form submission -->
                <input type="hidden" name="nama_produk" :value="selectedNama">
                <input type="hidden" name="kode_produk" :value="selectedKode">

                <div>
                    <label for="kategori" class="block text-sm font-medium text-slate-700 mb-1">Kategori</label>
                    <select name="kategori" id="kategori" required onchange="toggleTargetRole(this)" class="block w-full border border-slate-200 rounded-lg py-2 px-3 focus:outline-none focus:ring-1 focus:ring-brand-primary focus:border-brand-primary sm:text-sm transition">
                        <option value="BB" {{ old('kategori') == 'BB' ? 'selected' : '' }}>Bahan Baku (BB)</option>
                        <option value="ISIAN" {{ old('kategori') == 'ISIAN' ? 'selected' : '' }}>Isian</option>
                        <option value="GA" {{ old('kategori') == 'GA' ? 'selected' : '' }}>General Affair (GA)</option>
                    </select>
                    @error('kategori') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div id="target_role_wrap">
                    <label for="target_role" class="block text-sm font-medium text-slate-700 mb-1">Dipakai Oleh (Target Role)</label>
                    <select name="target_role" id="target_role" class="block w-full border border-slate-200 rounded-lg py-2 px-3 focus:outline-none focus:ring-1 focus:ring-brand-primary focus:border-brand-primary sm:text-sm transition">
                        <option value="">-- Semua Staff Cabang --</option>
                        <option value="staff_admin" {{ old('target_role') == 'staff_admin' ? 'selected' : '' }}>Staff Admin</option>
                        <option value="staff_produksi" {{ old('target_role') == 'staff_produksi' ? 'selected' : '' }}>Staff Produksi</option>
                        <option value="staff_dapur" {{ old('target_role') == 'staff_dapur' ? 'selected' : '' }}>Staff Dapur</option>
                        <option value="staff_pastry" {{ old('target_role') == 'staff_pastry' ? 'selected' : '' }}>Staff Pastry</option>
                        <option value="all" {{ old('target_role') == 'all' ? 'selected' : '' }}>Semua (Semua Staff Cabang)</option>
                    </select>
                    <p class="mt-1 text-xs text-slate-500 italic">Tentukan siapa yang boleh request produk ini.</p>
                    @error('target_role') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Assign ke Cabang (Opsional)</label>
                    <div class="grid grid-cols-2 gap-3 bg-slate-50 p-4 rounded-lg border border-slate-100">
                        @foreach($cabangs as $cabang)
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="cabang_ids[]" value="{{ $cabang->id }}" class="rounded border-slate-300 text-brand-primary focus:ring-brand-primary">
                                <span class="ml-2 text-sm text-slate-600">{{ $cabang->nama }}</span>
                            </label>
                        @endforeach
                    </div>
                    <p class="mt-2 text-xs text-slate-500 italic">Kosongkan jika produk ini tersedia untuk semua cabang.</p>
                </div>

                <div>
                    <label for="satuan" class="block text-sm font-medium text-slate-700 mb-1">Satuan</label>
                    <input type="text" name="satuan" id="satuan" :value="selectedSatuan" @input="selectedSatuan = $event.target.value" required class="block w-full border border-slate-200 rounded-lg py-2 px-3 focus:outline-none focus:ring-1 focus:ring-brand-primary focus:border-brand-primary sm:text-sm transition bg-slate-50" readonly>
                    @error('satuan') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-end space-x-3 pt-4">
                    <a href="{{ route('master-produk.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 rounded-lg font-semibold text-xs text-slate-700 uppercase tracking-widest shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-brand-primary border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-brand-secondary focus:bg-brand-secondary active:bg-brand-secondary focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2 transition ease-in-out duration-150">
                        Simpan Produk
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleTargetRole(select) {
            const wrap = document.getElementById('target_role_wrap');
            if (['BB', 'ISIAN'].includes(select.value)) {
                wrap.classList.remove('hidden');
            } else {
                wrap.classList.add('hidden');
                document.getElementById('target_role').value = '';
            }
        }
    </script>
</x-app-layout>
