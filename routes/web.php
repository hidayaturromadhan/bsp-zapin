<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\SessionHeartbeatController;


// WBS
use App\Http\Controllers\Web\WbsController;
use App\Http\Controllers\Wbs\Admin\DashboardController as WbsAdminDashboardController;
use App\Http\Controllers\Wbs\Admin\ReportController as WbsAdminReportController;
use App\Http\Controllers\Wbs\Pelapor\DashboardController as WbsPelaporDashboardController;
use App\Http\Controllers\Wbs\Pelapor\ReportController as WbsPelaporReportController;
use App\Http\Controllers\Wbs\NotificationController;

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
use App\Http\Controllers\Operational\BroadcastMessageController;

// Reviewer
use App\Http\Controllers\Reviewer\DashboardController as ReviewerDashboardController;
use App\Http\Controllers\Reviewer\NewsController as ReviewerNewsController;
use App\Http\Controllers\Reviewer\TjslController as ReviewerTjslController;

// Writer
use App\Http\Controllers\Writer\DashboardController as WriterDashboardController;
use App\Http\Controllers\Writer\NewsController as WriterNewsController;
use App\Http\Controllers\Writer\TjslController as WriterTjslController;

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

// Session
Route::post('/session/heartbeat', [SessionHeartbeatController::class, 'ping'])
    ->middleware('auth')
    ->name('session.heartbeat');

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login.post');

    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'register'])
        ->middleware('throttle:3,1')
        ->name('register.post');

    Route::get('/auth/google', [GoogleController::class, 'redirect'])
        ->middleware('throttle:10,1')
        ->name('google.redirect');

    Route::get('/auth/google/callback', [GoogleController::class, 'callback'])
        ->middleware('throttle:10,1')
        ->name('google.callback');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

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

