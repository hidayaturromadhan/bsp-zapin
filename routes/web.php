<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\PartnerController as AdminPartnerController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\GcgCategoryController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\GcgController;
use App\Http\Controllers\Web\NewsController;
use App\Http\Controllers\Web\PageController;
use App\Http\Controllers\Web\ProfileController;
use App\Http\Controllers\Admin\GcgHighlightItemController;
use App\Models\News;
use App\Models\Page;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| AUTH (tanpa locale)
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| ADMIN PANEL (tanpa locale)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth.session', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Partners
        Route::get('/partners', [AdminPartnerController::class, 'index'])->name('partners.index');
        Route::get('/partners/create', [AdminPartnerController::class, 'create'])->name('partners.create');
        Route::post('/partners', [AdminPartnerController::class, 'store'])->name('partners.store');
        Route::get('/partners/{partner}/edit', [AdminPartnerController::class, 'edit'])->name('partners.edit');
        Route::put('/partners/{partner}', [AdminPartnerController::class, 'update'])->name('partners.update');
        Route::delete('/partners/{partner}', [AdminPartnerController::class, 'destroy'])->name('partners.destroy');

        // Sliders
        Route::get('/sliders', [SliderController::class, 'index'])->name('sliders.index');
        Route::get('/sliders/create', [SliderController::class, 'create'])->name('sliders.create');
        Route::post('/sliders', [SliderController::class, 'store'])->name('sliders.store');
        Route::get('/sliders/{slider}/edit', [SliderController::class, 'edit'])->name('sliders.edit');
        Route::put('/sliders/{slider}', [SliderController::class, 'update'])->name('sliders.update');
        Route::delete('/sliders/{slider}', [SliderController::class, 'destroy'])->name('sliders.destroy');

        // Pages
        Route::get('/pages', [AdminPageController::class, 'index'])->name('pages.index');
        Route::get('/pages/{page}/edit', [AdminPageController::class, 'edit'])->name('pages.edit');
        Route::put('/pages/{page}', [AdminPageController::class, 'update'])->name('pages.update');
        Route::get('/pages/{page}/versions', [AdminPageController::class, 'versions'])->name('pages.versions');
        Route::post('/pages/{page}/versions/{version}/restore', [AdminPageController::class, 'restoreVersion'])->name('pages.versions.restore');
        Route::post('/pages/{page}/bundles/{bundle}/restore', [AdminPageController::class, 'restoreBundle'])->name('pages.bundles.restore');

        // Menus
        Route::get('/menus', [MenuController::class, 'index'])->name('menus.index');
        Route::get('/menus/create', [MenuController::class, 'create'])->name('menus.create');
        Route::post('/menus/store', [MenuController::class, 'store'])->name('menus.store');
        Route::get('/menus/{menu}/edit', [MenuController::class, 'edit'])->name('menus.edit');
        Route::put('/menus/{menu}/update', [MenuController::class, 'update'])->name('menus.update');
        Route::delete('/menus/{menu}/delete', [MenuController::class, 'destroy'])->name('menus.delete');

        // News
        Route::get('/news', [AdminNewsController::class, 'index'])->name('news.index');
        Route::get('/news/create', [AdminNewsController::class, 'create'])->name('news.create');
        Route::post('/news', [AdminNewsController::class, 'store'])->name('news.store');
        Route::get('/news/{news}/edit', [AdminNewsController::class, 'edit'])->name('news.edit');
        Route::put('/news/{news}', [AdminNewsController::class, 'update'])->name('news.update');
        Route::delete('/news/{news}', [AdminNewsController::class, 'destroy'])->name('news.destroy');
        Route::get('/news/{news}/versions', [AdminNewsController::class, 'versions'])->name('news.versions');
        Route::post('/news/{news}/versions/{version}/restore', [AdminNewsController::class, 'restoreVersion'])->name('news.versions.restore');
        Route::post('/news/{news}/bundles/{bundle}/restore', [AdminNewsController::class, 'restoreBundle'])->name('news.bundles.restore');

        // ── GCG ──────────────────────────────────────────────────────────
        // PERBAIKAN: hapus name('admin.gcg.') karena group parent
        // sudah name('admin.') → cukup name('gcg.')
        Route::prefix('gcg')->name('gcg.')->group(function () {
            Route::get('/', [GcgCategoryController::class, 'index'])->name('index');
            Route::get('/create', [GcgCategoryController::class, 'create'])->name('create');
            Route::post('/', [GcgCategoryController::class, 'store'])->name('store');
            Route::get('/{gcg}/edit', [GcgCategoryController::class, 'edit'])->name('edit');
            Route::put('/{gcg}', [GcgCategoryController::class, 'update'])->name('update');
            Route::delete('/{gcg}', [GcgCategoryController::class, 'destroy'])->name('destroy');

            // Documents
            Route::post('/{gcg}/documents', [GcgCategoryController::class, 'storeDocument'])->name('documents.store');
            Route::put('/{gcg}/documents/{document}', [GcgCategoryController::class, 'updateDocument'])->name('documents.update');
            Route::delete('/{gcg}/documents/{document}', [GcgCategoryController::class, 'destroyDocument'])->name('documents.destroy');
        });

        // ── GCG HIGHLIGHT ITEMS ─────────────────────────────────────────
        Route::get('/gcg-highlight-items', [GcgHighlightItemController::class, 'index'])
            ->name('gcg-highlight-items.index');

        Route::get('/gcg-highlight-items/create', [GcgHighlightItemController::class, 'create'])
            ->name('gcg-highlight-items.create');

        Route::post('/gcg-highlight-items', [GcgHighlightItemController::class, 'store'])
            ->name('gcg-highlight-items.store');

        Route::get('/gcg-highlight-items/{gcgHighlightItem}/edit', [GcgHighlightItemController::class, 'edit'])
            ->name('gcg-highlight-items.edit');

        Route::put('/gcg-highlight-items/{gcgHighlightItem}', [GcgHighlightItemController::class, 'update'])
            ->name('gcg-highlight-items.update');

        Route::delete('/gcg-highlight-items/{gcgHighlightItem}', [GcgHighlightItemController::class, 'destroy'])
            ->name('gcg-highlight-items.destroy');
    });

