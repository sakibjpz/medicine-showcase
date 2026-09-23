<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Admin' }} | MedSource</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 text-slate-800 antialiased">
    <div class="min-h-screen flex flex-col lg:flex-row" x-data="{ navOpen: false }">
        {{-- Mobile top bar --}}
        <div class="lg:hidden bg-navy-900 text-slate-200 sticky top-0 z-40 flex items-center justify-between px-4 py-3 border-b border-navy-800">
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-white font-bold">
                <span class="w-8 h-8 rounded-full bg-med-600 flex items-center justify-center text-sm">M</span>
                <span class="uppercase tracking-wider text-sm">MedSource</span>
                <span class="text-xs text-slate-400 font-normal">Admin</span>
            </a>
            <button type="button" @click="navOpen = !navOpen" class="p-2 rounded hover:bg-navy-800" aria-label="Toggle menu" :aria-expanded="navOpen">
                <svg x-show="!navOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg x-show="navOpen" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Sidebar: drawer on mobile, fixed column on desktop --}}
        <aside x-show="navOpen" style="display: none;" class="bg-navy-900 text-slate-200 w-full lg:w-64 lg:fixed lg:top-0 lg:left-0 lg:h-screen lg:overflow-y-auto lg:!block">
            <div class="hidden lg:block p-5 border-b border-navy-800">
                <a href="{{ route('home') }}" class="flex items-center gap-2 text-white font-bold">
                    <span class="w-8 h-8 rounded-full bg-med-600 flex items-center justify-center text-sm">M</span>
                    <span class="uppercase tracking-wider text-sm">MedSource</span>
                </a>
                <p class="text-xs text-slate-400 mt-1">Admin Panel</p>
            </div>
            <nav class="p-3 space-y-1" @click="navOpen = false">
                <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded hover:bg-navy-800 {{ request()->routeIs('admin.dashboard') ? 'bg-med-600 text-white' : 'text-slate-200' }}">Dashboard</a>
                <a href="{{ route('admin.pages') }}" class="block px-3 py-2 rounded hover:bg-navy-800 {{ request()->routeIs('admin.pages*') ? 'bg-med-600 text-white' : 'text-slate-200' }}">Pages</a>
                <a href="{{ route('admin.headlines.index') }}" class="block px-3 py-2 rounded hover:bg-navy-800 {{ request()->routeIs('admin.headlines*') ? 'bg-med-600 text-white' : 'text-slate-200' }}">Headlines</a>
                <a href="{{ route('admin.banners.index') }}" class="block px-3 py-2 rounded hover:bg-navy-800 {{ request()->routeIs('admin.banners*') ? 'bg-med-600 text-white' : 'text-slate-200' }}">Banners</a>
                <a href="{{ route('admin.ticker.index') }}" class="block px-3 py-2 rounded hover:bg-navy-800 {{ request()->routeIs('admin.ticker*') ? 'bg-med-600 text-white' : 'text-slate-200' }}">Updates Ticker</a>
                <a href="{{ route('admin.products.index') }}" class="block px-3 py-2 rounded hover:bg-navy-800 {{ request()->routeIs('admin.products*') ? 'bg-med-600 text-white' : 'text-slate-200' }}">Products</a>
                <a href="{{ route('admin.categories.index') }}" class="block px-3 py-2 rounded hover:bg-navy-800 {{ request()->routeIs('admin.categories*') ? 'bg-med-600 text-white' : 'text-slate-200' }}">Categories</a>
                <a href="{{ route('admin.enquiries') }}" class="block px-3 py-2 rounded hover:bg-navy-800 {{ request()->routeIs('admin.enquiries*') ? 'bg-med-600 text-white' : 'text-slate-200' }}">Enquiries</a>
                <a href="{{ route('admin.messages') }}" class="block px-3 py-2 rounded hover:bg-navy-800 {{ request()->routeIs('admin.messages*') ? 'bg-med-600 text-white' : 'text-slate-200' }}">Contact Messages</a>
            </nav>
            <div class="p-3 border-t border-navy-800 mt-4 lg:mt-0">
                <form method="POST" action="{{ route('logout') }}" class="block">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 rounded text-slate-200 hover:bg-navy-800">Log out</button>
                </form>
            </div>
        </aside>

        {{-- Main content --}}
        <main class="flex-1 p-4 sm:p-6 lg:p-10 lg:pl-64 overflow-x-auto">
            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded" role="status">
                    {{ session('success') }}
                </div>
            @endif

            @if (isset($title))
                <h1 class="text-2xl font-bold text-navy-900 mb-6">{{ $title }}</h1>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
