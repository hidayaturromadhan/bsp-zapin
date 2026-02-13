<?php
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth.session', 'role:admin,wbs_officer'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return 'Dashboard';
    })->name('admin.dashboard');
});

Route::get('/', function () {
    return view('web.home');
})->name('home');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');