@extends('layouts.admin')

@section('content')
<form method="POST" action="{{ $page ? route('admin.pages.update', $page) : route('admin.pages.store') }}" enctype="multipart/form-data" class="bg-white rounded-lg border border-slate-200 shadow-sm p-6 lg:p-8 space-y-6">
    @csrf
    @if ($page)
        @method('PATCH')
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            @if ($page)
                <label class="block text-sm font-medium text-slate-700">Slug</label>
                <div class="mt-1 w-full rounded border border-slate-200 bg-slate-50 px-3 py-2 font-mono text-sm text-slate-600 truncate">
                    {{ $page->slug }}
                </div>
                <p class="text-xs text-slate-500 mt-1">Auto-generated from the heading. Slugs are not editable.</p>
            @else
                <label class="block text-sm font-medium text-slate-700">Slug</label>
                <div class="mt-1 w-full rounded border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-600">
                    Generated after save
                </div>
                <p class="text-xs text-slate-500 mt-1">The slug is generated automatically from the heading.</p>
            @endif
        </div>

        <div>
            <label for="heading" class="block text-sm font-medium text-slate-700">Heading <span class="text-red-500">*</span></label>
            <input type="text" id="heading" name="heading" value="{{ old('heading', $page?->heading) }}" required
                   class="mt-1 w-full rounded border-slate-300">
        </div>
    </div>

    <div>
        <label for="meta_title" class="block text-sm font-medium text-slate-700">Meta Title</label>
        <input type="text" id="meta_title" name="meta_title" value="{{ old('meta_title', $page?->meta_title) }}"
               class="mt-1 w-full rounded border-slate-300">
        <p class="text-xs text-slate-500 mt-1">Browser tab title. Falls back to heading if empty.</p>
    </div>

    <div>
        <label for="lead" class="block text-sm font-medium text-slate-700">Lead / Subtitle</label>
        <input type="text" id="lead" name="lead" value="{{ old('lead', $page?->lead) }}"
               class="mt-1 w-full rounded border-slate-300">
    </div>

    <div class="p-5 bg-med-50/50 border border-med-100 rounded-lg">
        <h2 class="text-lg font-bold text-med-900 mb-4">Page Banner (Hero Image)</h2>
        <label for="banner_image_file" class="block text-sm font-medium text-slate-700">Upload a banner image</label>
        <input type="file" id="banner_image_file" name="banner_image_file" accept=".jpg,.jpeg,.png,.webp"
               class="mt-1 w-full text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-med-50 file:text-med-700">
        <label for="banner_image_url" class="block text-sm font-medium text-slate-700 mt-4">…or use an image URL</label>
        <input type="url" id="banner_image_url" name="banner_image_url" value="{{ old('banner_image_url', $page?->banner_image) }}"
               class="mt-1 w-full rounded border-slate-300" placeholder="https://example.com/banner.jpg">
        @if ($page?->banner_image)
            <img src="{{ $page->banner_image }}" alt="" class="mt-3 w-full h-24 object-cover rounded border border-slate-200">
            <label class="mt-2 flex items-center gap-2 text-sm text-slate-600">
                <input type="checkbox" name="remove_banner_image" value="1" class="rounded border-slate-300">
                Remove current banner image
            </label>
        @endif
        <p class="text-xs text-slate-500 mt-2">Displayed as the page hero background. 1200x400 recommended.</p>
    </div>

    <div>
        <label for="content" class="block text-sm font-medium text-slate-700">Content (HTML)</label>
        <textarea id="content" name="content" rows="15" class="mt-1 w-full rounded border-slate-300 font-mono text-sm">{{ old('content', $page?->content) }}</textarea>
        <p class="text-xs text-slate-500 mt-1">Full HTML content. Be careful with scripts and safe tags.</p>
    </div>

    <div>
        <label for="breadcrumbs" class="block text-sm font-medium text-slate-700">Breadcrumbs</label>
        <textarea id="breadcrumbs" name="breadcrumbs" rows="4" class="mt-1 w-full rounded border-slate-300 font-mono text-sm">{{ old('breadcrumbs', $page?->breadcrumbs ? implode("\n", array_column($page->breadcrumbs, 'label')) : '') }}</textarea>
        <p class="text-xs text-slate-500 mt-1">One label per line. These become the breadcrumb trail.</p>
    </div>

    <div class="flex items-center gap-2">
        <input type="checkbox" id="is_published" name="is_published" value="1" {{ old('is_published', $page?->is_published ?? true) ? 'checked' : '' }}
               class="rounded border-slate-300 text-med-600 focus:ring-med-500">
        <label for="is_published" class="text-sm font-medium text-slate-700">Published</label>
    </div>

    <div class="flex flex-wrap items-center gap-3 pt-4 border-t border-slate-100">
        <button type="submit" class="btn-primary w-full sm:w-auto text-center">{{ $page ? 'Update Page' : 'Create Page' }}</button>
        <a href="{{ route('admin.pages') }}" class="btn-outline w-full sm:w-auto text-center">Cancel</a>
    </div>
</form>
@endsection
