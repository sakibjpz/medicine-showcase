@extends('layouts.admin')

@section('content')
<div class="mb-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
    <p class="text-sm text-slate-500">Manage every public page on the site.</p>
    <a href="{{ route('admin.pages.create') }}" class="btn-primary w-full sm:w-auto text-center">Create Page</a>
</div>

<div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-left">
        <thead class="bg-slate-50 text-slate-600 uppercase text-xs">
            <tr>
                <th class="px-5 py-3 font-semibold">Heading</th>
                <th class="px-5 py-3 font-semibold">Slug</th>
                <th class="px-5 py-3 font-semibold">Status</th>
                <th class="px-5 py-3 font-semibold">Updated</th>
                <th class="px-5 py-3 font-semibold text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($pages as $page)
                <tr class="hover:bg-slate-50">
                    <td class="px-5 py-3">
                        @php
                            $publicUrl = $page->slug === 'home'
                                ? route('home')
                                : url('/'.str_replace('.', '/', $page->slug));
                        @endphp
                        <a href="{{ $publicUrl }}" target="_blank" class="font-medium text-med-700 hover:underline">{{ $page->heading }}</a>
                    </td>
                    <td class="px-5 py-3 font-mono text-slate-500">{{ $page->slug }}</td>
                    <td class="px-5 py-3">
                        @if ($page->is_published)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Published</span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600">Draft</span>
                        @endif
                    </td>
                    <td class="px-5 py-3">{{ $page->updated_at->format('M j, Y') }}</td>
                    <td class="px-5 py-3 text-right">
                        <a href="{{ route('admin.pages.edit', $page) }}" class="text-xs text-med-600 hover:underline mr-3">Edit</a>
                        <form method="POST" action="{{ route('admin.pages.destroy', $page) }}" class="inline" onsubmit="return confirm('Delete this page?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-red-600 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-5 py-6 text-center text-slate-500">No pages found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>

<div class="mt-6">
    {{ $pages->links() }}
</div>
@endsection
