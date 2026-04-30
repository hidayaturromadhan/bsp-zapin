<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\TjslProgram;
use Illuminate\Http\Request;

class TjslController extends Controller
{
    public function index(Request $request, string $locale)
    {
        $programs = TjslProgram::query()
            ->with([
                'translations' => fn ($q) => $q->whereIn('locale', [$locale, 'id', 'en']),
                'images' => fn ($q) => $q->orderBy('sort_order')->orderBy('id'),
            ])
            ->published()
            ->orderByDesc('year')
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get();

        $selectedYear = trim((string) $request->query('year'));

        if ($selectedYear !== '') {
            $activeProgram = $programs->firstWhere('year', $selectedYear);
        } else {
            $activeProgram = $programs->first();
        }

        if (! $activeProgram && $programs->count()) {
            $activeProgram = $programs->first();
        }

        $activeTranslation = $activeProgram?->getTranslation($locale);

        $metaTitle = $locale === 'id'
            ? 'TJSL - BSP Zapin'
            : 'TJSL / CSR - BSP Zapin';

        if ($activeProgram && $activeTranslation) {
            $metaTitle = ($activeTranslation->title ?: 'TJSL') . ' - BSP Zapin';
        }

        $metaDescription = $locale === 'id'
            ? 'Informasi program Tanggung Jawab Sosial dan Lingkungan PT Bumi Siak Pusako Zapin per tahun beserta galeri dokumentasinya.'
            : 'Annual Social and Environmental Responsibility programs of PT Bumi Siak Pusako Zapin along with their documentation gallery.';

        if ($activeTranslation && ! empty($activeTranslation->summary)) {
            $metaDescription = $activeTranslation->summary;
        }

        $metaImage = asset('images/logo.png');

        if ($activeProgram?->featured_image) {
            $metaImage = asset($activeProgram->featured_image);
        } elseif ($activeProgram?->images->first()?->image_path) {
            $metaImage = asset($activeProgram->images->first()->image_path);
        }

        return view('web.tjsl.index', [
            'locale'            => $locale,
            'programs'          => $programs,
            'activeProgram'     => $activeProgram,
            'activeTranslation' => $activeTranslation,
            'metaTitle'         => $metaTitle,
            'metaDescription'   => $metaDescription,
            'metaImage'         => $metaImage,
        ]);
    }
}