/*
|--------------------------------------------------------------------------
| OPERATIONAL PANEL
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:operational'])
    ->prefix('operational')
    ->name('operational.')
    ->group(function () {
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

        Route::get('/broadcast', [BroadcastMessageController::class, 'index'])->name('broadcast.index');
        Route::get('/broadcast/create', [BroadcastMessageController::class, 'create'])->name('broadcast.create');
        Route::post('/broadcast', [BroadcastMessageController::class, 'store'])->name('broadcast.store');
        Route::get('/broadcast/{broadcast}', [BroadcastMessageController::class, 'show'])->name('broadcast.show');
        Route::get('/broadcast/{broadcast}/edit', [BroadcastMessageController::class, 'edit'])->name('broadcast.edit');
        Route::put('/broadcast/{broadcast}', [BroadcastMessageController::class, 'update'])->name('broadcast.update');
        Route::patch('/broadcast/{broadcast}', [BroadcastMessageController::class, 'update'])->name('broadcast.patch');
        Route::delete('/broadcast/{broadcast}', [BroadcastMessageController::class, 'destroy'])->name('broadcast.destroy');
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
        Route::get('/news/{news}', [ReviewerNewsController::class, 'show'])->name('news.show');
        Route::get('/news/{news}/preview', [ReviewerNewsController::class, 'preview'])->name('news.preview');
        Route::get('/news/{news}/logs', [ReviewerNewsController::class, 'logs'])->name('news.logs');

        Route::get('/tjsl', [ReviewerTjslController::class, 'index'])->name('tjsl.index');
        Route::get('/tjsl/{tjsl}', [ReviewerTjslController::class, 'show'])->name('tjsl.show');
        Route::get('/tjsl/{tjsl}/preview', [ReviewerTjslController::class, 'preview'])->name('tjsl.preview');
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
        Route::get('/news/{news}', [WriterNewsController::class, 'show'])->name('news.show');
        Route::get('/news/{news}/edit', [WriterNewsController::class, 'edit'])->name('news.edit');
        Route::put('/news/{news}', [WriterNewsController::class, 'update'])->name('news.update');

        Route::get('/news/{news}/preview', [WriterNewsController::class, 'preview'])->name('news.preview');
        Route::get('/news/{news}/send-preview-whatsapp', [WriterNewsController::class, 'sendPreviewWhatsapp'])->name('news.send-preview-whatsapp');
        Route::patch('/news/{news}/publish', [WriterNewsController::class, 'publish'])->name('news.publish');
        Route::patch('/news/{news}/unpublish', [WriterNewsController::class, 'unpublish'])->name('news.unpublish');
        Route::delete('/news/{news}', [WriterNewsController::class, 'destroy'])->name('news.destroy');

        Route::get('/tjsl', [WriterTjslController::class, 'index'])->name('tjsl.index');
        Route::get('/tjsl/create', [WriterTjslController::class, 'create'])->name('tjsl.create');
        Route::post('/tjsl', [WriterTjslController::class, 'store'])->name('tjsl.store');
        Route::get('/tjsl/{tjsl}', [WriterTjslController::class, 'show'])->name('tjsl.show');
        Route::get('/tjsl/{tjsl}/edit', [WriterTjslController::class, 'edit'])->name('tjsl.edit');
        Route::put('/tjsl/{tjsl}', [WriterTjslController::class, 'update'])->name('tjsl.update');
        Route::get('/tjsl/{tjsl}/preview', [WriterTjslController::class, 'preview'])->name('tjsl.preview');
        Route::get('/tjsl/{tjsl}/send-preview-whatsapp', [WriterTjslController::class, 'send-preview-whatsapp'])->name('tjsl.send-preview-whatsapp');
        Route::patch('/tjsl/{tjsl}/publish', [WriterTjslController::class, 'publish'])->name('tjsl.publish');
        Route::patch('/tjsl/{tjsl}/unpublish', [WriterTjslController::class, 'unpublish'])->name('tjsl.unpublish');
        Route::delete('/tjsl/{tjsl}', [WriterTjslController::class, 'destroy'])->name('tjsl.destroy');
        Route::delete('/tjsl/{tjsl}/images/{image}', [WriterTjslController::class, 'deleteImage'])->name('tjsl.images.destroy');
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
| WBS NOTIFICATIONS (GLOBAL - ADMIN & PELAPOR)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth.session', 'role:pelapor,wbs_admin'])
    ->prefix('wbs/notifications')
    ->name('wbs.notifications.')
    ->group(function () {
        Route::get('/{notification}/open', [NotificationController::class, 'open'])->name('open');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllRead'])->name('mark-all-read');
    });

/*
|--------------------------------------------------------------------------
| WBS PELAPOR
|--------------------------------------------------------------------------
*/
Route::middleware(['auth.session', 'role:pelapor'])
    ->prefix('wbs')
    ->name('wbs.')
    ->group(function () {
        Route::get('/dashboard', [WbsPelaporDashboardController::class, 'index'])->name('pelapor.dashboard');

        Route::get('/reports', [WbsPelaporReportController::class, 'index'])->name('pelapor.reports.index');
        Route::get('/reports/create', [WbsPelaporReportController::class, 'create'])->name('pelapor.reports.create');
        Route::post('/reports', [WbsPelaporReportController::class, 'store'])->name('pelapor.reports.store');

        Route::get('/reports/{report}', [WbsPelaporReportController::class, 'show'])->name('pelapor.reports.show');
        Route::get('/reports/{report}/edit', [WbsPelaporReportController::class, 'edit'])->name('pelapor.reports.edit');
        Route::put('/reports/{report}', [WbsPelaporReportController::class, 'update'])->name('pelapor.reports.update');
        Route::delete('/reports/{report}', [WbsPelaporReportController::class, 'destroy'])->name('pelapor.reports.destroy');
    });

