<div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="flex flex-col w-64 bg-brand-primary h-screen fixed left-0 top-0 text-white shadow-xl transition-transform duration-300 ease-in-out transform z-50 lg:translate-x-0">
    <div class="flex items-center justify-center h-20 border-b border-brand-secondary px-4 relative">
        <!-- Close button mobile -->
        <button @click="sidebarOpen = false" class="absolute top-6 right-4 text-slate-200 hover:text-white lg:hidden">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
        <!-- Logo -->
        <img src="{{ asset('images/logo.png') }}" alt="Logo Roti" class="w-10 h-10 mr-2 drop-shadow-sm object-contain shrink-0">
        <span class="text-2xl font-bold tracking-tight text-white drop-shadow-sm truncate mt-0.5">
            RotiKebanggaan
        </span>
    </div>
    <div class="flex flex-col flex-1 overflow-y-auto mt-4">
        <nav class="flex-1 px-4 py-4 space-y-2">
            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg !text-slate-100 hover:bg-brand-secondary hover:text-amber-100 transition-colors duration-200">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Dashboard
            </x-nav-link>

            <div class="pt-4 pb-1 text-[10px] font-bold text-emerald-400/80 uppercase tracking-[0.2em] px-4">
                Permintaan
            </div>
            
            <x-nav-link :href="route('permintaan.index', ['kategori' => 'BB'])" :active="request()->query('kategori') === 'BB'" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg !text-slate-100 hover:bg-brand-secondary hover:text-amber-100 transition-colors duration-200">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                Permintaan Bahan Baku (BB)
            </x-nav-link>

            <x-nav-link :href="route('permintaan.index', ['kategori' => 'ISIAN'])" :active="request()->query('kategori') === 'ISIAN'" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg !text-slate-100 hover:bg-brand-secondary hover:text-amber-100 transition-colors duration-200">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                Permintaan Isian
            </x-nav-link>

            <x-nav-link :href="route('permintaan.index', ['kategori' => 'GA'])" :active="request()->query('kategori') === 'GA'" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg !text-slate-100 hover:bg-brand-secondary hover:text-amber-100 transition-colors duration-200">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                Permintaan GA
            </x-nav-link>

            <x-nav-link :href="route('permintaan.index')" :active="request()->routeIs('permintaan.index') && !request()->has('kategori')" class="hidden flex items-center px-4 py-2 text-sm font-medium rounded-lg !text-slate-100 hover:bg-brand-secondary hover:text-amber-100 transition-colors duration-200">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path></svg>
                Semua Permintaan
            </x-nav-link>

            @if(Auth::user()->isSuperUser())
            <div class="pt-6 pb-2 text-[10px] font-bold text-emerald-400/80 uppercase tracking-[0.2em] px-4">
                MASTER DATA
            </div>

            <div class="px-2 space-y-1">
                <a href="{{ route('master-produk.index') }}" 
                   class="flex items-center px-4 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 group {{ request()->routeIs('master-produk.*') ? 'bg-amber-400/10 text-amber-400' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('master-produk.*') ? 'text-amber-400' : 'text-slate-400 group-hover:text-amber-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    Master Produk
                </a>

                <a href="{{ route('cabang.index') }}" 
                   class="flex items-center px-4 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 group {{ request()->routeIs('cabang.*') ? 'bg-amber-400/10 text-amber-400' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('cabang.*') ? 'text-amber-400' : 'text-slate-400 group-hover:text-amber-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    Daftar Cabang
                </a>

                <a href="{{ route('master-driver.index') }}" 
                   class="flex items-center px-4 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 group {{ request()->routeIs('master-driver.*') ? 'bg-amber-400/10 text-amber-400' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('master-driver.*') ? 'text-amber-400' : 'text-slate-400 group-hover:text-amber-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Daftar Kurir
                </a>

                <a href="{{ route('users.index') }}" 
                   class="flex items-center px-4 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 group {{ request()->routeIs('users.*') ? 'bg-amber-400/10 text-amber-400' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('users.*') ? 'text-amber-400' : 'text-slate-400 group-hover:text-amber-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    User Control
                </a>

                <a href="{{ route('product-catalog.index') }}" 
                   class="flex items-center px-4 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 group {{ request()->routeIs('product-catalog.*') ? 'bg-amber-400/10 text-amber-400' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('product-catalog.*') ? 'text-amber-400' : 'text-slate-400 group-hover:text-amber-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    Katalog Barang
                </a>
            </div>
            @endif
        </nav>
    </div>

    <div class="p-4 border-t border-brand-secondary">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center w-full px-4 py-2 text-sm font-medium rounded-lg hover:bg-red-900/50 transition-colors duration-200">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Logout
            </button>
        </form>
    </div>
</div>
