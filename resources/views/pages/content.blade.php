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
    <div class="overflow-x-auto">
        <div class="prose prose-slate max-w-none lg:max-w-4xl">
            {!! $content !!}
        </div>
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
