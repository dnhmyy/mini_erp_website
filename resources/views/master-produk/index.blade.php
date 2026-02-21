<x-app-layout>
    <x-slot name="header">
        Master Produk
    </x-slot>

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-bold text-slate-800">Daftar Produk</h3>
            <a href="{{ route('master-produk.create') }}" class="inline-flex items-center px-4 py-2 bg-brand-primary border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-brand-secondary focus:bg-brand-secondary active:bg-brand-secondary focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Produk
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 mb-6">
            <form action="{{ route('master-produk.index') }}" method="GET" class="flex flex-col sm:flex-row sm:items-center space-y-4 sm:space-y-0 sm:space-x-4">
                <div class="flex-1">
                    <label for="search" class="sr-only">Cari Produk</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Cari berdasarkan Kode/Nama Produk..." class="block w-full rounded-lg border-slate-200 focus:border-brand-primary focus:ring-brand-primary sm:text-sm">
                </div>
                
                <div class="w-full sm:w-48">
                    <label for="kategori" class="sr-only">Kategori</label>
                    <select name="kategori" id="kategori" class="block w-full rounded-lg border-slate-200 focus:border-brand-primary focus:ring-brand-primary sm:text-sm bg-white">
                        <option value="">-- Semua Kategori --</option>
                        <option value="BB" {{ request('kategori') == 'BB' ? 'selected' : '' }}>Bahan Baku</option>
                        <option value="ISIAN" {{ request('kategori') == 'ISIAN' ? 'selected' : '' }}>Isian</option>
                        <option value="GA" {{ request('kategori') == 'GA' ? 'selected' : '' }}>General Affair</option>
                    </select>
                </div>

                <div class="w-full sm:w-48">
                    <label for="target_role" class="sr-only">Role Pengguna</label>
                    <select name="target_role" id="target_role" class="block w-full rounded-lg border-slate-200 focus:border-brand-primary focus:ring-brand-primary sm:text-sm bg-white">
                        <option value="">-- Semua Role --</option>
                        <option value="staff_admin" {{ request('target_role') == 'staff_admin' ? 'selected' : '' }}>Staff Admin</option>
                        <option value="staff_produksi" {{ request('target_role') == 'staff_produksi' ? 'selected' : '' }}>Staff Produksi</option>
                        <option value="staff_dapur" {{ request('target_role') == 'staff_dapur' ? 'selected' : '' }}>Staff Dapur</option>
                        <option value="staff_pastry" {{ request('target_role') == 'staff_pastry' ? 'selected' : '' }}>Staff Pastry</option>
                        <option value="all" {{ request('target_role') == 'all' ? 'selected' : '' }}>All (Admin & Produksi)</option>
                    </select>
                </div>

                <button type="submit" class="inline-flex items-center px-4 py-2 bg-slate-800 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-700 active:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Cari & Filter
                </button>
                @if(request('search') || request('kategori') || request('target_role'))
                <a href="{{ route('master-produk.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 rounded-lg font-semibold text-xs text-slate-700 uppercase tracking-widest shadow-sm hover:bg-slate-50 transition ease-in-out duration-150">
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
                            <th class="px-6 py-3 font-semibold">Kode Produk</th>
                            <th class="px-6 py-3 font-semibold">Nama Produk</th>
                            <th class="px-6 py-3 font-semibold">Kategori</th>
                            <th class="px-6 py-3 font-semibold">Satuan</th>
                            <th class="px-6 py-3 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($produks as $produk)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 text-slate-600">{{ $produks->firstItem() + $loop->index }}</td>
                                <td class="px-6 py-4 font-medium text-slate-700">{{ $produk->kode_produk }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $produk->nama_produk }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] uppercase font-bold bg-slate-100 text-slate-700">
                                        {{ $produk->kategori }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-600">{{ $produk->satuan }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <a href="{{ route('master-produk.edit', $produk) }}" class="text-blue-600 hover:text-blue-800">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        <form action="{{ route('master-produk.destroy', $produk) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
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
                                <td colspan="6" class="px-6 py-10 text-center text-slate-500">
                                    Belum ada produk yang terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($produks->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $produks->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
