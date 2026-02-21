<x-app-layout>
    <x-slot name="header">
        Katalog Barang (Master List)
    </x-slot>

    <div class="space-y-6">
        <!-- Search & Action Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <form action="{{ route('product-catalog.index') }}" method="GET" class="flex-1 max-w-md">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Cari nama atau kode barang..." 
                           class="w-full bg-white border border-slate-200 rounded-xl py-2 pl-10 pr-4 focus:ring-brand-primary focus:border-brand-primary transition shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>
            </form>
            
            <a href="{{ route('product-catalog.create') }}" class="inline-flex items-center px-4 py-2 bg-brand-primary text-white font-bold rounded-xl hover:bg-brand-secondary transition-all shadow-md active:scale-95">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Ke Katalog
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Kode</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Nama Barang</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-center">Satuan</th>
                            <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($items as $item)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-6 py-4 font-mono text-sm text-brand-primary font-bold">{{ $item->kode }}</td>
                            <td class="px-6 py-4 text-sm text-slate-700 font-semibold">{{ $item->nama }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 bg-slate-100 text-slate-600 text-[10px] font-bold rounded-md uppercase tracking-tight">{{ $item->satuan }}</span>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('product-catalog.edit', $item) }}" class="inline-flex p-2 text-slate-400 hover:text-brand-primary transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('product-catalog.destroy', $item) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus barang ini dari katalog?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-red-600 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-500 italic">Data katalog kosong...</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($items->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30">
                {{ $items->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
