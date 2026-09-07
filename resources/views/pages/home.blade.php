@extends('layouts.portal')

@section('title', 'MedSource – Global Medical Platforms and Centers')

@section('content')
<section class="bg-med-700 text-white">
    <div class="container-site py-16 lg:py-24">
        <div class="max-w-3xl">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight mb-6">
                {{ $heading }}
            </h1>
            <p class="text-lg md:text-xl text-med-100 mb-8">
                {{ $lead }}
            </p>
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('products') }}" class="btn-primary bg-white text-med-700 hover:bg-med-50 border border-white w-full sm:w-auto text-center">Browse Products</a>
                <a href="{{ route('professional-enquiry') }}" class="btn-outline border-white text-white hover:bg-med-600 hover:text-white w-full sm:w-auto text-center">Submit Enquiry</a>
            </div>
        </div>
    </div>
</section>

@if ($page->content ?? false)
<section class="container-site py-6">
    <div class="overflow-x-auto">
        <div class="prose prose-slate max-w-none">{!! $page->content !!}</div>
    </div>
</section>
@endif

<section class="container-site py-14">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="p-6 bg-slate-50 rounded-xl border border-slate-100">
            <div class="w-12 h-12 bg-med-100 text-med-700 rounded-lg flex items-center justify-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </div>
            <h2 class="text-lg font-bold text-med-900 mb-2">Intelligent Search</h2>
            <p class="text-slate-600 text-sm">Search products, manufacturers and topics in under 500 ms with structured results.</p>
        </div>
        <div class="p-6 bg-slate-50 rounded-xl border border-slate-100">
            <div class="w-12 h-12 bg-med-100 text-med-700 rounded-lg flex items-center justify-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <h2 class="text-lg font-bold text-med-900 mb-2">Trust &amp; Quality</h2>
            <p class="text-slate-600 text-sm">ISO, GMP and compliance policies with real-time safety notices from FDA, EMA and WHO.</p>
        </div>
        <div class="p-6 bg-slate-50 rounded-xl border border-slate-100">
            <div class="w-12 h-12 bg-med-100 text-med-700 rounded-lg flex items-center justify-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <h2 class="text-lg font-bold text-med-900 mb-2">Worldwide Reach</h2>
            <p class="text-slate-600 text-sm">Country-specific availability, multi-language support and global regulatory links.</p>
        </div>
    </div>
</section>

<section class="bg-slate-50 py-14">
    <div class="container-site">
        <div class="lg:flex lg:items-center lg:justify-between gap-10">
            <div class="lg:w-1/2 mb-8 lg:mb-0">
                <h2 class="text-2xl md:text-3xl font-bold text-med-900 mb-4">Explore therapeutic areas</h2>
                <p class="text-slate-600 mb-6">Find medicines, clinical data and regulatory status organised by medical condition.</p>
                <a href="{{ route('therapeutic-areas') }}" class="btn-primary">View Therapeutic Areas</a>
            </div>
            <div class="lg:w-1/2 grid grid-cols-2 gap-4">
                <a href="{{ route('therapeutic-areas') }}" class="p-4 bg-white rounded-lg border border-slate-200 hover:border-med-500 transition font-medium text-med-900">Cardiology</a>
                <a href="{{ route('therapeutic-areas') }}" class="p-4 bg-white rounded-lg border border-slate-200 hover:border-med-500 transition font-medium text-med-900">Oncology</a>
                <a href="{{ route('therapeutic-areas') }}" class="p-4 bg-white rounded-lg border border-slate-200 hover:border-med-500 transition font-medium text-med-900">Infectious Diseases</a>
                <a href="{{ route('therapeutic-areas') }}" class="p-4 bg-white rounded-lg border border-slate-200 hover:border-med-500 transition font-medium text-med-900">Endocrinology</a>
            </div>
        </div>
    </div>
</section>

<section class="container-site py-14">
    <div class="bg-med-900 text-white rounded-2xl p-8 lg:p-12 lg:flex lg:items-center lg:justify-between gap-8">
        <div>
            <h2 class="text-2xl md:text-3xl font-bold mb-3">Professional enquiry</h2>
            <p class="text-med-100 mb-2">Quality assurance · Worldwide reach · Professional response</p>
            <p class="text-slate-300 text-sm">Submit an enquiry and our team will respond with verified product and partnership information.</p>
        </div>
        <a href="{{ route('professional-enquiry') }}" class="btn-primary bg-white text-med-700 hover:bg-med-50 w-full sm:w-auto mt-6 lg:mt-0 text-center">Submit Enquiry</a>
    </div>
</section>
@endsection
