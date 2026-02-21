<x-app-layout>
    <x-slot name="header">
        Permintaan Barang - {{ $kategori }}
    </x-slot>

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-bold text-slate-800 tracking-tight flex items-center">
                <span class="mr-2">Daftar Permintaan</span>
                <span class="px-2 py-0.5 bg-brand-primary/10 text-brand-primary rounded text-xs uppercase font-extrabold">{{ $kategori === 'BB' ? 'Bahan Baku' : ($kategori === 'ISIAN' ? 'Isian' : 'General Affair') }}</span>
            </h3>
            @if(auth()->user()->isBranchLevel() || auth()->user()->isSuperUser())
                <a href="{{ route('permintaan.create', ['kategori' => $kategori]) }}" class="inline-flex items-center px-4 py-2.5 bg-brand-primary border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-brand-secondary transition-all shadow-sm active:scale-95">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Buat Permintaan
                </a>
            @endif
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative" role="alert">
                <span class="block sm:inline font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Search Bar Section -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 mb-6">
            <form action="{{ route('permintaan.index') }}" method="GET" class="flex items-center space-x-4">
                <input type="hidden" name="kategori" value="{{ $kategori }}">
                <div class="flex-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" 
                           name="search" 
                           id="search" 
                           value="{{ request('search') }}" 
                           placeholder="Cari No Request, Cabang, atau Pengirim..." 
                           autocomplete="off"
                           @input.debounce.500ms="$el.form.submit()"
                           class="block w-full pl-10 pr-4 py-2.5 rounded-lg border-slate-200 focus:border-brand-primary focus:ring-brand-primary sm:text-sm transition-all duration-200">
                </div>
                
                @if(request('search'))
                    <a href="{{ route('permintaan.index', ['kategori' => $kategori]) }}" class="text-xs font-bold text-slate-400 hover:text-brand-primary transition uppercase tracking-widest px-2">
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
                            <th class="px-6 py-4">No</th>
                            <th class="px-6 py-4">No Request</th>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4">Cabang</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Dibuat Oleh</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($requests as $request)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 text-slate-400 text-sm font-medium">
                                    {{ $requests->firstItem() + $loop->index }}
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('permintaan.show', $request) }}" class="font-bold text-brand-primary hover:underline underline-offset-4 decoration-2">
                                        {{ $request->no_request }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-slate-600 font-medium">{{ $request->tanggal->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 font-semibold text-slate-700">{{ $request->cabang->nama ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider
                                        {{ $request->status === 'pending' ? 'bg-amber-100 text-amber-700 border border-amber-200' : '' }}
                                        {{ $request->status === 'approved' ? 'bg-blue-100 text-blue-700 border border-blue-200' : '' }}
                                        {{ $request->status === 'shipped' ? 'bg-purple-100 text-purple-700 border border-purple-200' : '' }}
                                        {{ $request->status === 'received_complete' ? 'bg-green-100 text-green-700 border border-green-200' : '' }}
                                        {{ $request->status === 'received_partial' ? 'bg-orange-100 text-orange-700 border border-orange-200' : '' }}
                                        {{ $request->status === 'received' ? 'bg-green-100 text-green-700 border border-green-200' : '' }}
                                        {{ $request->status === 'rejected' ? 'bg-red-100 text-red-700 border border-red-200' : '' }}">
                                        {{ str_replace('_', ' ', ucfirst($request->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-500 text-xs font-bold uppercase">{{ $request->user->name }}</td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('permintaan.show', $request) }}" class="inline-flex items-center px-4 py-1.5 bg-slate-100 text-slate-700 text-[11px] font-bold rounded hover:bg-brand-primary hover:text-white transition-all uppercase tracking-widest">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-slate-200 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                        <p class="text-slate-400 font-medium">Belum ada permintaan barang.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($requests->hasPages())
                <div class="px-6 py-6 border-t border-slate-100 bg-slate-50/30">
                    {{ $requests->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
