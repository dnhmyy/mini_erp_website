<x-app-layout>
    <x-slot name="header">
        Edit User
    </x-slot>

    <div class="max-w-2xl bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden mx-auto">
        <div class="p-8">
            <form action="{{ route('users.update', $user) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="block w-full border border-slate-200 rounded-lg py-2 px-3 focus:outline-none focus:ring-1 focus:ring-brand-primary focus:border-brand-primary sm:text-sm transition">
                    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Username</label>
                    <input type="text" name="email" id="email" value="{{ old('email', $user->email) }}" required class="block w-full border border-slate-200 rounded-lg py-2 px-3 focus:outline-none focus:ring-1 focus:ring-brand-primary focus:border-brand-primary sm:text-sm transition">
                    @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Password (Kosongkan jika tidak ingin mengubah)</label>
                    <input type="password" name="password" id="password" class="block w-full border border-slate-200 rounded-lg py-2 px-3 focus:outline-none focus:ring-1 focus:ring-brand-primary focus:border-brand-primary sm:text-sm transition">
                    @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="role" class="block text-sm font-medium text-slate-700 mb-1">Role</label>
                    <select name="role" id="role" class="block w-full border border-slate-200 rounded-lg py-2 px-3 focus:outline-none focus:ring-1 focus:ring-brand-primary focus:border-brand-primary sm:text-sm transition" onchange="toggleCabangSelection()">
                        <option value="superuser" {{ old('role', $user->role) == 'superuser' ? 'selected' : '' }}>Super User</option>
                        <option value="staff_gudang" {{ old('role', $user->role) == 'staff_gudang' ? 'selected' : '' }}>Staff Gudang</option>
                        <option value="staff_admin" {{ old('role', $user->role) == 'staff_admin' ? 'selected' : '' }}>Staff Admin</option>
                        <option value="staff_produksi" {{ old('role', $user->role) == 'staff_produksi' ? 'selected' : '' }}>Staff Produksi</option>
                        <option value="staff_dapur" {{ old('role', $user->role) == 'staff_dapur' ? 'selected' : '' }}>Staff Dapur</option>
                        <option value="staff_pastry" {{ old('role', $user->role) == 'staff_pastry' ? 'selected' : '' }}>Staff Pastry</option>
                    </select>
                    @error('role') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div id="cabang_selection" class="{{ in_array(old('role', $user->role), ['staff_admin', 'staff_produksi', 'staff_dapur', 'staff_pastry']) ? '' : 'hidden' }}">
                    <label for="cabang_id" class="block text-sm font-medium text-slate-700 mb-1">Pilih Cabang</label>
                    <select name="cabang_id" id="cabang_id" class="block w-full border border-slate-200 rounded-lg py-2 px-3 focus:outline-none focus:ring-1 focus:ring-brand-primary focus:border-brand-primary sm:text-sm transition">
                        <option value="">-- Pilih Cabang --</option>
                        @foreach($cabangs as $cabang)
                            <option value="{{ $cabang->id }}" {{ old('cabang_id', $user->cabang_id) == $cabang->id ? 'selected' : '' }}>{{ $cabang->nama }}</option>
                        @endforeach
                    </select>
                    @error('cabang_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-end space-x-3 pt-4">
                    <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 rounded-lg font-semibold text-xs text-slate-700 uppercase tracking-widest shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-brand-primary border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-brand-secondary focus:bg-brand-secondary active:bg-brand-secondary focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2 transition ease-in-out duration-150">
                        Perbarui User
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleCabangSelection() {
            const roleSelect = document.getElementById('role');
            const cabangSelection = document.getElementById('cabang_selection');
            const cabangInput = document.getElementById('cabang_id');

            if (['staff_admin', 'staff_produksi', 'staff_dapur', 'staff_pastry'].includes(roleSelect.value)) {
                cabangSelection.classList.remove('hidden');
            } else {
                cabangSelection.classList.add('hidden');
            }
        }
    </script>
</x-app-layout>
