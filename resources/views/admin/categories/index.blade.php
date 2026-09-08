@extends('layouts.admin')

@section('content')
<div class="mb-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
    <p class="text-sm text-slate-500">Manage therapeutic categories shown on the frontend.</p>
    <a href="{{ route('admin.categories.create') }}" class="btn-primary w-full sm:w-auto text-center">Create Category</a>
</div>

@if ($errors->has('category'))
    <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded text-sm">{{ $errors->first('category') }}</div>
@endif

{{-- Desktop table --}}
<div class="hidden md:block bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
    <table class="min-w-full text-sm text-left">
        <thead class="bg-slate-50 text-slate-600 uppercase text-xs">
            <tr>
                <th class="px-5 py-3 font-semibold">Image</th>
                <th class="px-5 py-3 font-semibold">Name</th>
                <th class="px-5 py-3 font-semibold">Subcategories</th>
                <th class="px-5 py-3 font-semibold">Products</th>
                <th class="px-5 py-3 font-semibold">Status</th>
                <th class="px-5 py-3 font-semibold text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($categories as $category)
                <tr class="hover:bg-slate-50">
                    <td class="px-5 py-3">
                        @if ($category->image)
                            <img src="{{ $category->image }}" alt="{{ $category->name }}" class="w-10 h-10 rounded-full object-cover border border-slate-200">
                        @else
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-med-600 to-navy-900 flex items-center justify-center text-white text-xs font-bold">{{ strtoupper(substr($category->name, 0, 1)) }}</div>
                        @endif
                    </td>
                    <td class="px-5 py-3 font-medium text-slate-800">{{ $category->name }}</td>
                    <td class="px-5 py-3 text-slate-500">{{ implode(', ', $category->subcategories ?? []) ?: '—' }}</td>
                    <td class="px-5 py-3">{{ $productCounts[$category->name] ?? 0 }}</td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-slate-100 text-slate-500' }}">
                            {{ $category->is_active ? 'Active' : 'Hidden' }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-right whitespace-nowrap">
                        <a href="{{ route('admin.categories.edit', $category) }}" class="text-xs text-med-600 hover:underline mr-3">Edit</a>
                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="inline" onsubmit="return confirm('Delete this category?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-red-600 hover:underline">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-5 py-6 text-center text-slate-500">No categories found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Mobile cards --}}
<div class="md:hidden space-y-3">
    @forelse ($categories as $category)
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-4">
            <div class="flex items-center gap-3">
                @if ($category->image)
                    <img src="{{ $category->image }}" alt="{{ $category->name }}" class="w-12 h-12 rounded-full object-cover border border-slate-200 shrink-0">
                @else
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-med-600 to-navy-900 flex items-center justify-center text-white font-bold shrink-0">{{ strtoupper(substr($category->name, 0, 1)) }}</div>
                @endif
                <div class="min-w-0 flex-1">
                    <p class="font-medium text-slate-800 truncate">{{ $category->name }}</p>
                    <p class="text-xs text-slate-500">{{ $productCounts[$category->name] ?? 0 }} product{{ ($productCounts[$category->name] ?? 0) === 1 ? '' : 's' }}</p>
                </div>
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-slate-100 text-slate-500' }}">
                    {{ $category->is_active ? 'Active' : 'Hidden' }}
                </span>
            </div>
            @if (!empty($category->subcategories))
                <p class="mt-2 text-xs text-slate-500">{{ implode(', ', $category->subcategories) }}</p>
            @endif
            <div class="mt-3 flex gap-4 border-t border-slate-100 pt-3">
                <a href="{{ route('admin.categories.edit', $category) }}" class="text-xs text-med-600 hover:underline">Edit</a>
                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="inline" onsubmit="return confirm('Delete this category?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-xs text-red-600 hover:underline">Delete</button>
                </form>
            </div>
        </div>
    @empty
        <p class="text-center text-slate-500 py-6">No categories found.</p>
    @endforelse
</div>

<div class="mt-6">
    {{ $categories->links() }}
</div>
@endsection
