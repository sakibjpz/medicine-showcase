@extends('layouts.admin')

@section('content')
<form method="POST" action="{{ $headline ? route('admin.headlines.update', $headline) : route('admin.headlines.store') }}" class="bg-white rounded-lg border border-slate-200 shadow-sm p-6 lg:p-8 space-y-6">
    @csrf
    @if ($headline)
        @method('PATCH')
    @endif

    <div>
        <label for="text" class="block text-sm font-medium text-slate-700">Headline text <span class="text-red-500">*</span></label>
        <input type="text" id="text" name="text" value="{{ old('text', $headline?->text) }}" required maxlength="255"
               class="mt-1 w-full rounded border-slate-300">
        <p class="text-xs text-slate-500 mt-1">Shown in the homepage hero banner. Headlines rotate every few seconds.</p>
        @error('text')
            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="sort_order" class="block text-sm font-medium text-slate-700">Sort order</label>
        <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $headline?->sort_order ?? 0) }}" min="0" max="9999"
               class="mt-1 w-full rounded border-slate-300">
        <p class="text-xs text-slate-500 mt-1">Lower numbers show first.</p>
    </div>

    <div class="flex items-center gap-2">
        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $headline?->is_active ?? true) ? 'checked' : '' }}
               class="rounded border-slate-300 text-med-600 focus:ring-med-500">
        <label for="is_active" class="text-sm font-medium text-slate-700">Active</label>
    </div>

    <div class="flex flex-wrap items-center gap-3 pt-4 border-t border-slate-100">
        <button type="submit" class="btn-primary w-full sm:w-auto text-center">{{ $headline ? 'Update Headline' : 'Add Headline' }}</button>
        <a href="{{ route('admin.headlines.index') }}" class="btn-outline w-full sm:w-auto text-center">Cancel</a>
    </div>
</form>
@endsection
