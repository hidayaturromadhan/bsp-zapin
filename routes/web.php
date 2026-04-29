<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthController;

// Admin
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\PartnerController as AdminPartnerController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\TjslController as AdminTjslController;
use App\Http\Controllers\Admin\GcgCategoryController;
use App\Http\Controllers\Admin\GcgHighlightItemController;
use App\Http\Controllers\Admin\InvestorDocumentController;
use App\Http\Controllers\Admin\InvestorHighlightItemController;
use App\Http\Controllers\Admin\ProfilePageController as AdminProfilePageController;

// Operational
use App\Http\Controllers\Operational\DashboardController as OperationalDashboardController;
use App\Http\Controllers\Operational\FlowGasDailyRecordController;
use App\Http\Controllers\Operational\FlowGasExportController;
use App\Http\Controllers\Operational\CrudeDailyRecordController;
use App\Http\Controllers\Operational\VitolRecordController;
use App\Http\Controllers\Web\OperationalController;

// Reviewer
use App\Http\Controllers\Reviewer\DashboardController as ReviewerDashboardController;
use App\Http\Controllers\Reviewer\NewsController as ReviewerNewsController;

// Writer
use App\Http\Controllers\Writer\DashboardController as WriterDashboardController;
use App\Http\Controllers\Writer\NewsController as WriterNewsController;

// Web
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\NewsController;
use App\Http\Controllers\Web\ProfileController;
use App\Http\Controllers\Web\PageController;
use App\Http\Controllers\Web\GcgController;
use App\Http\Controllers\Web\TjslController;
use App\Http\Controllers\Web\InvestorRelationController;

use App\Models\Page;
use App\Models\News;

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| ADMIN DASHBOARD
|--------------------------------------------------------------------------
*/
Route::middleware(['auth.session', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    });


// Operational Panel
Route::middleware(['auth', 'role:operational'])->prefix('operational')->name('operational.')->group(function () {
    Route::get('/dashboard', [OperationalDashboardController::class, 'index'])->name('dashboard');
    Route::get('/tv', [OperationalDashboardController::class, 'tv'])->name('tv');

    Route::get('/flow-gas', [FlowGasDailyRecordController::class, 'index'])->name('flow-gas.index');
    Route::get('/flow-gas/create', [FlowGasDailyRecordController::class, 'create'])->name('flow-gas.create');
    Route::post('/flow-gas', [FlowGasDailyRecordController::class, 'store'])->name('flow-gas.store');
    Route::get('/flow-gas/{flowGas}/edit', [FlowGasDailyRecordController::class, 'edit'])->name('flow-gas.edit');
    Route::put('/flow-gas/{flowGas}', [FlowGasDailyRecordController::class, 'update'])->name('flow-gas.update');
    Route::delete('/flow-gas/{flowGas}', [FlowGasDailyRecordController::class, 'destroy'])->name('flow-gas.destroy');

    Route::get('/flow-gas-export/monthly', [FlowGasExportController::class, 'monthly'])->name('flow-gas.export.monthly');

    Route::get('/crude', [CrudeDailyRecordController::class, 'index'])->name('crude.index');
    Route::get('/crude/create', [CrudeDailyRecordController::class, 'create'])->name('crude.create');
    Route::post('/crude', [CrudeDailyRecordController::class, 'store'])->name('crude.store');
    Route::get('/crude/{crude}/edit', [CrudeDailyRecordController::class, 'edit'])->name('crude.edit');
    Route::put('/crude/{crude}', [CrudeDailyRecordController::class, 'update'])->name('crude.update');
    Route::delete('/crude/{crude}', [CrudeDailyRecordController::class, 'destroy'])->name('crude.destroy');

    Route::get('/vitol', [VitolRecordController::class, 'index'])->name('vitol.index');
    Route::get('/vitol/create', [VitolRecordController::class, 'create'])->name('vitol.create');
    Route::post('/vitol', [VitolRecordController::class, 'store'])->name('vitol.store');
    Route::get('/vitol/{vitol}/edit', [VitolRecordController::class, 'edit'])->name('vitol.edit');
    Route::put('/vitol/{vitol}', [VitolRecordController::class, 'update'])->name('vitol.update');
    Route::delete('/vitol/{vitol}', [VitolRecordController::class, 'destroy'])->name('vitol.destroy');
});

