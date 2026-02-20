<x-app-layout>
    <x-slot name="header">
        Edit Produk
    </x-slot>

    <div class="max-w-2xl bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden mx-auto">
        <div class="p-8">
            <form action="{{ route('master-produk.update', $masterProduk) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                <div>
                    <label for="kode_produk" class="block text-sm font-medium text-slate-700 mb-1">Kode Produk</label>
                    <input type="text" name="kode_produk" id="kode_produk" value="{{ old('kode_produk', $masterProduk->kode_produk) }}" required class="block w-full border border-slate-200 rounded-lg py-2 px-3 focus:outline-none focus:ring-1 focus:ring-brand-primary focus:border-brand-primary sm:text-sm transition">
                    @error('kode_produk') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="nama_produk" class="block text-sm font-medium text-slate-700 mb-1">Nama Produk</label>
                    <input type="text" name="nama_produk" id="nama_produk" value="{{ old('nama_produk', $masterProduk->nama_produk) }}" required class="block w-full border border-slate-200 rounded-lg py-2 px-3 focus:outline-none focus:ring-1 focus:ring-brand-primary focus:border-brand-primary sm:text-sm transition">
                    @error('nama_produk') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="kategori" class="block text-sm font-medium text-slate-700 mb-1">Kategori</label>
                    <select name="kategori" id="kategori" required class="block w-full border border-slate-200 rounded-lg py-2 px-3 focus:outline-none focus:ring-1 focus:ring-brand-primary focus:border-brand-primary sm:text-sm transition">
                        <option value="BB" {{ old('kategori', $masterProduk->kategori) == 'BB' ? 'selected' : '' }}>Bahan Baku (BB)</option>
                        <option value="ISIAN" {{ old('kategori', $masterProduk->kategori) == 'ISIAN' ? 'selected' : '' }}>Isian</option>
                        <option value="GA" {{ old('kategori', $masterProduk->kategori) == 'GA' ? 'selected' : '' }}>General Affair (GA)</option>
                    </select>
                    @error('kategori') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div id="target_role_wrap">
                    <label for="target_role" class="block text-sm font-medium text-slate-700 mb-1">Dipakai Oleh (Target Role)</label>
                    <select name="target_role" id="target_role" class="block w-full border border-slate-200 rounded-lg py-2 px-3 focus:outline-none focus:ring-1 focus:ring-brand-primary focus:border-brand-primary sm:text-sm transition">
                        <option value="">-- Tidak Dispesifikasi --</option>
                        <option value="staff_admin" {{ old('target_role', $masterProduk->target_role) == 'staff_admin' ? 'selected' : '' }}>Staff Admin</option>
                        <option value="staff_produksi" {{ old('target_role', $masterProduk->target_role) == 'staff_produksi' ? 'selected' : '' }}>Staff Produksi</option>
                        <option value="staff_dapur" {{ old('target_role', $masterProduk->target_role) == 'staff_dapur' ? 'selected' : '' }}>Staff Dapur</option>
                        <option value="staff_pastry" {{ old('target_role', $masterProduk->target_role) == 'staff_pastry' ? 'selected' : '' }}>Staff Pastry</option>
                        <option value="all" {{ old('target_role', $masterProduk->target_role) == 'all' ? 'selected' : '' }}>Semua (Semua Staff Cabang)</option>
                    </select>
                    <p class="mt-1 text-xs text-slate-500 italic">Tentukan siapa yang boleh request produk ini. Pilih "Semua" jika berlaku untuk semua staff cabang.</p>
                    @error('target_role') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Assign ke Cabang (Opsional)</label>
                    <div class="grid grid-cols-2 gap-3 bg-slate-50 p-4 rounded-lg border border-slate-100">
                        @foreach($cabangs as $cabang)
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="cabang_ids[]" value="{{ $cabang->id }}" 
                                    {{ $masterProduk->cabangs->contains($cabang->id) ? 'checked' : '' }}
                                    class="rounded border-slate-300 text-brand-primary focus:ring-brand-primary">
                                <span class="ml-2 text-sm text-slate-600">{{ $cabang->nama }}</span>
                            </label>
                        @endforeach
                    </div>
                    <p class="mt-2 text-xs text-slate-500 italic">Kosongkan jika produk ini tersedia untuk semua cabang.</p>
                </div>

                <div>
                    <label for="satuan" class="block text-sm font-medium text-slate-700 mb-1">Satuan</label>
                    <input type="text" name="satuan" id="satuan" value="{{ old('satuan', $masterProduk->satuan) }}" required class="block w-full border border-slate-200 rounded-lg py-2 px-3 focus:outline-none focus:ring-1 focus:ring-brand-primary focus:border-brand-primary sm:text-sm transition">
                    @error('satuan') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-end space-x-3 pt-4">
                    <a href="{{ route('master-produk.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 rounded-lg font-semibold text-xs text-slate-700 uppercase tracking-widest shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-brand-primary border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-brand-secondary focus:bg-brand-secondary active:bg-brand-secondary focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2 transition ease-in-out duration-150">
                        Perbarui Produk
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleTargetRole(select) {
            const wrap = document.getElementById('target_role_wrap');
            if (['BB', 'ISIAN'].includes(select.value)) {
                wrap.classList.remove('hidden');
            } else {
                wrap.classList.add('hidden');
                document.getElementById('target_role').value = '';
            }
        }
    </script>
</x-app-layout>
