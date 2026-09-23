@extends('layouts.admin')

@section('content')
<form method="POST" action="{{ $item ? route('admin.ticker.update', $item) : route('admin.ticker.store') }}" class="bg-white rounded-lg border border-slate-200 shadow-sm p-6 lg:p-8 space-y-6">
    @csrf
    @if ($item)
        @method('PATCH')
    @endif

    <div>
        <label for="title" class="block text-sm font-medium text-slate-700">Text <span class="text-red-500">*</span></label>
        <input type="text" id="title" name="title" value="{{ old('title', $item?->title) }}" required maxlength="255"
               class="mt-1 w-full rounded border-slate-300">
        <p class="text-xs text-slate-500 mt-1">Shown in the updates strip under the logo. Items rotate every 3 seconds.</p>
        @error('title')
            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="url" class="block text-sm font-medium text-slate-700">Link URL</label>
        <input type="text" id="url" name="url" value="{{ old('url', $item?->url) }}" maxlength="255"
               class="mt-1 w-full rounded border-slate-300" placeholder="/products or https://...">
        <p class="text-xs text-slate-500 mt-1">Where visitors go when they click the ticker. Optional.</p>
        @error('url')
            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="color" class="block text-sm font-medium text-slate-700">Text color</label>
        <select id="color" name="color" class="mt-1 w-full rounded border-slate-300">
            @foreach ($colors as $value => $label)
                <option value="{{ $value }}" {{ old('color', $item?->color ?? 'text-navy-800') === $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="sort_order" class="block text-sm font-medium text-slate-700">Sort order</label>
        <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $item?->sort_order ?? 0) }}" min="0" max="9999"
               class="mt-1 w-full rounded border-slate-300">
        <p class="text-xs text-slate-500 mt-1">Lower numbers show first.</p>
    </div>

    <div class="flex items-center gap-2">
        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $item?->is_active ?? true) ? 'checked' : '' }}
               class="rounded border-slate-300 text-med-600 focus:ring-med-500">
        <label for="is_active" class="text-sm font-medium text-slate-700">Active</label>
    </div>

    <div class="flex flex-wrap items-center gap-3 pt-4 border-t border-slate-100">
        <button type="submit" class="btn-primary w-full sm:w-auto text-center">{{ $item ? 'Update Item' : 'Add Item' }}</button>
        <a href="{{ route('admin.ticker.index') }}" class="btn-outline w-full sm:w-auto text-center">Cancel</a>
    </div>
</form>
@endsection
