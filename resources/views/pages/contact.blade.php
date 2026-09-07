@extends('layouts.portal')

@section('title', $title ?? $heading)

@section('content')
<section class="bg-med-700 text-white py-12 lg:py-16">
    <div class="container-site">
        <h1 class="text-3xl md:text-4xl font-bold mb-4">{{ $heading }}</h1>
        <p class="text-lg text-med-100">{{ $lead }}</p>
    </div>
</section>

@if ($page->content ?? false)
<section class="container-site py-6">
    <div class="overflow-x-auto">
        <div class="prose prose-slate max-w-none">{!! $page->content !!}</div>
    </div>
</section>
@endif

<section class="container-site py-10 lg:py-14">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
        <div class="space-y-6">
            <div class="p-6 bg-slate-50 rounded-xl border border-slate-100">
                <h2 class="font-bold text-med-900 mb-3">General enquiries</h2>
                <p class="text-slate-600 text-sm mb-1">Email: <a href="mailto:info@medsource.example" class="text-med-700 hover:underline">info@medsource.example</a></p>
                <p class="text-slate-600 text-sm">Phone: <a href="tel:+1234567890" class="text-med-700 hover:underline">+1 (234) 567-890</a></p>
            </div>

            <div class="p-6 bg-slate-50 rounded-xl border border-slate-100">
                <h2 class="font-bold text-med-900 mb-3">Support tickets</h2>
                <p class="text-slate-600 text-sm">Use the contact form to send a message. For B2B or product-related requests, please use the <a href="{{ route('professional-enquiry') }}" class="text-med-700 hover:underline">Professional Enquiry</a> form.</p>
            </div>

            <div class="p-6 bg-med-50 rounded-xl border border-med-100">
                <h2 class="font-bold text-med-900 mb-3">Professional Enquiry</h2>
                <p class="text-slate-700 text-sm mb-4">Quality assurance · Worldwide reach · Professional response</p>
                <a href="{{ route('professional-enquiry') }}" class="btn-primary">Submit Enquiry</a>
            </div>
        </div>

        <div>
            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 p-4 rounded-lg mb-6" role="status">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 p-4 rounded-lg mb-6" role="alert">
                    <ul class="list-disc pl-5 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('contact-hub') }}" class="space-y-5 bg-white p-6 md:p-8 rounded-xl border border-slate-200 shadow-sm">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700">Name <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required class="mt-1 w-full rounded border-slate-300">
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700">Email <span class="text-red-500">*</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required class="mt-1 w-full rounded border-slate-300">
                </div>
                <div>
                    <label for="subject" class="block text-sm font-medium text-slate-700">Subject</label>
                    <input type="text" id="subject" name="subject" value="{{ old('subject') }}" class="mt-1 w-full rounded border-slate-300">
                </div>
                <div>
                    <label for="message" class="block text-sm font-medium text-slate-700">Message <span class="text-red-500">*</span></label>
                    <textarea id="message" name="message" rows="5" required class="mt-1 w-full rounded border-slate-300">{{ old('message') }}</textarea>
                </div>
                <button type="submit" class="btn-primary w-full sm:w-auto text-center">Send Message</button>
            </form>
        </div>
    </div>
</section>
@endsection
