<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Admin' }} | MedSource</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 text-slate-800 antialiased">
    <div class="min-h-screen flex flex-col lg:flex-row">
        {{-- Sidebar --}}
        <aside class="bg-navy-900 text-slate-200 w-full lg:w-64 lg:fixed lg:top-0 lg:left-0 lg:h-screen lg:overflow-y-auto">
            <div class="p-5 border-b border-navy-800">
                <a href="{{ route('home') }}" class="flex items-center gap-2 text-white font-bold">
                    <span class="w-8 h-8 rounded-full bg-med-600 flex items-center justify-center text-sm">M</span>
                    <span class="uppercase tracking-wider text-sm">MedSource</span>
                </a>
                <p class="text-xs text-slate-400 mt-1">Admin Panel</p>
            </div>
            <nav class="p-3 space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded hover:bg-navy-800 {{ request()->routeIs('admin.dashboard') ? 'bg-med-600 text-white' : 'text-slate-200' }}">Dashboard</a>
                <a href="{{ route('admin.pages') }}" class="block px-3 py-2 rounded hover:bg-navy-800 {{ request()->routeIs('admin.pages*') ? 'bg-med-600 text-white' : 'text-slate-200' }}">Pages</a>
                <a href="{{ route('admin.products.index') }}" class="block px-3 py-2 rounded hover:bg-navy-800 {{ request()->routeIs('admin.products*') ? 'bg-med-600 text-white' : 'text-slate-200' }}">Products</a>
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
        <main class="flex-1 p-6 lg:p-10 lg:pl-64 overflow-x-auto">
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
