<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Partner;
use App\Models\Slider;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    private int $cacheMinutes = 20;

    public function index(string $locale)
    {
        $locale = in_array($locale, ['id', 'en'], true) ? $locale : 'id';

        /*
        |--------------------------------------------------------------------------
        | Slider
        |--------------------------------------------------------------------------
        | Slider tidak dicache agar update dari admin langsung tampil di home.
        */
        $sliders = Slider::query()
            ->select([
                'id',
                'title',
                'title_en',
                'image_path',
                'link_url',
                'sort_order',
                'is_active',
            ])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Partners
        |--------------------------------------------------------------------------
        | Partner juga tidak dicache agar perubahan aktif/nonaktif, logo, nama,
        | dan kategori langsung tampil di halaman home.
        */
        $customerPartners = Partner::query()
            ->select([
                'id',
                'name',
                'logo_path',
                'website_url',
                'category',
                'sort_order',
                'is_active',
            ])
            ->where('is_active', true)
            ->where('category', Partner::CATEGORY_CUSTOMER)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $businessPartners = Partner::query()
            ->select([
                'id',
                'name',
                'logo_path',
                'website_url',
                'category',
                'sort_order',
                'is_active',
            ])
            ->where('is_active', true)
            ->where('category', Partner::CATEGORY_BUSINESS_PARTNER)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | News Cache
        |--------------------------------------------------------------------------
        | Berita tetap dicache karena tidak perlu berubah secepat slider/partner.
        */
        $cacheKey = 'web_home_news_data_' . $locale;

        $data = Cache::remember($cacheKey, now()->addMinutes($this->cacheMinutes), function () use ($locale) {
            $locales = array_values(array_unique([$locale, 'id', 'en']));

            $latestNews = News::query()
                ->select([
                    'id',
                    'news_category_id',
                    'status',
                    'published_at',
                    'is_featured',
                    'is_visible',
                    'featured_image',
                    'created_by',
                ])
                ->with([
                    'translations' => function ($query) use ($locales) {
                        $query->select([
                            'id',
                            'news_id',
                            'locale',
                            'title',
                            'slug',
                            'excerpt',
                        ])->whereIn('locale', $locales);
                    },
                    'category' => function ($query) {
                        $query->select([
                            'id',
                            'name',
                            'slug',
                        ]);
                    },
                ])
                ->where('is_visible', true)
                ->where('status', 'published')
                ->whereNotNull('published_at')
                ->where('published_at', '<=', now())
                ->whereHas('category', function ($query) {
                    $query->where('slug', '!=', 'tjsl');
                })
                ->orderByDesc('published_at')
                ->orderByDesc('id')
                ->limit(3)
                ->get();

            $featuredNews = News::query()
                ->select([
                    'id',
                    'news_category_id',
                    'status',
                    'published_at',
                    'is_featured',
                    'is_visible',
                    'featured_image',
                    'created_by',
                ])
                ->with([
                    'translations' => function ($query) use ($locales) {
                        $query->select([
                            'id',
                            'news_id',
                            'locale',
                            'title',
                            'slug',
                            'excerpt',
                        ])->whereIn('locale', $locales);
                    },
                    'category' => function ($query) {
                        $query->select([
                            'id',
                            'name',
                            'slug',
                        ]);
                    },
                ])
                ->where('is_visible', true)
                ->where('status', 'published')
                ->where('is_featured', true)
                ->whereNotNull('published_at')
                ->where('published_at', '<=', now())
                ->whereHas('category', function ($query) {
                    $query->where('slug', '!=', 'tjsl');
                })
                ->orderByDesc('published_at')
                ->orderByDesc('id')
                ->limit(3)
                ->get();

            return [
                'latestNews' => $latestNews,
                'featuredNews' => $featuredNews,
            ];
        });

        return view('web.home', [
            'sliders' => $sliders,
            'latestNews' => $data['latestNews'],
            'featuredNews' => $data['featuredNews'],
            'customerPartners' => $customerPartners,
            'businessPartners' => $businessPartners,
            'locale' => $locale,
            'metaTitle' => $locale === 'id'
                ? 'BSP Zapin - Beranda'
                : 'BSP Zapin - Home',
            'metaDescription' => $locale === 'id'
                ? 'Website resmi PT Bumi Siak Pusako Zapin. Menyediakan informasi perusahaan, layanan, publikasi, dan berita terbaru.'
                : 'Official website of PT Bumi Siak Pusako Zapin. Providing company information, services, publications, and latest news.',
            'metaImage' => asset('images/logo.png'),
        ]);
    }
}