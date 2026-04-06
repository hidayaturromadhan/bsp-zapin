<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use App\Services\PublicImageUploader;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(20);

        return view('admin.sliders.index', compact('sliders'));
    }

    public function create()
    {
        return view('admin.sliders.create');
    }

    public function store(Request $request, PublicImageUploader $uploader)
    {
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:190'],
            'link_url' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'image' => ['required', 'file', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $imagePath = $uploader->upload(
            $request->file('image'),
            'images/sliders',
            2
        );

        Slider::create([
            'title' => $data['title'] ?? null,
            'link_url' => $data['link_url'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active' => (bool) ($data['is_active'] ?? false),
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

    public function update(Request $request, Slider $slider, PublicImageUploader $uploader)
    {
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:190'],
            'link_url' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $payload = [
            'title' => $data['title'] ?? null,
            'link_url' => $data['link_url'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active' => (bool) ($data['is_active'] ?? false),
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
}