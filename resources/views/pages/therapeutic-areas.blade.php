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
    @include('partials.category-circles')

    {{-- Category sections: each subcategory has a disease part + recommended products part --}}
    <div class="mt-16 space-y-16">
        @foreach ($categories as $category)
            @php
                $categoryProducts = $productsByCategory->get($category->name) ?? collect();
            @endphp
            <div id="category-{{ $category->slug }}" class="scroll-mt-28">
                <div class="flex items-center gap-4 mb-8">
                    <div class="relative w-16 h-16 rounded-full overflow-hidden border-4 border-white shadow-md shrink-0">
                        <img src="{{ $categoryImages[$category->name] ?? asset('images/categories/other.svg') }}" alt="{{ $category->name }}" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h2 class="text-2xl md:text-3xl font-bold text-med-900">{{ $category->name }}</h2>
                        <p class="text-sm text-slate-500">{{ $categoryProducts->count() }} product{{ $categoryProducts->count() === 1 ? '' : 's' }} · {{ count($category->subcategories ?? []) }} disease area{{ (count($category->subcategories ?? []) === 1) ? '' : 's' }}</p>
                    </div>
                </div>

                @if (!empty($category->description))
                    <p class="text-slate-600 mb-8 max-w-4xl">{{ $category->description }}</p>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach ($category->subcategories ?? [] as $subcategory)
                        @php
                            $subcategoryProducts = $productsBySubcategory->get($category->name . '|' . $subcategory) ?? collect();
                        @endphp
                        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden hover:shadow-lg transition">
                            {{-- Disease part --}}
                            <div class="bg-med-50 px-5 py-4 border-b border-med-100">
                                <div class="flex items-center gap-2">
                                    <span class="w-8 h-8 rounded-full bg-med-100 text-med-700 flex items-center justify-center text-sm font-bold shrink-0">
                                        {{ strtoupper(substr($subcategory, 0, 1)) }}
                                    </span>
                                    <h3 class="text-lg font-bold text-med-900">{{ $subcategory }}</h3>
                                </div>
                                <p class="text-xs text-slate-500 mt-1">Disease / condition</p>
                            </div>

                            {{-- Recommended products part --}}
                            <div class="p-5">
                                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-3">Recommended products</p>

                                @if ($subcategoryProducts->isNotEmpty())
                                    <div class="space-y-4">
                                        @foreach ($subcategoryProducts as $product)
                                            @php
                                                $productImage = $product->product_images[0] ?? null;
                                                if (empty($productImage)) {
                                                    $local = public_path('images/products/' . $product->url_slug . '.jpg');
                                                    $productImage = file_exists($local) ? 'images/products/' . $product->url_slug . '.jpg' : null;
                                                } elseif (! str_starts_with($productImage, 'http')) {
                                                    $productImage = asset($productImage);
                                                }
                                            @endphp
                                            <a href="{{ route('products.show', $product->url_slug) }}" class="group flex gap-4">
                                                <div class="w-16 h-16 rounded-lg bg-slate-100 overflow-hidden shrink-0">
                                                    @if ($productImage)
                                                        <img src="{{ $productImage }}" alt="{{ $product->brand_name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                                    @else
                                                        <div class="w-full h-full flex items-center justify-center text-slate-400">
                                                            <span class="text-xl font-bold text-slate-300">{{ strtoupper(substr($product->brand_name, 0, 1)) }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <h4 class="font-semibold text-med-900 group-hover:text-med-600 transition">{{ $product->brand_name }}</h4>
                                                    <p class="text-sm text-slate-600">{{ $product->generic_inn_name }}</p>
                                                    <p class="text-xs text-slate-500 mt-1">{{ $product->strength }}{{ $product->manufacturer ? ' · ' . $product->manufacturer->name : '' }}</p>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-sm text-slate-500 italic">No recommended products listed for this condition yet.</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-16 p-6 bg-med-50 border border-med-100 rounded-xl lg:flex lg:items-center lg:justify-between gap-6">
        <div>
            <h2 class="text-xl font-bold text-med-900 mb-2">Need professional support?</h2>
            <p class="text-slate-600">Our team provides quality assurance, worldwide reach, and professional response.</p>
        </div>
        <a href="{{ route('professional-enquiry') }}" class="btn-primary w-full sm:w-auto mt-4 lg:mt-0 text-center">Submit Enquiry</a>
    </div>
</section>
@endsection
