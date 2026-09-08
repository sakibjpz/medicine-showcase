@extends('layouts.admin')

@section('content')
<div class="max-w-3xl">
    <form method="POST"
          action="{{ $category ? route('admin.categories.update', $category) : route('admin.categories.store') }}"
          enctype="multipart/form-data"
          class="bg-white rounded-lg border border-slate-200 shadow-sm p-5 sm:p-8 space-y-6">
        @csrf
        @if ($category) @method('PUT') @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700">Category Name <span class="text-red-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name', $category?->name) }}" required
                       class="mt-1 w-full rounded border-slate-300" placeholder="e.g. Oncology">
                @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="slug" class="block text-sm font-medium text-slate-700">Slug</label>
                <input type="text" id="slug" name="slug" value="{{ old('slug', $category?->slug) }}"
                       class="mt-1 w-full rounded border-slate-300" placeholder="auto-generated if empty">
                @error('slug') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label for="subcategories" class="block text-sm font-medium text-slate-700">Subcategories</label>
            <input type="text" id="subcategories" name="subcategories"
                   value="{{ old('subcategories', implode(', ', $category->subcategories ?? [])) }}"
                   class="mt-1 w-full rounded border-slate-300" placeholder="Comma separated, e.g. Solid tumours, Haematology, Other">
            <p class="text-xs text-slate-400 mt-1">These appear in the product form once this category is selected.</p>
            @error('subcategories') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-slate-700">Description</label>
            <textarea id="description" name="description" rows="3"
                      class="mt-1 w-full rounded border-slate-300">{{ old('description', $category?->description) }}</textarea>
            @error('description') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label for="image_file" class="block text-sm font-medium text-slate-700">Category Image</label>
                <input type="file" id="image_file" name="image_file" accept="image/*"
                       class="mt-1 w-full text-sm text-slate-600 file:mr-3 file:rounded file:border-0 file:bg-med-50 file:px-3 file:py-2 file:text-med-700">
                @error('image_file') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                <input type="url" id="image_url" name="image_url" value="{{ old('image_url', $category?->image) }}"
                       class="mt-2 w-full rounded border-slate-300" placeholder="...or paste an image URL">
                @error('image_url') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                @if ($category?->image)
                    <img src="{{ $category->image }}" alt="" class="mt-3 w-20 h-20 rounded-full object-cover border border-slate-200">
                    <label class="mt-3 flex items-center gap-2 text-sm text-slate-600">
                        <input type="checkbox" name="remove_image" value="1" class="rounded border-slate-300">
                        Remove current image
                    </label>
                @endif
            </div>
            <div>
                <label for="sort_order" class="block text-sm font-medium text-slate-700">Sort Order</label>
                <input type="number" id="sort_order" name="sort_order" min="0" value="{{ old('sort_order', $category?->sort_order ?? 0) }}"
                       class="mt-1 w-full rounded border-slate-300">
                @error('sort_order') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror

                <label class="mt-5 flex items-center gap-2 text-sm text-slate-700">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" class="rounded border-slate-300"
                           {{ old('is_active', $category?->is_active ?? true) ? 'checked' : '' }}>
                    Visible on frontend
                </label>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 border-t border-slate-100 pt-5">
            <button type="submit" class="btn-primary w-full sm:w-auto text-center">{{ $category ? 'Update Category' : 'Create Category' }}</button>
            <a href="{{ route('admin.categories.index') }}" class="btn-outline w-full sm:w-auto text-center">Cancel</a>
        </div>
    </form>
</div>
@endsection
