<x-app-layout>
    <x-slot name="header">
        Master Cabang
    </x-slot>

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-bold text-slate-800 tracking-tight">Daftar Cabang</h3>
            <a href="{{ route('cabang.create') }}" class="inline-flex items-center px-4 py-2.5 bg-brand-primary border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-brand-secondary transition-all shadow-sm active:scale-95">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Cabang
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative mb-4" role="alert">
                <span class="block sm:inline font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 mb-6">
            <form action="{{ route('cabang.index') }}" method="GET" class="flex items-center space-x-4">
                <div class="flex-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" 
                           name="search" 
                           id="search" 
                           value="{{ request('search') }}" 
                           placeholder="Cari Nama atau Alamat Cabang..." 
                           autocomplete="off"
                           @input.debounce.500ms="$el.form.submit()"
                           class="block w-full pl-10 pr-4 py-2.5 rounded-lg border-slate-200 focus:border-brand-primary focus:ring-brand-primary sm:text-sm transition-all duration-200">
                </div>
                
                @if(request('search'))
                    <a href="{{ route('cabang.index') }}" class="text-xs font-bold text-slate-400 hover:text-brand-primary transition uppercase tracking-widest">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 text-slate-500 text-[11px] uppercase tracking-[0.15em] font-bold">
                        <tr>
                            <th class="px-6 py-4 w-16">No</th>
                            <th class="px-6 py-4">Nama Cabang</th>
                            <th class="px-6 py-4">Alamat</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($cabangs as $cabang)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 text-slate-400 text-sm font-medium">{{ $cabangs->firstItem() + $loop->index }}</td>
                                <td class="px-6 py-4 font-bold text-slate-700">{{ $cabang->nama }}</td>
                                <td class="px-6 py-4 text-slate-500 text-sm leading-relaxed">{{ $cabang->alamat ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center space-x-3">
                                        <a href="{{ route('cabang.edit', $cabang) }}" class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        <form action="{{ route('cabang.destroy', $cabang) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus cabang ini?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-slate-200 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                        <p class="text-slate-400 font-medium">Belum ada cabang yang terdaftar.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($cabangs->hasPages())
                <div class="px-6 py-6 border-t border-slate-100 bg-slate-50/30">
                    {{ $cabangs->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
