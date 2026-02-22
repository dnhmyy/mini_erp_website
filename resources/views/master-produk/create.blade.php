<x-app-layout>
    <x-slot name="header">
        Tambah Produk
    </x-slot>

    <div class="max-w-2xl bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mx-auto">
        <div class="p-8">
            <form action="{{ route('master-produk.store') }}" method="POST" class="space-y-6" x-data="{ 
                open: false, 
                search: '{{ old('nama_produk', '') }}', 
                catalog: {{ $catalog->map(fn($item) => ['id' => $item->id, 'kode' => $item->kode, 'nama' => $item->nama, 'satuan' => $item->satuan])->toJson() }},
                selectedNama: '{{ old('nama_produk', '') }}',
                selectedKode: '{{ old('kode_produk', '') }}',
                selectedSatuan: '{{ old('satuan', 'Pcs') }}',
                get filteredCatalog() {
                    if (this.search === '') return [];
                    return this.catalog.filter(item => 
                        item.nama.toLowerCase().includes(this.search.toLowerCase()) || 
                        item.kode.toLowerCase().includes(this.search.toLowerCase())
                    ).slice(0, 10);
                },
                selectItem(item) {
                    this.selectedNama = item.nama;
                    this.search = item.nama;
                    this.selectedKode = item.kode;
                    this.selectedSatuan = item.satuan;
                    this.open = false;
                }
            }">
                @csrf
                
                <!-- Smart Product Selection (Combobox) -->
                <div class="relative">
                    <label for="search_input" class="block text-sm font-bold text-slate-700 mb-1">Nama Produk (Ketik untuk mencari dari Katalog)</label>
                    <div class="relative">
                        <input type="text" 
                               id="search_input"
                               x-model="search" 
                               @input="open = true; selectedNama = $event.target.value"
                               @click="open = true"
                               @click.away="open = false"
                               placeholder="Cari nama barang..." 
                               autocomplete="off"
                               class="block w-full border border-slate-200 rounded-xl py-3 px-4 focus:ring-brand-primary focus:border-brand-primary transition pr-10 {{ $errors->has('nama_produk') ? 'border-red-500' : '' }}">
                        
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400">
                             <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>
                    @error('nama_produk') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror

                    <!-- Dropdown Results -->
                    <div x-show="open && filteredCatalog.length > 0" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="absolute z-50 mt-2 w-full bg-white shadow-2xl rounded-xl py-2 border border-slate-100 overflow-hidden ring-1 ring-black ring-opacity-5">
                        
                        <ul class="divide-y divide-slate-50">
                            <template x-for="item in filteredCatalog" :key="item.id">
                                <li @click="selectItem(item)" 
                                    class="cursor-pointer select-none relative py-3 px-4 hover:bg-brand-primary transition-colors group">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-800 group-hover:text-white" x-text="item.nama"></span>
                                        <span class="text-xs text-slate-500 group-hover:text-amber-100 font-medium" x-text="'Kode: ' + item.kode + ' | Satuan: ' + item.satuan"></span>
                                    </div>
                                </li>
                            </template>
                        </ul>
                    </div>
                </div>

                <!-- Hidden inputs for form submission -->
                <input type="hidden" name="nama_produk" :value="selectedNama">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="kode_produk" class="block text-sm font-bold text-slate-700 mb-1">Kode Produk</label>
                        <input type="text" name="kode_produk" id="kode_produk" :value="selectedKode" @input="selectedKode = $event.target.value" required 
                               class="block w-full border border-slate-200 rounded-xl py-2.5 px-4 focus:ring-brand-primary focus:border-brand-primary transition bg-slate-50 font-mono font-bold text-brand-primary" readonly>
                        @error('kode_produk') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="satuan" class="block text-sm font-bold text-slate-700 mb-1">Satuan</label>
                        <input type="text" name="satuan" id="satuan" :value="selectedSatuan" @input="selectedSatuan = $event.target.value" required 
                               class="block w-full border border-slate-200 rounded-xl py-2.5 px-4 focus:ring-brand-primary focus:border-brand-primary transition bg-slate-50 font-bold text-slate-600" readonly>
                        @error('satuan') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="kategori" class="block text-sm font-bold text-slate-700 mb-1">Kategori</label>
                        <select name="kategori" id="kategori" required onchange="toggleTargetRole(this)" class="block w-full border border-slate-200 rounded-xl py-2.5 px-4 focus:ring-brand-primary focus:border-brand-primary transition">
                            <option value="BB" {{ old('kategori') == 'BB' ? 'selected' : '' }}>Bahan Baku (BB)</option>
                            <option value="ISIAN" {{ old('kategori') == 'ISIAN' ? 'selected' : '' }}>Isian</option>
                            <option value="GA" {{ old('kategori') == 'GA' ? 'selected' : '' }}>General Affair (GA)</option>
                        </select>
                        @error('kategori') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div id="target_role_wrap">
                        <label for="target_role" class="block text-sm font-bold text-slate-700 mb-1">Target Role</label>
                        <select name="target_role" id="target_role" class="block w-full border border-slate-200 rounded-xl py-2.5 px-4 focus:ring-brand-primary focus:border-brand-primary transition">
                            <option value="">-- Semua Staff Cabang --</option>
                            <option value="staff_admin" {{ old('target_role') == 'staff_admin' ? 'selected' : '' }}>Staff Admin</option>
                            <option value="staff_produksi" {{ old('target_role') == 'staff_produksi' ? 'selected' : '' }}>Staff Produksi</option>
                            <option value="staff_dapur" {{ old('target_role') == 'staff_dapur' ? 'selected' : '' }}>Staff Dapur</option>
                            <option value="staff_pastry" {{ old('target_role') == 'staff_pastry' ? 'selected' : '' }}>Staff Pastry</option>
                            <option value="all" {{ old('target_role') == 'all' ? 'selected' : '' }}>Semua (Semua Staff)</option>
                        </select>
                        @error('target_role') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-3 text-center uppercase tracking-widest text-[11px] text-slate-400">Pilih Ketersediaan Cabang</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 bg-slate-50 p-6 rounded-2xl border border-slate-100">
                        @foreach($cabangs as $cabang)
                            <label class="inline-flex items-center group cursor-pointer">
                                <input type="checkbox" name="cabang_ids[]" value="{{ $cabang->id }}" class="w-5 h-5 rounded border-slate-300 text-brand-primary focus:ring-brand-primary transition">
                                <span class="ml-3 text-sm font-semibold text-slate-600 group-hover:text-brand-primary transition-colors">{{ $cabang->nama }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-3 pt-6 border-t border-slate-100 mt-6">
                    <p class="text-[10px] text-slate-400 italic flex-1">* Jika tidak memilih cabang tertentu, produk akan tersedia di semua cabang.</p>
                    <a href="{{ route('master-produk.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 rounded-lg font-semibold text-xs text-slate-700 uppercase tracking-widest shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-brand-primary border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-brand-secondary active:bg-brand-secondary active:scale-95 focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Simpan Master
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleTargetRole(select) {
            const wrap = document.getElementById('target_role_wrap');
            if (['BB', 'ISIAN', 'GA'].includes(select.value)) {
                wrap.classList.remove('opacity-25', 'pointer-events-none');
            } else {
                wrap.classList.add('opacity-25', 'pointer-events-none');
                document.getElementById('target_role').value = '';
            }
        }
        // Initialize state
        document.addEventListener('DOMContentLoaded', () => {
            toggleTargetRole(document.getElementById('kategori'));
        });
    </script>
</x-app-layout>
