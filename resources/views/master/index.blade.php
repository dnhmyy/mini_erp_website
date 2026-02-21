<x-app-layout>
    <x-slot name="header">
        Master Data Center
    </x-slot>

    <div class="space-y-8">
        <div class="bg-gradient-to-r from-brand-primary to-brand-secondary rounded-2xl p-8 text-white shadow-lg overflow-hidden relative">
            <div class="relative z-10">
                <h2 class="text-3xl font-bold mb-2">Pusat Data Master</h2>
                <p class="text-amber-100 max-w-2xl text-lg">Kelola semua data fundamental aplikasi dari satu tempat. Tambah, edit, atau cari produk, driver, dan cabang dengan mudah.</p>
            </div>
            <!-- Subtle background decoration -->
            <div class="absolute -right-20 -top-20 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute -left-20 -bottom-20 w-60 h-60 bg-black/10 rounded-full blur-2xl"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($stats as $key => $data)
            <div class="group bg-white rounded-2xl shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 p-6 flex flex-col items-center text-center">
                <div class="w-16 h-16 rounded-2xl bg-slate-50 flex items-center justify-center text-brand-primary mb-4 group-hover:bg-brand-primary group-hover:text-white transition-colors duration-300">
                    @if($data['icon'] == 'box')
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    @elseif($data['icon'] == 'truck')
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    @elseif($data['icon'] == 'office-building')
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    @else
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    @endif
                </div>
                
                <h3 class="text-xl font-bold text-slate-800 mb-1 uppercase">{{ $key }}</h3>
                <div class="text-3xl font-extrabold text-brand-primary mb-4">{{ $data['total'] }}</div>
                
                @if($key === 'produk')
                    <div class="flex space-x-2 mb-6">
                        <div class="px-2 py-1 bg-blue-50 text-blue-700 text-[10px] font-bold rounded-md">BB: {{ $data['bb'] }}</div>
                        <div class="px-2 py-1 bg-purple-50 text-purple-700 text-[10px] font-bold rounded-md">ISIAN: {{ $data['isian'] }}</div>
                        <div class="px-2 py-1 bg-emerald-50 text-emerald-700 text-[10px] font-bold rounded-md">GA: {{ $data['ga'] }}</div>
                    </div>
                @endif

                <a href="{{ route($data['route']) }}" class="mt-auto w-full inline-flex items-center justify-center px-4 py-2 bg-slate-800 text-white text-sm font-semibold rounded-xl hover:bg-slate-700 transition-colors shadow-sm">
                    {{ $data['label'] }}
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>
            @endforeach
        </div>

        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-xl">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        <strong>Tips Keamanan:</strong> Gunakan tombol "Pilihan Produk" untuk mengatur ketersediaan barang di tiap cabang. Produk yang tidak di-assign ke satu cabang pun akan otomatis muncul di <strong>SEMUA</strong> cabang.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
