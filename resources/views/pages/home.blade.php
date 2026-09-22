@extends('layouts.portal')

@section('title', 'MedSource – Global Medical Platforms and Centers')

@section('content')
@php
    $defaultBanner = public_path('images/pages/home-banner.jpg');
    $bannerImage = ($page?->banner_image ?? '') ?: (file_exists($defaultBanner) ? asset('images/pages/home-banner.jpg?v=' . filemtime($defaultBanner)) : null);
@endphp
<section class="relative text-white lg:min-h-screen flex flex-col" @if ($bannerImage) style="background-image: url('{{ $bannerImage }}'); background-size: cover; background-position: center;" @endif>
    <div class="absolute inset-0 bg-navy-900/60"></div>
    <div class="container-site py-8 lg:py-10 relative z-10 flex-1 flex flex-col min-h-0">
        @php
            $firstCategory = $categories->first();
            $defaultCategory = $firstCategory?->name;
            $firstCategoryProducts = $productsByCategory->get($defaultCategory) ?? collect();
            $defaultSubcategory = $firstCategory?->subcategories[0] ?? '';
            foreach ($firstCategory?->subcategories ?? [] as $sub) {
                if ($firstCategoryProducts->contains(fn ($p) => ($p->subcategory ?: 'Other') === $sub)) {
                    $defaultSubcategory = $sub;
                    break;
                }
            }
        @endphp
        <div x-data='{
            activeCategory: @json($defaultCategory),
            activeSubcategory: @json($defaultSubcategory),
            menuOpen: false
        }'
        class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-6 h-full items-stretch">
            {{-- All drug categories sidebar --}}
            <div class="order-2 lg:order-1 lg:col-span-3 lg:h-full flex flex-col">
                <div class="relative bg-[#1e6fdb] rounded-xl shadow-lg lg:h-full flex flex-col overflow-hidden lg:overflow-visible"
                     @mouseleave="menuOpen = false">
                    <div class="px-4 py-3 bg-[#1558b0] rounded-t-xl font-bold flex items-center gap-2 text-sm uppercase tracking-wide">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        All drug categories
                    </div>
                    <ul class="flex-1 flex flex-col divide-y divide-blue-400/30">
                        @foreach ($categories as $category)
                            @php
                                $categoryProductsForDefault = $productsByCategory->get($category->name) ?? collect();
                                $defaultSubForCategory = $category->subcategories[0] ?? '';
                                foreach ($category->subcategories ?? [] as $sub) {
                                    if ($categoryProductsForDefault->contains(fn ($p) => ($p->subcategory ?: 'Other') === $sub)) {
                                        $defaultSubForCategory = $sub;
                                        break;
                                    }
                                }
                            @endphp
                            <li class="flex-1 flex">
                                <button type="button"
                                        @mouseenter="activeCategory = '{{ $category->name }}'; activeSubcategory = '{{ $defaultSubForCategory }}'; menuOpen = true"
                                        @click="activeCategory = '{{ $category->name }}'; activeSubcategory = '{{ $defaultSubForCategory }}'; menuOpen = true"
                                        :class="{ 'bg-[#1558b0]': activeCategory === '{{ $category->name }}' }"
                                        class="w-full h-full text-left px-4 py-3 text-sm hover:bg-[#1558b0] transition flex items-center justify-between gap-2 {{ $loop->last ? 'lg:rounded-b-xl' : '' }}">
                                    <span>{{ $category->name }}</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </li>
                        @endforeach
                    </ul>

                    {{-- Flyout panel: opens to the right of the sidebar on desktop, stacks inside the card on mobile --}}
                    @foreach ($categories as $category)
                        @php
                            $categoryProducts = $productsByCategory->get($category->name) ?? collect();
                            $productsBySubcategory = $categoryProducts->groupBy(fn ($p) => $p->subcategory ?: 'Other');
                        @endphp
                        <div x-show="activeCategory === '{{ $category->name }}' && menuOpen"
                             x-cloak
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 -translate-x-2"
                             x-transition:enter-end="opacity-100 translate-x-0"
                             @mouseenter="activeCategory = '{{ $category->name }}'"
                             class="bg-white text-slate-800 border-t border-blue-400/30 lg:border-t-0 lg:absolute lg:left-full lg:top-0 lg:bottom-0 lg:w-[560px] xl:w-[660px] lg:rounded-r-xl lg:shadow-mega lg:z-30 lg:overflow-hidden">
                            <div class="grid grid-cols-1 lg:grid-cols-12 lg:h-full">
                                {{-- Subcategory list --}}
                                <div class="lg:col-span-4 lg:min-h-0 lg:overflow-y-auto border-b lg:border-b-0 lg:border-r border-slate-200 p-4">
                                    @if (!empty($category->subcategories))
                                        <ul class="space-y-2">
                                            @foreach ($category->subcategories as $subcategory)
                                                <li>
                                                    <button type="button"
                                                            @mouseenter="activeCategory = '{{ $category->name }}'; activeSubcategory = '{{ $subcategory }}'"
                                                            @click="activeCategory = '{{ $category->name }}'; activeSubcategory = '{{ $subcategory }}'"
                                                            :class="{ 'bg-med-100 text-med-900': activeSubcategory === '{{ $subcategory }}' }"
                                                            class="w-full text-left px-3 py-2 rounded-lg hover:bg-med-50 text-sm transition">
                                                        {{ $subcategory }}
                                                    </button>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-sm text-slate-500">No disease areas listed for this category.</p>
                                    @endif
                                </div>

                                {{-- Products for active subcategory --}}
                                <div class="lg:col-span-8 lg:min-h-0 lg:overflow-y-auto p-4">
                                    @foreach ($category->subcategories as $subcategory)
                                        @php
                                            $subcategoryProducts = $productsBySubcategory->get($subcategory) ?? collect();
                                        @endphp
                                        <div x-show="activeSubcategory === '{{ $subcategory }}'" class="min-h-0">
                                            <h3 class="text-sm font-bold text-med-700 uppercase tracking-wider mb-4 flex items-center gap-2">
                                                <span class="w-1 h-4 bg-med-500 rounded-full"></span>
                                                {{ $subcategory }}
                                            </h3>
                                            @if ($subcategoryProducts->isNotEmpty())
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                    @foreach ($subcategoryProducts as $product)
                                                        <a href="{{ route('products.show', $product->url_slug) }}" class="group block p-3 rounded-lg border border-slate-100 hover:border-med-300 hover:bg-med-50 transition">
                                                            <p class="font-semibold text-slate-800 group-hover:text-med-700 text-sm break-words">{{ $product->brand_name }}</p>
                                                            <p class="text-xs text-slate-500 break-words">{{ $product->generic_inn_name }}{{ $product->strength ? ' · ' . $product->strength : '' }}</p>
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @else
                                                <p class="text-sm text-slate-500">No recommended products listed for this condition.</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Right side: hero text over the banner --}}
            <div class="order-1 lg:order-2 lg:col-span-9 h-full flex flex-col min-h-0">
                <div class="max-w-3xl my-auto py-8">
                    <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight mb-4 break-words">
                        {{ $heading }}
                    </h1>
                    <p class="text-lg md:text-xl text-med-100 mb-6 break-words">
                        {{ $lead }}
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('products') }}" class="btn-primary bg-white text-med-700 hover:bg-med-50 border border-white w-full sm:w-auto text-center">Browse Products</a>
                        <a href="{{ route('professional-enquiry') }}" class="btn-outline border-white text-white hover:bg-med-600 hover:text-white w-full sm:w-auto text-center">Submit Enquiry</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@if ($page->content ?? false)
