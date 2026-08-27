<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsEvent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class NewsEventController extends Controller
{
    public function index(): View
    {
        return view('admin.news-events.index', [
            'newsEvents' => NewsEvent::query()->orderBy('sort_order')->orderByDesc('id')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.news-events.form', [
            'newsEvent' => new NewsEvent(['is_active' => true]),
            'action' => route('admin.news-events.store'),
            'method' => 'POST',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        NewsEvent::create($this->payload($request));

        return redirect()->route('admin.news-events.index')->with('status', 'News or event created successfully.');
    }

    public function edit(NewsEvent $newsEvent): View
    {
        return view('admin.news-events.form', [
            'newsEvent' => $newsEvent,
            'action' => route('admin.news-events.update', $newsEvent),
            'method' => 'PUT',
        ]);
    }

    public function update(Request $request, NewsEvent $newsEvent): RedirectResponse
    {
        $data = $this->payload($request, $newsEvent);

        if (($data['image_path'] ?? null) && $newsEvent->image_path) {
            $this->deletePublicFile($newsEvent->image_path);
        }

        $newsEvent->update($data);

        return redirect()->route('admin.news-events.index')->with('status', 'News or event updated successfully.');
    }

    public function destroy(NewsEvent $newsEvent): RedirectResponse
    {
        if ($newsEvent->image_path) {
            $this->deletePublicFile($newsEvent->image_path);
        }

        $newsEvent->delete();

        return redirect()->route('admin.news-events.index')->with('status', 'News or event deleted successfully.');
    }

    public function reorder(Request $request): Response
    {
        $data = $request->validate([
            'news_events' => ['required', 'array'],
            'news_events.*' => ['integer', 'exists:news_events,id'],
        ]);

        foreach ($data['news_events'] as $index => $newsEventId) {
            NewsEvent::whereKey($newsEventId)->update(['sort_order' => $index + 1]);
        }

        return response()->noContent();
    }

    private function payload(Request $request, ?NewsEvent $newsEvent = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'summary' => ['required', 'string', 'max:1000'],
            'event_date' => ['nullable', 'date'],
            'image' => ['nullable', 'image', 'max:4096'],
            'is_active' => ['nullable'],
        ]);

        $payload = [
            'title' => $data['title'],
            'summary' => $data['summary'],
            'event_date' => $data['event_date'] ?? null,
            'is_active' => isset($data['is_active']),
            'sort_order' => $newsEvent?->sort_order ?? ((int) NewsEvent::max('sort_order') + 1),
        ];

        if ($request->hasFile('image')) {
            $payload['image_path'] = $this->storeImage($request, 'news-events', $data['title']);
        }

        return $payload;
    }

    private function storeImage(Request $request, string $folder, string $name): string
    {
        $image = $request->file('image');
        $filename = Str::slug($name) . '-' . Str::random(8) . '.' . $image->getClientOriginalExtension();
        $directory = public_path('data/' . $folder);

        File::ensureDirectoryExists($directory);
        $image->move($directory, $filename);

        return 'data/' . $folder . '/' . $filename;
    }

    private function deletePublicFile(string $path): void
    {
        $fullPath = public_path($path);

        if (File::exists($fullPath)) {
            File::delete($fullPath);
        }
    }
}
