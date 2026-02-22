<x-app-layout>
    <x-slot name="header">
        Pengaturan Sistem
    </x-slot>

    <div class="px-0 sm:px-2 space-y-6">
        @if(session('success'))
            <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded-lg shadow-sm mb-4" role="alert">
                <p class="font-bold">Berhasil</p>
                <p class="text-sm">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-700 p-4 rounded-lg shadow-sm mb-4" role="alert">
                <p class="font-bold">Kesalahan</p>
                <p class="text-sm">{{ session('error') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Backup Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-md transition-shadow">
                <div class="p-8">
                    <div class="flex items-center space-x-4 mb-8">
                        <div class="p-4 bg-blue-50 rounded-2xl text-blue-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-xl font-black text-slate-800 tracking-tight">Backup Database</h4>
                            <p class="text-sm text-slate-500 font-medium">Download full .sql backup</p>
                        </div>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-6 mb-8 border border-slate-100 text-sm text-slate-600 leading-relaxed italic">
                        "Cadangkan seluruh data sistem secara berkala untuk menghindari kehilangan data transaksi yang penting."
                    </div>
                    <a href="{{ route('system.backup') }}" class="w-full flex items-center justify-center px-6 py-4 bg-blue-600 text-white rounded-xl text-md font-bold hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition-all active:scale-95 space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        <span>Unduh Database</span>
                    </a>
                </div>
            </div>

            <!-- Reset Data Card -->
            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100/50 hover:shadow-xl hover:shadow-rose-600/5 transition-all duration-500 group"
                 x-data="{ open: false, confirmation: '', loading: false }">
                <div class="flex items-start justify-between mb-6">
                    <div class="h-14 w-14 rounded-2xl bg-rose-50 flex items-center justify-center text-rose-500 group-hover:scale-110 group-hover:bg-rose-600 group-hover:text-white transition-all duration-500 shadow-sm">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </div>
                    <span class="px-4 py-1.5 rounded-full bg-rose-50 text-rose-600 text-[10px] font-black uppercase tracking-widest border border-rose-100">Danger Zone</span>
                </div>
                <h3 class="text-xl font-black text-slate-800 mb-2">Reset Data Request</h3>
                <p class="text-sm text-slate-500 leading-relaxed mb-8">Hapus seluruh riwayat permintaan barang. Tindakan ini tidak dapat dibatalkan.</p>
                
                <button @click="open = true" class="w-full py-4 bg-slate-50 text-slate-600 rounded-2xl font-bold text-sm hover:bg-rose-600 hover:text-white transition-all duration-300 border border-slate-100 flex items-center justify-center group/btn">
                    <span>Mulailah Reset</span>
                    <svg class="w-4 h-4 ml-2 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                </button>

                <!-- Confirmation Modal -->
                <template x-teleport="body">
                    <div x-show="open" 
                         class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6" 
                         x-cloak>
                        
                        <!-- Overlay -->
                        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" 
                             x-show="open"
                             x-transition:enter="ease-out duration-300"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="ease-in duration-200"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             @click="open = false"></div>

                        <!-- Modal Content Box: Centered Card -->
                        <div class="relative bg-white rounded-[2.5rem] shadow-2xl transform transition-all w-full max-w-md overflow-hidden border border-slate-200 mx-auto"
                             x-show="open"
                             x-transition:enter="ease-out duration-300"
                             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                             x-transition:leave="ease-in duration-200"
                             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                            
                            <div class="px-10 pt-12 pb-8">
                                <div class="text-center">
                                    <!-- Enriched Warn Icon -->
                                    <div class="inline-flex items-center justify-center h-20 w-20 rounded-3xl bg-rose-50 text-rose-600 mb-8 rotate-3 shadow-lg shadow-rose-600/10">
                                        <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    </div>
                                    
                                    <h3 class="text-3xl font-black text-slate-800 tracking-tight leading-tight mb-4">Reset Seluruh Data?</h3>
                                    
                                    <div class="bg-slate-50 rounded-2xl p-5 mb-8 border border-slate-100 text-left">
                                        <p class="text-sm text-slate-500 leading-relaxed">
                                            Tindakan ini akan <span class="text-rose-600 font-bold underline">menghapus permanen</span> seluruh riwayat permintaan barang. Data master tetap aman.
                                        </p>
                                    </div>
                                    
                                    <div class="space-y-4">
                                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">Konfirmasi Penghapusan</label>
                                        <input type="text" 
                                               x-model="confirmation" 
                                               @keydown.enter="if(confirmation === 'RESET') { loading = true; $refs.submitForm.submit() }"
                                               placeholder="Ketik 'RESET' di sini" 
                                               class="block w-full rounded-2xl border-2 border-slate-100 focus:border-rose-500 focus:ring-0 text-xl bg-slate-50 transition-all font-mono font-black uppercase text-center p-5 placeholder:text-slate-300">
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons: Aligned Right -->
                            <div class="bg-slate-50/50 px-10 py-8 flex flex-col sm:flex-row justify-end gap-4 border-t border-slate-100">
                                <button @click="open = false; confirmation = ''" 
                                        type="button" 
                                        class="order-2 sm:order-1 inline-flex justify-center items-center rounded-2xl px-8 py-4 bg-white border-2 border-slate-100 text-sm font-bold text-slate-500 hover:bg-white hover:border-slate-300 transition-all uppercase tracking-widest active:scale-95">
                                    Batal
                                </button>
                                <form action="{{ route('system.reset-requests') }}" method="POST" x-ref="submitForm" class="order-1 sm:order-2">
                                    @csrf
                                    <input type="hidden" name="confirmation" x-bind:value="confirmation">
                                    <button type="submit" 
                                            @click="loading = true"
                                            :disabled="confirmation !== 'RESET' || loading"
                                            class="w-full inline-flex justify-center items-center rounded-2xl px-10 py-4 bg-rose-600 text-sm font-bold text-white hover:bg-rose-700 shadow-xl shadow-rose-600/30 transition-all disabled:opacity-20 disabled:cursor-not-allowed uppercase tracking-widest active:scale-95 min-w-[160px]">
                                        <template x-if="!loading">
                                            <span>Lanjutkan</span>
                                        </template>
                                        <template x-if="loading">
                                            <div class="flex items-center">
                                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                                <span>Memproses...</span>
                                            </div>
                                        </template>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</x-app-layout>
