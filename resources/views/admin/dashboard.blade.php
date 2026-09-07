@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <a href="{{ route('admin.pages') }}" class="inline-flex flex-wrap items-center gap-2 px-5 py-3 bg-white border border-slate-200 rounded-lg shadow-sm hover:border-med-500 transition">
        <span class="font-bold text-navy-900">Manage Pages</span>
        <span class="text-sm text-slate-500">Create, edit and delete all public website content</span>
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm">
        <p class="text-sm text-slate-500 uppercase tracking-wider">Total Pages</p>
        <p class="text-3xl font-bold text-navy-900">{{ $pageCount ?? 0 }}</p>
    </div>
    <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm">
        <p class="text-sm text-slate-500 uppercase tracking-wider">Total Enquiries</p>
        <p class="text-3xl font-bold text-navy-900">{{ $enquiryCount }}</p>
    </div>
    <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm">
        <p class="text-sm text-slate-500 uppercase tracking-wider">Pending Enquiries</p>
        <p class="text-3xl font-bold text-med-600">{{ $enquiryPending }}</p>
    </div>
    <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm">
        <p class="text-sm text-slate-500 uppercase tracking-wider">Total Messages</p>
        <p class="text-3xl font-bold text-navy-900">{{ $messageCount }}</p>
    </div>
    <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm">
        <p class="text-sm text-slate-500 uppercase tracking-wider">Pending Messages</p>
        <p class="text-3xl font-bold text-med-600">{{ $messagePending }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="font-bold text-navy-900">Recent Enquiries</h2>
            <a href="{{ route('admin.enquiries') }}" class="text-sm text-med-600 hover:underline">View all</a>
        </div>
        <ul class="divide-y divide-slate-100">
            @forelse ($recentEnquiries as $enquiry)
                <li class="px-5 py-3">
                    <a href="{{ route('admin.enquiries.show', $enquiry) }}" class="block hover:bg-slate-50 -mx-2 px-2 py-1 rounded">
                        <p class="font-medium text-slate-800">{{ $enquiry->name }} <span class="text-slate-400">· {{ $enquiry->inquiry_type }}</span></p>
                        <p class="text-xs text-slate-500">{{ $enquiry->created_at->format('M j, Y g:i a') }}</p>
                    </a>
                </li>
            @empty
                <li class="px-5 py-6 text-sm text-slate-500">No enquiries yet.</li>
            @endforelse
        </ul>
    </div>

    <div class="bg-white rounded-lg border border-slate-200 shadow-sm">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="font-bold text-navy-900">Recent Contact Messages</h2>
            <a href="{{ route('admin.messages') }}" class="text-sm text-med-600 hover:underline">View all</a>
        </div>
        <ul class="divide-y divide-slate-100">
            @forelse ($recentMessages as $message)
                <li class="px-5 py-3">
                    <a href="{{ route('admin.messages.show', $message) }}" class="block hover:bg-slate-50 -mx-2 px-2 py-1 rounded">
                        <p class="font-medium text-slate-800">{{ $message->name }} <span class="text-slate-400">· {{ $message->subject ?: 'No subject' }}</span></p>
                        <p class="text-xs text-slate-500">{{ $message->created_at->format('M j, Y g:i a') }}</p>
                    </a>
                </li>
            @empty
                <li class="px-5 py-6 text-sm text-slate-500">No messages yet.</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection
