@extends('layouts.portal')

@section('title', 'Search Results')

@section('content')
<section class="bg-med-700 text-white py-12 lg:py-16">
    <div class="container-site">
        <h1 class="text-3xl md:text-4xl font-bold mb-4">{{ $heading }}</h1>
        <p class="text-lg text-med-100">{{ $lead }}</p>

        <form action="{{ route('search') }}" method="GET" class="mt-6 max-w-2xl" role="search">
            <label for="q" class="sr-only">Search</label>
            <div class="flex flex-col sm:flex-row gap-2">
                <input id="q" name="q" type="search" value="{{ $query }}" class="flex-1 rounded-lg px-4 py-3 text-slate-800" placeholder="Search products, pages, manufacturers and more">
                <button type="submit" class="btn-primary bg-white text-med-700 hover:bg-med-50 w-full sm:w-auto text-center">Search</button>
            </div>
        </form>
    </div>
</section>

<section class="container-site py-10 lg:py-14">
    @if ($query === '')
        <div class="bg-slate-50 rounded-xl p-8 text-center">
            <p class="text-slate-600">Enter a product, manufacturer, therapeutic area, country or page above to see structured results.</p>
        </div>
    @elseif (empty($results))
        <div class="bg-amber-50 border border-amber-100 rounded-xl p-8 text-center">
            <p class="text-amber-800 font-medium">No results found for "{{ $query }}".</p>
            <p class="text-amber-700 text-sm mt-1">Try a different keyword or browse <a href="{{ route('products') }}" class="underline">All Products</a>.</p>
        </div>
    @else
        <h2 class="text-xl font-bold text-med-900 mb-6">{{ count($results) }} result(s)</h2>
        <div class="space-y-4">
            @foreach ($results as $result)
                <a href="{{ $result['url'] }}" class="block p-5 border border-slate-200 rounded-lg hover:border-med-500 hover:shadow transition">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="text-xs font-semibold uppercase tracking-wider px-2 py-1 rounded bg-med-100 text-med-700">{{ $result['type'] }}</span>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900">{{ $result['title'] }}</h3>
                    <p class="text-slate-600 text-sm mt-1">{{ $result['summary'] }}</p>
                </a>
            @endforeach
        </div>
    @endif
</section>
@endsection
