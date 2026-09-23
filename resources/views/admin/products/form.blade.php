@php
$statuses = ['Draft', 'Under Review', 'Approved', 'Published', 'Archived'];
$dosageForms = ['Tablet', 'Capsule', 'Injection', 'Oral liquid', 'Cream/ointment', 'Other'];
$routes = ['Oral', 'Injection', 'Topical', 'Inhalation', 'Other'];
$legalStatuses = ['Prescription only', 'Non-prescription', 'Hospital only', 'Country dependent', 'Unknown'];
$availability = ['Information available', 'Professional enquiry only', 'Country dependent', 'Unavailable', 'Discontinued', 'Under verification'];
$languages = ['English', 'Simplified Chinese', 'Traditional Chinese', 'French', 'Spanish', 'Arabic', 'Other'];

function oldOr($product, $path, $default = '') {
    $value = old($path) !== null ? old($path) : ($product ? data_get($product, $path, $default) : $default);
    return is_array($value) ? implode(', ', $value) : $value;
}
@endphp

@extends('layouts.admin')

@section('content')
<form method="POST"
      action="{{ $product ? route('admin.products.update', $product) : route('admin.products.store') }}"
      enctype="multipart/form-data"
      class="bg-white rounded-xl border border-slate-200 shadow-lg p-6 lg:p-8 space-y-6">
    @csrf
    @if ($product)
        @method('PUT')
    @endif

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
            <p class="font-medium">Please fix the errors below.</p>
            <ul class="list-disc list-inside text-sm mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Section 1: Basic Product Information --}}
    <section class="bg-slate-50 rounded-xl border border-slate-200 p-6 space-y-6 shadow-sm">
        <h2 class="text-lg font-semibold text-navy-900 border-b border-slate-200 pb-2 mb-4">1. Basic Product Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="internal_product_id" class="block text-sm font-medium text-slate-700">Internal Product ID <span class="text-red-500">*</span></label>
                <input type="text" id="internal_product_id" name="internal_product_id" value="{{ oldOr($product, 'internal_product_id', $internalId) }}"
                       class="mt-1 w-full rounded border-slate-300 font-mono text-sm" required>
                <p class="text-xs text-slate-500 mt-1">Auto-generated as Category-###. Editable.</p>
            </div>

            <div>
                <label for="brand_name" class="block text-sm font-medium text-slate-700">Brand Name <span class="text-red-500">*</span></label>
                <input type="text" id="brand_name" name="brand_name" value="{{ oldOr($product, 'brand_name') }}"
                       class="mt-1 w-full rounded border-slate-300" required>
            </div>

            <div>
                <label for="generic_inn_name" class="block text-sm font-medium text-slate-700">Generic / INN Name <span class="text-red-500">*</span></label>
                <input type="text" id="generic_inn_name" name="generic_inn_name" value="{{ oldOr($product, 'generic_inn_name') }}"
                       class="mt-1 w-full rounded border-slate-300" required>
            </div>

            <div class="md:col-span-2">
                <label class="flex items-center gap-2 text-sm font-medium text-slate-700">
                    <input type="checkbox" id="show_other_name" name="show_other_name" value="1"
                           {{ oldOr($product, 'other_name') ? 'checked' : '' }}
                           class="rounded border-slate-300 text-med-600 focus:ring-med-500">
                    Add alternative name
                </label>
                <div id="other_name_wrapper" class="mt-2 {{ oldOr($product, 'other_name') ? '' : 'hidden' }}">
                    <input type="text" id="other_name" name="other_name" value="{{ oldOr($product, 'other_name') }}"
                           class="w-full rounded border-slate-300" placeholder="Other / alternative name">
                </div>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700">Therapeutic Categories <span class="text-red-500">*</span></label>
                <p class="text-xs text-slate-500 mt-1">Select one or more categories this product belongs to.</p>
                @php
                    $selectedCategories = old('categories', $product?->categories ?? array_filter([$product?->therapeutic_category]));
                @endphp
                <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                    @foreach ($categories as $cat)
                        <label class="flex items-center gap-2 text-sm text-slate-700 rounded border border-slate-200 px-3 py-2 bg-white cursor-pointer hover:border-med-300">
                            <input type="checkbox" name="categories[]" value="{{ $cat }}"
                                   {{ in_array($cat, $selectedCategories ?? []) ? 'checked' : '' }}
                                   class="category-checkbox rounded border-slate-300 text-med-600 focus:ring-med-500">
                            {{ $cat }}
                        </label>
                    @endforeach
                </div>
                @error('categories')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700">Subcategories</label>
                <p class="text-xs text-slate-500 mt-1">Optional — pick disease areas under each selected category. Products without a subcategory appear under "Other".</p>
                @php
                    $selectedSubcategories = old('subcategories', $product?->subcategories ?? ($product?->subcategory ? [$product->therapeutic_category . '|' . $product->subcategory] : []));
                @endphp
                <div id="subcategory-groups" class="mt-2 space-y-3"></div>
                <p id="subcategory-empty" class="text-xs text-slate-400 mt-1">Select a category above to see its subcategories.</p>
                @if (! empty($subcategoryMap))
                    <script type="application/json" id="subcategory-data">@json($subcategoryMap)</script>
                @endif
                <script type="application/json" id="subcategory-selected">@json($selectedSubcategories ?? [])</script>
            </div>

            <div>
                <label for="dosage_form" class="block text-sm font-medium text-slate-700">Dosage Form <span class="text-red-500">*</span></label>
                <select id="dosage_form" name="dosage_form" class="mt-1 w-full rounded border-slate-300" required>
                    <option value="">Select...</option>
                    @foreach ($dosageForms as $form)
                        <option value="{{ $form }}" {{ oldOr($product, 'dosage_form') === $form ? 'selected' : '' }}>{{ $form }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="strength" class="block text-sm font-medium text-slate-700">Strength <span class="text-red-500">*</span></label>
                <div class="mt-1 relative">
                    <input type="text" id="strength" name="strength" value="{{ oldOr($product, 'strength') }}"
                           class="w-full rounded border-slate-300" placeholder="e.g. 500 mg" required>
                    <span class="absolute right-3 top-2 text-xs text-slate-400 pointer-events-none">mg · mcg · mL</span>
                </div>
            </div>

            <div>
                <label for="pack_size_spec" class="block text-sm font-medium text-slate-700">Pack Size / Spec <span class="text-red-500">*</span></label>
                <input type="text" id="pack_size_spec" name="pack_size_spec" value="{{ oldOr($product, 'pack_size_spec') }}"
                       class="mt-1 w-full rounded border-slate-300" placeholder="e.g. 30 capsules per pack" required>
            </div>

            <div>
                <label for="sort_order" class="block text-sm font-medium text-slate-700">Display Order</label>
                <input type="number" id="sort_order" name="sort_order" value="{{ oldOr($product, 'sort_order', 0) }}" min="0" max="9999"
                       class="mt-1 w-full rounded border-slate-300" placeholder="0">
                <p class="text-xs text-slate-500 mt-1">Lower numbers show first in product listings. Same number = alphabetical.</p>
            </div>

            <div>
                <label for="route_admin" class="block text-sm font-medium text-slate-700">Route of Administration</label>
                <select id="route_admin" name="route_admin" class="mt-1 w-full rounded border-slate-300">
                    <option value="">Select...</option>
                    @foreach ($routes as $r)
                        <option value="{{ $r }}" {{ oldOr($product, 'route_admin') === $r ? 'selected' : '' }}>{{ $r }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="manufacturer_id" class="block text-sm font-medium text-slate-700">Manufacturer <span class="text-red-500">*</span></label>
                <select id="manufacturer_id" name="manufacturer_id" class="mt-1 w-full rounded border-slate-300" required>
                    <option value="">Select...</option>
                    @foreach ($manufacturers as $m)
                        <option value="{{ $m->id }}" {{ oldOr($product, 'manufacturer_id') == $m->id ? 'selected' : '' }}>{{ $m->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="country_of_origin" class="block text-sm font-medium text-slate-700">Country of Origin <span class="text-red-500">*</span></label>
                <input type="text" id="country_of_origin" name="country_of_origin" value="{{ oldOr($product, 'country_of_origin') }}"
                       class="mt-1 w-full rounded border-slate-300" required>
            </div>

            <div>
                <label for="legal_status" class="block text-sm font-medium text-slate-700">Legal Status <span class="text-red-500">*</span></label>
                <select id="legal_status" name="legal_status" class="mt-1 w-full rounded border-slate-300" required>
                    <option value="">Select...</option>
                    @foreach ($legalStatuses as $ls)
                        <option value="{{ $ls }}" {{ oldOr($product, 'legal_status') === $ls ? 'selected' : '' }}>{{ $ls }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </section>

    {{-- Section 2: Product Description and Media --}}
    <section class="bg-slate-50 rounded-xl border border-slate-200 p-6 space-y-6 shadow-sm">
        <h2 class="text-lg font-semibold text-navy-900 border-b border-slate-200 pb-2 mb-4">2. Product Description and Media</h2>
        <div class="grid grid-cols-1 gap-6">
            <div>
                <label for="active_ingredients" class="block text-sm font-medium text-slate-700">Active Ingredients <span class="text-red-500">*</span></label>
                <textarea id="active_ingredients" name="active_ingredients" rows="3" class="mt-1 w-full rounded border-slate-300" required>{{ oldOr($product, 'active_ingredients') }}</textarea>
            </div>

            <div>
                <label for="short_description" class="block text-sm font-medium text-slate-700">Short Description <span class="text-red-500">*</span></label>
                <textarea id="short_description" name="short_description" rows="4" class="mt-1 w-full rounded border-slate-300" required>{{ oldOr($product, 'short_description') }}</textarea>
                <p class="text-xs text-slate-500 mt-1">Word count: <span id="short_word_count">0</span></p>
            </div>

            <div>
                <label for="full_description" class="block text-sm font-medium text-slate-700">Full Description <span class="text-red-500">*</span></label>
                <textarea id="full_description" name="full_description" rows="6" class="mt-1 w-full rounded border-slate-300" required>{{ oldOr($product, 'full_description') }}</textarea>
            </div>

            <div>
                <label for="approved_indication" class="block text-sm font-medium text-slate-700">Approved Indication <span class="text-red-500">*</span></label>
                <textarea id="approved_indication" name="approved_indication" rows="5" class="mt-1 w-full rounded border-slate-300" required>{{ oldOr($product, 'approved_indication') }}</textarea>
            </div>

            <div>
                <label for="image_files" class="block text-sm font-medium text-slate-700">Product Images <span class="text-red-500">*</span></label>
                <input type="file" id="image_files" name="image_files[]" multiple accept=".jpg,.jpeg,.png,.webp"
                       class="mt-1 w-full text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-med-50 file:text-med-700">
                <p class="text-xs text-slate-500 mt-1">Allowed: .jpg, .png, .webp — max 5 MB each.</p>

                @if ($product && ! empty($product->product_images))
                    <div class="mt-3 grid grid-cols-2 sm:grid-cols-4 gap-3">
                        @foreach ($product->product_images as $i => $img)
                            <div class="rounded border border-slate-200 overflow-hidden bg-white">
                                <a href="{{ $img }}" target="_blank" class="block">
                                    <img src="{{ $img }}" alt="" class="w-full h-24 object-cover">
                                </a>
                                <div class="p-2 space-y-1.5">
                                    <input type="text" name="image_labels[{{ $i }}]" value="{{ $product->product_image_labels[$i] ?? '' }}"
                                           placeholder="Label (e.g. 250 ml)" maxlength="100"
                                           class="w-full rounded border-slate-300 text-xs px-2 py-1">
                                    <label class="flex items-center gap-1.5 text-xs text-red-600">
                                        <input type="checkbox" name="remove_images[]" value="{{ $i }}" class="rounded border-slate-300 text-red-600 focus:ring-red-500">
                                        Remove
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <p class="text-xs text-slate-500 mt-2">Labels appear under the image on the product page (e.g. 250 ml, 500 ml). Tick "Remove" and save to delete an image.</p>
                @endif
            </div>

            <div>
                <label for="image_alt_text" class="block text-sm font-medium text-slate-700">Image Alt Text <span class="text-red-500">*</span></label>
                <input type="text" id="image_alt_text" name="image_alt_text" value="{{ oldOr($product, 'image_alt_text') }}"
                       class="mt-1 w-full rounded border-slate-300" required>
            </div>

            <div>
                <label for="banner_image_file" class="block text-sm font-medium text-slate-700">Banner Image</label>
                <input type="file" id="banner_image_file" name="banner_image_file" accept=".jpg,.jpeg,.png,.webp"
                       class="mt-1 w-full text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-med-50 file:text-med-700">
                <input type="url" id="banner_image_url" name="banner_image_url" value="{{ oldOr($product, 'banner_image') }}"
                       class="mt-2 w-full rounded border-slate-300" placeholder="...or paste a banner image URL">
                @if ($product?->banner_image)
                    <img src="{{ $product->banner_image }}" alt="" class="mt-3 w-full h-24 object-cover rounded border border-slate-200">
                    <label class="mt-2 flex items-center gap-2 text-sm text-slate-600">
                        <input type="checkbox" name="remove_banner_image" value="1" class="rounded border-slate-300">
                        Remove current banner image
                    </label>
                @endif
                <p class="text-xs text-slate-500 mt-1">Used as the HD banner on the product detail page. 1200x400 recommended.</p>
            </div>
        </div>
    </section>

    {{-- Section 3: Use and Safety Information --}}
    <section class="bg-slate-50 rounded-xl border border-slate-200 p-6 space-y-6 shadow-sm">
        <h2 class="text-lg font-semibold text-navy-900 border-b border-slate-200 pb-2 mb-4">3. Use and Safety Information</h2>
        <div class="grid grid-cols-1 gap-6">
            <div id="dosage_admin_wrapper">
                <label for="dosage_admin_text" class="block text-sm font-medium text-slate-700">Dosage Administration</label>
                <textarea id="dosage_admin_text" name="dosage_admin_text" rows="5" class="mt-1 w-full rounded border-slate-300">{{ oldOr($product, 'dosage_admin_text') }}</textarea>
            </div>

            <div>
                <label for="safety_info" class="block text-sm font-medium text-slate-700">Safety Info <span class="text-red-500">*</span></label>
                <textarea id="safety_info" name="safety_info" rows="5" class="mt-1 w-full rounded border-slate-300" required>{{ oldOr($product, 'safety_info') }}</textarea>
                <p class="text-xs text-slate-500 mt-1">Warnings, contraindications and adverse reactions.</p>
            </div>

            <div>
                <label class="flex items-center gap-2 text-sm font-medium text-slate-700">
                    <input type="hidden" name="has_known_interactions" value="0">
                    <input type="checkbox" id="has_known_interactions" name="has_known_interactions" value="1"
                           {{ old('has_known_interactions', $product && $product->has_known_interactions ? '1' : '0') == '1' ? 'checked' : '' }}
                           class="rounded border-slate-300 text-med-600 focus:ring-med-500">
                    This product has known interactions
                </label>
                <div id="drug_interactions_wrapper" class="mt-2 {{ old('has_known_interactions', $product && $product->has_known_interactions ? '1' : '0') == '1' ? '' : 'hidden' }}">
                    <textarea id="drug_interactions" name="drug_interactions" rows="4" class="w-full rounded border-slate-300">{{ oldOr($product, 'drug_interactions') }}</textarea>
                </div>
            </div>

            <div>
                <label class="flex items-center gap-2 text-sm font-medium text-slate-700">
                    <input type="hidden" name="has_precautions" value="0">
                    <input type="checkbox" id="has_precautions" name="has_precautions" value="1"
                           {{ old('has_precautions', $product && $product->has_precautions ? '1' : '0') == '1' ? 'checked' : '' }}
                           class="rounded border-slate-300 text-med-600 focus:ring-med-500">
                    Special population precautions apply
                </label>
                <div id="precautions_wrapper" class="mt-2 {{ old('has_precautions', $product && $product->has_precautions ? '1' : '0') == '1' ? '' : 'hidden' }}">
                    <textarea id="precautions" name="precautions" rows="4" class="w-full rounded border-slate-300">{{ oldOr($product, 'precautions') }}</textarea>
                </div>
            </div>

            <div>
                <label for="storage_conditions" class="block text-sm font-medium text-slate-700">Storage Conditions <span class="text-red-500">*</span></label>
                <textarea id="storage_conditions" name="storage_conditions" rows="3" class="mt-1 w-full rounded border-slate-300" required>{{ oldOr($product, 'storage_conditions') }}</textarea>
            </div>
        </div>
    </section>

    {{-- Section 4: Website and Market Information --}}
    <section class="bg-slate-50 rounded-xl border border-slate-200 p-6 space-y-6 shadow-sm">
        <h2 class="text-lg font-semibold text-navy-900 border-b border-slate-200 pb-2 mb-4">4. Website and Market Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="availability_status" class="block text-sm font-medium text-slate-700">Availability Status <span class="text-red-500">*</span></label>
                <select id="availability_status" name="availability_status" class="mt-1 w-full rounded border-slate-300" required>
                    <option value="">Select...</option>
                    @foreach ($availability as $a)
                        <option value="{{ $a }}" {{ oldOr($product, 'availability_status') === $a ? 'selected' : '' }}>{{ $a }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="country_market" class="block text-sm font-medium text-slate-700">Country Market <span class="text-red-500">*</span></label>
                <input type="text" id="country_market" name="country_market" value="{{ oldOr($product, 'country_market') }}"
                       class="mt-1 w-full rounded border-slate-300" placeholder="e.g. China, USA, United Kingdom" required>
            </div>

            <div>
                <label for="page_language" class="block text-sm font-medium text-slate-700">Page Language <span class="text-red-500">*</span></label>
                <select id="page_language" name="page_language" class="mt-1 w-full rounded border-slate-300" required>
                    <option value="">Select...</option>
                    @foreach ($languages as $l)
                        <option value="{{ $l }}" {{ oldOr($product, 'page_language') === $l ? 'selected' : '' }}>{{ $l }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="enquiry_contact_link" class="block text-sm font-medium text-slate-700">Enquiry Contact Link <span class="text-red-500">*</span></label>
                <input type="text" id="enquiry_contact_link" name="enquiry_contact_link" value="{{ oldOr($product, 'enquiry_contact_link') }}"
                       class="mt-1 w-full rounded border-slate-300" placeholder="/professional-enquiry or https://..." required>
            </div>

            <div>
                <label for="url_slug" class="block text-sm font-medium text-slate-700">URL Slug <span class="text-red-500">*</span></label>
                <input type="text" id="url_slug" name="url_slug" value="{{ oldOr($product, 'url_slug') }}"
                       class="mt-1 w-full rounded border-slate-300 font-mono text-sm" required>
                <p class="text-xs text-slate-500 mt-1">Lowercase letters, numbers and hyphens only.</p>
            </div>

            <div>
                <label for="seo_title" class="block text-sm font-medium text-slate-700">SEO Title <span class="text-red-500">*</span></label>
                <input type="text" id="seo_title" name="seo_title" value="{{ oldOr($product, 'seo_title') }}"
                       class="mt-1 w-full rounded border-slate-300" maxlength="60" placeholder="Product Name - Strength - Brand" required>
            </div>

            <div class="md:col-span-2">
                <label for="meta_description" class="block text-sm font-medium text-slate-700">Meta Description <span class="text-red-500">*</span></label>
                <textarea id="meta_description" name="meta_description" rows="3" maxlength="160" class="mt-1 w-full rounded border-slate-300" required>{{ oldOr($product, 'meta_description') }}</textarea>
                <p class="text-xs text-slate-500 mt-1"><span id="meta_char_count">0</span> / 160 characters</p>
            </div>
        </div>
    </section>

    {{-- Section 5: Source, Review and Publication --}}
    <section class="bg-slate-50 rounded-xl border border-slate-200 p-6 space-y-6 shadow-sm">
        <h2 class="text-lg font-semibold text-navy-900 border-b border-slate-200 pb-2 mb-4">5. Source, Review and Publication</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="official_source_url" class="block text-sm font-medium text-slate-700">Official Source URL</label>
                <input type="url" id="official_source_url" name="official_source_url" value="{{ oldOr($product, 'official_source_url') }}"
                       class="mt-1 w-full rounded border-slate-300">
            </div>

            <div>
                <label for="last_verified_date" class="block text-sm font-medium text-slate-700">Last Verified Date</label>
                <input type="date" id="last_verified_date" name="last_verified_date"
                       value="{{ ($date = oldOr($product, 'last_verified_date')) ? \Illuminate\Support\Carbon::parse($date)->format('Y-m-d') : '' }}"
                       class="mt-1 w-full rounded border-slate-300">
            </div>

            <div>
                <label for="content_status" class="block text-sm font-medium text-slate-700">Content Status <span class="text-red-500">*</span></label>
                <select id="content_status" name="content_status" class="mt-1 w-full rounded border-slate-300" required>
                    @foreach ($statuses as $s)
                        <option value="{{ $s }}" {{ oldOr($product, 'content_status', 'Draft') === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="reviewer_approver" class="block text-sm font-medium text-slate-700">Reviewer / Approver <span class="text-red-500">*</span></label>
                <input type="text" id="reviewer_approver" name="reviewer_approver"
                       value="{{ oldOr($product, 'reviewer_approver', auth()->user()->name ?? '') }}"
                       class="mt-1 w-full rounded border-slate-300" required>
            </div>

            <div class="md:col-span-2">
                <label for="information_disclaimer" class="block text-sm font-medium text-slate-700">Information Disclaimer <span class="text-red-500">*</span></label>
                <textarea id="information_disclaimer" name="information_disclaimer" rows="5" class="mt-1 w-full rounded border-slate-300" required>{{ oldOr($product, 'information_disclaimer', 'This product information is provided for educational purposes only and is not a substitute for professional medical advice. Always consult a qualified healthcare provider before use.') }}</textarea>
            </div>
        </div>
    </section>

    <div class="flex flex-wrap items-center gap-3 p-4 bg-slate-50 rounded-xl border border-slate-200 shadow-sm">
        <button type="submit" name="action" value="draft" class="btn-outline w-full sm:w-auto text-center">Save Draft</button>
        <button type="submit" name="action" value="save" class="btn-primary w-full sm:w-auto text-center">{{ $product ? 'Update' : 'Create' }}</button>
        <button type="submit" name="action" value="publish" class="btn-primary w-full sm:w-auto text-center bg-green-600 hover:bg-green-700">Publish</button>

        @if ($product)
            <a href="{{ url('/products/' . $product->url_slug) }}" target="_blank" class="btn-outline w-full sm:w-auto text-center">Preview</a>
        @endif

        <a href="{{ route('admin.products.index') }}" class="btn-outline w-full sm:w-auto text-center ml-auto">Cancel</a>
    </div>
</form>

<script>
(function () {
    const dosageForm = document.getElementById('dosage_form');
    const routeAdmin = document.getElementById('route_admin');
    const legalStatus = document.getElementById('legal_status');
    const dosageAdmin = document.getElementById('dosage_admin_text');
    const contentStatus = document.getElementById('content_status');
    const officialSource = document.getElementById('official_source_url');
    const verifiedDate = document.getElementById('last_verified_date');

    function updateRouteAdmin() {
        if (dosageForm.value === 'Other') {
            routeAdmin.disabled = true;
            routeAdmin.value = '';
            routeAdmin.closest('div').classList.add('opacity-50');
        } else {
            routeAdmin.disabled = false;
            routeAdmin.closest('div').classList.remove('opacity-50');
        }
    }

    function updateDosageAdminRequired() {
        const isUnknown = legalStatus.value === 'Unknown';
        dosageAdmin.required = !isUnknown;
        document.getElementById('dosage_admin_wrapper').classList.toggle('hidden', isUnknown);
    }

    function updateSourceRequired() {
        const needsSource = ['Approved', 'Published'].includes(contentStatus.value);
        officialSource.required = needsSource;
        verifiedDate.required = needsSource && officialSource.value !== '';
    }

    function toggleOtherName() {
        const show = document.getElementById('show_other_name').checked;
        document.getElementById('other_name_wrapper').classList.toggle('hidden', !show);
    }

    function toggleInteractions() {
        const show = document.getElementById('has_known_interactions').checked;
        document.getElementById('drug_interactions_wrapper').classList.toggle('hidden', !show);
        document.getElementById('drug_interactions').required = show;
    }

    function togglePrecautions() {
        const show = document.getElementById('has_precautions').checked;
        document.getElementById('precautions_wrapper').classList.toggle('hidden', !show);
        document.getElementById('precautions').required = show;
    }

    function wordCount(text) {
        return text.trim().split(/\s+/).filter(w => w.length > 0).length;
    }

    function updateWordCount() {
        const text = document.getElementById('short_description').value;
        document.getElementById('short_word_count').textContent = wordCount(text);
    }

    function updateCharCount() {
        const text = document.getElementById('meta_description').value;
        document.getElementById('meta_char_count').textContent = text.length;
    }

    function suggestSlug() {
        const brand = document.getElementById('brand_name').value.trim();
        const strength = document.getElementById('strength').value.trim().toLowerCase().replace(/\s+/g, '-');
        const slug = document.getElementById('url_slug');
        if (brand && !slug.dataset.touched) {
            slug.value = (brand + '-' + strength).toLowerCase().replace(/[^a-z0-9-]+/g, '-').replace(/-+/g, '-').replace(/^-|-$/g, '');
        }
    }

    let initialSubsApplied = false;

    function renderSubcategoryGroups() {
        const data = JSON.parse(document.getElementById('subcategory-data').textContent);
        const container = document.getElementById('subcategory-groups');
        const checkedCats = Array.from(document.querySelectorAll('.category-checkbox:checked')).map(cb => cb.value);

        const active = new Set(
            Array.from(container.querySelectorAll('input[name="subcategories[]"]:checked')).map(i => i.value)
        );
        if (!initialSubsApplied) {
            JSON.parse(document.getElementById('subcategory-selected').textContent).forEach(s => active.add(s));
        }

        container.innerHTML = '';
        checkedCats.forEach(function (cat) {
            const subs = data[cat] || [];
            if (!subs.length) return;

            const group = document.createElement('div');
            group.className = 'rounded border border-slate-200 bg-white p-3';
            const heading = document.createElement('p');
            heading.className = 'text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2';
            heading.textContent = cat;
            group.appendChild(heading);

            const grid = document.createElement('div');
            grid.className = 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-1.5';
            subs.forEach(function (sub) {
                const value = cat + '|' + sub;
                const label = document.createElement('label');
                label.className = 'flex items-center gap-2 text-sm text-slate-700 cursor-pointer';
                const input = document.createElement('input');
                input.type = 'checkbox';
                input.name = 'subcategories[]';
                input.value = value;
                input.checked = active.has(value);
                input.className = 'rounded border-slate-300 text-med-600 focus:ring-med-500';
                label.appendChild(input);
                label.appendChild(document.createTextNode(sub));
                grid.appendChild(label);
            });
            group.appendChild(grid);
            container.appendChild(group);
        });

        document.getElementById('subcategory-empty').style.display = container.children.length ? 'none' : '';
        initialSubsApplied = true;
    }

    function suggestInternalId() {
        const first = document.querySelector('.category-checkbox:checked');
        const id = document.getElementById('internal_product_id');
        if (first && !id.dataset.touched) {
            id.value = (first.value.replace(/[^a-z]/gi, '').substring(0, 3).toUpperCase() || 'GEN') + '-';
        }
    }

    dosageForm.addEventListener('change', updateRouteAdmin);
    legalStatus.addEventListener('change', updateDosageAdminRequired);
    contentStatus.addEventListener('change', updateSourceRequired);
    officialSource.addEventListener('input', updateSourceRequired);
    document.getElementById('show_other_name').addEventListener('change', toggleOtherName);
    document.getElementById('has_known_interactions').addEventListener('change', toggleInteractions);
    document.getElementById('has_precautions').addEventListener('change', togglePrecautions);
    document.getElementById('short_description').addEventListener('input', updateWordCount);
    document.getElementById('meta_description').addEventListener('input', updateCharCount);
    document.getElementById('brand_name').addEventListener('blur', suggestSlug);
    document.getElementById('strength').addEventListener('blur', suggestSlug);
    document.getElementById('url_slug').addEventListener('input', () => document.getElementById('url_slug').dataset.touched = '1');
    document.querySelectorAll('.category-checkbox').forEach(cb => {
        cb.addEventListener('change', renderSubcategoryGroups);
        cb.addEventListener('change', suggestInternalId);
    });
    document.getElementById('internal_product_id').addEventListener('input', () => document.getElementById('internal_product_id').dataset.touched = '1');

    renderSubcategoryGroups();
    updateRouteAdmin();
    updateDosageAdminRequired();
    updateSourceRequired();
    toggleOtherName();
    toggleInteractions();
    togglePrecautions();
    updateWordCount();
    updateCharCount();
})();
</script>
@endsection
