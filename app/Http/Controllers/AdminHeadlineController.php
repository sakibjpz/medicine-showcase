<?php

namespace App\Http\Controllers;

use App\Models\Headline;
use Illuminate\Http\Request;

class AdminHeadlineController extends Controller
{
    public function index()
    {
        return view('admin.headlines.index', [
            'title' => 'Headlines',
            'headlines' => Headline::orderBy('sort_order')->orderBy('id')->paginate(15),
        ]);
    }

    public function create()
    {
        return view('admin.headlines.form', [
            'title' => 'Add Headline',
            'headline' => null,
        ]);
    }

    public function store(Request $request)
    {
        Headline::create($this->validateInput($request));

        return redirect()->route('admin.headlines.index')->with('success', 'Headline created.');
    }

    public function edit(Headline $headline)
    {
        return view('admin.headlines.form', [
            'title' => 'Edit Headline',
            'headline' => $headline,
        ]);
    }

    public function update(Request $request, Headline $headline)
    {
        $headline->update($this->validateInput($request));

        return redirect()->route('admin.headlines.index')->with('success', 'Headline updated.');
    }

    public function destroy(Headline $headline)
    {
        $headline->delete();

        return redirect()->route('admin.headlines.index')->with('success', 'Headline deleted.');
    }

    private function validateInput(Request $request): array
    {
        $input = $request->validate([
            'text' => 'required|string|max:255',
            'sort_order' => 'nullable|integer|min:0|max:9999',
            'is_active' => 'nullable|boolean',
        ]);

        $input['is_active'] = $request->boolean('is_active');
        $input['sort_order'] = (int) ($input['sort_order'] ?? 0);

        return $input;
    }
}
