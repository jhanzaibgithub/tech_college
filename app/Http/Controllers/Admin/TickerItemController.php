<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TickerItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TickerItemController extends Controller
{
    public function index(): View
    {
        return view('admin.ticker.index', [
            'items' => TickerItem::orderByDesc('is_active')->orderByDesc('item_date')->orderByDesc('id')->paginate(15),
            'shownCount' => TickerItem::shown()->count(),
            'hiddenCount' => TickerItem::where('is_active', false)->count(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        TickerItem::create($this->validated($request));

        return redirect()->route('admin.ticker.index')->with('status', 'News bar item added.');
    }

    public function edit(TickerItem $ticker): View
    {
        return view('admin.ticker.edit', ['item' => $ticker]);
    }

    public function update(Request $request, TickerItem $ticker): RedirectResponse
    {
        $ticker->update($this->validated($request));

        return redirect()->route('admin.ticker.index')->with('status', 'News bar item updated.');
    }

    /** Quick switch from the list: show or hide one entry in the bar. */
    public function toggle(TickerItem $ticker): RedirectResponse
    {
        $ticker->update(['is_active' => ! $ticker->is_active]);

        return back()->with('status', $ticker->is_active ? 'Item is now showing in the news bar.' : 'Item hidden from the news bar.');
    }

    public function destroy(TickerItem $ticker): RedirectResponse
    {
        $ticker->delete();

        return redirect()->route('admin.ticker.index')->with('status', 'News bar item deleted.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'item_date' => ['required', 'date'],
        ]);

        // An unticked box is simply absent from the request.
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
