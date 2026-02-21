<x-app-layout>
    <x-slot name="header">
        Permintaan Barang - {{ $kategori ?? 'Semua' }}
    </x-slot>

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-bold text-slate-800">Daftar Permintaan {{ $kategori === 'BB' ? 'Bahan Baku' : ($kategori === 'ISIAN' ? 'Isian' : ($kategori === 'GA' ? 'General Affair' : 'Semua')) }}</h3>
            @if((auth()->user()->isBranchLevel() || auth()->user()->isSuperUser()) && $kategori)
                <a href="{{ route('permintaan.create', ['kategori' => $kategori]) }}" class="inline-flex items-center px-4 py-2 bg-brand-primary border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-brand-secondary focus:bg-brand-secondary active:bg-brand-secondary focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Buat Permintaan {{ $kategori }}
                </a>
            @endif
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 mb-6">
            <form action="{{ route('permintaan.index') }}" method="GET" class="flex flex-col sm:flex-row sm:items-center space-y-4 sm:space-y-0 sm:space-x-4">
                @if($kategori)
                    <input type="hidden" name="kategori" value="{{ $kategori }}">
                @endif
                <div class="flex-1">
                    <label for="search" class="sr-only">Cari Permintaan</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" 
                           @input.debounce.500ms="$el.form.submit()"
                           placeholder="Cari No Request, Cabang, atau Pembuat..." 
                           class="block w-full rounded-lg border-slate-200 focus:border-brand-primary focus:ring-brand-primary sm:text-sm">
                </div>
                
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-slate-800 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-700 active:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Cari
                </button>
                @if(request('search'))
                <a href="{{ route('permintaan.index', ['kategori' => $kategori]) }}" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 rounded-lg font-semibold text-xs text-slate-700 uppercase tracking-widest shadow-sm hover:bg-slate-50 transition ease-in-out duration-150">
                    Reset
                </a>
                @endif
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3 font-semibold">No</th>
                            <th class="px-6 py-3 font-semibold">No Request</th>
                            <th class="px-6 py-3 font-semibold">Tanggal</th>
                            <th class="px-6 py-3 font-semibold">Cabang</th>
                            <th class="px-6 py-3 font-semibold">Status</th>
                            <th class="px-6 py-3 font-semibold">Dibuat Oleh</th>
                            <th class="px-6 py-3 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($requests as $request)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 text-slate-600">
                                    {{ $requests->firstItem() + $loop->index }}
                                </td>
                                <td class="px-6 py-4 font-medium text-slate-700">
                                    {{ $request->no_request }}
                                </td>
                                <td class="px-6 py-4 text-slate-600">{{ $request->tanggal->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $request->cabang->nama ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $request->status === 'pending' ? 'bg-amber-100 text-amber-700' : '' }}
                                        {{ $request->status === 'approved' ? 'bg-blue-100 text-blue-700' : '' }}
                                        {{ $request->status === 'shipped' ? 'bg-purple-100 text-purple-700' : '' }}
                                        {{ $request->status === 'received_complete' ? 'bg-green-100 text-green-700' : '' }}
                                        {{ $request->status === 'received_partial' ? 'bg-orange-100 text-orange-700' : '' }}
                                        {{ $request->status === 'received' ? 'bg-green-100 text-green-700' : '' }}
                                        {{ $request->status === 'rejected' ? 'bg-red-100 text-red-700' : '' }}">
                                        {{ str_replace('_', ' ', ucfirst($request->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-600 text-sm">{{ $request->user->name }}</td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('permintaan.show', $request) }}" class="text-brand-primary hover:text-brand-secondary font-semibold text-sm">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-slate-500">
                                    Belum ada permintaan barang.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($requests->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $requests->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
