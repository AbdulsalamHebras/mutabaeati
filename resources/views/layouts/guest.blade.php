<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'متابعاتي') }}</title>

        <!-- Premium Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Almarai:wght@300;400;700;800&family=Outfit:wght@300;400;600;800;900&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            :root {
                --primary: #2563eb;
                --primary-hover: #1d4ed8;
                --bg-main: #f8fafc;
                --input-bg: #eff6ff;
            }
            
            body {
                font-family: 'Almarai', 'Outfit', sans-serif !important;
                background-color: var(--bg-main);
                background-image: none; /* Removed mesh gradient for cleaner look */
            }

            .premium-card {
                background: #ffffff;
                border: 1px solid #f1f5f9;
                box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
            }

            .premium-input {
                background-color: var(--input-bg) !important;
                border: 1px solid transparent !important;
                border-radius: 0.85rem !important;
                transition: all 0.2s ease-in-out !important;
            }

            .premium-input:focus {
                background-color: #ffffff !important;
                border-color: var(--primary) !important;
                box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1) !important;
            }
        </style>
    </head>
    <body class="antialiased text-slate-900 flex items-center justify-center min-h-screen p-6">
        <div class="w-full max-w-[500px]">
            {{ $slot }}
        </div>
    </body>
</html>
