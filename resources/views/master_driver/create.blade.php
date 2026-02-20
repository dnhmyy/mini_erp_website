<x-app-layout>
    <x-slot name="header">
        Tambah Kurir
    </x-slot>

    <div class="max-w-2xl">
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
            <form action="{{ route('master-driver.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="nama" class="block text-sm font-medium text-slate-700 mb-1">Nama Kurir</label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-brand-primary focus:border-brand-primary transition">
                        @error('nama')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end pt-4">
                        <a href="{{ route('master-driver.index') }}" class="text-sm text-slate-500 hover:text-slate-800 mr-4 transition">Batal</a>
                        <button type="submit" class="px-6 py-2 bg-brand-primary text-white rounded-lg text-sm font-semibold hover:bg-brand-secondary transition">Simpan Kurir</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
