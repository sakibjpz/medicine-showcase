@extends('layouts.portal')

@section('title', ($title ?? $product->brand_name) . ' – MedSource')

@section('content')
@php
    $defaultBanner = public_path('images/products/' . $product->url_slug . '-banner.jpg');
    $bannerImage = $product->banner_image ?: (file_exists($defaultBanner) ? asset('images/products/' . $product->url_slug . '-banner.jpg') : null);
@endphp
<section class="relative text-white py-16 lg:py-24 overflow-hidden" @if ($bannerImage) style="background-image: url('{{ $bannerImage }}'); background-size: cover; background-position: center;" @endif>
    <div class="absolute inset-0 bg-navy-900/80"></div>
    <div class="container-site relative z-10">
        <p class="text-med-100 text-sm font-semibold uppercase tracking-wider mb-2">{{ implode(' · ', $product->categoryList()) }} &middot; {{ $product->dosage_form }}</p>
        <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold leading-tight break-words">{{ $product->brand_name }}</h1>
        <p class="text-base sm:text-lg md:text-xl text-med-100 mt-3 break-words">{{ $product->generic_inn_name }} &middot; {{ $product->strength }}</p>
    </div>
</section>

<section class="container-site py-10 lg:py-14">
    <a href="{{ route('products') }}" class="text-sm text-med-600 hover:underline">&larr; Back to Products</a>

    <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-8">
        <aside class="lg:col-span-1">
            @php
                $localImage = public_path('images/products/' . $product->url_slug . '.jpg');
                $images = collect($product->product_images ?? [])
                    ->filter()
                    ->map(fn ($img) => str_starts_with($img, 'http') ? $img : asset($img))
                    ->values();
                if ($images->isEmpty()) {
                    $images = collect([file_exists($localImage)
                        ? asset('images/products/' . $product->url_slug . '.jpg')
                        : asset('images/products/placeholder.svg')]);
                }
                $imageLabels = $product->product_image_labels ?? [];
            @endphp
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4"
                 x-data="{ active: 0, images: {{ Js::from($images) }} }">
                <a :href="images[active]" target="_blank" class="block" title="View full image">
                    @foreach ($images as $i => $img)
                        <img src="{{ $img }}" alt="{{ $product->image_alt_text ?? $product->brand_name }}"
                             x-show="active === {{ $i }}" @if ($i > 0) x-cloak @endif
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             class="rounded-lg w-full h-48 sm:h-64 lg:h-72 object-contain bg-slate-50">
                    @endforeach
                </a>
                @foreach ($images as $i => $img)
                    @if (!empty($imageLabels[$i]))
                        <p x-show="active === {{ $i }}" @if ($i > 0) x-cloak @endif class="mt-2 text-center text-xs font-medium text-slate-500">{{ $imageLabels[$i] }}</p>
                    @endif
                @endforeach
                @if ($images->count() > 1)
                    <div class="mt-3 grid grid-cols-4 sm:grid-cols-5 gap-2">
                        @foreach ($images as $i => $img)
                            <button type="button" @click="active = {{ $i }}"
                                    :class="active === {{ $i }} ? 'ring-2 ring-med-500 border-med-400' : 'border-slate-200 hover:border-med-300'"
                                    class="rounded-lg border overflow-hidden bg-slate-50 focus:outline-none transition"
                                    aria-label="View image {{ $i + 1 }}">
                                <img src="{{ $img }}" alt="" class="w-full h-14 sm:h-16 object-contain">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="mt-6 space-y-3">
                <a href="{{ $product->enquiry_contact_link ?: route('professional-enquiry') }}" class="btn-primary w-full text-center block">Professional Enquiry</a>
                <a href="{{ route('products') }}" class="btn-outline w-full text-center block">Back to Products</a>
            </div>

            <div class="mt-6 bg-slate-50 rounded-lg border border-slate-200 p-4 text-sm">
                <h3 class="font-semibold text-navy-900 mb-2">Quick Facts</h3>
                <ul class="space-y-2 text-slate-600">
                    <li><span class="font-medium text-slate-700">Internal ID:</span> {{ $product->internal_product_id }}</li>
                    <li><span class="font-medium text-slate-700">Manufacturer:</span> {{ $product->manufacturer?->name ?? 'N/A' }}</li>
                    <li><span class="font-medium text-slate-700">Country:</span> {{ $product->country_of_origin ?? 'N/A' }}</li>
                    <li><span class="font-medium text-slate-700">Availability:</span> {{ $product->availability_status ?? 'N/A' }}</li>
                </ul>
            </div>
        </aside>

        <div class="lg:col-span-2" x-data="{ tab: 'product' }">
            {{-- Horizontal tab bar --}}
            <div class="bg-white rounded-t-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="flex overflow-x-auto border-b border-slate-200" role="tablist">
                    <button type="button" role="tab" @click="tab = 'product'"
                            :class="tab === 'product' ? 'border-med-600 text-med-700' : 'border-transparent text-slate-500 hover:text-med-700 hover:border-slate-300'"
                            class="px-5 py-3.5 text-sm font-semibold border-b-2 -mb-px whitespace-nowrap transition shrink-0">
                        Product
                    </button>
                    <button type="button" role="tab" @click="tab = 'description'"
                            :class="tab === 'description' ? 'border-med-600 text-med-700' : 'border-transparent text-slate-500 hover:text-med-700 hover:border-slate-300'"
                            class="px-5 py-3.5 text-sm font-semibold border-b-2 -mb-px whitespace-nowrap transition shrink-0">
                        Description
                    </button>
                    <button type="button" role="tab" @click="tab = 'safety'"
                            :class="tab === 'safety' ? 'border-med-600 text-med-700' : 'border-transparent text-slate-500 hover:text-med-700 hover:border-slate-300'"
                            class="px-5 py-3.5 text-sm font-semibold border-b-2 -mb-px whitespace-nowrap transition shrink-0">
                        Use &amp; Safety
                    </button>
                    <button type="button" role="tab" @click="tab = 'related'"
                            :class="tab === 'related' ? 'border-med-600 text-med-700' : 'border-transparent text-slate-500 hover:text-med-700 hover:border-slate-300'"
                            class="px-5 py-3.5 text-sm font-semibold border-b-2 -mb-px whitespace-nowrap transition shrink-0">
                        Related Information
                    </button>
                </div>
            </div>

            <div class="bg-white rounded-b-xl border border-t-0 border-slate-200 shadow-sm p-6 lg:p-8 space-y-6">
            <article x-show="tab === 'product'" x-cloak>
                <h2 class="text-xl font-semibold text-navy-900 border-b border-slate-200 pb-3 mb-4">Basic Product Information</h2>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                    <div><dt class="font-medium text-slate-500">Brand Name</dt><dd class="text-slate-800 break-words">{{ $product->brand_name }}</dd></div>
                    <div><dt class="font-medium text-slate-500">Generic / INN</dt><dd class="text-slate-800 break-words">{{ $product->generic_inn_name }}</dd></div>
                    <div><dt class="font-medium text-slate-500">Other Name</dt><dd class="text-slate-800 break-words">{{ $product->other_name ?: 'N/A' }}</dd></div>
                    <div><dt class="font-medium text-slate-500">Therapeutic Category</dt><dd class="text-slate-800 break-words">{{ implode(', ', $product->categoryList()) }}</dd></div>
                    <div><dt class="font-medium text-slate-500">Subcategory</dt><dd class="text-slate-800 break-words">{{ implode(', ', $product->subcategoryNames()) ?: 'N/A' }}</dd></div>
                    <div><dt class="font-medium text-slate-500">Dosage Form</dt><dd class="text-slate-800 break-words">{{ $product->dosage_form }}</dd></div>
                    <div><dt class="font-medium text-slate-500">Strength</dt><dd class="text-slate-800 break-words">{{ $product->strength }}</dd></div>
                    <div><dt class="font-medium text-slate-500">Pack Size</dt><dd class="text-slate-800 break-words">{{ $product->pack_size_spec ?: 'N/A' }}</dd></div>
                    <div><dt class="font-medium text-slate-500">Route</dt><dd class="text-slate-800 break-words">{{ $product->route_admin ?: 'N/A' }}</dd></div>
                    <div><dt class="font-medium text-slate-500">Legal Status</dt><dd class="text-slate-800 break-words">{{ $product->legal_status }}</dd></div>
                </dl>
            </article>

            <article x-show="tab === 'description'" x-cloak>
                <h2 class="text-xl font-semibold text-navy-900 border-b border-slate-200 pb-3 mb-4">Product Description</h2>
                <div class="space-y-4 text-sm text-slate-700 break-words">
                    @if ($product->short_description)
                        <p>{{ $product->short_description }}</p>
                    @endif
                    @if ($product->full_description)
                        <h3 class="text-base font-semibold text-slate-900">Full Description</h3>
                        <p>{{ $product->full_description }}</p>
                    @endif
                    @if ($product->approved_indication)
                        <h3 class="text-base font-semibold text-slate-900">Approved Indication</h3>
                        <p>{{ $product->approved_indication }}</p>
                    @endif
                </div>
            </article>

            <article x-show="tab === 'safety'" x-cloak>
                <h2 class="text-xl font-semibold text-navy-900 border-b border-slate-200 pb-3 mb-4">Use and Safety</h2>
                <div class="space-y-4 text-sm text-slate-700">
                    @if ($product->dosage_admin_text)
                        <div>
                            <h3 class="font-semibold text-slate-900">Dosage Administration</h3>
                            <p>{{ $product->dosage_admin_text }}</p>
                        </div>
                    @endif
                    @if ($product->safety_info)
                        <div>
                            <h3 class="font-semibold text-slate-900">Safety Info</h3>
                            <p>{{ $product->safety_info }}</p>
                        </div>
                    @endif
                    @if ($product->drug_interactions)
                        <div>
                            <h3 class="font-semibold text-slate-900">Drug Interactions</h3>
                            <p>{{ $product->drug_interactions }}</p>
                        </div>
                    @endif
                    @if ($product->precautions)
                        <div>
                            <h3 class="font-semibold text-slate-900">Precautions</h3>
                            <p>{{ $product->precautions }}</p>
                        </div>
                    @endif
                    @if ($product->storage_conditions)
                        <div>
                            <h3 class="font-semibold text-slate-900">Storage Conditions</h3>
                            <p>{{ $product->storage_conditions }}</p>
                        </div>
                    @endif
                </div>
            </article>

            <article x-show="tab === 'related'" x-cloak>
                <h2 class="text-xl font-semibold text-navy-900 border-b border-slate-200 pb-3 mb-4">Related Information</h2>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                    <div><dt class="font-medium text-slate-500">Availability</dt><dd class="text-slate-800 break-words">{{ $product->availability_status }}</dd></div>
                    <div><dt class="font-medium text-slate-500">Country Market</dt><dd class="text-slate-800 break-words">{{ is_array($product->country_market) ? implode(', ', $product->country_market) : ($product->country_market ?: 'N/A') }}</dd></div>
                    <div><dt class="font-medium text-slate-500">Page Language</dt><dd class="text-slate-800 break-words">{{ $product->page_language }}</dd></div>
                    <div><dt class="font-medium text-slate-500">URL Slug</dt><dd class="text-slate-800 break-words">{{ $product->url_slug }}</dd></div>
                    <div><dt class="font-medium text-slate-500">Official Source</dt><dd class="text-slate-800 break-words">@if ($product->official_source_url)<a href="{{ $product->official_source_url }}" target="_blank" class="text-med-600 hover:underline break-all">{{ $product->official_source_url }}</a>@else N/A @endif</dd></div>
                    <div><dt class="font-medium text-slate-500">Last Verified</dt><dd class="text-slate-800 break-words">{{ $product->last_verified_date?->format('M j, Y') ?: 'N/A' }}</dd></div>
                    <div><dt class="font-medium text-slate-500">Content Status</dt><dd class="text-slate-800 break-words">{{ $product->content_status }}</dd></div>
                    <div><dt class="font-medium text-slate-500">Reviewer</dt><dd class="text-slate-800 break-words">{{ $product->reviewer_approver ?: 'N/A' }}</dd></div>
                </dl>
            </article>

            @if ($product->information_disclaimer)
                <article x-show="tab === 'related'" x-cloak class="bg-med-50 border border-med-100 rounded-xl p-6">
                    <h2 class="text-lg font-semibold text-med-900 mb-2">Information Disclaimer</h2>
                    <p class="text-sm text-slate-600">{{ $product->information_disclaimer }}</p>
                </article>
            @endif
            </div>
        </div>
    </div>
</section>
@endsection
