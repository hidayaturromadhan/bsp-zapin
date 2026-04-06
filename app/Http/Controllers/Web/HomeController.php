<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Partner;
use App\Models\Slider;

class HomeController extends Controller
{
    public function index(string $locale)
    {
        $sliders = Slider::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $latestNews = News::query()
            ->with(['translations', 'category'])
            ->where('is_visible', true)
            ->where('status', 'published')
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->limit(3)
            ->get();

        $featuredNews = News::query()
            ->with(['translations', 'category'])
            ->where('is_visible', true)
            ->where('status', 'published')
            ->where('is_featured', true)
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->limit(3)
            ->get();

        $customerPartners = Partner::query()
            ->where('is_active', true)
            ->where('category', Partner::CATEGORY_CUSTOMER)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $businessPartners = Partner::query()
            ->where('is_active', true)
            ->where('category', Partner::CATEGORY_BUSINESS_PARTNER)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('web.home', [
            'sliders' => $sliders,
            'latestNews' => $latestNews,
            'featuredNews' => $featuredNews,
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