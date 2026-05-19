<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use App\Services\NewsAutoTranslator;
use App\Services\PublicImageUploader;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(10);

        return view('admin.sliders.index', compact('sliders'));
    }

    public function create()
    {
        return view('admin.sliders.create');
    }

    public function store(
        Request $request,
        PublicImageUploader $uploader,
        NewsAutoTranslator $translator
    ) {
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:190'],
            'link_url' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable'],
            'image' => ['required', 'file', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $title = trim((string) ($data['title'] ?? ''));
        $linkUrl = trim((string) ($data['link_url'] ?? ''));

        $titleEn = $title !== ''
            ? $translator->translateText($title, 'id', 'en')
            : null;

        $imagePath = $uploader->upload(
            $request->file('image'),
            'images/sliders',
            2
        );

        Slider::create([
            'title' => $title !== '' ? $title : null,
            'title_en' => $titleEn ?: null,
            'link_url' => $linkUrl !== '' ? $linkUrl : null,
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'is_active' => $this->resolveCheckbox($request, 'is_active'),
            'image_path' => $imagePath,
        ]);

        return redirect()
            ->route('admin.sliders.index')
            ->with('success', 'Slider berhasil dibuat.');
    }

    public function edit(Slider $slider)
    {
        return view('admin.sliders.edit', compact('slider'));
    }

    public function update(
        Request $request,
        Slider $slider,
        PublicImageUploader $uploader,
        NewsAutoTranslator $translator
    ) {
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:190'],
            'link_url' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable'],
            'image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $title = trim((string) ($data['title'] ?? ''));
        $linkUrl = trim((string) ($data['link_url'] ?? ''));

        $payload = [
            'title' => $title !== '' ? $title : null,
            'title_en' => $title !== ''
                ? $translator->translateText($title, 'id', 'en')
                : null,
            'link_url' => $linkUrl !== '' ? $linkUrl : null,
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'is_active' => $this->resolveCheckbox($request, 'is_active'),
        ];

        if ($request->hasFile('image')) {
            $uploader->delete($slider->image_path);

            $payload['image_path'] = $uploader->upload(
                $request->file('image'),
                'images/sliders',
                2
            );
        }

        $slider->update($payload);

        return redirect()
            ->route('admin.sliders.index')
            ->with('success', 'Slider berhasil diupdate.');
    }

    public function destroy(Slider $slider, PublicImageUploader $uploader)
    {
        $uploader->delete($slider->image_path);

        $slider->delete();

        return redirect()
            ->route('admin.sliders.index')
            ->with('success', 'Slider berhasil dihapus.');
    }

    private function resolveCheckbox(Request $request, string $key): bool
    {
        $value = $request->input($key);

        if (is_array($value)) {
            $value = end($value);
        }

        return in_array($value, ['1', 1, true, 'true', 'on', 'yes'], true);
    }
}