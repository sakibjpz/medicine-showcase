<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminCategoryController extends Controller
{
    public function index()
    {
        $counts = Product::selectRaw('therapeutic_category, COUNT(*) as count')
            ->groupBy('therapeutic_category')
            ->pluck('count', 'therapeutic_category');

        return view('admin.categories.index', [
            'title' => 'Categories',
            'categories' => Category::orderBy('sort_order')->orderBy('name')->paginate(15),
            'productCounts' => $counts,
        ]);
    }

    public function create()
    {
        return view('admin.categories.form', [
            'title' => 'Create Category',
            'category' => null,
        ]);
    }

    public function store(Request $request)
    {
        $input = $this->validateInput($request);

        Category::create($input);

        return redirect()->route('admin.categories.index')->with('success', 'Category created.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.form', [
            'title' => 'Edit Category: ' . $category->name,
            'category' => $category,
        ]);
    }

    public function update(Request $request, Category $category)
    {
        $input = $this->validateInput($request, $category);

        $category->update($input);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated.');
    }

    public function destroy(Category $category)
    {
        if (Product::where('therapeutic_category', $category->name)->exists()) {
            return back()->withErrors(['category' => 'Cannot delete: products are assigned to this category. Reassign or archive them first.']);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted.');
    }

    private function validateInput(Request $request, ?Category $category = null): array
    {
        $input = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('categories', 'name')->ignore($category?->id)],
            'slug' => ['nullable', 'string', 'max:120', 'regex:/^[a-z0-9-]+$/', Rule::unique('categories', 'slug')->ignore($category?->id)],
            'description' => 'nullable|string|max:2000',
            'subcategories' => 'nullable|string|max:2000',
            'sort_order' => 'nullable|integer|min:0|max:9999',
            'is_active' => 'nullable|boolean',
            'image_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'image_url' => 'nullable|url|max:255',
        ]);

        $input['subcategories'] = collect(explode(',', $input['subcategories'] ?? ''))
            ->map(fn ($s) => trim($s))
            ->filter()
            ->values()
            ->all();

        $input['is_active'] = $request->boolean('is_active');
        $input['sort_order'] = (int) ($input['sort_order'] ?? 0);

        if (empty($input['slug'])) {
            $input['slug'] = Category::uniqueSlug(Str::slug($input['name']), $category?->id);
        }

        if ($request->hasFile('image_file')) {
            $input['image'] = asset('storage/' . $request->file('image_file')->store('categories', 'public'));
        } elseif ($request->boolean('remove_image')) {
            $input['image'] = null;
        } elseif (! empty($input['image_url'])) {
            $input['image'] = $input['image_url'];
        } else {
            $input['image'] = null;
        }

        unset($input['image_file'], $input['image_url']);

        return $input;
    }
}
