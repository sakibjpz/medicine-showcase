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

    @stack('scripts')
</body>
</html>
