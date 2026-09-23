<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Authentication - MedSource')</title>
    <meta name="description" content="Secure access to the MedSource professional portal.">

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans text-slate-800 antialiased min-h-screen bg-slate-50">

    <div class="min-h-screen flex flex-col md:flex-row">
        {{-- Branded side panel --}}
        <div class="hidden md:flex md:w-5/12 lg:w-1/3 bg-navy-900 text-white flex-col justify-between p-10 lg:p-12">
            <div>
                <a href="{{ route('home') }}" class="inline-flex items-center gap-3" aria-label="MedSource Home">
                    <div class="w-12 h-12 rounded-full bg-med-600 flex items-center justify-center text-white font-bold text-xl">M</div>
                    <span class="text-2xl font-bold tracking-tight">MedSource</span>
                </a>
            </div>

            <div class="space-y-8">
                <div>
                    <h2 class="text-3xl lg:text-4xl font-bold leading-tight">Global Medical Platforms and Centers</h2>
                    <p class="mt-4 text-med-100 text-lg">Secure, professional-grade access to product information, regulatory data and support tools.</p>
                </div>

                <ul class="space-y-4 text-slate-200">
                    <li class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-med-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span>Centralised product &amp; regulatory data</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-med-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span>Secure role-based administration</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-med-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span>Quality-first professional access</span>
                    </li>
                </ul>
            </div>

            <div class="text-sm text-slate-400">
                &copy; {{ date('Y') }} MedSource. All rights reserved.
            </div>
        </div>

        {{-- Form panel --}}
        <div class="flex-1 flex items-center justify-center p-6 md:p-12 lg:p-16">
            <div class="w-full max-w-lg bg-white rounded-2xl shadow-mega p-8 lg:p-10">
                @yield('content')
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
