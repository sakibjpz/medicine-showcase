@extends('layouts.admin')

@section('content')
<div class="mb-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
    <p class="text-sm text-slate-500">Headlines rotate on the homepage hero banner, one at a time.</p>
    <a href="{{ route('admin.headlines.create') }}" class="btn-primary w-full sm:w-auto text-center">Add Headline</a>
</div>

<div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-left">
        <thead class="bg-slate-50 text-slate-600 uppercase text-xs">
            <tr>
                <th class="px-5 py-3 font-semibold">Headline</th>
                <th class="px-5 py-3 font-semibold">Order</th>
                <th class="px-5 py-3 font-semibold">Status</th>
                <th class="px-5 py-3 font-semibold">Updated</th>
                <th class="px-5 py-3 font-semibold text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($headlines as $headline)
                <tr class="hover:bg-slate-50">
                    <td class="px-5 py-3 font-medium text-slate-800">{{ $headline->text }}</td>
                    <td class="px-5 py-3 text-slate-500">{{ $headline->sort_order }}</td>
                    <td class="px-5 py-3">
                        @if ($headline->is_active)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600">Inactive</span>
                        @endif
                    </td>
                    <td class="px-5 py-3">{{ $headline->updated_at->format('M j, Y') }}</td>
                    <td class="px-5 py-3 text-right">
                        <a href="{{ route('admin.headlines.edit', $headline) }}" class="text-xs text-med-600 hover:underline mr-3">Edit</a>
                        <form method="POST" action="{{ route('admin.headlines.destroy', $headline) }}" class="inline" onsubmit="return confirm('Delete this headline?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-red-600 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-5 py-6 text-center text-slate-500">No headlines yet. The homepage falls back to the "home" page heading.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>

<div class="mt-6">
    {{ $headlines->links() }}
</div>
@endsection