/*
|--------------------------------------------------------------------------
| REVIEWER PANEL
|--------------------------------------------------------------------------
*/
Route::middleware(['auth.session', 'role:reviewer'])
    ->prefix('reviewer')
    ->name('reviewer.')
    ->group(function () {
        Route::get('/dashboard', [ReviewerDashboardController::class, 'index'])->name('dashboard');

        Route::get('/news', [ReviewerNewsController::class, 'index'])->name('news.index');
        Route::get('/news/{news}/edit', [ReviewerNewsController::class, 'edit'])->name('news.edit');
        Route::put('/news/{news}', [ReviewerNewsController::class, 'update'])->name('news.update');
        Route::post('/news/{news}/review', [ReviewerNewsController::class, 'review'])->name('news.review');
        Route::get('/news/{news}/logs', [ReviewerNewsController::class, 'logs'])->name('news.logs');
        Route::delete('/news/{news}', [ReviewerNewsController::class, 'destroy'])->name('news.destroy');
    });

/*
|--------------------------------------------------------------------------
| WRITER PANEL
|--------------------------------------------------------------------------
*/
Route::middleware(['auth.session', 'role:writer'])
    ->prefix('writer')
    ->name('writer.')
    ->group(function () {
        Route::get('/dashboard', [WriterDashboardController::class, 'index'])->name('dashboard');

        Route::get('/news', [WriterNewsController::class, 'index'])->name('news.index');
        Route::get('/news/create', [WriterNewsController::class, 'create'])->name('news.create');
        Route::post('/news', [WriterNewsController::class, 'store'])->name('news.store');
        Route::get('/news/{news}/edit', [WriterNewsController::class, 'edit'])->name('news.edit');
        Route::put('/news/{news}', [WriterNewsController::class, 'update'])->name('news.update');
    });

/*
|--------------------------------------------------------------------------
| ADMIN NEWS (READ ONLY)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth.session', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/news', [AdminNewsController::class, 'index'])->name('news.index');
        Route::get('/news/{news}', [AdminNewsController::class, 'show'])->name('news.show');
        Route::get('/news/{news}/logs', [AdminNewsController::class, 'logs'])->name('news.logs');
    });

/*
|--------------------------------------------------------------------------
| ADMIN ONLY MODULES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth.session', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('partners', AdminPartnerController::class);
        Route::resource('sliders', SliderController::class);

        Route::get('/profile-pages', [AdminProfilePageController::class, 'index'])->name('profile-pages.index');
        Route::get('/profile-pages/{page}/edit', [AdminProfilePageController::class, 'edit'])->name('profile-pages.edit');
        Route::put('/profile-pages/{page}', [AdminProfilePageController::class, 'update'])->name('profile-pages.update');

        Route::get('/pages', [AdminPageController::class, 'index'])->name('pages.index');
        Route::get('/pages/{page}/edit', [AdminPageController::class, 'edit'])->name('pages.edit');
        Route::put('/pages/{page}', [AdminPageController::class, 'update'])->name('pages.update');
        Route::get('/pages/{page}/versions', [AdminPageController::class, 'versions'])->name('pages.versions');
        Route::post('/pages/{page}/versions/{version}/restore', [AdminPageController::class, 'restoreVersion'])->name('pages.versions.restore');
        Route::post('/pages/{page}/bundles/{bundle}/restore', [AdminPageController::class, 'restoreBundle'])->name('pages.bundles.restore');

        Route::resource('menus', MenuController::class);
        Route::delete('/menus/{menu}/delete', [MenuController::class, 'destroy'])->name('menus.delete');

        Route::resource('tjsl', AdminTjslController::class);
        Route::delete('/tjsl/{program}/images/{image}', [AdminTjslController::class, 'destroyImage'])
            ->name('tjsl.images.destroy');

        Route::resource('gcg', GcgCategoryController::class);

        Route::prefix('gcg/{gcg}/documents')
            ->name('gcg.documents.')
            ->group(function () {
                Route::post('/', [GcgCategoryController::class, 'storeDocument'])->name('store');
                Route::put('/{document}', [GcgCategoryController::class, 'updateDocument'])->name('update');
                Route::delete('/{document}', [GcgCategoryController::class, 'destroyDocument'])->name('destroy');
            });

        Route::resource('investor-relations', InvestorDocumentController::class);

        Route::get('/gcg-highlight-items', [GcgHighlightItemController::class, 'index'])->name('gcg-highlight-items.index');
        Route::get('/gcg-highlight-items/create', [GcgHighlightItemController::class, 'create'])->name('gcg-highlight-items.create');
        Route::post('/gcg-highlight-items', [GcgHighlightItemController::class, 'store'])->name('gcg-highlight-items.store');
        Route::get('/gcg-highlight-items/{gcgHighlightItem}/edit', [GcgHighlightItemController::class, 'edit'])->name('gcg-highlight-items.edit');
        Route::put('/gcg-highlight-items/{gcgHighlightItem}', [GcgHighlightItemController::class, 'update'])->name('gcg-highlight-items.update');
        Route::delete('/gcg-highlight-items/{gcgHighlightItem}', [GcgHighlightItemController::class, 'destroy'])->name('gcg-highlight-items.destroy');

        Route::get('/investor-highlight-items', [InvestorHighlightItemController::class, 'index'])->name('investor-highlight-items.index');
        Route::get('/investor-highlight-items/create', [InvestorHighlightItemController::class, 'create'])->name('investor-highlight-items.create');
        Route::post('/investor-highlight-items', [InvestorHighlightItemController::class, 'store'])->name('investor-highlight-items.store');
        Route::get('/investor-highlight-items/{investorHighlightItem}/edit', [InvestorHighlightItemController::class, 'edit'])->name('investor-highlight-items.edit');
        Route::put('/investor-highlight-items/{investorHighlightItem}', [InvestorHighlightItemController::class, 'update'])->name('investor-highlight-items.update');
        Route::delete('/investor-highlight-items/{investorHighlightItem}', [InvestorHighlightItemController::class, 'destroy'])->name('investor-highlight-items.destroy');
    });

/*
|--------------------------------------------------------------------------
| ROOT
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
    $news = News::with('translations')->get();
    $xml = view('sitemap', compact('pages', 'news'));

    return response($xml, 200)->header('Content-Type', 'application/xml');
})->name('sitemap');

/*
|--------------------------------------------------------------------------
| DOWNLOAD DOKUMEN
|--------------------------------------------------------------------------
*/
Route::get('/gcg/download/{document}', [GcgController::class, 'download'])->name('gcg.download');
Route::get('/investor-relations/download/{document}', [InvestorRelationController::class, 'download'])->name('investor-relations.download');

