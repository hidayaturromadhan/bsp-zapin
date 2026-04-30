<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class WbsController extends Controller
{
    public function index(string $locale)
    {
        if (! in_array($locale, ['id', 'en'], true)) {
            abort(404);
        }

        $metaTitle = $locale === 'en'
            ? 'Whistleblowing System - PT Bumi Siak Pusako Zapin'
            : 'Whistleblowing System - PT Bumi Siak Pusako Zapin';

        $metaDescription = $locale === 'en'
            ? 'Whistleblowing System reporting channel of PT Bumi Siak Pusako Zapin.'
            : 'Saluran pelaporan Whistleblowing System PT Bumi Siak Pusako Zapin.';

        $metaImage = asset('images/logo.png');

        return view('web.wbs.index', compact(
            'locale',
            'metaTitle',
            'metaDescription',
            'metaImage'
        ));
    }
}