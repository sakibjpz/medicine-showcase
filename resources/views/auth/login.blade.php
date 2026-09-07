@extends('layouts.auth')

@section('title', 'Log in - MedSource')

@section('content')
    <div class="md:hidden text-center mb-8">
        <a href="{{ route('home') }}" class="inline-flex items-center gap-3" aria-label="MedSource Home">
            <div class="w-10 h-10 rounded-full bg-med-600 flex items-center justify-center text-white font-bold text-lg">M</div>
            <span class="text-xl font-bold text-navy-900">MedSource</span>
        </a>
        <p class="mt-2 text-sm text-slate-500">Global Medical Platforms and Centers</p>
    </div>

    <h1 class="text-2xl font-bold text-navy-900 mb-2">Welcome back</h1>
    <p class="text-slate-500 mb-8">Log in to access the MedSource professional portal.</p>

    @if (session('status'))
        <div class="mb-6 p-4 rounded-lg bg-med-50 border border-med-200 text-med-800 text-sm" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <div>
            <label for="email" class="block text-sm font-semibold text-slate-700">Email address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                   class="mt-2 block w-full rounded-lg border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-med-500 focus:ring-med-500 sm:text-sm"
                   placeholder="you@example.com">
            @error('email')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-semibold text-slate-700">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                   class="mt-2 block w-full rounded-lg border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-med-500 focus:ring-med-500 sm:text-sm"
                   placeholder="••••••••">
            @error('password')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between gap-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-med-600 focus:ring-med-500">
                <span class="ml-2 text-sm text-slate-600">Remember me</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-med-700 hover:text-med-800 hover:underline whitespace-nowrap" href="{{ route('password.request') }}">
                    Forgot your password?
                </a>
            @endif
        </div>

        <button type="submit" class="w-full flex justify-center rounded-lg bg-med-600 px-4 py-3 text-sm font-semibold text-white shadow hover:bg-med-700 focus:outline-none focus:ring-2 focus:ring-med-500 focus:ring-offset-2 transition">
            Log in
        </button>

        <p class="text-center text-sm text-slate-500">
            Back to <a href="{{ route('home') }}" class="text-med-700 hover:text-med-800 hover:underline">MedSource home</a>
        </p>
    </form>
@endsection
