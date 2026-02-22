<x-app-layout>
    <x-slot name="header">
        Pengaturan Sistem
    </x-slot>

    <div class="px-4 sm:px-6 lg:px-8 py-6 max-w-5xl mx-auto space-y-6">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-4" role="alert">
                <span class="block sm:inline font-medium">INFO:</span>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Backup Card -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden group">
                <div class="p-6 sm:p-8">
                    <div class="flex items-center space-x-4 mb-6">
                        <div class="p-3 bg-blue-50 rounded-lg text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-slate-800">Backup Database</h4>
                            <p class="text-xs text-slate-500">Download salinan database lengkap (.sql)</p>
                        </div>
                    </div>
                    <div class="bg-slate-50 rounded-lg p-4 mb-6 border border-slate-100 text-sm text-slate-600 italic">
                        "Backup ini mencakup semua data termasuk Master Data, User, dan Riwayat Permintaan."
                    </div>
                    <a href="{{ route('system.backup') }}" class="w-full flex items-center justify-center px-6 py-3 bg-blue-600 text-white rounded-lg text-sm font-bold hover:bg-blue-700 shadow-md transition-all active:scale-95 space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        <span>Download Full Backup</span>
                    </a>
                </div>
            </div>

            <!-- Reset Card -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden group" x-data="{ open: false, confirmation: '' }">
                <div class="p-6 sm:p-8">
                    <div class="flex items-center space-x-4 mb-6">
                        <div class="p-3 bg-red-50 rounded-lg text-red-600 group-hover:bg-red-600 group-hover:text-white transition-all duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-slate-800">Reset Data Request</h4>
                            <p class="text-xs text-slate-500 text-red-500 font-medium">Zona Bahaya: Menghapus data transaksi</p>
                        </div>
                    </div>
                    <div class="bg-red-50 rounded-lg p-4 mb-6 border border-red-100 text-sm text-red-800 leading-relaxed">
                        <strong>PERINGATAN:</strong> Ini akan menghapus seluruh data Permintaan dan Detail Permintaan. 
                        Data Master (Produk, Cabang, User) akan tetap aman.
                    </div>
                    <button @click="open = true" class="w-full flex items-center justify-center px-6 py-3 bg-white border-2 border-red-500 text-red-600 rounded-lg text-sm font-bold hover:bg-red-50 transition-all active:scale-95 space-x-2 outline-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <span>Bersihkan Data Request</span>
                    </button>

                    <!-- Confirmation Modal -->
                    <template x-teleport="body">
                        <div x-show="open" 
                             class="fixed inset-0 z-[100] flex items-center justify-center min-h-screen p-4 sm:p-6" 
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

                            <!-- Modal Content -->
                            <div class="relative bg-white rounded-2xl shadow-2xl transform transition-all w-full max-w-lg overflow-hidden border border-slate-100"
                                 x-show="open"
                                 x-transition:enter="ease-out duration-300"
                                 x-transition:enter-start="opacity-0 translate-y-8 sm:scale-95"
                                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                 x-transition:leave="ease-in duration-200"
                                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                 x-transition:leave-end="opacity-0 translate-y-8 sm:scale-95">
                                
                                <div class="px-6 sm:px-8 pt-8 pb-6">
                                    <div class="flex items-start space-x-4">
                                        <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 text-red-600">
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="text-xl font-bold text-slate-800">Reset Data Request?</h3>
                                            <div class="mt-4 space-y-3">
                                                <p class="text-sm text-slate-500 leading-relaxed">
                                                    Tindakan ini akan <span class="text-red-600 font-bold underline decoration-2 underline-offset-2">MENGHAPUS PERMANEN</span> semua data permintaan barang.
                                                </p>
                                                <div class="bg-amber-50 rounded-lg p-3 text-xs text-amber-800 border border-amber-100">
                                                    <strong>Info:</strong> Data master produk, user, dan cabang tidak akan terhapus.
                                                </div>
                                                <p class="text-sm font-bold text-slate-700">
                                                    Ketik <span class="text-red-600 uppercase">RESET</span> untuk konfirmasi:
                                                </p>
                                                <input type="text" 
                                                       x-model="confirmation" 
                                                       @keydown.enter="if(confirmation === 'RESET') $refs.submitBtn.click()"
                                                       placeholder="Ketik disini..." 
                                                       class="block w-full rounded-lg border-slate-200 focus:border-red-500 focus:ring-red-500 sm:text-sm bg-slate-50 transition-all font-mono">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-slate-50 px-6 sm:px-8 py-6 flex flex-col sm:flex-row-reverse gap-3">
                                    <form action="{{ route('system.reset-requests') }}" method="POST" class="w-full sm:w-auto">
                                        @csrf
                                        <input type="hidden" name="confirmation" x-bind:value="confirmation">
                                        <button type="submit" 
                                                x-ref="submitBtn"
                                                :disabled="confirmation !== 'RESET'"
                                                class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-6 py-2.5 bg-red-600 text-sm font-bold text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all disabled:opacity-50 disabled:cursor-not-allowed uppercase tracking-wider">
                                            Hapus Sekarang
                                        </button>
                                    </form>
                                    <button @click="open = false; confirmation = ''" type="button" class="w-full sm:w-auto inline-flex justify-center rounded-lg border border-slate-300 shadow-sm px-6 py-2.5 bg-white text-sm font-bold text-slate-700 hover:bg-slate-100 transition-all uppercase tracking-wider">
                                        Batal
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
