<x-app-layout>
    <x-slot name="header">
        Manajamen Katalog
    </x-slot>

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-bold text-slate-800">Daftar Katalog</h3>
            <a href="{{ route('product-catalog.create') }}" class="inline-flex items-center px-4 py-2 bg-brand-primary border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-brand-secondary focus:bg-brand-secondary active:bg-brand-secondary focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Ke Katalog
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4">
            <form action="{{ route('product-catalog.index') }}" method="GET" class="flex flex-col sm:flex-row sm:items-center space-y-4 sm:space-y-0 sm:space-x-4">
                <div class="flex-1">
                    <label for="search" class="sr-only">Cari Barang</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" 
                           @input.debounce.500ms="$el.form.submit()"
                           placeholder="Cari berdasarkan Kode atau Nama Barang..." 
                           class="block w-full rounded-lg border-slate-200 focus:border-brand-primary focus:ring-brand-primary sm:text-sm">
                </div>
                
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-slate-800 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-700 active:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Filter
                </button>
                @if(request('search'))
                <a href="{{ route('product-catalog.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 rounded-lg font-semibold text-xs text-slate-700 uppercase tracking-widest shadow-sm hover:bg-slate-50 transition ease-in-out duration-150">
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
                            <th class="px-6 py-3 font-semibold w-16">No</th>
                            <th class="px-6 py-3 font-semibold">Kode Barang</th>
                            <th class="px-6 py-3 font-semibold">Nama Barang</th>
                            <th class="px-6 py-3 font-semibold text-center">Satuan</th>
                            <th class="px-6 py-3 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($items as $item)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 text-slate-600">{{ $items->firstItem() + $loop->index }}</td>
                            <td class="px-6 py-4 font-medium text-slate-700">{{ $item->kode }}</td>
                            <td class="px-6 py-4 text-slate-600 font-medium">{{ $item->nama }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[10px] uppercase font-bold bg-slate-100 text-slate-700">
                                    {{ $item->satuan }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-3">
                                    <a href="{{ route('product-catalog.edit', $item) }}" class="text-blue-600 hover:text-blue-800">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('product-catalog.destroy', $item) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus barang ini dari katalog?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-slate-500 italic">Data katalog kosong...</td>
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
