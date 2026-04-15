<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TjslProgram;
use App\Models\TjslProgramImage;
use App\Models\TjslProgramTranslation;
use App\Services\NewsAutoTranslator;
use App\Services\PublicImageUploader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TjslController extends Controller
{
    public function index()
    {
        $programs = TjslProgram::with(['translations', 'images'])
            ->orderByDesc('year')
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->paginate(10);

        return view('admin.tjsl.index', compact('programs'));
    }

    public function create()
    {
        return view('admin.tjsl.create');
    }

    public function store(
        Request $request,
        NewsAutoTranslator $translator,
        PublicImageUploader $uploader
    ) {
        $data = $request->validate([
            'year'            => ['required', 'string', 'max:10'],
            'title'           => ['required', 'string', 'max:255'],
            'summary'         => ['nullable', 'string'],
            'content'         => ['nullable', 'string'],
            'sort_order'      => ['nullable', 'integer', 'min:0'],
            'is_active'       => ['nullable', 'boolean'],

            'featured_image'  => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'gallery_images'  => ['nullable', 'array'],
            'gallery_images.*'=> ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        DB::transaction(function () use ($request, $data, $translator, $uploader) {
            $payload = [
                'year'       => $data['year'],
                'is_active'  => (bool) ($data['is_active'] ?? false),
                'sort_order' => (int) ($data['sort_order'] ?? 0),
            ];

            if ($request->hasFile('featured_image')) {
                $payload['featured_image'] = $uploader->upload(
                    $request->file('featured_image'),
                    'images/tjsl',
                    2
                );
            }

            $program = TjslProgram::create($payload);

            $titleId   = trim((string) $data['title']);
            $summaryId = trim((string) ($data['summary'] ?? ''));
            $contentId = trim((string) ($data['content'] ?? ''));

            $titleEn   = $translator->translateText($titleId, 'id', 'en');
            $summaryEn = $translator->translateText($summaryId, 'id', 'en');
            $contentEn = $translator->translateHtml($contentId, 'id', 'en');

            TjslProgramTranslation::create([
                'tjsl_program_id' => $program->id,
                'locale'          => 'id',
                'title'           => $titleId,
                'summary'         => $summaryId !== '' ? $summaryId : null,
                'content'         => $contentId !== '' ? $contentId : null,
            ]);

            TjslProgramTranslation::create([
                'tjsl_program_id' => $program->id,
                'locale'          => 'en',
                'title'           => trim($titleEn) !== '' ? trim($titleEn) : $titleId,
                'summary'         => trim($summaryEn) !== '' ? trim($summaryEn) : ($summaryId !== '' ? $summaryId : null),
                'content'         => trim($contentEn) !== '' ? trim($contentEn) : ($contentId !== '' ? $contentId : null),
            ]);

            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $index => $image) {
                    if (! $image) {
                        continue;
                    }

                    TjslProgramImage::create([
                        'tjsl_program_id' => $program->id,
                        'image_path'      => $uploader->upload(
                            $image,
                            'images/tjsl/gallery',
                            2
                        ),
                        'caption'         => null,
                        'sort_order'      => $index,
                    ]);
                }
            }
        });

        return redirect()
            ->route('admin.tjsl.index')
            ->with('success', 'Program TJSL berhasil ditambahkan.');
    }

    public function edit(TjslProgram $program)
    {
        $program->load([
            'translations',
            'images' => fn ($q) => $q->orderBy('sort_order')->orderBy('id'),
        ]);

        return view('admin.tjsl.edit', compact('program'));
    }

    public function update(
        Request $request,
        TjslProgram $program,
        NewsAutoTranslator $translator,
        PublicImageUploader $uploader
    ) {
        $data = $request->validate([
            'year'            => ['required', 'string', 'max:10'],
            'title'           => ['required', 'string', 'max:255'],
            'summary'         => ['nullable', 'string'],
            'content'         => ['nullable', 'string'],
            'sort_order'      => ['nullable', 'integer', 'min:0'],
            'is_active'       => ['nullable', 'boolean'],

            'featured_image'  => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'gallery_images'  => ['nullable', 'array'],
            'gallery_images.*'=> ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        DB::transaction(function () use ($request, $data, $program, $translator, $uploader) {
            $payload = [
                'year'       => $data['year'],
                'is_active'  => (bool) ($data['is_active'] ?? false),
                'sort_order' => (int) ($data['sort_order'] ?? 0),
            ];

            if ($request->hasFile('featured_image')) {
                if ($program->featured_image) {
                    $uploader->delete($program->featured_image);
                }

                $payload['featured_image'] = $uploader->upload(
                    $request->file('featured_image'),
                    'images/tjsl',
                    2
                );
            }

            $program->update($payload);

            $titleId   = trim((string) $data['title']);
            $summaryId = trim((string) ($data['summary'] ?? ''));
            $contentId = trim((string) ($data['content'] ?? ''));

            $titleEn   = $translator->translateText($titleId, 'id', 'en');
            $summaryEn = $translator->translateText($summaryId, 'id', 'en');
            $contentEn = $translator->translateHtml($contentId, 'id', 'en');

            $program->translations()->updateOrCreate(
                ['locale' => 'id'],
                [
                    'title'   => $titleId,
                    'summary' => $summaryId !== '' ? $summaryId : null,
                    'content' => $contentId !== '' ? $contentId : null,
                ]
            );

            $program->translations()->updateOrCreate(
                ['locale' => 'en'],
                [
                    'title'   => trim($titleEn) !== '' ? trim($titleEn) : $titleId,
                    'summary' => trim($summaryEn) !== '' ? trim($summaryEn) : ($summaryId !== '' ? $summaryId : null),
                    'content' => trim($contentEn) !== '' ? trim($contentEn) : ($contentId !== '' ? $contentId : null),
                ]
            );

            if ($request->hasFile('gallery_images')) {
                $currentMax = (int) $program->images()->max('sort_order');

                foreach ($request->file('gallery_images') as $index => $image) {
                    if (! $image) {
                        continue;
                    }

                    TjslProgramImage::create([
                        'tjsl_program_id' => $program->id,
                        'image_path'      => $uploader->upload(
                            $image,
                            'images/tjsl/gallery',
                            2
                        ),
                        'caption'         => null,
                        'sort_order'      => $currentMax + $index + 1,
                    ]);
                }
            }
        });

        return redirect()
            ->route('admin.tjsl.edit', $program)
            ->with('success', 'Program TJSL berhasil diperbarui.');
    }

    public function destroy(TjslProgram $program, PublicImageUploader $uploader)
    {
        if ($program->featured_image) {
            $uploader->delete($program->featured_image);
        }

        $program->load('images');

        foreach ($program->images as $image) {
            $uploader->delete($image->image_path);
        }

        $program->delete();

        return redirect()
            ->route('admin.tjsl.index')
            ->with('success', 'Program TJSL berhasil dihapus.');
    }

    public function destroyImage(
        TjslProgram $program,
        TjslProgramImage $image,
        PublicImageUploader $uploader
    ) {
        abort_if((int) $image->tjsl_program_id !== (int) $program->id, 404);

        $uploader->delete($image->image_path);
        $image->delete();

        return redirect()
            ->route('admin.tjsl.edit', $program)
            ->with('success', 'Foto galeri berhasil dihapus.');
    }
}