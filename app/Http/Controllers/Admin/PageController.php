<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContentVersion;
use App\Models\Page;
use App\Models\PageTranslation;
use App\Services\ContentVersionService;
use App\Services\NewsAutoTranslator;
use App\Services\PublicImageUploader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::query()
            ->with(['translations' => fn ($q) => $q->whereIn('locale', ['id', 'en'])])
            ->orderByDesc('id')
            ->paginate(20);

        return view('admin.pages.index', compact('pages'));
    }

    public function edit(Page $page)
    {
        $translations = $page->translations()
            ->whereIn('locale', ['id', 'en'])
            ->get()
            ->keyBy('locale');

        $tId = $translations->get('id') ?? new PageTranslation(['locale' => 'id']);
        $tEn = $translations->get('en') ?? new PageTranslation(['locale' => 'en']);

        return view('admin.pages.edit', compact('page', 'tId', 'tEn'));
    }

    public function update(
        Request $request,
        Page $page,
        PublicImageUploader $uploader,
        ContentVersionService $versioner,
        NewsAutoTranslator $translator
    ) {
        $data = $request->validate([
            'is_active' => ['nullable', 'boolean'],
            'cover_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:4096'],

            'id_title' => ['required', 'string', 'max:190'],
            'id_slug' => ['required', 'string', 'max:190'],
            'id_content' => ['nullable', 'string'],
        ]);

        $translatedTitle = $translator->translateText($data['id_title'], 'id', 'en');
        $translatedContent = $translator->translateHtml($data['id_content'] ?? '', 'id', 'en');

        $enTitle = trim($translatedTitle) !== '' ? trim($translatedTitle) : $data['id_title'];
        $enContent = trim($translatedContent) !== '' ? $translatedContent : ($data['id_content'] ?? '');

        $enSlug = Str::slug($enTitle);
        if ($enSlug === '') {
            $enSlug = Str::slug($data['id_title'] . '-en');
        }

        $this->validateUniqueSlug('id', $data['id_slug'], $page->id);
        $this->validateUniqueSlug('en', $enSlug, $page->id);

        $versioner->snapshotPage($page);

        DB::transaction(function () use ($request, $page, $data, $uploader, $enTitle, $enSlug, $enContent) {
            $payload = [
                'is_active' => (bool) ($data['is_active'] ?? false),
            ];

            if ($request->hasFile('cover_image')) {
                $uploader->delete($page->cover_image);

                $payload['cover_image'] = $uploader->upload(
                    $request->file('cover_image'),
                    'images/pages',
                    2
                );
            }

            $page->update($payload);

            PageTranslation::updateOrCreate(
                ['page_id' => $page->id, 'locale' => 'id'],
                [
                    'title' => $data['id_title'],
                    'slug' => $data['id_slug'],
                    'content' => $data['id_content'] ?? null,
                ]
            );

            PageTranslation::updateOrCreate(
                ['page_id' => $page->id, 'locale' => 'en'],
                [
                    'title' => $enTitle,
                    'slug' => $enSlug,
                    'content' => $enContent ?: null,
                ]
            );
        });

        return redirect()
            ->route('admin.pages.index')
            ->with('success', 'Halaman berhasil diupdate. Versi English diperbarui otomatis.');
    }

    public function versions(Page $page)
    {
        $perPage = 10;

        $bundles = ContentVersion::query()
            ->where('entity_type', 'page')
            ->where('entity_id', $page->id)
            ->select('bundle_id')
            ->groupBy('bundle_id')
            ->orderByDesc(DB::raw('MAX(id)'))
            ->paginate($perPage);

        $bundleIds = $bundles->pluck('bundle_id');

        $versions = ContentVersion::query()
            ->whereIn('bundle_id', $bundleIds)
            ->orderByDesc('id')
            ->get()
            ->groupBy('bundle_id');

        return view('admin.pages.versions', [
            'page' => $page,
            'bundles' => $bundles,
            'versions' => $versions,
        ]);
    }

    public function restoreVersion(Page $page, int $version, ContentVersionService $svc)
    {
        $svc->snapshotPage($page);
        $svc->restorePage($page, $version);

        return redirect()
            ->route('admin.pages.edit', $page)
            ->with('success', 'Versi berhasil direstore.');
    }

    public function restoreBundle(Page $page, string $bundle, ContentVersionService $svc)
    {
        $svc->snapshotPage($page);
        $svc->restorePageBundle($page, $bundle);

        return redirect()
            ->route('admin.pages.edit', $page)
            ->with('success', 'Bundle berhasil direstore (GLOBAL+ID+EN).');
    }

    private function validateUniqueSlug(string $locale, string $slug, int $pageId): void
    {
        $exists = PageTranslation::query()
            ->where('locale', $locale)
            ->where('slug', $slug)
            ->where('page_id', '!=', $pageId)
            ->exists();

        if ($exists) {
            abort(422, "Slug ({$slug}) sudah dipakai untuk locale {$locale}.");
        }
    }
}