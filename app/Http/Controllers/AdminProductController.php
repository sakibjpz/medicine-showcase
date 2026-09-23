<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Manufacturer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Web CRUD
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        return view('admin.products.index', [
            'title' => 'Products',
            'products' => Product::with('manufacturer')->latest()->paginate(15),
        ]);
    }

    public function create()
    {
        return view('admin.products.form', [
            'title' => 'Create Product',
            'product' => null,
            'internalId' => Product::nextInternalId($this->categoryNames()[0] ?? 'Other'),
            ...$this->lookupLists(),
        ]);
    }

    public function store(Request $request)
    {
        $input = $this->prepareInput($request, null);
        $isDraft = ($input['action'] ?? '') === 'draft';

        $validator = Validator::make($input, $isDraft ? $this->draftRules() : $this->fullRules(null, $input));

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $resolved = $this->resolveProductImages($request, null);
        $input['product_images'] = $resolved['images'];
        $input['product_image_labels'] = $resolved['labels'];
        $input['banner_image'] = $this->bannerImage($request, null);

        if (! $isDraft && empty($input['product_images'])) {
            return back()->withErrors(['product_images' => 'At least one product image is required.'])->withInput();
        }

        $input = $this->normalizeConditionalFields($input);

        Product::create($input);

        return redirect()->route('admin.products.index')->with('success', $isDraft ? 'Draft saved.' : 'Product saved.');
    }

    public function edit(Product $product)
    {
        return view('admin.products.form', [
            'title' => 'Edit Product: ' . $product->brand_name,
            'product' => $product,
            'internalId' => $product->internal_product_id,
            ...$this->lookupLists(),
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $input = $this->prepareInput($request, $product);
        $isDraft = ($input['action'] ?? '') === 'draft';

        $validator = Validator::make($input, $isDraft ? $this->draftRules() : $this->fullRules($product, $input));

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $resolved = $this->resolveProductImages($request, $product);
        $input['product_images'] = $resolved['images'];
        $input['product_image_labels'] = $resolved['labels'];
        $input['banner_image'] = $this->bannerImage($request, $product);

        $input = $this->normalizeConditionalFields($input);

        $product->update($input);

        return redirect()->route('admin.products.index')->with('success', $isDraft ? 'Draft saved.' : 'Product updated.');
    }

    public function destroy(Product $product)
    {
        $product->update(['content_status' => 'Archived']);

        return redirect()->route('admin.products.index')->with('success', 'Product archived.');
    }

    /*
    |--------------------------------------------------------------------------
    | API endpoints
    |--------------------------------------------------------------------------
    */

    public function apiIndex(Request $request)
    {
        $query = Product::with('manufacturer');

        if ($request->filled('status')) {
            $query->where('content_status', $request->input('status'));
        }

        if ($request->filled('category')) {
            $query->where('therapeutic_category', $request->input('category'));
        }

        if ($request->filled('subcategory')) {
            $query->where('subcategory', $request->input('subcategory'));
        }

        if ($request->filled('manufacturer')) {
            $query->where('manufacturer_id', $request->input('manufacturer'));
        }

        return response()->json($query->latest()->paginate(20));
    }

    public function apiShow(Product $product)
    {
        return response()->json($product->load('manufacturer'));
    }

    public function apiStore(Request $request)
    {
        $input = $this->prepareInput($request, null);
        $validator = Validator::make($input, $this->fullRules(null, $input));

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $resolved = $this->resolveProductImages($request, null);
        $input['product_images'] = $resolved['images'];
        $input['product_image_labels'] = $resolved['labels'];

        if (empty($input['product_images'])) {
            return response()->json(['errors' => ['product_images' => ['At least one product image is required.']]], 422);
        }

        $input = $this->normalizeConditionalFields($input);

        $product = Product::create($input);

        return response()->json($product->load('manufacturer'), 201);
    }

    public function apiUpdate(Request $request, Product $product)
    {
        $input = $this->prepareInput($request, $product);
        $validator = Validator::make($input, $this->fullRules($product, $input));

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $resolved = $this->resolveProductImages($request, $product);
        $input['product_images'] = $resolved['images'];
        $input['product_image_labels'] = $resolved['labels'];
        $input = $this->normalizeConditionalFields($input);

        $product->update($input);

        return response()->json($product->load('manufacturer'));
    }

    public function apiDestroy(Product $product)
    {
        $product->update(['content_status' => 'Archived']);

        return response()->json(['message' => 'Product archived.']);
    }

    public function apiManufacturers()
    {
        return response()->json(Manufacturer::orderBy('name')->get(['id', 'name', 'country']));
    }

    public function validateSlug(Request $request)
    {
        $slug = $request->input('slug');
        $exists = Product::where('url_slug', $slug)->exists();

        return response()->json(['available' => ! $exists]);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    private function prepareInput(Request $request, ?Product $product): array
    {
        $input = $request->all();

        $input['internal_product_id'] = ! empty($input['internal_product_id'])
            ? $input['internal_product_id']
            : ($product?->internal_product_id ?? Product::nextInternalId($input['therapeutic_category'] ?? 'Other'));

        $input['url_slug'] = ! empty($input['url_slug'])
            ? $input['url_slug']
            : ($product?->url_slug ?? Product::uniqueSlug(Product::slugFrom($input['brand_name'] ?? '', $input['strength'] ?? '')));

        $action = $input['action'] ?? 'save';
        if ($action === 'draft') {
            $input['content_status'] = 'Draft';
        } elseif ($action === 'publish') {
            $input['content_status'] = 'Published';
        } else {
            $input['content_status'] = $input['content_status'] ?? ($product?->content_status ?? 'Draft');
        }

        $input['reviewer_approver'] = ! empty($input['reviewer_approver'])
            ? $input['reviewer_approver']
            : (auth()->user()?->name ?? 'System');

        return $input;
    }

    private function normalizeConditionalFields(array $input): array
    {
        if (($input['dosage_form'] ?? '') === 'Other') {
            $input['route_admin'] = null;
        }

        if (($input['legal_status'] ?? '') === 'Unknown') {
            $input['dosage_admin_text'] = null;
        }

        $input['has_known_interactions'] = ! empty($input['has_known_interactions']);
        if (! $input['has_known_interactions']) {
            $input['drug_interactions'] = null;
        }

        $input['has_precautions'] = ! empty($input['has_precautions']);
        if (! $input['has_precautions']) {
            $input['precautions'] = null;
        }

        $input['sort_order'] = (int) ($input['sort_order'] ?? 0);
        $input['country_market'] = $this->toArray($input['country_market'] ?? '');
        $input['product_images'] = $input['product_images'] ?? [];

        unset($input['has_interactions']);

        return $input;
    }

    private function toArray($value): array
    {
        if (is_array($value)) {
            return array_values(array_filter(array_map('trim', $value)));
        }

        if (empty($value)) {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode(',', $value))));
    }

    private function resolveProductImages(Request $request, ?Product $product): array
    {
        $newImages = [];

        if ($request->hasFile('image_files')) {
            $directory = public_path('images/products');
            if (! is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            foreach ($request->file('image_files') as $file) {
                $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension() ?: $file->guessExtension() ?: 'jpg';
                $name = Str::slug($filename) . '-' . time() . '-' . uniqid() . '.' . $extension;
                $file->move($directory, $name);
                $newImages[] = asset('images/products/' . $name);
            }
        }

        $existing = $product ? ($product->product_images ?? []) : [];
        $existingLabels = $product ? ($product->product_image_labels ?? []) : [];
        $removed = array_map('intval', (array) $request->input('remove_images', []));

        $keptImages = [];
        $keptLabels = [];
        foreach ($existing as $idx => $img) {
            if (in_array($idx, $removed, true)) {
                continue;
            }
            $keptImages[] = $img;
            $keptLabels[] = trim((string) $request->input('image_labels.' . $idx, $existingLabels[$idx] ?? ''));
        }

        $images = array_values(array_unique(array_merge($newImages, $keptImages)));
        $labels = array_merge(array_fill(0, count($newImages), ''), $keptLabels);
        $labels = array_pad(array_slice($labels, 0, count($images)), count($images), '');

        return ['images' => $images, 'labels' => $labels];
    }

    private function bannerImage(Request $request, ?Product $product): ?string
    {
        if ($request->boolean('remove_banner_image')) {
            return null;
        }

        if ($request->hasFile('banner_image_file')) {
            $directory = public_path('images/products/banners');
            if (! is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            $file = $request->file('banner_image_file');
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension() ?: $file->guessExtension() ?: 'jpg';
            $name = Str::slug($filename) . '-' . time() . '-' . uniqid() . '.' . $extension;
            $file->move($directory, $name);

            return asset('images/products/banners/' . $name);
        }

        $url = $request->input('banner_image_url');
        if (! empty($url)) {
            return $url;
        }

        return $product?->banner_image;
    }

    private function draftRules(): array
    {
        return [
            'internal_product_id' => 'required|string|max:50|unique:products,internal_product_id',
            'brand_name' => 'required|string|max:255',
            'generic_inn_name' => 'required|string|max:255',
        ];
    }

    private function fullRules(?Product $product, array $input): array
    {
        $imageRequired = is_null($product) ? 'required' : 'nullable';

        return [
            'internal_product_id' => [
                'required', 'string', 'max:50',
                Rule::unique('products', 'internal_product_id')->ignore($product?->id),
            ],
            'brand_name' => 'required|string|max:255',
            'generic_inn_name' => 'required|string|max:255',
            'other_name' => 'nullable|string|max:255',
            'therapeutic_category' => ['required', 'string', Rule::in($this->categoryNames())],
            'subcategory' => [
                'required',
                'string',
                Rule::in($this->subcategoryMap()[$input['therapeutic_category'] ?? ''] ?? []),
            ],
            'dosage_form' => 'required|string|in:Tablet,Capsule,Injection,Oral liquid,Cream/ointment,Other',
            'strength' => ['required', 'string', 'max:50', 'regex:/^\d+\s*(mg|g|mcg|mL|IU|%)?$/i'],
            'pack_size_spec' => 'required|string|max:255',
            'sort_order' => 'nullable|integer|min:0|max:9999',
            'route_admin' => 'nullable|string|in:Oral,Injection,Topical,Inhalation,Other',
            'manufacturer_id' => 'required|exists:manufacturers,id',
            'country_of_origin' => 'required|string|max:100',
            'legal_status' => 'required|string|in:Prescription only,Non-prescription,Hospital only,Country dependent,Unknown',
            'active_ingredients' => 'required|string',
            'short_description' => 'required|string',
            'full_description' => 'required|string',
            'approved_indication' => 'required|string',
            'image_alt_text' => 'required|string|max:255',
            'dosage_admin_text' => 'required_unless:legal_status,Unknown|nullable|string',
            'safety_info' => 'required|string',
            'drug_interactions' => 'required_if:has_known_interactions,1|nullable|string',
            'precautions' => 'required_if:has_precautions,1|nullable|string',
            'storage_conditions' => 'required|string',
            'availability_status' => 'required|string|in:Information available,Professional enquiry only,Country dependent,Unavailable,Discontinued,Under verification',
            'country_market' => 'required|string|max:100',
            'page_language' => 'required|string|in:English,Simplified Chinese,Traditional Chinese,French,Spanish,Arabic,Other',
            'enquiry_contact_link' => ['required', 'string', 'max:255', 'regex:/^(https?:\/\/|mailto:|tel:|\/).+$/i'],
            'url_slug' => [
                'required', 'string', 'max:255', 'regex:/^[a-z0-9-]+$/',
                Rule::unique('products', 'url_slug')->ignore($product?->id),
            ],
            'seo_title' => 'required|string|max:60',
            'meta_description' => 'required|string|max:160',
            'official_source_url' => 'required_if:content_status,Approved,Published|nullable|url|max:255',
            'last_verified_date' => 'required_with:official_source_url|nullable|date',
            'content_status' => 'required|string|in:Draft,Under Review,Approved,Published,Archived',
            'reviewer_approver' => 'required|string|max:255',
            'information_disclaimer' => 'required|string',
            'image_files' => $imageRequired . '|array',
            'image_files.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
            'remove_images' => 'nullable|array',
            'remove_images.*' => 'integer|min:0',
            'image_labels' => 'nullable|array',
            'image_labels.*' => 'nullable|string|max:100',
            'banner_image_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'banner_image_url' => 'nullable|url|max:255',
            'remove_banner_image' => 'nullable|boolean',
        ];
    }

    private function lookupLists(): array
    {
        return [
            'manufacturers' => Manufacturer::orderBy('name')->get(),
            'categories' => $this->categoryNames(),
            'subcategoryMap' => $this->subcategoryMap(),
            'dosageForms' => ['Tablet', 'Capsule', 'Injection', 'Oral liquid', 'Cream/ointment', 'Other'],
            'routes' => ['Oral', 'Injection', 'Topical', 'Inhalation', 'Other'],
            'legalStatuses' => ['Prescription only', 'Non-prescription', 'Hospital only', 'Country dependent', 'Unknown'],
            'availability' => ['Information available', 'Professional enquiry only', 'Country dependent', 'Unavailable', 'Discontinued', 'Under verification'],
            'languages' => ['English', 'Simplified Chinese', 'Traditional Chinese', 'French', 'Spanish', 'Arabic', 'Other'],
            'statuses' => ['Draft', 'Under Review', 'Approved', 'Published', 'Archived'],
        ];
    }

    private function categoryNames(): array
    {
        return Category::orderBy('sort_order')->orderBy('name')->pluck('name')->all();
    }

    private function subcategoryMap(): array
    {
        return Category::orderBy('sort_order')->orderBy('name')
            ->get(['name', 'subcategories'])
            ->mapWithKeys(fn (Category $c) => [$c->name => $c->subcategories ?? ['Other']])
            ->all();
    }
}
