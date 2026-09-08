@extends('layouts.portal')

@section('title', $title ?? $heading)

@section('content')
<section class="bg-med-700 text-white py-12 lg:py-16">
    <div class="container-site">
        <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-4 break-words">{{ $heading }}</h1>
        <p class="text-base sm:text-lg text-med-100 max-w-2xl break-words">{{ $lead }}</p>
    </div>
</section>

@if ($page->content ?? false)
<section class="container-site py-6">
    <div class="overflow-x-auto">
        <div class="prose prose-slate max-w-none break-words">{!! $page->content !!}</div>
    </div>
</section>
@endif

<section class="container-site py-10 lg:py-14">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <div class="lg:col-span-2">
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

            <form method="POST" action="{{ route('professional-enquiry') }}" class="space-y-6 bg-white p-6 md:p-8 rounded-xl border border-slate-200 shadow-sm">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700">Name <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required class="mt-1 w-full rounded border-slate-300">
                    </div>
                    <div>
                        <label for="institution" class="block text-sm font-medium text-slate-700">Institution <span class="text-red-500">*</span></label>
                        <input type="text" id="institution" name="institution" value="{{ old('institution') }}" required class="mt-1 w-full rounded border-slate-300">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700">Email <span class="text-red-500">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required class="mt-1 w-full rounded border-slate-300">
                    </div>
                    <div>
                        <label for="inquiry_type" class="block text-sm font-medium text-slate-700">Inquiry Type <span class="text-red-500">*</span></label>
                        <select id="inquiry_type" name="inquiry_type" required class="mt-1 w-full rounded border-slate-300">
                            <option value="" disabled {{ old('inquiry_type') ? '' : 'selected' }}>Select an option</option>
                            <option value="Product Information" {{ old('inquiry_type') === 'Product Information' ? 'selected' : '' }}>Product Information</option>
                            <option value="Partnership" {{ old('inquiry_type') === 'Partnership' ? 'selected' : '' }}>Partnership</option>
                            <option value="Regulatory" {{ old('inquiry_type') === 'Regulatory' ? 'selected' : '' }}>Regulatory</option>
                            <option value="Other" {{ old('inquiry_type') === 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="message" class="block text-sm font-medium text-slate-700">Message</label>
                    <textarea id="message" name="message" rows="5" class="mt-1 w-full rounded border-slate-300">{{ old('message') }}</textarea>
                </div>

                <button type="submit" class="btn-primary w-full sm:w-auto text-center">Submit Enquiry</button>
            </form>
        </div>

        <aside class="space-y-6">
            <div class="p-6 bg-med-50 rounded-xl border border-med-100">
                <h2 class="font-bold text-med-900 mb-3">Why MedSource?</h2>
                <ul class="space-y-2 text-sm text-slate-700">
                    <li class="flex gap-2"><span class="text-med-600">✓</span> Quality assurance</li>
                    <li class="flex gap-2"><span class="text-med-600">✓</span> Worldwide reach</li>
                    <li class="flex gap-2"><span class="text-med-600">✓</span> Professional response</li>
                </ul>
            </div>

            <div class="p-6 bg-slate-50 rounded-xl border border-slate-100">
                <h2 class="font-bold text-med-900 mb-3">Contact us</h2>
                <p class="text-sm text-slate-600 mb-1">Email: <a href="mailto:info@medsource.example" class="text-med-700 hover:underline">info@medsource.example</a></p>
                <p class="text-sm text-slate-600">Phone: <a href="tel:+1234567890" class="text-med-700 hover:underline">+1 (234) 567-890</a></p>
            </div>
        </aside>
    </div>
</section>
@endsection
