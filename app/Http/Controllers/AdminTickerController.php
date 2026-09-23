<?php

namespace App\Http\Controllers;

use App\Models\TickerItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminTickerController extends Controller
{
    public function index()
    {
        return view('admin.ticker.index', [
            'title' => 'Updates Ticker',
            'items' => TickerItem::orderBy('sort_order')->orderBy('id')->paginate(15),
        ]);
    }

    public function create()
    {
        return view('admin.ticker.form', [
            'title' => 'Add Ticker Item',
            'item' => null,
            'colors' => TickerItem::colorOptions(),
        ]);
    }

    public function store(Request $request)
    {
        TickerItem::create($this->validateInput($request));

        return redirect()->route('admin.ticker.index')->with('success', 'Ticker item created.');
    }

    public function edit(TickerItem $ticker)
    {
        return view('admin.ticker.form', [
            'title' => 'Edit Ticker Item',
            'item' => $ticker,
            'colors' => TickerItem::colorOptions(),
        ]);
    }

    public function update(Request $request, TickerItem $ticker)
    {
        $ticker->update($this->validateInput($request));

        return redirect()->route('admin.ticker.index')->with('success', 'Ticker item updated.');
    }

    public function destroy(TickerItem $ticker)
    {
        $ticker->delete();

        return redirect()->route('admin.ticker.index')->with('success', 'Ticker item deleted.');
    }

    private function validateInput(Request $request): array
    {
        $input = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'color' => ['required', 'string', Rule::in(array_keys(TickerItem::colorOptions()))],
            'sort_order' => 'nullable|integer|min:0|max:9999',
            'is_active' => 'nullable|boolean',
        ]);

        $input['is_active'] = $request->boolean('is_active');
        $input['sort_order'] = (int) ($input['sort_order'] ?? 0);

        return $input;
    }
}
