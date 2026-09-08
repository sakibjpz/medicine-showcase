@extends('layouts.portal')

@section('title', 'Navigation overview')

@section('content')
<section class="bg-white py-10 lg:py-14 border-b border-slate-200">
    <div class="container-site">
        <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-navy-900 mb-2 break-words">{{ $heading }}</h1>
        <p class="text-base sm:text-lg text-slate-500 mb-8 break-words">{{ $lead }}</p>
        @if ($page->content ?? false)
            <div class="overflow-x-auto mb-8">
                <div class="prose prose-slate max-w-none break-words">{!! $page->content !!}</div>
            </div>
        @endif

        {{-- Mobile menu grid --}}
        <div class="grid grid-cols-2 gap-3 md:hidden mb-8">
            <a href="{{ route('home') }}" class="px-4 py-3 text-sm font-medium rounded-lg bg-navy-900 text-white text-center hover:bg-navy-800">Home</a>
            <a href="{{ route('products') }}" class="px-4 py-3 text-sm font-medium rounded-lg bg-navy-900 text-white text-center hover:bg-navy-800">Products</a>
            <a href="{{ route('therapeutic-areas') }}" class="px-4 py-3 text-sm font-medium rounded-lg bg-navy-900 text-white text-center hover:bg-navy-800">Therapeutic Areas</a>
            <a href="{{ route('manufacturers') }}" class="px-4 py-3 text-sm font-medium rounded-lg bg-navy-900 text-white text-center hover:bg-navy-800">Manufacturers</a>
            <a href="{{ route('how-it-works') }}" class="px-4 py-3 text-sm font-medium rounded-lg bg-navy-900 text-white text-center hover:bg-navy-800">How It Works</a>
            <a href="{{ route('quality-compliance') }}" class="px-4 py-3 text-sm font-medium rounded-lg bg-navy-900 text-white text-center hover:bg-navy-800">Quality</a>
            <a href="{{ route('articles-updates') }}" class="px-4 py-3 text-sm font-medium rounded-lg bg-navy-900 text-white text-center hover:bg-navy-800">Resources</a>
            <a href="{{ route('about') }}" class="px-4 py-3 text-sm font-medium rounded-lg bg-navy-900 text-white text-center hover:bg-navy-800">About</a>
            <a href="{{ route('contact-hub') }}" class="col-span-2 px-4 py-3 text-sm font-medium rounded-lg bg-navy-900 text-white text-center hover:bg-navy-800">Contact</a>
        </div>

        {{-- Desktop navigation cards --}}
        <div class="hidden md:grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Product Information --}}
            <div class="p-5 rounded-lg bg-med-50 border border-med-100">
                <h2 class="text-med-700 font-bold uppercase text-xs tracking-wider mb-5">Product Information</h2>
                <ul class="space-y-4 text-sm">
                    <li class="flex gap-3">
                        <span class="mt-1 w-1.5 h-1.5 rounded-full bg-med-500 shrink-0"></span>
                        <div>
                            <a href="{{ route('products') }}" class="font-medium text-slate-800 hover:text-med-700">All Products</a>
                            <p class="text-xs text-slate-500">Clear destination and content label</p>
                        </div>
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-1 w-1.5 h-1.5 rounded-full bg-med-500 shrink-0"></span>
                        <div>
                            <a href="{{ route('products.search') }}" class="font-medium text-slate-800 hover:text-med-700">Product Search</a>
                            <p class="text-xs text-slate-500">Clear destination and content label</p>
                        </div>
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-1 w-1.5 h-1.5 rounded-full bg-med-500 shrink-0"></span>
                        <div>
                            <a href="{{ route('products.documents') }}" class="font-medium text-slate-800 hover:text-med-700">Product Documents</a>
                            <p class="text-xs text-slate-500">Clear destination and content label</p>
                        </div>
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-1 w-1.5 h-1.5 rounded-full bg-med-500 shrink-0"></span>
                        <div>
                            <a href="{{ route('products.country-status') }}" class="font-medium text-slate-800 hover:text-med-700">Country Status</a>
                            <p class="text-xs text-slate-500">Clear destination and content label</p>
                        </div>
                    </li>
                </ul>
            </div>

            {{-- Explore --}}
            <div class="p-5 rounded-lg bg-med-50 border border-med-100">
                <h2 class="text-med-700 font-bold uppercase text-xs tracking-wider mb-5">Explore</h2>
                <ul class="space-y-4 text-sm">
                    <li class="flex gap-3">
                        <span class="mt-1 w-1.5 h-1.5 rounded-full bg-med-500 shrink-0"></span>
                        <div>
                            <a href="{{ route('therapeutic-areas') }}" class="font-medium text-slate-800 hover:text-med-700">Therapeutic Areas</a>
                            <p class="text-xs text-slate-500">Clear destination and content label</p>
                        </div>
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-1 w-1.5 h-1.5 rounded-full bg-med-500 shrink-0"></span>
                        <div>
                            <a href="{{ route('manufacturers') }}" class="font-medium text-slate-800 hover:text-med-700">Manufacturers</a>
                            <p class="text-xs text-slate-500">Clear destination and content label</p>
                        </div>
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-1 w-1.5 h-1.5 rounded-full bg-med-500 shrink-0"></span>
                        <div>
                            <a href="{{ route('countries-languages') }}" class="font-medium text-slate-800 hover:text-med-700">Countries & Languages</a>
                            <p class="text-xs text-slate-500">Clear destination and content label</p>
                        </div>
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-1 w-1.5 h-1.5 rounded-full bg-med-500 shrink-0"></span>
                        <div>
                            <a href="{{ route('how-it-works') }}" class="font-medium text-slate-800 hover:text-med-700">How It Works</a>
                            <p class="text-xs text-slate-500">Clear destination and content label</p>
                        </div>
                    </li>
                </ul>
            </div>

            {{-- Trust & Quality --}}
            <div class="p-5 rounded-lg bg-med-50 border border-med-100">
                <h2 class="text-med-700 font-bold uppercase text-xs tracking-wider mb-5">Trust & Quality</h2>
                <ul class="space-y-4 text-sm">
                    <li class="flex gap-3">
                        <span class="mt-1 w-1.5 h-1.5 rounded-full bg-med-500 shrink-0"></span>
                        <div>
                            <a href="{{ route('quality-compliance') }}" class="font-medium text-slate-800 hover:text-med-700">Quality & Compliance</a>
                            <p class="text-xs text-slate-500">Clear destination and content label</p>
                        </div>
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-1 w-1.5 h-1.5 rounded-full bg-med-500 shrink-0"></span>
                        <div>
                            <a href="{{ route('safety-notices') }}" class="font-medium text-slate-800 hover:text-med-700">Safety Notices</a>
                            <p class="text-xs text-slate-500">Clear destination and content label</p>
                        </div>
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-1 w-1.5 h-1.5 rounded-full bg-med-500 shrink-0"></span>
                        <div>
                            <a href="{{ route('official-sources') }}" class="font-medium text-slate-800 hover:text-med-700">Official Sources</a>
                            <p class="text-xs text-slate-500">Clear destination and content label</p>
                        </div>
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-1 w-1.5 h-1.5 rounded-full bg-med-500 shrink-0"></span>
                        <div>
                            <a href="{{ route('accessibility') }}" class="font-medium text-slate-800 hover:text-med-700">Accessibility</a>
                            <p class="text-xs text-slate-500">Clear destination and content label</p>
                        </div>
                    </li>
                </ul>
            </div>

            {{-- Resources & Help --}}
            <div class="p-5 rounded-lg bg-med-50 border border-med-100">
                <h2 class="text-med-700 font-bold uppercase text-xs tracking-wider mb-5">Resources & Help</h2>
                <ul class="space-y-4 text-sm">
                    <li class="flex gap-3">
                        <span class="mt-1 w-1.5 h-1.5 rounded-full bg-med-500 shrink-0"></span>
                        <div>
                            <a href="{{ route('articles-updates') }}" class="font-medium text-slate-800 hover:text-med-700">Articles & Updates</a>
                            <p class="text-xs text-slate-500">Clear destination and content label</p>
                        </div>
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-1 w-1.5 h-1.5 rounded-full bg-med-500 shrink-0"></span>
                        <div>
                            <a href="{{ route('faq') }}" class="font-medium text-slate-800 hover:text-med-700">FAQ</a>
                            <p class="text-xs text-slate-500">Clear destination and content label</p>
                        </div>
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-1 w-1.5 h-1.5 rounded-full bg-med-500 shrink-0"></span>
                        <div>
                            <a href="{{ route('contact-hub') }}" class="font-medium text-slate-800 hover:text-med-700">Contact Hub</a>
                            <p class="text-xs text-slate-500">Clear destination and content label</p>
                        </div>
                    </li>
                    <li class="flex gap-3">
                        <span class="mt-1 w-1.5 h-1.5 rounded-full bg-med-500 shrink-0"></span>
                        <div>
                            <a href="{{ route('professional-enquiry') }}" class="font-medium text-slate-800 hover:text-med-700">Professional Enquiry</a>
                            <p class="text-xs text-slate-500">Clear destination and content label</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="bg-med-50 py-10 lg:py-14">
    <div class="container-site">
        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-4">Therapeutic categories</p>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-10">
            <a href="{{ route('therapeutic-areas') }}" class="px-4 py-2 text-sm font-medium rounded-full border border-med-600 text-med-700 bg-white hover:bg-med-100 text-center w-full">Oncology</a>
            <a href="{{ route('therapeutic-areas') }}" class="px-4 py-2 text-sm font-medium rounded-full border border-med-600 text-med-700 bg-white hover:bg-med-100 text-center w-full">Hepatology</a>
            <a href="{{ route('therapeutic-areas') }}" class="px-4 py-2 text-sm font-medium rounded-full border border-med-600 text-med-700 bg-white hover:bg-med-100 text-center w-full">Respiratory</a>
            <a href="{{ route('therapeutic-areas') }}" class="px-4 py-2 text-sm font-medium rounded-full border border-med-600 text-med-700 bg-white hover:bg-med-100 text-center w-full">Renal</a>
            <a href="{{ route('therapeutic-areas') }}" class="px-4 py-2 text-sm font-medium rounded-full border border-med-600 text-med-700 bg-white hover:bg-med-100 text-center w-full">Cardiovascular</a>
            <a href="{{ route('therapeutic-areas') }}" class="px-4 py-2 text-sm font-medium rounded-full border border-med-600 text-med-700 bg-white hover:bg-med-100 text-center w-full">Dermatology</a>
            <a href="{{ route('therapeutic-areas') }}" class="px-4 py-2 text-sm font-medium rounded-full border border-med-600 text-med-700 bg-white hover:bg-med-100 text-center w-full">Diabetes</a>
            <a href="{{ route('therapeutic-areas') }}" class="px-4 py-2 text-sm font-medium rounded-full border border-med-600 text-med-700 bg-white hover:bg-med-100 text-center w-full">Other</a>
        </div>

        <h2 class="text-2xl font-bold text-navy-900 mb-6"><span class="text-slate-400 font-mono">P01</span> Navigation overview</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-5 bg-white rounded-lg border border-med-100">
                <h3 class="font-semibold text-navy-900 mb-2">Quality assurance</h3>
                <p class="text-sm text-slate-600">Verified content, regulatory links and professional validation at every step.</p>
            </div>
            <div class="p-5 bg-white rounded-lg border border-med-100">
                <h3 class="font-semibold text-navy-900 mb-2">Worldwide reach</h3>
                <p class="text-sm text-slate-600">Country-specific availability, multi-language support and global manufacturers.</p>
            </div>
            <div class="p-5 bg-white rounded-lg border border-med-100">
                <h3 class="font-semibold text-navy-900 mb-2">Professional response</h3>
                <p class="text-sm text-slate-600">Direct enquiry forms and dedicated support for healthcare professionals.</p>
            </div>
        </div>

        <a href="{{ route('professional-enquiry') }}" class="block w-full sm:w-auto sm:inline-flex text-center btn-primary mt-8">
            Professional Enquiry
        </a>
    </div>
</section>

@endsection
