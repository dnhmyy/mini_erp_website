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

            <!-- Reset Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-md transition-shadow" x-data="{ open: false, confirmation: '' }">
                <div class="p-8">
                    <div class="flex items-center space-x-4 mb-8">
                        <div class="p-4 bg-rose-50 rounded-2xl text-rose-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-xl font-black text-slate-800 tracking-tight">Reset Data Request</h4>
                            <p class="text-sm text-rose-500 font-bold tracking-wide">Danger Zone</p>
                        </div>
                    </div>
                    <div class="bg-rose-50 rounded-xl p-6 mb-8 border border-rose-100 text-sm text-rose-800 leading-relaxed shadow-inner">
                        <p class="font-bold flex items-center mb-2">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                            PERHATIAN!
                        </p>
                        Ini akan menghapus permanen seluruh data Riwayat Permintaan. Data Master Produk & User tetap aman.
                    </div>
                    <button @click="open = true" class="w-full flex items-center justify-center px-6 py-4 bg-white border-2 border-rose-500 text-rose-600 rounded-xl text-md font-bold hover:bg-rose-50 transition-all active:scale-95 space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <span>Bersihkan Data</span>
                    </button>

                    <!-- Confirmation Modal -->
                    <template x-teleport="body">
                        <div x-show="open" 
                             class="fixed inset-0 z-[100] flex items-center justify-center p-6" 
                             x-cloak>
                            
                            <!-- Overlay: Dark and blurred -->
                            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" 
                                 x-show="open"
                                 x-transition:enter="ease-out duration-300"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"
                                 x-transition:leave="ease-in duration-200"
                                 x-transition:leave-start="opacity-100"
                                 x-transition:leave-end="opacity-0"
                                 @click="open = false"></div>

                            <!-- Modal Content Box: Forced Small Width -->
                            <div class="relative bg-white rounded-3xl shadow-2xl transform transition-all w-full border border-slate-200"
                                 style="max-width: 450px; min-height: 400px;"
                                 x-show="open"
                                 x-transition:enter="ease-out duration-300"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="ease-in duration-200"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95">
                                
                                <div class="p-10 text-center h-full flex flex-col justify-between">
                                    <div>
                                        <!-- Error Icon -->
                                        <div class="inline-flex items-center justify-center h-20 w-20 rounded-full bg-rose-50 text-rose-600 mb-6">
                                            <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                        </div>
                                        
                                        <h3 class="text-2xl font-black text-slate-800 tracking-tight leading-tight mb-4">Reset Seluruh Data?</h3>
                                        <p class="text-sm text-slate-500 leading-relaxed mb-8">
                                            Aksi ini akan menghapus permanen seluruh <strong>Riwayat Permintaan</strong>. Data Master Produk & User tetap aman.
                                        </p>

                                        <div class="space-y-4 mb-10">
                                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest">Ketik <span class="text-rose-600">RESET</span> untuk konfirmasi</label>
                                            <input type="text" 
                                                   x-model="confirmation" 
                                                   @keydown.enter="if(confirmation === 'RESET') $refs.submitBtn.click()"
                                                   placeholder="..." 
                                                   class="block w-full rounded-2xl border-2 border-slate-100 focus:border-rose-500 focus:ring-0 text-2xl bg-slate-50 transition-all font-mono font-black uppercase text-center p-5">
                                        </div>
                                    </div>

                                    <div class="flex flex-col gap-3">
                                        <form action="{{ route('system.reset-requests') }}" method="POST" class="w-full">
                                            @csrf
                                            <input type="hidden" name="confirmation" x-bind:value="confirmation">
                                            <button type="submit" 
                                                    x-ref="submitBtn"
                                                    :disabled="confirmation !== 'RESET'"
                                                    class="w-full inline-flex justify-center items-center rounded-2xl px-8 py-5 bg-rose-600 text-lg font-bold text-white hover:bg-rose-700 shadow-xl shadow-rose-600/30 transition-all disabled:opacity-20 disabled:cursor-not-allowed uppercase tracking-widest active:scale-95">
                                                Lanjutkan
                                            </button>
                                        </form>
                                        <button @click="open = false; confirmation = ''" type="button" class="w-full inline-flex justify-center items-center rounded-2xl px-8 py-4 bg-white border-2 border-slate-100 text-base font-bold text-slate-500 hover:bg-slate-50 transition-all uppercase tracking-widest">
                                            Batal
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
