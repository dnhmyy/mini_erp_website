<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
        
        <style>
            .font-custom {
                font-family: -apple-system, BlinkMacSystemFont, 'SF Pro Text', 'SF Pro Display', 'Inter', system-ui, sans-serif;
            }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-custom text-slate-800 antialiased bg-[#001f18] relative min-h-screen flex items-center justify-center p-4">
        
        <!-- Ornamen Latar -->
        <div class="absolute inset-0 pointer-events-none z-0">
            <!-- Overlay Image Background with blur equivalent to style.css -->
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat opacity-[0.30] blur-[8px]" style="background-image: url('{{ asset('images/login-bg.png') }}');"></div>
        </div>

        <div class="w-full max-w-[400px] bg-white/90 backdrop-blur-[25px] saturate-[180%] shadow-[0_16px_48px_rgba(0,0,0,0.15)] rounded-[24px] border border-white/30 text-center relative z-10 p-8 sm:p-8">
            <!-- Header Card -->
            <div class="text-center mb-5">
                <a href="/" class="inline-block relative z-10 transition-transform hover:scale-105">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Roti" class="w-[120px] h-auto mx-auto mb-1 drop-shadow-md object-contain">
                </a>
                <h2 class="mt-0 text-[2rem] font-[800] tracking-[-0.05em] leading-[1.1] text-[#001f18]">
                    RotiKebanggaan
                </h2>
                <p class="mt-0 text-[1rem] leading-[1.4] text-[#3a3a3c] font-medium mb-[2.5rem]">Silakan masuk ke akun Anda untuk melanjutkan.</p>
            </div>

            <!-- Body Card / Slot -->
            <div class="text-left">
                {{ $slot }}
            </div>
            
        </div>
    </body>
</html>