/*
|--------------------------------------------------------------------------
| PUBLIC SITE
|--------------------------------------------------------------------------
*/
Route::prefix('{locale}')
    ->where(['locale' => 'id|en'])
    ->group(function () {
        Route::get('/', [HomeController::class, 'index'])->name('web.home');
        Route::get('/home', [HomeController::class, 'index'])->name('home');

        Route::prefix('profil')->group(function () {
            Route::get('/', [ProfileController::class, 'index'])->name('profil.index');
            Route::get('/{slug}', [ProfileController::class, 'show'])->name('profil.show');
        });

        Route::get('/privacy-policy', function (string $locale) {
            return view('web.legal.privacy', compact('locale'));
        })->name('legal.privacy');

        Route::get('/terms', function (string $locale) {
            return view('web.legal.terms', compact('locale'));
        })->name('legal.terms');

        Route::get('/wbs', function (string $locale) {
            return view('wbs.index', compact('locale'));
        })->name('wbs.index');

        Route::get('/media-publikasi', [NewsController::class, 'mediaPublikasi'])->name('media_publikasi.index');
        Route::get('/berita/{slug}', [NewsController::class, 'show'])->name('news.show');

        Route::get('/tjsl', [TjslController::class, 'index'])->name('tjsl.index');

        Route::prefix('gcg')->name('gcg.')->group(function () {
            Route::get('/', [GcgController::class, 'index'])->name('index');
            Route::get('/{slug}', [GcgController::class, 'show'])->name('show');
        });

        Route::get('/hubungan-investor', [InvestorRelationController::class, 'index'])->name('investor-relations.index');

        // ── FIX: web.operational dipindah ke dalam prefix group ──
        // Ditempatkan SEBELUM /{slug} agar tidak ditangkap sebagai page slug
        Route::get('/operasional', [OperationalController::class, 'index'])->name('web.operational');

        Route::get('/{slug}', [PageController::class, 'show'])
            ->where('slug', 'layanan|publikasi|kontak')
            ->name('page.show');
    });