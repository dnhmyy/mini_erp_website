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
            <div class="bg-white rounded-[2rem] p-8 shadow-lg border-2 border-slate-100 hover:border-blue-500 hover:shadow-blue-500/10 transition-all duration-500 group"
                 x-data="{ confirmBackup: false }">
                <div class="flex items-start justify-between mb-6">
                    <div class="h-14 w-14 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-all duration-500 border border-blue-100 shadow-sm">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                    </div>
                    <span class="px-4 py-1.5 rounded-full bg-blue-50 text-blue-700 text-[10px] font-black uppercase tracking-widest border border-blue-100 shadow-sm">Database Safe</span>
                </div>
                <h4 class="text-xl font-black text-slate-900 mb-2">Backup Database</h4>
                <p class="text-sm text-slate-600 leading-relaxed mb-8 font-medium">Download cadangan data sistem (.sql) secara instan untuk keamanan data Anda.</p>
                
                <button @click="confirmBackup = true" class="w-full py-4 bg-white text-blue-600 rounded-2xl font-bold text-sm hover:bg-blue-600 hover:text-white transition-all duration-300 border-2 border-blue-100 flex items-center justify-center group/btn shadow-sm">
                    <span>Unduh Sekarang</span>
                    <svg class="w-4 h-4 ml-2 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                </button>

                <!-- Backup Confirmation -->
                <template x-teleport="body">
                    <div x-show="confirmBackup" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6" x-cloak>
                        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="confirmBackup = false" x-show="confirmBackup" x-transition:opacity></div>
                        <div class="relative bg-white rounded-[2.5rem] shadow-2xl w-full max-w-sm p-8 text-center border border-slate-100" x-show="confirmBackup" x-transition:scale>
                            <div class="inline-flex items-center justify-center h-16 w-16 rounded-2xl bg-blue-50 text-blue-600 mb-6 border border-blue-100 shadow-sm">
                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            </div>
                            <h3 class="text-2xl font-black text-slate-900 mb-2">Mulai Backup?</h3>
                            <p class="text-sm text-slate-600 mb-8 font-medium">Database akan diproses dan langsung diunduh ke perangkat Anda.</p>
                            <div class="flex justify-end gap-3">
                                <button @click="confirmBackup = false" class="px-6 py-3 bg-white border-2 border-slate-100 rounded-xl text-sm font-bold text-slate-500 hover:bg-slate-50 transition-all">Batal</button>
                                <a href="{{ route('system.backup') }}" @click="confirmBackup = false" class="px-6 py-3 bg-blue-600 text-white rounded-xl text-sm font-bold shadow-lg shadow-blue-600/30 hover:bg-blue-700 transition-all">Lanjutkan</a>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Reset Data Card -->
            <div class="bg-white rounded-[2rem] p-8 shadow-lg border-2 border-slate-100 hover:border-rose-500 hover:shadow-rose-500/10 transition-all duration-500 group"
                 x-data="{ open: false, confirmation: '', loading: false }">
                <div class="flex items-start justify-between mb-6">
                    <div class="h-14 w-14 rounded-2xl bg-rose-50 flex items-center justify-center text-rose-600 group-hover:bg-rose-600 group-hover:text-white transition-all duration-500 border border-rose-100 shadow-sm">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </div>
                    <span class="px-4 py-1.5 rounded-full bg-rose-50 text-rose-700 text-[10px] font-black uppercase tracking-widest border border-rose-100 shadow-sm">Danger Zone</span>
                </div>
                <h3 class="text-xl font-black text-slate-900 mb-2">Reset Data Request</h3>
                <p class="text-sm text-slate-600 leading-relaxed mb-8 font-medium">Hapus seluruh riwayat permintaan barang secara permanen dari database.</p>
                
                <button @click="open = true" class="w-full py-4 bg-white text-rose-600 rounded-2xl font-bold text-sm hover:bg-rose-600 hover:text-white transition-all duration-300 border-2 border-rose-100 flex items-center justify-center group/btn shadow-sm">
                    <span>Bersihkan Data</span>
                    <svg class="w-4 h-4 ml-2 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                </button>

                <!-- Reset Confirmation Modal -->
                <template x-teleport="body">
                    <div x-show="open" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6" x-cloak>
                        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="open = false" x-show="open" x-transition:opacity></div>
                        
                        <div class="relative bg-white rounded-[2.5rem] shadow-2xl w-full max-w-sm overflow-hidden border border-slate-200"
                             x-show="open" 
                             x-transition:enter="ease-out duration-300"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100">
                            
                            <form action="{{ route('system.reset-requests') }}" method="POST" x-ref="resetForm" @submit="loading = true">
                                @csrf
                                <div class="p-8">
                                    <div class="text-center mb-6">
                                        <div class="inline-flex items-center justify-center h-20 w-20 rounded-3xl bg-rose-50 text-rose-600 mb-6 shadow-sm border border-rose-100">
                                            <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                        </div>
                                        <h3 class="text-2xl font-black text-slate-900 mb-2">Konfirmasi Reset</h3>
                                        <p class="text-sm text-slate-600 font-medium leading-relaxed">Tindakan ini permanen. Ketik <span class="text-rose-600 font-bold underline">RESET</span> untuk melanjutkan.</p>
                                    </div>

                                    <div class="mb-8">
                                        <input type="text" 
                                               name="confirmation"
                                               x-model="confirmation" 
                                               @keydown.enter.prevent="if(confirmation.toUpperCase() === 'RESET') $refs.resetForm.submit()"
                                               placeholder="KETIK DI SINI" 
                                               class="w-full rounded-2xl border-2 border-slate-200 focus:border-rose-500 focus:ring-0 text-lg bg-white transition-all font-mono font-black uppercase text-center p-4 shadow-inner">
                                    </div>

                                    <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                                        <button @click="open = false; confirmation = ''" 
                                                type="button"
                                                class="px-6 py-3 bg-white border-2 border-slate-200 rounded-xl text-sm font-bold text-slate-500 hover:bg-slate-50 transition-all font-sans">
                                            Batal
                                        </button>
                                        <button type="submit" 
                                                :disabled="confirmation.toUpperCase() !== 'RESET' || loading"
                                                class="px-8 py-3 bg-rose-600 text-white rounded-xl text-sm font-bold shadow-lg shadow-rose-600/30 hover:bg-rose-700 transition-all disabled:opacity-20 flex items-center min-w-[120px] justify-center uppercase tracking-widest">
                                            <template x-if="!loading">
                                                <span>Lanjutkan</span>
                                            </template>
                                            <template x-if="loading">
                                                <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                            </template>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</x-app-layout>