<section class="container-site py-6">
    <div class="overflow-x-auto">
        <div class="prose prose-slate max-w-none break-words">{!! $page->content !!}</div>
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

<section class="container-site py-14">
    @include('partials.category-circles')
</section>

<section class="container-site py-14">
    <h2 class="text-2xl md:text-3xl font-bold text-med-900 mb-8">Best-selling drugs</h2>
    <div class="space-y-12">
        @foreach ($categories as $category)
            @php
                $products = $productsByCategory->get($category->name);
            @endphp
            @if ($products && $products->isNotEmpty())
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                    <div class="lg:col-span-1">
                        <div class="lg:sticky lg:top-6">
                            <div class="relative w-24 h-24 mb-4 rounded-full overflow-hidden border-4 border-white shadow-md">
                                <img src="{{ $categoryImages[$category->name] ?? asset('images/categories/other.svg') }}" alt="{{ $category->name }}" class="w-full h-full object-cover">
                            </div>
                            <h3 class="text-xl font-bold text-med-900">{{ $category->name }}</h3>
                            <p class="text-sm text-slate-500 mt-1">{{ $productCounts[$category->name] ?? 0 }} products</p>
                            <a href="{{ route('products') }}" class="text-sm text-med-600 hover:underline mt-3 inline-block">View all</a>
                        </div>
                    </div>
                    <div class="lg:col-span-3">
                        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                            @foreach ($products as $product)
                                @php
                                    $productImage = $product->product_images[0] ?? null;
                                    if (empty($productImage)) {
                                        $local = public_path('images/products/' . $product->url_slug . '.jpg');
                                        $productImage = file_exists($local) ? 'images/products/' . $product->url_slug . '.jpg' : null;
                                    } elseif (! str_starts_with($productImage, 'http')) {
                                        $productImage = asset($productImage);
                                    }
                                @endphp
                                <a href="{{ route('products.show', $product->url_slug) }}" class="group bg-white rounded-xl border border-slate-200 overflow-hidden hover:shadow-lg hover:border-med-400 transition">
                                    <div class="h-48 bg-slate-100 overflow-hidden">
                                        @if ($productImage)
                                            <img src="{{ $productImage }}" alt="{{ $product->brand_name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-slate-400 bg-slate-100">
                                                <span class="text-3xl font-bold text-slate-300">{{ strtoupper(substr($product->brand_name, 0, 1)) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="p-4">
                                        <h4 class="font-bold text-med-900 break-words">{{ $product->brand_name }}</h4>
                                        <p class="text-sm text-slate-600 mt-1 break-words">{{ $product->generic_inn_name }}{{ $product->other_name ? ' · ' . $product->other_name : '' }}</p>
                                        @if ($product->manufacturer)
                                            <p class="text-xs text-slate-500 mt-2 break-words">{{ $product->manufacturer->name }}</p>
                                        @endif
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
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
                <a href="{{ route('therapeutic-areas') }}" class="p-4 bg-white rounded-lg border border-slate-200 hover:border-med-500 transition font-medium text-med-900 break-words text-center">Cardiology</a>
                <a href="{{ route('therapeutic-areas') }}" class="p-4 bg-white rounded-lg border border-slate-200 hover:border-med-500 transition font-medium text-med-900 break-words text-center">Oncology</a>
                <a href="{{ route('therapeutic-areas') }}" class="p-4 bg-white rounded-lg border border-slate-200 hover:border-med-500 transition font-medium text-med-900 break-words text-center">Infectious Diseases</a>
                <a href="{{ route('therapeutic-areas') }}" class="p-4 bg-white rounded-lg border border-slate-200 hover:border-med-500 transition font-medium text-med-900 break-words text-center">Endocrinology</a>
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