/*
|--------------------------------------------------------------------------
| ROOT REDIRECT
|--------------------------------------------------------------------------
*/
Route::redirect('/', '/id')->name('root.redirect');

/*
|--------------------------------------------------------------------------
| SITEMAP
|--------------------------------------------------------------------------
*/
Route::get('/sitemap.xml', function () {
    $pages = Page::with('translations')->get();
    $news  = News::with('translations')->get();
    $xml   = view('sitemap', compact('pages', 'news'));
    return response($xml, 200)->header('Content-Type', 'application/xml');
})->name('sitemap');

/*
|--------------------------------------------------------------------------
| DOWNLOAD DOKUMEN GCG (tanpa locale, akses langsung)
|--------------------------------------------------------------------------
*/
Route::get('/gcg/download/{document}', [GcgController::class, 'download'])
    ->name('gcg.download');

/*
|--------------------------------------------------------------------------
| PUBLIC SITE (Locale Prefix)
|--------------------------------------------------------------------------
*/
Route::prefix('{locale}')
    ->where(['locale' => 'id|en'])
    ->group(function () {

        // Home
        Route::get('/', [HomeController::class, 'index'])->name('web.home');
        Route::get('/home', [HomeController::class, 'index'])->name('home');

        // Profil
        Route::prefix('profil')->group(function () {
            Route::get('/', [ProfileController::class, 'index'])->name('profil.index');
            Route::get('/{slug}', [ProfileController::class, 'show'])->name('profil.show');
        });

        // Legal pages
        Route::get('/privacy-policy', function (string $locale) {
            return view('web.legal.privacy', compact('locale'));
        })->name('legal.privacy');

        Route::get('/terms', function (string $locale) {
            return view('web.legal.terms', compact('locale'));
        })->name('legal.terms');

        // WBS placeholder
        Route::get('/wbs', function (string $locale) {
            return view('wbs.index', compact('locale'));
        })->name('wbs.index');

        // News
        Route::get('/media-publikasi', [NewsController::class, 'mediaPublikasi'])->name('media_publikasi.index');
        Route::get('/tjsl', [NewsController::class, 'tjsl'])->name('tjsl.index');
        Route::get('/berita/{slug}', [NewsController::class, 'show'])->name('news.show');

        // ── GCG PUBLIC ────────────────────────────────────────────────────
        // PERBAIKAN: letakkan SEBELUM /{slug} agar tidak tercover
        Route::prefix('gcg')->name('gcg.')->group(function () {
            Route::get('/', [GcgController::class, 'index'])->name('index');
            Route::get('/{slug}', [GcgController::class, 'show'])->name('show');
        });

        // Static pages utama
        // PERBAIKAN: hapus 'gcg' dari sini karena sudah ditangani prefix gcg di atas
        Route::get('/{slug}', [PageController::class, 'show'])
            ->where('slug', 'layanan|operasional|publikasi|kontak|hubungan-investor')
            ->name('page.show');
    });