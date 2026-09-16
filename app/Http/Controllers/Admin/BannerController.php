<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Throwable;

class BannerController extends Controller
{
    public function index(): View
    {
        return view('admin.banners.index', [
            'banners' => Banner::orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.banners.form', [
            'banner' => new Banner(['is_active' => true, 'sort_order' => (int) Banner::max('sort_order') + 1]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->save($request, new Banner);

        return redirect()->route('admin.banners.index')->with('status', 'Banner added successfully.');
    }

    public function edit(Banner $banner): View
    {
        return view('admin.banners.form', compact('banner'));
    }

    public function update(Request $request, Banner $banner): RedirectResponse
    {
        $this->save($request, $banner);

        return redirect()->route('admin.banners.index')->with('status', 'Banner updated successfully.');
    }

    public function destroy(Banner $banner): RedirectResponse
    {
        $path = $banner->image_path;
        $banner->delete();
        Storage::disk('banners')->delete($path);

        return redirect()->route('admin.banners.index')->with('status', 'Banner deleted successfully.');
    }

    private function save(Request $request, Banner $banner): void
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'image' => [$banner->exists ? 'nullable' : 'required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:1000000'],
            'is_active' => ['required', 'boolean'],
        ]);

        $oldPath = $banner->image_path;
        $newPath = $request->hasFile('image')
            ? $request->file('image')->store('', 'banners')
            : null;

        try {
            $banner->fill([
                'title' => $data['title'],
                'sort_order' => $data['sort_order'],
                'is_active' => $request->boolean('is_active'),
                'image_path' => $newPath ?? $oldPath,
            ])->save();
        } catch (Throwable $exception) {
            if ($newPath) {
                Storage::disk('banners')->delete($newPath);
            }
            throw $exception;
        }

        if ($newPath && $oldPath) {
            Storage::disk('banners')->delete($oldPath);
        }
    }
}
