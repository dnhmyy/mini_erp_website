<x-app-layout>
    <x-slot name="header">
        Tambah Produk Batch (Bulk)
    </x-slot>

    <div class="max-w-6xl mx-auto" x-data="{
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
            }).slice(0, 20); // Limit results for performance
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
        <form action="{{ route('master-produk.bulk-store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <!-- LEFT COLUMN: SETTINGS & SUMMARY (4/12) -->
                <div class="lg:col-span-4 space-y-6">
                    <!-- Batch Settings -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">1. Pengaturan Batch</h4>
                        <div class="space-y-4">
                            <div>
                                <label for="kategori" class="block text-xs font-bold text-slate-700 mb-1">Kategori</label>
                                <select name="kategori" id="kategori" x-model="kategori" required 
                                        class="block w-full border border-slate-200 rounded-xl py-2.5 px-4 focus:ring-brand-primary focus:border-brand-primary transition text-sm">
                                    <option value="BB">Bahan Baku (BB)</option>
                                    <option value="ISIAN">Isian</option>
                                    <option value="GA">General Affair (GA)</option>
                                </select>
                            </div>

                            <div x-show="kategori !== 'GA'" x-transition>
                                <label for="target_role" class="block text-xs font-bold text-slate-700 mb-1">Target Role</label>
                                <select name="target_role" id="target_role" x-model="target_role"
                                        class="block w-full border border-slate-200 rounded-xl py-2.5 px-4 focus:ring-brand-primary focus:border-brand-primary transition text-sm">
                                    <option value="">-- Semua Staff Cabang --</option>
                                    <option value="staff_admin">Staff Admin</option>
                                    <option value="staff_produksi">Staff Produksi</option>
                                    <option value="staff_dapur">Staff Dapur</option>
                                    <option value="staff_pastry">Staff Pastry</option>
                                    <option value="all">Semua (Admin & Produksi)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Selected Items Bucket (Empty Space filler) -->
                    <div class="bg-slate-50 rounded-2xl border border-dashed border-slate-200 p-6 min-h-[300px] flex flex-col">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-xs font-bold text-slate-500 uppercase tracking-widest">Item Terpilih (<span x-text="selectedItems.length"></span>)</h4>
                            <button type="button" x-show="selectedItems.length > 0" @click="selectedItems = []" class="text-[10px] text-red-500 font-bold hover:underline">Hapus Semua</button>
                        </div>

                        <div class="flex-1 space-y-2 overflow-y-auto max-h-[400px] pr-2 custom-scrollbar">
                            <template x-for="item in selectedItems" :key="item.id">
                                <div class="bg-white p-3 rounded-xl border border-slate-100 flex items-center justify-between shadow-sm group">
                                    <div class="flex flex-col min-w-0">
                                        <span class="text-xs font-bold text-slate-800 truncate" x-text="item.nama"></span>
                                        <span class="text-[10px] text-slate-500" x-text="item.kode"></span>
                                    </div>
                                    <button type="button" @click="removeItem(item.id)" class="text-slate-300 hover:text-red-500 transition-colors p-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                    <input type="hidden" name="catalog_ids[]" :value="item.id">
                                </div>
                            </template>

                            <template x-if="selectedItems.length === 0">
                                <div class="flex flex-col items-center justify-center py-12 text-center">
                                    <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center mb-3">
                                        <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-3.586a1 1 0 00-.707.293l-1.414 1.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-1.414-1.414A1 1 0 009.586 13H6"></path></svg>
                                    </div>
                                    <p class="text-[11px] text-slate-400 font-medium">Belum ada item dipilih.<br>Cari dan pilih di kolom kanan.</p>
                                </div>
                            </template>
                        </div>

                        <div class="mt-6 pt-6 border-t border-slate-200">
                             <button type="submit" :disabled="selectedItems.length === 0"
                                    class="w-full inline-flex items-center justify-center px-6 py-3 bg-brand-primary text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-brand-primary/20 disabled:opacity-30 disabled:shadow-none hover:bg-brand-secondary active:scale-95">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Simpan Ke Master Produk
                            </button>
                        </div>
                    </div>
                </div>

                <!-- RIGHT COLUMN: SEARCH & CATALOG (8/12) -->
                <div class="lg:col-span-8 bg-white rounded-3xl shadow-sm border border-slate-100 min-h-[600px] flex flex-col overflow-hidden">
                    <div class="p-8 border-b border-slate-50 bg-slate-50/50">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-sm font-bold text-slate-800">2. Cari Produk dari Katalog</h4>
                            <span class="text-[10px] bg-amber-100 text-amber-700 px-2 py-1 rounded-full font-bold">Katalog Barang</span>
                        </div>
                        
                        <div class="relative">
                            <input type="text" x-model="search" autofocus
                                   placeholder="Ketik minimal 2 karakter untuk mencari... (Contoh: Lapis, Box, Tepung)" 
                                   class="block w-full border-2 border-slate-200 rounded-2xl py-4 px-6 focus:ring-brand-primary focus:border-brand-primary transition text-lg font-medium placeholder:text-slate-300">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-6 text-slate-300">
                                <svg x-show="search.length < 2" class="w-6 h-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                <button type="button" x-show="search.length >= 2" @click="search = ''" class="hover:text-slate-500 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="flex-1 overflow-y-auto custom-scrollbar">
                        <!-- Instructions when empty search -->
                        <div x-show="search.length < 2" class="h-full flex flex-col items-center justify-center p-12 text-center">
                            <div class="w-20 h-20 bg-brand-primary/5 rounded-full flex items-center justify-center mb-6">
                                <svg class="w-10 h-10 text-brand-primary/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 21h7a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v11m14-11H14V5"></path></svg>
                            </div>
                            <h5 class="text-slate-800 font-bold mb-2">Siap untuk Menambah Master Data?</h5>
                            <p class="text-sm text-slate-500 max-w-sm">Ketik nama atau kode barang di kotak atas untuk mulai memilih data dari Katalog Barang.</p>
                        </div>

                        <!-- Results List -->
                        <div x-show="search.length >= 2" class="divide-y divide-slate-50">
                            <template x-for="item in filteredCatalog" :key="item.id">
                                <div class="px-8 py-4 flex items-center justify-between hover:bg-slate-50 transition-colors group cursor-pointer" @click="toggleItem(item)">
                                    <div class="flex items-center space-x-6 flex-1">
                                        <div class="relative">
                                            <input type="checkbox" :checked="isSelected(item.id)" 
                                                   class="w-6 h-6 rounded-lg border-2 border-slate-300 text-brand-primary focus:ring-brand-primary transition pointer-events-none">
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-slate-800 group-hover:text-brand-primary transition-colors" x-text="item.nama"></span>
                                            <div class="flex items-center space-x-3 text-[11px] text-slate-400 font-bold uppercase tracking-vibrant">
                                                <span class="bg-slate-100 px-2 py-0.5 rounded" x-text="'Kode: ' + item.kode"></span>
                                                <span class="bg-slate-100 px-2 py-0.5 rounded" x-text="'Satuan: ' + item.satuan"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                         <span x-show="isSelected(item.id)" class="text-[10px] font-bold text-brand-primary bg-brand-primary/10 px-3 py-1 rounded-full flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                            Pilihan
                                         </span>
                                    </div>
                                </div>
                            </template>

                            <template x-if="search.length >= 2 && filteredCatalog.length === 0">
                                <div class="p-12 text-center">
                                    <p class="text-sm text-slate-400 italic">Tidak menemukan produk dengan kata kunci "<span x-text="search"></span>" atau produk sudah ada di Master.</p>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="p-6 bg-slate-50/50 border-t border-slate-50 flex items-center justify-between">
                         <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest italic">
                            * Menampilkan Maksimal 20 Hasil Pencarian
                         </div>
                         <a href="{{ route('master-produk.index') }}" class="text-sm font-bold text-slate-500 hover:text-slate-800 transition">Batal & Kembali</a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
        .tracking-vibrant { letter-spacing: 0.025em; }
    </style>
</x-app-layout>
