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
    @if ($products->count())
        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($products as $product)
                <a href="{{ route('products.show', $product->url_slug) }}" class="block p-5 bg-white border border-slate-200 rounded-lg hover:border-med-500 hover:shadow-md transition group">
                    <h3 class="font-semibold text-med-900 text-lg group-hover:text-med-600 break-words">{{ $product->brand_name }}</h3>
                    <p class="text-sm text-slate-500 mt-1 break-words">{{ $product->generic_inn_name }} &middot; {{ $product->strength }}</p>
                    <p class="text-sm text-slate-500 mt-1 break-words">{{ implode(', ', $product->categoryList()) }} &middot; {{ $product->dosage_form }}</p>
                    @if ($product->short_description)
                        <p class="text-sm text-slate-600 mt-3 line-clamp-3 break-words">{{ $product->short_description }}</p>
                    @endif
                    <p class="text-sm mt-4 font-medium break-words {{ $product->availability_status === 'Marketed' ? 'text-green-700' : 'text-amber-600' }}">{{ $product->availability_status }}</p>
                </a>
            @endforeach
        </div>
    @else
        <p class="text-slate-600">No published products found.</p>
    @endif

    <div class="mt-12 p-6 bg-med-50 border border-med-100 rounded-xl lg:flex lg:items-center lg:justify-between gap-6">
        <div>
            <h2 class="text-xl font-bold text-med-900 mb-2">Need professional support?</h2>
            <p class="text-slate-600">Our team provides quality assurance, worldwide reach, and professional response.</p>
        </div>
        <a href="{{ route('professional-enquiry') }}" class="btn-primary w-full sm:w-auto mt-4 lg:mt-0 text-center">Submit Enquiry</a>
    </div>
</section>
@endsection
