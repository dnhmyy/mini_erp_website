<x-app-layout>
    <x-slot name="header">
        Pengaturan Sistem
    </x-slot>

    <div class="space-y-6">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-4 shadow-sm" role="alert">
                <span class="block sm:inline font-bold">Berhasil!</span>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-4 shadow-sm" role="alert">
                <div class="flex">
                    <div class="py-1"><svg class="fill-current h-6 w-6 text-red-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm1.41-1.41a8 8 0 1 0 11.32-11.32 8 8 0 0 0-11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/></svg></div>
                    <div>
                        <p class="font-bold">Error Terdeteksi</p>
                        <p class="text-sm">{{ session('error') }}</p>
                    </div>
                </div>
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
                            <p class="text-xs text-slate-500 font-medium">Download salinan database lengkap (.sql)</p>
                        </div>
                    </div>
                    <div class="bg-slate-50 rounded-lg p-5 mb-6 border border-slate-100 text-sm text-slate-600 italic leading-relaxed">
                        "Backup ini mencakup semua data termasuk Master Data, User, dan Riwayat Permintaan. Sangat disarankan dilakukan secara berkala."
                    </div>
                    <a href="{{ route('system.backup') }}" class="w-full flex items-center justify-center px-6 py-4 bg-blue-600 text-white rounded-lg text-sm font-bold hover:bg-blue-700 shadow-lg shadow-blue-600/20 transition-all active:scale-95 space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
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
                            <p class="text-xs text-red-500 font-bold tracking-wide">Danger Zone: High Risk Action</p>
                        </div>
                    </div>
                    <div class="bg-red-50 rounded-lg p-5 mb-6 border border-red-100 text-sm text-red-800 leading-relaxed shadow-inner">
                        <div class="font-bold flex items-center mb-1">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                            PERINGATAN KERAS
                        </div>
                        Aksi ini akan membersihkan seluruh riwayat transaksi (Permintaan & Detail Permintaan). Data Master akan tetap aman.
                    </div>
                    <button @click="open = true" class="w-full flex items-center justify-center px-6 py-4 bg-white border-2 border-red-500 text-red-600 rounded-lg text-sm font-bold hover:bg-red-500 hover:text-white shadow-lg shadow-red-500/10 transition-all active:scale-95 space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <span>Mulai Reset Data</span>
                    </button>

                    <!-- Confirmation Modal -->
                    <template x-teleport="body">
                        <div x-show="open" 
                             class="fixed inset-0 z-[100] flex items-center justify-center p-4" 
                             x-cloak>
                            
                            <!-- Overlay -->
                            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" 
                                 x-show="open"
                                 x-transition:enter="ease-out duration-300"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"
                                 x-transition:leave="ease-in duration-200"
                                 x-transition:leave-start="opacity-100"
                                 x-transition:leave-end="opacity-0"
                                 @click="open = false"></div>

                            <!-- Modal Box -->
                            <div class="relative bg-white rounded-xl shadow-2xl transform transition-all w-full max-w-[480px] mx-auto overflow-hidden border border-slate-200"
                                 x-show="open"
                                 x-transition:enter="ease-out duration-300"
                                 x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave="ease-in duration-200"
                                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave-end="opacity-0 translate-y-4 scale-95">
                                
                                <div class="p-6">
                                    <div class="flex items-start sm:items-center space-x-4">
                                        <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 text-red-600">
                                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                        </div>
                                        <div>
                                            <h3 class="text-xl font-black text-slate-800 tracking-tight">Konfirmasi Reset Data</h3>
                                        </div>
                                    </div>

                                    <div class="mt-6 space-y-4">
                                        <div class="bg-red-50 rounded-lg p-4 text-sm text-red-900 leading-relaxed border border-red-100">
                                            Aksi ini akan menghapus permanen seluruh data riwayat permintaan. <span class="font-bold underline">Tindakan ini tidak bisa dibatalkan!</span>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-bold text-slate-700 mb-2">
                                                Ketik <span class="text-red-600 font-black">RESET</span> untuk melanjutkan:
                                            </label>
                                            <input type="text" 
                                                   x-model="confirmation" 
                                                   @keydown.enter="if(confirmation === 'RESET') $refs.submitBtn.click()"
                                                   placeholder="Ketik disini..." 
                                                   class="block w-full rounded-lg border-slate-300 focus:border-red-500 focus:ring-red-500 sm:text-sm bg-white shadow-sm transition-all py-3 px-4 font-mono font-bold uppercase placeholder:lowercase placeholder:font-normal">
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-slate-50 px-6 py-4 flex flex-col sm:flex-row-reverse gap-3 border-t border-slate-100">
                                    <form action="{{ route('system.reset-requests') }}" method="POST" class="w-full sm:w-auto">
                                        @csrf
                                        <input type="hidden" name="confirmation" x-bind:value="confirmation">
                                        <button type="submit" 
                                                x-ref="submitBtn"
                                                :disabled="confirmation !== 'RESET'"
                                                class="w-full inline-flex justify-center rounded-lg shadow-md px-8 py-3 bg-red-600 text-sm font-black text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-600 transition-all disabled:opacity-30 disabled:cursor-not-allowed uppercase tracking-widest">
                                            Konfirmasi
                                        </button>
                                    </form>
                                    <button @click="open = false; confirmation = ''" type="button" class="w-full sm:w-auto inline-flex justify-center rounded-lg border border-slate-300 px-8 py-3 bg-white text-sm font-bold text-slate-600 hover:bg-slate-50 transition-all uppercase tracking-widest">
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