/*
|--------------------------------------------------------------------------
| WBS ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth.session', 'role:wbs_admin,wbs_officer'])
    ->prefix('wbs/admin')
    ->name('wbs.admin.')
    ->group(function () {
        Route::get('/dashboard', [WbsAdminDashboardController::class, 'index'])->name('dashboard');

        Route::get('/reports', [WbsAdminReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export-filtered-pdf', [WbsAdminReportController::class, 'exportFilteredPdf'])->name('reports.export-filtered-pdf');

        Route::get('/reports/{report}', [WbsAdminReportController::class, 'show'])->name('reports.show');
        Route::get('/reports/{report}/edit', [WbsAdminReportController::class, 'edit'])->name('reports.edit');
        Route::put('/reports/{report}', [WbsAdminReportController::class, 'update'])->name('reports.update');
        Route::put('/reports/{report}/status', [WbsAdminReportController::class, 'updateStatus'])->name('reports.update-status');
        Route::get('/reports/{report}/export-pdf', [WbsAdminReportController::class, 'exportPdf'])->name('reports.export-pdf');
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

        /*
        |--------------------------------------------------------------------------
        | PARTNERS
        |--------------------------------------------------------------------------
        */
        Route::get('/partners', [AdminPartnerController::class, 'index'])->name('partners.index');
        Route::get('/partners/create', [AdminPartnerController::class, 'create'])->name('partners.create');
        Route::post('/partners', [AdminPartnerController::class, 'store'])->name('partners.store');
        Route::get('/partners/{partner}', [AdminPartnerController::class, 'show'])->name('partners.show');
        Route::get('/partners/{partner}/edit', [AdminPartnerController::class, 'edit'])->name('partners.edit');
        Route::put('/partners/{partner}', [AdminPartnerController::class, 'update'])->name('partners.update');
        Route::patch('/partners/{partner}', [AdminPartnerController::class, 'update'])->name('partners.patch');
        Route::delete('/partners/{partner}', [AdminPartnerController::class, 'destroy'])->name('partners.destroy');

        /*
        |--------------------------------------------------------------------------
        | SLIDERS
        |--------------------------------------------------------------------------
        */
        Route::get('/sliders', [SliderController::class, 'index'])->name('sliders.index');
        Route::get('/sliders/create', [SliderController::class, 'create'])->name('sliders.create');
        Route::post('/sliders', [SliderController::class, 'store'])->name('sliders.store');
        Route::get('/sliders/{slider}', [SliderController::class, 'show'])->name('sliders.show');
        Route::get('/sliders/{slider}/edit', [SliderController::class, 'edit'])->name('sliders.edit');
        Route::put('/sliders/{slider}', [SliderController::class, 'update'])->name('sliders.update');
        Route::patch('/sliders/{slider}', [SliderController::class, 'update'])->name('sliders.patch');
        Route::delete('/sliders/{slider}', [SliderController::class, 'destroy'])->name('sliders.destroy');

        /*
        |--------------------------------------------------------------------------
        | PROFILE PAGES
        |--------------------------------------------------------------------------
        */
        Route::get('/profile-pages', [AdminProfilePageController::class, 'index'])->name('profile-pages.index');
        Route::get('/profile-pages/{page}/edit', [AdminProfilePageController::class, 'edit'])->name('profile-pages.edit');
        Route::put('/profile-pages/{page}', [AdminProfilePageController::class, 'update'])->name('profile-pages.update');
        Route::patch('/profile-pages/{page}', [AdminProfilePageController::class, 'update'])->name('profile-pages.patch');

        /*
        |--------------------------------------------------------------------------
        | PAGES
        |--------------------------------------------------------------------------
        */
        Route::get('/pages', [AdminPageController::class, 'index'])->name('pages.index');
        Route::get('/pages/{page}/edit', [AdminPageController::class, 'edit'])->name('pages.edit');
        Route::put('/pages/{page}', [AdminPageController::class, 'update'])->name('pages.update');
        Route::patch('/pages/{page}', [AdminPageController::class, 'update'])->name('pages.patch');
        Route::get('/pages/{page}/versions', [AdminPageController::class, 'versions'])->name('pages.versions');
        Route::post('/pages/{page}/versions/{version}/restore', [AdminPageController::class, 'restoreVersion'])->name('pages.versions.restore');
        Route::post('/pages/{page}/bundles/{bundle}/restore', [AdminPageController::class, 'restoreBundle'])->name('pages.bundles.restore');

        /*
        |--------------------------------------------------------------------------
        | MENUS
        |--------------------------------------------------------------------------
        */
        Route::get('/menus', [MenuController::class, 'index'])->name('menus.index');
        Route::get('/menus/create', [MenuController::class, 'create'])->name('menus.create');
        Route::post('/menus', [MenuController::class, 'store'])->name('menus.store');
        Route::get('/menus/{menu}', [MenuController::class, 'show'])->name('menus.show');
        Route::get('/menus/{menu}/edit', [MenuController::class, 'edit'])->name('menus.edit');
        Route::put('/menus/{menu}', [MenuController::class, 'update'])->name('menus.update');
        Route::patch('/menus/{menu}', [MenuController::class, 'update'])->name('menus.patch');
        Route::delete('/menus/{menu}', [MenuController::class, 'destroy'])->name('menus.destroy');
        Route::delete('/menus/{menu}/delete', [MenuController::class, 'destroy'])->name('menus.delete');

        /*
        |--------------------------------------------------------------------------
        | TJSL
        |--------------------------------------------------------------------------
        */
        Route::get('/tjsl', [AdminTjslController::class, 'index'])->name('tjsl.index');
        Route::get('/tjsl/{tjsl}', [AdminTjslController::class, 'show'])->name('tjsl.show');

        /*
        |--------------------------------------------------------------------------
        | GCG
        | Sesuai layout admin: admin.gcg.*
        |--------------------------------------------------------------------------
        */
        Route::get('/gcg', [GcgCategoryController::class, 'index'])->name('gcg.index');
        Route::get('/gcg/create', [GcgCategoryController::class, 'create'])->name('gcg.create');
        Route::post('/gcg', [GcgCategoryController::class, 'store'])->name('gcg.store');
        Route::get('/gcg/{gcg}/edit', [GcgCategoryController::class, 'edit'])->name('gcg.edit');
        Route::put('/gcg/{gcg}', [GcgCategoryController::class, 'update'])->name('gcg.update');
        Route::patch('/gcg/{gcg}', [GcgCategoryController::class, 'update'])->name('gcg.patch');
        Route::delete('/gcg/{gcg}', [GcgCategoryController::class, 'destroy'])->name('gcg.destroy');

        /*
        |--------------------------------------------------------------------------
        | GCG CATEGORIES
        | Route lama tetap disediakan agar kode lama tidak rusak
        |--------------------------------------------------------------------------
        */
        Route::get('/gcg-categories', [GcgCategoryController::class, 'index'])->name('gcg-categories.index');
        Route::get('/gcg-categories/create', [GcgCategoryController::class, 'create'])->name('gcg-categories.create');
        Route::post('/gcg-categories', [GcgCategoryController::class, 'store'])->name('gcg-categories.store');
        Route::get('/gcg-categories/{gcgCategory}/edit', [GcgCategoryController::class, 'edit'])->name('gcg-categories.edit');
        Route::put('/gcg-categories/{gcgCategory}', [GcgCategoryController::class, 'update'])->name('gcg-categories.update');
        Route::patch('/gcg-categories/{gcgCategory}', [GcgCategoryController::class, 'update'])->name('gcg-categories.patch');
        Route::delete('/gcg-categories/{gcgCategory}', [GcgCategoryController::class, 'destroy'])->name('gcg-categories.destroy');

        /*
        |--------------------------------------------------------------------------
        | GCG HIGHLIGHT ITEMS
        |--------------------------------------------------------------------------
        */
        Route::get('/gcg-highlight-items', [GcgHighlightItemController::class, 'index'])->name('gcg-highlight-items.index');
        Route::get('/gcg-highlight-items/create', [GcgHighlightItemController::class, 'create'])->name('gcg-highlight-items.create');
        Route::post('/gcg-highlight-items', [GcgHighlightItemController::class, 'store'])->name('gcg-highlight-items.store');
        Route::get('/gcg-highlight-items/{gcgHighlightItem}/edit', [GcgHighlightItemController::class, 'edit'])->name('gcg-highlight-items.edit');
        Route::put('/gcg-highlight-items/{gcgHighlightItem}', [GcgHighlightItemController::class, 'update'])->name('gcg-highlight-items.update');
        Route::patch('/gcg-highlight-items/{gcgHighlightItem}', [GcgHighlightItemController::class, 'update'])->name('gcg-highlight-items.patch');
        Route::delete('/gcg-highlight-items/{gcgHighlightItem}', [GcgHighlightItemController::class, 'destroy'])->name('gcg-highlight-items.destroy');

        /*
        |--------------------------------------------------------------------------
        | INVESTOR RELATIONS
        | Sesuai layout admin: admin.investor-relations.*
        |--------------------------------------------------------------------------
        */
        Route::get('/investor-relations', [InvestorDocumentController::class, 'index'])->name('investor-relations.index');
        Route::get('/investor-relations/create', [InvestorDocumentController::class, 'create'])->name('investor-relations.create');
        Route::post('/investor-relations', [InvestorDocumentController::class, 'store'])->name('investor-relations.store');
        Route::get('/investor-relations/{investorRelation}/edit', [InvestorDocumentController::class, 'edit'])->name('investor-relations.edit');
        Route::put('/investor-relations/{investorRelation}', [InvestorDocumentController::class, 'update'])->name('investor-relations.update');
        Route::patch('/investor-relations/{investorRelation}', [InvestorDocumentController::class, 'update'])->name('investor-relations.patch');
        Route::delete('/investor-relations/{investorRelation}', [InvestorDocumentController::class, 'destroy'])->name('investor-relations.destroy');

        /*
        |--------------------------------------------------------------------------
        | INVESTOR DOCUMENTS
        | Route lama tetap disediakan agar kode lama tidak rusak
        |--------------------------------------------------------------------------
        */
        Route::get('/investor-documents', [InvestorDocumentController::class, 'index'])->name('investor-documents.index');
        Route::get('/investor-documents/create', [InvestorDocumentController::class, 'create'])->name('investor-documents.create');
        Route::post('/investor-documents', [InvestorDocumentController::class, 'store'])->name('investor-documents.store');
        Route::get('/investor-documents/{investorDocument}/edit', [InvestorDocumentController::class, 'edit'])->name('investor-documents.edit');
        Route::put('/investor-documents/{investorDocument}', [InvestorDocumentController::class, 'update'])->name('investor-documents.update');
        Route::patch('/investor-documents/{investorDocument}', [InvestorDocumentController::class, 'update'])->name('investor-documents.patch');
        Route::delete('/investor-documents/{investorDocument}', [InvestorDocumentController::class, 'destroy'])->name('investor-documents.destroy');

        /*
        |--------------------------------------------------------------------------
        | INVESTOR HIGHLIGHT ITEMS
        |--------------------------------------------------------------------------
        */
        Route::get('/investor-highlight-items', [InvestorHighlightItemController::class, 'index'])->name('investor-highlight-items.index');
        Route::get('/investor-highlight-items/create', [InvestorHighlightItemController::class, 'create'])->name('investor-highlight-items.create');
        Route::post('/investor-highlight-items', [InvestorHighlightItemController::class, 'store'])->name('investor-highlight-items.store');
        Route::get('/investor-highlight-items/{investorHighlightItem}/edit', [InvestorHighlightItemController::class, 'edit'])->name('investor-highlight-items.edit');
        Route::put('/investor-highlight-items/{investorHighlightItem}', [InvestorHighlightItemController::class, 'update'])->name('investor-highlight-items.update');
        Route::patch('/investor-highlight-items/{investorHighlightItem}', [InvestorHighlightItemController::class, 'update'])->name('investor-highlight-items.patch');
        Route::delete('/investor-highlight-items/{investorHighlightItem}', [InvestorHighlightItemController::class, 'destroy'])->name('investor-highlight-items.destroy');
    });

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

        Route::get('/wbs', [WbsController::class, 'index'])->name('web.wbs.index');

        Route::get('/media-publikasi', [NewsController::class, 'mediaPublikasi'])->name('media_publikasi.index');
        Route::get('/berita/{slug}', [NewsController::class, 'show'])->name('news.show');

        Route::get('/tjsl', [TjslController::class, 'index'])->name('tjsl.index');

        Route::prefix('gcg')->name('gcg.')->group(function () {
            Route::get('/', [GcgController::class, 'index'])->name('index');
            Route::get('/{slug}', [GcgController::class, 'show'])->name('show');
        });

        Route::get('/hubungan-investor', [InvestorRelationController::class, 'index'])->name('investor-relations.index');

        Route::get('/operasional', [OperationalController::class, 'index'])->name('web.operational');

        Route::get('/{slug}', [PageController::class, 'show'])
            ->where('slug', 'layanan|publikasi|kontak')
            ->name('page.show');
    });

/*
|--------------------------------------------------------------------------
| Redirect URL Publik Tanpa Locale ke Locale Default
|--------------------------------------------------------------------------
| Contoh:
| /documents/gcg  -> /id/documents/gcg
| /profile        -> /id/profile
| /news           -> /id/news
*/
Route::get('/documents/{path?}', function (?string $path = null) {
    return redirect('/id/documents' . ($path ? '/' . $path : ''), 302);
})->where('path', '.*');

Route::get('/profile/{path?}', function (?string $path = null) {
    return redirect('/id/profile' . ($path ? '/' . $path : ''), 302);
})->where('path', '.*');

Route::get('/news/{path?}', function (?string $path = null) {
    return redirect('/id/news' . ($path ? '/' . $path : ''), 302);
})->where('path', '.*');

Route::get('/contact/{path?}', function (?string $path = null) {
    return redirect('/id/contact' . ($path ? '/' . $path : ''), 302);
})->where('path', '.*');

/*
|--------------------------------------------------------------------------
| Fallback Aman
|--------------------------------------------------------------------------
| Semua URL ngawur langsung dipental ke homepage Indonesia.
*/
Route::fallback(function () {
    return redirect('/id', 302);
});