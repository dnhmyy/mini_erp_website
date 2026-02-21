<x-app-layout>
    <x-slot name="header">
        Master Produk
    </x-slot>

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-bold text-slate-800 tracking-tight">Daftar Produk</h3>
            <a href="{{ route('master-produk.create') }}" class="inline-flex items-center px-4 py-2.5 bg-brand-primary border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-brand-secondary focus:bg-brand-secondary active:bg-brand-secondary focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm active:scale-95">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Produk
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative mb-4" role="alert">
                <span class="block sm:inline font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 mb-6">
            <form action="{{ route('master-produk.index') }}" method="GET" class="flex flex-col lg:flex-row lg:items-center gap-4">
                <div class="flex-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" 
                           name="search" 
                           id="search" 
                           value="{{ request('search') }}" 
                           placeholder="Cari Kode atau Nama Produk..." 
                           autocomplete="off"
                           @input.debounce.500ms="$el.form.submit()"
                           class="block w-full pl-10 pr-4 py-2.5 rounded-lg border-slate-200 focus:border-brand-primary focus:ring-brand-primary sm:text-sm transition-all duration-200">
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <select name="kategori" 
                            onchange="this.form.submit()"
                            class="block w-full rounded-lg border-slate-200 focus:border-brand-primary focus:ring-brand-primary sm:text-sm bg-white font-medium text-slate-600">
                        <option value="">-- Semua Kategori --</option>
                        <option value="BB" {{ request('kategori') == 'BB' ? 'selected' : '' }}>Bahan Baku</option>
                        <option value="ISIAN" {{ request('kategori') == 'ISIAN' ? 'selected' : '' }}>Isian</option>
                        <option value="GA" {{ request('kategori') == 'GA' ? 'selected' : '' }}>General Affair</option>
                    </select>

                    <select name="target_role" 
                            onchange="this.form.submit()"
                            class="block w-full rounded-lg border-slate-200 focus:border-brand-primary focus:ring-brand-primary sm:text-sm bg-white font-medium text-slate-600">
                        <option value="">-- Semua Role --</option>
                        <option value="staff_admin" {{ request('target_role') == 'staff_admin' ? 'selected' : '' }}>Staff Admin</option>
                        <option value="staff_produksi" {{ request('target_role') == 'staff_produksi' ? 'selected' : '' }}>Staff Produksi</option>
                        <option value="staff_dapur" {{ request('target_role') == 'staff_dapur' ? 'selected' : '' }}>Staff Dapur</option>
                        <option value="staff_pastry" {{ request('target_role') == 'staff_pastry' ? 'selected' : '' }}>Staff Pastry</option>
                        <option value="all" {{ request('target_role') == 'all' ? 'selected' : '' }}>All (Admin & Produksi)</option>
                    </select>
                </div>

                @if(request('search') || request('kategori') || request('target_role'))
                <a href="{{ route('master-produk.index') }}" class="text-xs font-bold text-slate-400 hover:text-brand-primary transition uppercase tracking-widest px-2 py-2">
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
                            <th class="px-6 py-4">Kode Produk</th>
                            <th class="px-6 py-4">Nama Produk</th>
                            <th class="px-6 py-4">Kategori</th>
                            <th class="px-6 py-4">Satuan</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($produks as $produk)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 text-slate-400 text-sm font-medium">{{ $produks->firstItem() + $loop->index }}</td>
                                <td class="px-6 py-4 font-bold text-brand-primary font-mono bg-slate-50/30">{{ $produk->kode_produk }}</td>
                                <td class="px-6 py-4 font-semibold text-slate-700">{{ $produk->nama_produk }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 rounded-md text-[10px] uppercase font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                        {{ $produk->kategori }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-600 font-medium lowercase italic">{{ $produk->satuan }}</td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center space-x-3">
                                        <a href="{{ route('master-produk.edit', $produk) }}" class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        <form action="{{ route('master-produk.destroy', $produk) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');" class="inline">
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
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-slate-200 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        <p class="text-slate-400 font-medium">Belum ada produk yang terdaftar.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($produks->hasPages())
                <div class="px-6 py-6 border-t border-slate-100 bg-slate-50/30">
                    {{ $produks->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
