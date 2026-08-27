<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentTestimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class StudentTestimonialController extends Controller
{
    public function index(): View
    {
        return view('admin.testimonials.index', [
            'testimonials' => StudentTestimonial::query()->orderBy('sort_order')->orderByDesc('id')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.testimonials.form', [
            'testimonial' => new StudentTestimonial(['is_active' => true]),
            'action' => route('admin.testimonials.store'),
            'method' => 'POST',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        StudentTestimonial::create($this->payload($request));

        return redirect()->route('admin.testimonials.index')->with('status', 'Testimonial created successfully.');
    }

    public function edit(StudentTestimonial $testimonial): View
    {
        return view('admin.testimonials.form', [
            'testimonial' => $testimonial,
            'action' => route('admin.testimonials.update', $testimonial),
            'method' => 'PUT',
        ]);
    }

    public function update(Request $request, StudentTestimonial $testimonial): RedirectResponse
    {
        $data = $this->payload($request, $testimonial);

        if (($data['image_path'] ?? null) && $testimonial->image_path) {
            $this->deletePublicFile($testimonial->image_path);
        }

        $testimonial->update($data);

        return redirect()->route('admin.testimonials.index')->with('status', 'Testimonial updated successfully.');
    }

    public function destroy(StudentTestimonial $testimonial): RedirectResponse
    {
        if ($testimonial->image_path) {
            $this->deletePublicFile($testimonial->image_path);
        }

        $testimonial->delete();

        return redirect()->route('admin.testimonials.index')->with('status', 'Testimonial deleted successfully.');
    }

    public function reorder(Request $request): Response
    {
        $data = $request->validate([
            'testimonials' => ['required', 'array'],
            'testimonials.*' => ['integer', 'exists:student_testimonials,id'],
        ]);

        foreach ($data['testimonials'] as $index => $testimonialId) {
            StudentTestimonial::whereKey($testimonialId)->update(['sort_order' => $index + 1]);
        }

        return response()->noContent();
    }

    private function payload(Request $request, ?StudentTestimonial $testimonial = null): array
    {
        $data = $request->validate([
            'student_name' => ['required', 'string', 'max:255'],
            'designation' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:1000'],
            'image' => ['nullable', 'image', 'max:4096'],
            'is_active' => ['nullable'],
        ]);

        $payload = [
            'student_name' => $data['student_name'],
            'designation' => $data['designation'] ?? null,
            'message' => $data['message'],
            'is_active' => isset($data['is_active']),
            'sort_order' => $testimonial?->sort_order ?? ((int) StudentTestimonial::max('sort_order') + 1),
        ];

        if ($request->hasFile('image')) {
            $payload['image_path'] = $this->storeImage($request, 'testimonials', $data['student_name']);
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
