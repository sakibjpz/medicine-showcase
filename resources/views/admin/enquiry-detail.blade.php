@extends('layouts.admin')

@section('content')
<div class="bg-white rounded-lg border border-slate-200 shadow-sm p-6 lg:p-8">
    <div class="flex flex-col sm:flex-row items-start justify-between gap-4 mb-6">
        <div>
            <p class="text-sm text-slate-500 mb-1">Enquiry #{{ $enquiry->id }}</p>
            <h2 class="text-xl font-bold text-navy-900">{{ $enquiry->name }}</h2>
        </div>
        <div class="sm:text-right">
            @if ($enquiry->responded)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Responded</span>
            @else
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">Pending</span>
            @endif
        </div>
    </div>

    <dl class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm mb-6">
        <div>
            <dt class="text-slate-500 uppercase text-xs tracking-wider">Institution</dt>
            <dd class="font-medium text-slate-800">{{ $enquiry->institution }}</dd>
        </div>
        <div>
            <dt class="text-slate-500 uppercase text-xs tracking-wider">Email</dt>
            <dd class="font-medium text-slate-800 break-words"><a href="mailto:{{ $enquiry->email }}" class="text-med-600 hover:underline">{{ $enquiry->email }}</a></dd>
        </div>
        <div>
            <dt class="text-slate-500 uppercase text-xs tracking-wider">Inquiry Type</dt>
            <dd class="font-medium text-slate-800">{{ $enquiry->inquiry_type }}</dd>
        </div>
        <div>
            <dt class="text-slate-500 uppercase text-xs tracking-wider">Received</dt>
            <dd class="font-medium text-slate-800">{{ $enquiry->created_at->format('M j, Y g:i a') }}</dd>
        </div>
    </dl>

    <div class="mb-6">
        <dt class="text-slate-500 uppercase text-xs tracking-wider mb-2">Message</dt>
        <dd class="text-slate-800 bg-slate-50 p-4 rounded border border-slate-100 whitespace-pre-wrap break-words">{{ $enquiry->message ?: 'No message provided.' }}</dd>
    </div>

    <div class="flex flex-wrap items-center gap-3">
        <form method="POST" action="{{ route('admin.enquiries.toggle', $enquiry) }}">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn-primary">{{ $enquiry->responded ? 'Mark as pending' : 'Mark as responded' }}</button>
        </form>
        <form method="POST" action="{{ route('admin.enquiries.destroy', $enquiry) }}" onsubmit="return confirm('Delete this enquiry?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-outline border-red-600 text-red-600 hover:bg-red-50">Delete</button>
        </form>
    </div>
</div>
@endsection
