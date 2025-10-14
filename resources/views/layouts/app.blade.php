<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', 'Valtus')</title>
        <meta name="color-scheme" content="dark light">
        
        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="/favicon/favicon.ico">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
        <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
        <link rel="shortcut icon" href="/favicon/favicon.ico">
        <link rel="manifest" href="/favicon/site.webmanifest">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <script src="https://cdn.tailwindcss.com"></script>
            <script>
                tailwind.config = {
                    theme: {
                        extend: {
                            fontFamily: { sans: ['Instrument Sans', 'ui-sans-serif', 'system-ui', 'sans-serif'] },
                            colors: {
                                gray: {
                                    50: '#f9f9f9', 100: '#f2f2f2', 200: '#e5e5e5', 300: '#d4d4d4',
                                    400: '#a3a3a3', 500: '#737373', 600: '#525252', 700: '#3f3f3f',
                                    800: '#262626', 900: '#171717'
                                }
                            }
                        }
                    }
                }
            </script>
            <style>
                :root { color-scheme: dark light; }
                html, body { font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif; }
                body { background:#111827; color:#f3f4f6; }
                a { color: inherit; text-decoration: none; }
                .border { border: 1px solid rgba(255,255,255,0.1); }
            </style>
            <link rel="stylesheet" href="{{ asset('css/admin-custom.css') }}">
        @endif
    </head>
    <body class="min-h-screen bg-gray-900 text-gray-100 antialiased selection:bg-white/10">
        <div class="w-full">
            @yield('body')
        </div>
    </body>
</html>


