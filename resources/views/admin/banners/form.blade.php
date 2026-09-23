@extends('layouts.admin')

@section('content')
<form method="POST" action="{{ $banner ? route('admin.banners.update', $banner) : route('admin.banners.store') }}" enctype="multipart/form-data" class="bg-white rounded-lg border border-slate-200 shadow-sm p-6 lg:p-8 space-y-6">
    @csrf
    @if ($banner)
        @method('PATCH')
    @endif

    <div>
        <label for="image_file" class="block text-sm font-medium text-slate-700">Upload banner image @if (!$banner)<span class="text-red-500">*</span>@endif</label>
        <input type="file" id="image_file" name="image_file" accept=".jpg,.jpeg,.png,.webp"
               class="mt-1 w-full text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-med-50 file:text-med-700">
        @error('image_file')
            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
        @enderror

        <label for="image_url" class="block text-sm font-medium text-slate-700 mt-4">…or use an image URL</label>
        <input type="url" id="image_url" name="image_url" value="{{ old('image_url') }}"
               class="mt-1 w-full rounded border-slate-300" placeholder="https://example.com/banner.jpg">
        @error('image_url')
            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
        @enderror

        @if ($banner?->image)
            <img src="{{ $banner->image }}" alt="" class="mt-3 w-full h-32 object-cover rounded border border-slate-200">
            <p class="text-xs text-slate-500 mt-1">Current image. Upload a file or paste a URL to replace it.</p>
        @endif
        <p class="text-xs text-slate-500 mt-2">Displayed as the homepage hero background. 1600x900 or larger recommended.</p>
    </div>

    <div>
        <label for="sort_order" class="block text-sm font-medium text-slate-700">Sort order</label>
        <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $banner?->sort_order ?? 0) }}" min="0" max="9999"
               class="mt-1 w-full rounded border-slate-300">
        <p class="text-xs text-slate-500 mt-1">Lower numbers show first.</p>
    </div>

    <div class="flex items-center gap-2">
        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $banner?->is_active ?? true) ? 'checked' : '' }}
               class="rounded border-slate-300 text-med-600 focus:ring-med-500">
        <label for="is_active" class="text-sm font-medium text-slate-700">Active</label>
    </div>

    <div class="flex flex-wrap items-center gap-3 pt-4 border-t border-slate-100">
        <button type="submit" class="btn-primary w-full sm:w-auto text-center">{{ $banner ? 'Update Banner' : 'Add Banner' }}</button>
        <a href="{{ route('admin.banners.index') }}" class="btn-outline w-full sm:w-auto text-center">Cancel</a>
    </div>
</form>
@endsection
