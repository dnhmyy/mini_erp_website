<x-app-layout>
    <x-slot name="header">
        Tambah Produk Batch (Bulk)
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-8">
                <form action="{{ route('master-produk.bulk-store') }}" method="POST" x-data="{
                    search: '',
                    kategori: 'BB',
                    target_role: '',
                    catalog: {{ $catalog->map(fn($item) => [
                        'id' => $item->id, 
                        'kode' => $item->kode, 
                        'nama' => $item->nama, 
                        'satuan' => $item->satuan,
                        'is_existing' => in_array($item->kode, $existingCodes)
                    ])->toJson() }},
                    selectedIds: [],
                    get filteredCatalog() {
                        return this.catalog.filter(item => {
                            if (item.is_existing) return false;
                            const term = this.search.toLowerCase();
                            return item.nama.toLowerCase().includes(term) || item.kode.toLowerCase().includes(term);
                        });
                    },
                    toggleAll() {
                        if (this.selectedIds.length === this.filteredCatalog.length) {
                            this.selectedIds = [];
                        } else {
                            this.selectedIds = this.filteredCatalog.map(i => i.id);
                        }
                    },
                    toggleItem(id) {
                        const index = this.selectedIds.indexOf(id);
                        if (index > -1) {
                            this.selectedIds.splice(index, 1);
                        } else {
                            this.selectedIds.push(id)
                        }
                    }
                }">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        <!-- Step 1: Kategori & Role -->
                        <div class="space-y-6">
                            <h4 class="text-sm font-bold text-slate-400 uppercase tracking-widest">1. Pengaturan Batch</h4>
                            
                            <div>
                                <label for="kategori" class="block text-sm font-bold text-slate-700 mb-2">Kategori Produk</label>
                                <select name="kategori" id="kategori" x-model="kategori" required 
                                        class="block w-full border border-slate-200 rounded-xl py-3 px-4 focus:ring-brand-primary focus:border-brand-primary transition">
                                    <option value="BB">Bahan Baku (BB)</option>
                                    <option value="ISIAN">Isian</option>
                                    <option value="GA">General Affair (GA)</option>
                                </select>
                            </div>

                            <div x-show="kategori !== 'GA'" x-transition>
                                <label for="target_role" class="block text-sm font-bold text-slate-700 mb-2">Target Role</label>
                                <select name="target_role" id="target_role" x-model="target_role"
                                        class="block w-full border border-slate-200 rounded-xl py-3 px-4 focus:ring-brand-primary focus:border-brand-primary transition">
                                    <option value="">-- Semua Staff Cabang --</option>
                                    <option value="staff_admin">Staff Admin</option>
                                    <option value="staff_produksi">Staff Produksi</option>
                                    <option value="staff_dapur">Staff Dapur</option>
                                    <option value="staff_pastry">Staff Pastry</option>
                                    <option value="all">Semua (Admin & Produksi)</option>
                                </select>
                            </div>
                            
                            <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 flex items-start space-x-3">
                                <svg class="w-5 h-5 text-blue-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <p class="text-xs text-blue-700 leading-relaxed">
                                    Produk yang Anda pilih akan otomatis dihubungkan ke <strong>SEMUA CABANG</strong> agar langsung muncul di form permintaan.
                                </p>
                            </div>
                        </div>

                        <!-- Step 2: Pencarian -->
                        <div class="space-y-6">
                            <h4 class="text-sm font-bold text-slate-400 uppercase tracking-widest">2. Pilih Produk</h4>
                            
                            <div>
                                <label for="catalog_search" class="block text-sm font-bold text-slate-700 mb-2">Cari di Katalog</label>
                                <div class="relative">
                                    <input type="text" id="catalog_search" x-model="search" placeholder="Ketik nama atau kode barang..." 
                                           class="block w-full border border-slate-200 rounded-xl py-3 px-4 focus:ring-brand-primary focus:border-brand-primary transition pr-10">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between text-xs font-bold text-slate-500 px-1">
                                <span x-text="filteredCatalog.length + ' Produk ditemukan'"></span>
                                <button type="button" @click="toggleAll()" class="text-brand-primary hover:underline">
                                    <span x-text="selectedIds.length === filteredCatalog.length ? 'Batal Pilih Semua' : 'Pilih Semua'"></span>
                                </button>
                            </div>

                            <div class="border border-slate-100 rounded-xl overflow-hidden max-h-[400px] overflow-y-auto bg-slate-50 custom-scrollbar">
                                <ul class="divide-y divide-slate-100">
                                    <template x-for="item in filteredCatalog" :key="item.id">
                                        <li class="hover:bg-white transition-colors cursor-pointer" @click="toggleItem(item.id)">
                                            <label class="flex items-center p-4 cursor-pointer w-full">
                                                <input type="checkbox" name="catalog_ids[]" :value="item.id" x-model="selectedIds"
                                                       class="w-5 h-5 rounded border-slate-300 text-brand-primary focus:ring-brand-primary transition">
                                                <div class="ml-4">
                                                    <div class="text-sm font-bold text-slate-800" x-text="item.nama"></div>
                                                    <div class="text-[11px] text-slate-500 font-medium uppercase" x-text="item.kode + ' • ' + item.satuan"></div>
                                                </div>
                                            </label>
                                        </li>
                                    </template>
                                    <template x-if="filteredCatalog.length === 0">
                                        <li class="p-8 text-center text-slate-400 italic text-sm">
                                            Tidak ada produk yang cocok atau semua produk sudah ada di Master.
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-8 border-t border-slate-100">
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-slate-800" x-text="selectedIds.length + ' Produk dipilih'"></span>
                            <span class="text-xs text-slate-500">Klik simpan untuk memasukkan ke Master Produk</span>
                        </div>
                        
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('master-produk.index') }}" class="px-6 py-2.5 text-sm font-bold text-slate-500 hover:text-slate-800 transition">Batal</a>
                            <button type="submit" :disabled="selectedIds.length === 0"
                                    class="inline-flex items-center px-8 py-3 bg-brand-primary text-white font-bold rounded-xl' + (selectedIds.length === 0 ? ' opacity-50 cursor-not-allowed' : ' hover:bg-brand-secondary active:scale-95 shadow-lg') + ' transition-all">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                Simpan Batch Ke Master
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
    </style>
</x-app-layout>
