@extends('layouts.admin')

@section('content')
<div class="mb-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
    <p class="text-sm text-slate-500">Manage product records and source data.</p>
    <a href="{{ route('admin.products.create') }}" class="btn-primary w-full sm:w-auto text-center">Create Product</a>
</div>

<div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-left">
            <thead class="bg-slate-50 text-slate-600 uppercase text-xs">
                <tr>
                    <th class="px-5 py-3 font-semibold">Internal ID</th>
                    <th class="px-5 py-3 font-semibold">Brand</th>
                    <th class="px-5 py-3 font-semibold">Category</th>
                    <th class="px-5 py-3 font-semibold">Order</th>
                    <th class="px-5 py-3 font-semibold">Manufacturer</th>
                    <th class="px-5 py-3 font-semibold">Status</th>
                    <th class="px-5 py-3 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($products as $product)
                    <tr class="hover:bg-slate-50">
                        <td class="px-5 py-3 font-mono text-slate-500">{{ $product->internal_product_id }}</td>
                        <td class="px-5 py-3 font-medium text-slate-800">{{ $product->brand_name }}</td>
                        <td class="px-5 py-3">{{ $product->therapeutic_category }}</td>
                        <td class="px-5 py-3 text-slate-500">{{ $product->sort_order }}</td>
                        <td class="px-5 py-3">{{ $product->manufacturer?->name }}</td>
                        <td class="px-5 py-3">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                @if ($product->content_status === 'Published') bg-green-100 text-green-800
                                @elseif ($product->content_status === 'Archived') bg-slate-100 text-slate-500
                                @elseif ($product->content_status === 'Draft') bg-slate-100 text-slate-600
                                @else bg-blue-100 text-blue-800 @endif">
                                {{ $product->content_status }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-right">
                            <a href="{{ route('admin.products.edit', $product) }}" class="text-xs text-med-600 hover:underline mr-3">Edit</a>
                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="inline" onsubmit="return confirm('Archive this product?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-red-600 hover:underline">Archive</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-6 text-center text-slate-500">No products found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">
    {{ $products->links() }}
</div>
@endsection
