<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-50" x-data="{ sidebarOpen: false }">
        <div class="flex min-h-screen relative">
            <!-- Mobile Overlay -->
            <div x-show="sidebarOpen" 
                 x-transition:enter="transition-opacity ease-linear duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm lg:hidden"
                 @click="sidebarOpen = false" 
                 style="display: none;"></div>
            <!-- Sidebar -->
            @include('layouts.sidebar')

            <!-- Main Content Area -->
            <div class="flex-1 lg:ml-64 flex flex-col min-w-0 transition-all duration-300">
                <!-- Topbar -->
                <header class="bg-white shadow-sm h-20 px-4 sm:px-8 flex items-center justify-between sticky top-0 z-10 border-b border-slate-100">
                    <div class="flex items-center">
                        <button @click="sidebarOpen = true" class="p-2 mr-2 text-slate-500 hover:text-brand-primary hover:bg-slate-100 rounded-lg focus:outline-none lg:hidden transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        </button>
                        
                        <div class="hidden sm:block ml-2">
                            @php
                                $hour = now()->format('H');
                                if ($hour < 12) {
                                    $greeting = 'Selamat Pagi';
                                } elseif ($hour < 15) {
                                    $greeting = 'Selamat Siang';
                                } elseif ($hour < 18) {
                                    $greeting = 'Selamat Sore';
                                } else {
                                    $greeting = 'Selamat Malam';
                                }
                            @endphp
                            <h2 class="text-lg font-bold text-slate-800 tracking-tight">{{ $greeting }}, {{ explode(' ', Auth::user()->name)[0] }}!</h2>
                            <p class="text-xs text-slate-500 font-medium">{{ now()->translatedFormat('l, d F Y') }}</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3 sm:space-x-4">
                        <div class="text-right mr-1 sm:mr-2">
                            <div class="text-sm font-semibold text-slate-700 leading-tight block truncate max-w-[120px] sm:max-w-xs">{{ Auth::user()->name }}</div>
                            @php
                                $roleLabels = [
                                    'superuser'     => 'Super User',
                                    'staff_gudang'  => 'Staff Gudang',
                                    'staff_admin'   => 'Staff Admin',
                                    'staff_produksi'=> 'Staff Produksi',
                                    'staff_dapur'   => 'Staff Dapur',
                                    'staff_pastry'  => 'Staff Pastry',
                                ];
                                $roleDisplay = $roleLabels[Auth::user()->role] ?? str_replace('_', ' ', Auth::user()->role);
                            @endphp
                            <div class="text-xs text-slate-500 uppercase tracking-wider">{{ $roleDisplay }}</div>
                        </div>
                        <div class="h-10 w-10 rounded-full bg-brand-primary flex items-center justify-center text-white font-bold ring-2 ring-slate-100">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </div>
                </header>

                <!-- Page Heading -->
                @isset($header)
                    <div class="px-4 sm:px-8 pt-6 sm:pt-8 pb-4">
                        <h2 class="font-bold text-xl sm:text-2xl text-slate-800 leading-tight">
                            {{ $header }}
                        </h2>
                    </div>
                @endisset

                <!-- Page Content -->
                <main class="flex-1 px-4 sm:px-8 py-4 overflow-x-hidden">
                    {{ $slot }}
                </main>

                <!-- Footer -->
                <footer class="bg-white border-t border-slate-100 py-6 mt-auto">
                    <div class="text-center text-xs text-slate-400 tracking-wide">
                        © {{ date('Y') }} RotiKebanggaan - Developed by DnnTech
                    </div>
                </footer>
            </div>
        </div>
    </body>
</html>
