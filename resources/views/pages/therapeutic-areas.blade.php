@extends('layouts.portal')

@section('title', $title . ' – MedSource')

@section('content')
<section class="bg-med-700 text-white py-16 lg:py-20">
    <div class="container-site">
        <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-4">{{ $heading }}</h1>
        @if (!empty($lead))
            <p class="text-lg md:text-xl text-med-100 max-w-3xl">{{ $lead }}</p>
        @endif
    </div>
</section>

<section class="container-site py-10 lg:py-14">
    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-6">Our categories</p>
    <h2 class="text-2xl font-bold text-navy-900 mb-2">Lots of new products and product collections</h2>
    <p class="text-slate-500 mb-8">Browse therapeutic areas and their subcategories.</p>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
        @foreach ($productsByCategory as $category => $items)
            <a href="{{ route('products') }}?category={{ urlencode($category) }}" class="group text-center">
                <div class="w-32 h-32 mx-auto rounded-full bg-gradient-to-br from-med-600 to-navy-900 flex items-center justify-center text-white text-2xl font-bold shadow-md group-hover:scale-105 transition">
                    {{ strtoupper(substr($category, 0, 1)) }}
                </div>
                <span class="block mt-3 text-sm font-medium text-slate-800 group-hover:text-med-700">{{ $category }}</span>
                <span class="text-xs text-slate-500">{{ $items->sum('count') }} product{{ $items->sum('count') !== 1 ? 's' : '' }}</span>
            </a>
        @endforeach
    </div>

    <div class="mt-12 p-6 bg-med-50 border border-med-100 rounded-xl lg:flex lg:items-center lg:justify-between gap-6">
        <div>
            <h2 class="text-xl font-bold text-med-900 mb-2">Need professional support?</h2>
            <p class="text-slate-600">Our team provides quality assurance, worldwide reach, and professional response.</p>
        </div>
        <a href="{{ route('professional-enquiry') }}" class="btn-primary w-full sm:w-auto mt-4 lg:mt-0 text-center">Submit Enquiry</a>
    </div>
</section>
@endsection
