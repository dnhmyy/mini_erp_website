<x-app-layout>
    <x-slot name="header">
        Tambah Cabang
    </x-slot>

    <div class="max-w-2xl bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden mx-auto">
        <div class="p-8">
            <form action="{{ route('cabang.store') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label for="nama" class="block text-sm font-medium text-slate-700 mb-1">Nama Cabang</label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required class="block w-full border border-slate-200 rounded-lg py-2 px-3 focus:outline-none focus:ring-1 focus:ring-brand-primary focus:border-brand-primary sm:text-sm transition">
                    @error('nama') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="alamat" class="block text-sm font-medium text-slate-700 mb-1">Alamat</label>
                    <textarea name="alamat" id="alamat" rows="3" class="block w-full border border-slate-200 rounded-lg py-2 px-3 focus:outline-none focus:ring-1 focus:ring-brand-primary focus:border-brand-primary sm:text-sm transition">{{ old('alamat') }}</textarea>
                    @error('alamat') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-end space-x-3 pt-4">
                    <a href="{{ route('cabang.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 rounded-lg font-semibold text-xs text-slate-700 uppercase tracking-widest shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-brand-primary border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-brand-secondary focus:bg-brand-secondary active:bg-brand-secondary focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2 transition ease-in-out duration-150">
                        Simpan Cabang
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
