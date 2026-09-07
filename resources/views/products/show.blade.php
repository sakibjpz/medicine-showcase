<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? $product->brand_name }} | MedSource</title>
    <meta name="description" content="{{ $product->meta_description }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 text-slate-800 antialiased">
    <div class="max-w-4xl mx-auto p-6 lg:p-10">
        <a href="{{ route('products') }}" class="text-sm text-med-600 hover:underline">&larr; Back to Products</a>

        <article class="mt-6 bg-white rounded-xl border border-slate-200 shadow-lg p-6 lg:p-10 space-y-6">
            <header>
                <h1 class="text-3xl font-bold text-navy-900">{{ $product->brand_name }}</h1>
                <p class="text-slate-500 mt-1">{{ $product->generic_inn_name }} &middot; {{ $product->strength }} &middot; {{ $product->manufacturer?->name }}</p>
                <p class="text-xs text-slate-400 mt-2 font-mono">{{ $product->internal_product_id }}</p>
            </header>

            @if (! empty($product->product_images))
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    @foreach ($product->product_images as $img)
                        <img src="{{ $img }}" alt="{{ $product->image_alt_text }}" class="rounded border border-slate-200 object-cover h-40 w-full">
                    @endforeach
                </div>
            @endif

            <section class="bg-slate-50 rounded-xl border border-slate-200 p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-navy-900 border-b border-slate-200 pb-2 mb-3">1. Basic Product Information</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm text-slate-700">
                    <div><strong>Internal ID:</strong> {{ $product->internal_product_id }}</div>
                    <div><strong>Brand Name:</strong> {{ $product->brand_name }}</div>
                    <div><strong>Generic / INN:</strong> {{ $product->generic_inn_name }}</div>
                    <div><strong>Other Name:</strong> {{ $product->other_name ?: 'N/A' }}</div>
                    <div><strong>Therapeutic Category:</strong> {{ $product->therapeutic_category }}</div>
                    <div><strong>Subcategory:</strong> {{ $product->subcategory }}</div>
                    <div><strong>Dosage Form:</strong> {{ $product->dosage_form }}</div>
                    <div><strong>Strength:</strong> {{ $product->strength }}</div>
                    <div><strong>Pack Size:</strong> {{ $product->pack_size_spec }}</div>
                    <div><strong>Route:</strong> {{ $product->route_admin ?: 'N/A' }}</div>
                    <div><strong>Manufacturer:</strong> {{ $product->manufacturer?->name }}</div>
                    <div><strong>Country of Origin:</strong> {{ $product->country_of_origin }}</div>
                    <div><strong>Legal Status:</strong> {{ $product->legal_status }}</div>
                </div>
            </section>

            <section class="bg-slate-50 rounded-xl border border-slate-200 p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-navy-900 border-b border-slate-200 pb-2 mb-3">2. Product Description and Media</h2>
                <div class="space-y-4 text-sm text-slate-700">
                    <div>
                        <h3 class="font-medium text-slate-900">Active Ingredients</h3>
                        {!! nl2br(e($product->active_ingredients)) !!}
                    </div>
                    <div>
                        <h3 class="font-medium text-slate-900">Short Description</h3>
                        {!! nl2br(e($product->short_description)) !!}
                    </div>
                    <div>
                        <h3 class="font-medium text-slate-900">Full Description</h3>
                        {!! nl2br(e($product->full_description)) !!}
                    </div>
                    <div>
                        <h3 class="font-medium text-slate-900">Approved Indication</h3>
                        {!! nl2br(e($product->approved_indication)) !!}
                    </div>
                    <div>
                        <h3 class="font-medium text-slate-900">Image Alt Text</h3>
                        {{ $product->image_alt_text }}
                    </div>
                </div>
            </section>

            <section class="bg-slate-50 rounded-xl border border-slate-200 p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-navy-900 border-b border-slate-200 pb-2 mb-3">3. Use and Safety Information</h2>
                <div class="space-y-4 text-sm text-slate-700">
                    @if ($product->dosage_admin_text)
                        <div>
                            <h3 class="font-medium text-slate-900">Dosage Administration</h3>
                            {!! nl2br(e($product->dosage_admin_text)) !!}
                        </div>
                    @endif
                    <div>
                        <h3 class="font-medium text-slate-900">Safety Info</h3>
                        {!! nl2br(e($product->safety_info)) !!}
                    </div>
                    @if ($product->drug_interactions)
                        <div>
                            <h3 class="font-medium text-slate-900">Drug Interactions</h3>
                            {!! nl2br(e($product->drug_interactions)) !!}
                        </div>
                    @endif
                    @if ($product->precautions)
                        <div>
                            <h3 class="font-medium text-slate-900">Precautions</h3>
                            {!! nl2br(e($product->precautions)) !!}
                        </div>
                    @endif
                    <div>
                        <h3 class="font-medium text-slate-900">Storage Conditions</h3>
                        {!! nl2br(e($product->storage_conditions)) !!}
                    </div>
                </div>
            </section>

            <section class="bg-slate-50 rounded-xl border border-slate-200 p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-navy-900 border-b border-slate-200 pb-2 mb-3">4. Website and Market Information</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm text-slate-700">
                    <div><strong>Availability:</strong> {{ $product->availability_status }}</div>
                    <div><strong>Country Market:</strong> {{ is_array($product->country_market) ? implode(', ', $product->country_market) : $product->country_market }}</div>
                    <div><strong>Page Language:</strong> {{ $product->page_language }}</div>
                    <div><strong>URL Slug:</strong> {{ $product->url_slug }}</div>
                    <div><strong>SEO Title:</strong> {{ $product->seo_title }}</div>
                    <div><strong>Meta Description:</strong> {{ $product->meta_description }}</div>
                </div>
            </section>

            <section class="bg-slate-50 rounded-xl border border-slate-200 p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-navy-900 border-b border-slate-200 pb-2 mb-3">5. Source, Review and Publication</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm text-slate-700">
                    <div><strong>Official Source:</strong> {!! $product->official_source_url ? '<a href="'.e($product->official_source_url).'" target="_blank" class="text-med-600 hover:underline">'.e($product->official_source_url).'</a>' : 'N/A' !!}</div>
                    <div><strong>Last Verified:</strong> {{ $product->last_verified_date?->format('M j, Y') ?: 'N/A' }}</div>
                    <div><strong>Content Status:</strong> {{ $product->content_status }}</div>
                    <div><strong>Reviewer / Approver:</strong> {{ $product->reviewer_approver }}</div>
                </div>
            </section>

            <section class="bg-slate-50 rounded-xl border border-slate-200 p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-navy-900 border-b border-slate-200 pb-2 mb-3">Information Disclaimer</h2>
                <div class="text-sm text-slate-600">
                    {!! nl2br(e($product->information_disclaimer)) !!}
                </div>
            </section>

            <div class="pt-4 flex flex-wrap items-center gap-3 border-t border-slate-100">
                <a href="{{ $product->enquiry_contact_link }}" class="btn-primary text-sm">Professional Enquiry</a>
                <a href="{{ route('products') }}" class="btn-outline text-sm">Back to Products</a>
            </div>
        </article>
    </div>
</body>
</html>
