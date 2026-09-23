<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;

class AdminBannerController extends Controller
{
    public function index()
    {
        return view('admin.banners.index', [
            'title' => 'Homepage Banners',
            'banners' => Banner::orderBy('sort_order')->orderBy('id')->paginate(15),
        ]);
    }

    public function create()
    {
        return view('admin.banners.form', [
            'title' => 'Add Banner',
            'banner' => null,
        ]);
    }

    public function store(Request $request)
    {
        $input = $this->validateInput($request, true);
        $input['image'] = $this->resolveImage($request);

        Banner::create($input);

        return redirect()->route('admin.banners.index')->with('success', 'Banner added.');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.form', [
            'title' => 'Edit Banner',
            'banner' => $banner,
        ]);
    }

    public function update(Request $request, Banner $banner)
    {
        $input = $this->validateInput($request, false);
        $input['image'] = $this->resolveImage($request) ?? $banner->image;

        $banner->update($input);

        return redirect()->route('admin.banners.index')->with('success', 'Banner updated.');
    }

    public function destroy(Banner $banner)
    {
        $banner->delete();

        return redirect()->route('admin.banners.index')->with('success', 'Banner deleted.');
    }

    private function validateInput(Request $request, bool $requireImage): array
    {
        $input = $request->validate([
            'image_file' => [$requireImage ? 'required_without:image_url' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'image_url' => [$requireImage ? 'required_without:image_file' : 'nullable', 'url', 'max:255'],
            'sort_order' => 'nullable|integer|min:0|max:9999',
            'is_active' => 'nullable|boolean',
        ]);

        $input['is_active'] = $request->boolean('is_active');
        $input['sort_order'] = (int) ($input['sort_order'] ?? 0);

        unset($input['image_file'], $input['image_url']);

        return $input;
    }

    private function resolveImage(Request $request): ?string
    {
        if ($request->hasFile('image_file')) {
            return asset('storage/' . $request->file('image_file')->store('banners', 'public'));
        }

        $url = $request->input('image_url');

        return ! empty($url) ? $url : null;
    }
}
