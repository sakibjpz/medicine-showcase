<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'MedSource – Global Medical Platforms and Centers')</title>
    <meta name="description" content="@yield('meta_description', 'MedSource is a professional-grade medical information portal for healthcare professionals, pharmacists, and informed patients.')">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans text-slate-800 bg-white antialiased flex flex-col min-h-screen">

    @include('partials.header')

    @if (!empty($breadcrumbs) && is_array($breadcrumbs))
        @include('partials.breadcrumbs')
    @endif

    <main class="flex-1" id="main-content">
        @yield('content')
    </main>

    @include('partials.footer')

    {{-- Mobile bottom navigation --}}
    <nav class="fixed bottom-0 inset-x-0 z-40 bg-white border-t border-slate-200 shadow-[0_-2px_12px_rgba(11,30,62,0.08)] md:hidden" aria-label="Quick navigation">
        <div class="grid grid-cols-4">
            <a href="{{ route('home') }}" class="flex flex-col items-center gap-1 py-2 text-[10px] font-semibold {{ request()->routeIs('home') ? 'text-med-700' : 'text-slate-500' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                Home
            </a>
            <a href="{{ route('products') }}" class="flex flex-col items-center gap-1 py-2 text-[10px] font-semibold {{ request()->routeIs('products', 'products.*') ? 'text-med-700' : 'text-slate-500' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                Products
            </a>
            <a href="{{ route('search') }}" class="flex flex-col items-center gap-1 py-2 text-[10px] font-semibold {{ request()->routeIs('search') ? 'text-med-700' : 'text-slate-500' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                Search
            </a>
            <a href="{{ route('professional-enquiry') }}" class="flex flex-col items-center gap-1 py-2 text-[10px] font-semibold {{ request()->routeIs('professional-enquiry') ? 'text-med-700' : 'text-slate-500' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                Enquiry
            </a>
        </div>
    </nav>

    @stack('scripts')
</body>
</html>
