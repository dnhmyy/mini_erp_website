<x-app-layout>
    <x-slot name="header">
        Tambah Produk Batch (Bulk)
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6" x-data="{
        search: '',
        kategori: 'BB',
        target_role: 'staff_admin',
        catalog: {{ $catalog->map(fn($item) => [
            'id' => $item->id, 
            'kode' => $item->kode, 
            'nama' => $item->nama, 
            'satuan' => $item->satuan,
            'is_existing' => in_array($item->kode, $existingCodes)
        ])->toJson() }},
        selectedItems: [],
        get filteredCatalog() {
            const term = this.search.toLowerCase();
            if (term.length < 2) return [];
            return this.catalog.filter(item => {
                if (item.is_existing) return false;
                return item.nama.toLowerCase().includes(term) || item.kode.toLowerCase().includes(term);
            }).slice(0, 20);
        },
        toggleItem(item) {
            const index = this.selectedItems.findIndex(i => i.id === item.id);
            if (index > -1) {
                this.selectedItems.splice(index, 1);
            } else {
                this.selectedItems.push(item);
            }
        },
        isSelected(id) {
            return this.selectedItems.some(i => i.id === id);
        },
        removeItem(id) {
            this.selectedItems = this.selectedItems.filter(i => i.id !== id);
        }
    }">
        <form action="{{ route('master-produk.bulk-store') }}" method="POST" class="space-y-6">
            @csrf
            
            <!-- SECTION 1: PENGATURAN BATCH -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
                <h4 class="text-sm font-bold text-slate-800 mb-6 flex items-center">
                    <span class="w-8 h-8 bg-brand-primary text-white rounded-full flex items-center justify-center mr-3 text-xs">1</span>
                    Pengaturan Batch
                </h4>
                
                <div class="space-y-6">
                    <div>
                        <label for="kategori" class="block text-sm font-bold text-slate-700 mb-2">Kategori Batch</label>
                        <select name="kategori" id="kategori" x-model="kategori" required 
                                class="block w-full border border-slate-200 rounded-xl py-3 px-4 focus:ring-brand-primary focus:border-brand-primary transition">
                            <option value="BB">Bahan Baku (BB)</option>
                            <option value="ISIAN">Isian</option>
                            <option value="GA">General Affair (GA)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-4 uppercase tracking-widest text-[11px] text-slate-400">Target Role (Dapat pilih lebih dari satu)</label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3 gap-3 bg-slate-50 p-6 rounded-2xl border border-slate-100">
                            @foreach($roles as $val => $label)
                                <label class="inline-flex items-center group cursor-pointer bg-white p-3 rounded-xl border border-slate-100 hover:border-brand-primary transition-all">
                                    <input type="checkbox" name="target_role[]" value="{{ $val }}" 
                                           class="w-5 h-5 rounded border-slate-300 text-brand-primary focus:ring-brand-primary transition">
                                    <span class="ml-3 text-sm font-semibold text-slate-600 group-hover:text-brand-primary transition-colors">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: CARI PRODUK DARI KATALOG -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-8 border-b border-slate-50 bg-slate-50/50">
                    <h4 class="text-sm font-bold text-slate-800 mb-6 flex items-center">
                        <span class="w-8 h-8 bg-brand-primary text-white rounded-full flex items-center justify-center mr-3 text-xs">2</span>
                        Cari Produk dari Katalog
                    </h4>
                    
                    <div class="relative">
                        <input type="text" x-model="search" autofocus
                               placeholder="Ketik minimal 2 karakter untuk mencari.. (Contoh: Lapis, Box, Tepung)" 
                               class="block w-full border-2 border-slate-200 rounded-2xl py-4 px-6 focus:ring-brand-primary focus:border-brand-primary transition text-lg font-medium placeholder:text-slate-300">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-6 text-slate-300">
                            <svg x-show="search.length < 2" class="w-6 h-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            <button type="button" x-show="search.length >= 2" @click="search = ''" class="hover:text-slate-500 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="min-h-[200px] max-h-[400px] overflow-y-auto custom-scrollbar">
                    <!-- Instructions when empty search -->
                    <div x-show="search.length < 2" class="py-12 flex flex-col items-center justify-center text-center">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4 text-slate-300">
                             <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <h5 class="text-slate-800 font-bold mb-1">Siap untuk Menambah Master Data?</h5>
                        <p class="text-xs text-slate-400">Ketik nama barang di kotak atas untuk mulai.</p>
                    </div>

                    <!-- Results List -->
                    <div x-show="search.length >= 2" class="divide-y divide-slate-50">
                        <template x-for="item in filteredCatalog" :key="item.id">
                            <div class="px-8 py-4 flex items-center justify-between hover:bg-slate-50 transition-colors group cursor-pointer" @click="toggleItem(item)">
                                <div class="flex items-center space-x-6">
                                    <input type="checkbox" :checked="isSelected(item.id)" 
                                           class="w-6 h-6 rounded-lg border-2 border-slate-300 text-brand-primary focus:ring-brand-primary transition pointer-events-none">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-slate-800" x-text="item.nama"></span>
                                        <span class="text-[10px] text-slate-400 font-bold uppercase" x-text="item.kode + ' • ' + item.satuan"></span>
                                    </div>
                                </div>
                                <span x-show="isSelected(item.id)" class="text-[10px] font-bold text-brand-primary bg-brand-primary/10 px-3 py-1 rounded-full">Terpilih</span>
                            </div>
                        </template>
                        <template x-if="search.length >= 2 && filteredCatalog.length === 0">
                            <div class="p-12 text-center text-slate-400 italic text-xs">Produk tidak ditemukan atau sudah ada di Master.</div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- SECTION 3: ITEM TERPILIH & SUBMIT -->
            <div class="bg-slate-50 rounded-2xl border border-dashed border-slate-200 p-8">
                <div class="flex items-center justify-between mb-6">
                    <h4 class="text-sm font-bold text-slate-800 flex items-center">
                         <span class="w-8 h-8 bg-slate-800 text-white rounded-full flex items-center justify-center mr-3 text-xs">3</span>
                         Item Terpilih (<span x-text="selectedItems.length"></span>)
                    </h4>
                    <button type="button" x-show="selectedItems.length > 0" @click="selectedItems = []" class="text-xs text-red-500 font-bold hover:underline">Hapus Semua Pilihan</button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 mb-8">
                    <template x-for="item in selectedItems" :key="item.id">
                        <div class="bg-white p-3 rounded-xl border border-slate-100 flex items-center justify-between shadow-sm group">
                            <div class="flex flex-col min-w-0 pr-2">
                                <span class="text-xs font-bold text-slate-800 truncate" x-text="item.nama"></span>
                                <span class="text-[10px] text-slate-500" x-text="item.kode"></span>
                            </div>
                            <button type="button" @click="removeItem(item.id)" class="text-slate-300 hover:text-red-500 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                            <input type="hidden" name="catalog_ids[]" :value="item.id">
                        </div>
                    </template>
                    
                    <template x-if="selectedItems.length === 0">
                        <div class="col-span-full py-8 text-center bg-white/50 rounded-xl border border-dashed border-slate-200">
                             <p class="text-[11px] text-slate-400 font-medium italic">Belum ada item dipilih.</p>
                        </div>
                    </template>
                </div>

                <div class="flex items-center justify-between pt-6 border-t border-slate-200">
                     <a href="{{ route('master-produk.index') }}" class="text-sm font-bold text-slate-500 hover:text-slate-800 transition">Batal & Kembali</a>
                     <button type="submit" :disabled="selectedItems.length === 0"
                             class="inline-flex items-center px-8 py-3 bg-brand-primary text-white text-sm font-bold rounded-xl transition-all shadow-lg hover:bg-brand-secondary active:scale-95 disabled:opacity-30 disabled:shadow-none">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Simpan Master Produk
                    </button>
                </div>
            </div>
        </form>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
    </style>
</x-app-layout>
