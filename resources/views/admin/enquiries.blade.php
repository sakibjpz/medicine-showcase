@extends('layouts.admin')

@section('content')
<div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-left">
        <thead class="bg-slate-50 text-slate-600 uppercase text-xs">
            <tr>
                <th class="px-5 py-3 font-semibold">ID</th>
                <th class="px-5 py-3 font-semibold">Name</th>
                <th class="px-5 py-3 font-semibold">Institution</th>
                <th class="px-5 py-3 font-semibold">Inquiry Type</th>
                <th class="px-5 py-3 font-semibold">Status</th>
                <th class="px-5 py-3 font-semibold">Received</th>
                <th class="px-5 py-3 font-semibold text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($enquiries as $enquiry)
                <tr class="hover:bg-slate-50">
                    <td class="px-5 py-3">{{ $enquiry->id }}</td>
                    <td class="px-5 py-3">
                        <a href="{{ route('admin.enquiries.show', $enquiry) }}" class="font-medium text-med-700 hover:underline">{{ $enquiry->name }}</a>
                    </td>
                    <td class="px-5 py-3">{{ $enquiry->institution }}</td>
                    <td class="px-5 py-3">{{ $enquiry->inquiry_type }}</td>
                    <td class="px-5 py-3">
                        @if ($enquiry->responded)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Responded</span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">Pending</span>
                        @endif
                    </td>
                    <td class="px-5 py-3">{{ $enquiry->created_at->format('M j, Y') }}</td>
                    <td class="px-5 py-3 text-right">
                        <form method="POST" action="{{ route('admin.enquiries.toggle', $enquiry) }}" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="text-xs text-med-600 hover:underline mr-3">{{ $enquiry->responded ? 'Mark pending' : 'Mark responded' }}</button>
                        </form>
                        <form method="POST" action="{{ route('admin.enquiries.destroy', $enquiry) }}" class="inline" onsubmit="return confirm('Delete this enquiry?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-red-600 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-5 py-6 text-center text-slate-500">No enquiries found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>

<div class="mt-6">
    {{ $enquiries->links() }}
</div>
@endsection
