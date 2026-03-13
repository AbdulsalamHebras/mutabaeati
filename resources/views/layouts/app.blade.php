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
                --primary: #1d4ed8; /* Blue-700 */
                --primary-dark: #1e3a8a; /* Blue-900 */
                --accent: #ffcc00; /* Yellow highlight */
            }
            
            body {
                font-family: 'Almarai', 'Outfit', sans-serif !important;
                background-color: #ffffff;
                margin: 0;
                padding: 0;
            }

            .hero-gradient {
                background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 50%, #1e3a8a 100%);
                color: white;
            }

            .glass-panel {
                background: rgba(255, 255, 255, 0.85);
                backdrop-filter: blur(16px);
                border: 1px solid rgba(255, 255, 255, 0.4);
            }

            .premium-shadow {
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
            }

            /* Fix for overlapping navbar */
            .nav-spacer {
                height: 80px;
            }
        </style>

        @stack('styles')
    </head>
    <body class="antialiased text-slate-900 selection:bg-blue-100 selection:text-blue-700">
        <div class="min-h-screen">
            @include('layouts.navigation')
            <div class="nav-spacer"></div>

            <!-- Page Heading -->
            @if (isset($header))
                <header class="relative py-10 overflow-hidden">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main class="relative z-10 pb-20">
                {{ $slot }}
            </main>
        </div>

        @stack('scripts')
    </body>
</html>
