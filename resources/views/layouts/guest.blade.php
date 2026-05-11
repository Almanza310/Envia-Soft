<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">



        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-black antialiased bg-slate-50 relative overflow-hidden">
        <!-- Decorative background elements -->
        <div class="fixed inset-0 z-0 bg-gradient-to-br from-red-50/60 via-white to-blue-50/40 pointer-events-none"></div>
        <div class="fixed -top-32 -right-32 w-96 h-96 bg-blue-100 rounded-full mix-blend-multiply filter blur-3xl  pointer-events-none"></div>
        <div class="fixed -bottom-48 -left-48 w-[500px] h-[500px] bg-red-100 rounded-full mix-blend-multiply filter blur-3xl  pointer-events-none"></div>

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative z-10 w-full px-4">
            <div class="mb-6">
                <a href="/" class="inline-flex items-center justify-center transition-all duration-300 hover:scale-105">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo ENVIA" class="h-28 w-auto drop-shadow-md">
                </a>
            </div>

            <div class="w-full sm:max-w-[420px] bg-white shadow-2xl shadow-gray-200/50 sm:rounded-xl px-10 py-12 relative">
                {{ $slot }}
            </div>


        </div>
    </body>
</html>

