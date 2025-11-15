<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Client\AuthController as ClientAuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // User Management
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);

    // Admin Management
    Route::resource('admins', \App\Http\Controllers\Admin\AdminController::class);

    // Program Management
    Route::resource('programs', \App\Http\Controllers\Admin\ProgramController::class);

    // Instructor Management (Konfirmasi Akun Instruktur)
    Route::get('instructors', [\App\Http\Controllers\Admin\InstructorController::class, 'index'])->name('instructors.index');
    Route::get('instructors/create', [\App\Http\Controllers\Admin\InstructorController::class, 'create'])->name('instructors.create');
    Route::post('instructors', [\App\Http\Controllers\Admin\InstructorController::class, 'store'])->name('instructors.store');
    Route::post('instructors/{id}/approve', [\App\Http\Controllers\Admin\InstructorController::class, 'approve'])->name('instructors.approve');
    Route::post('instructors/{id}/reject', [\App\Http\Controllers\Admin\InstructorController::class, 'reject'])->name('instructors.reject');
    Route::delete('instructors/{id}', [\App\Http\Controllers\Admin\InstructorController::class, 'destroy'])->name('instructors.destroy');

    // Program Proof Management
    Route::get('program-proofs', [\App\Http\Controllers\Admin\ProgramProofController::class, 'index'])->name('program-proofs.index');
    Route::get('program-proofs/{id}', [\App\Http\Controllers\Admin\ProgramProofController::class, 'show'])->name('program-proofs.show');
    Route::post('program-proofs/{id}/accept', [\App\Http\Controllers\Admin\ProgramProofController::class, 'accept'])->name('program-proofs.accept');
    Route::post('program-proofs/{id}/reject', [\App\Http\Controllers\Admin\ProgramProofController::class, 'reject'])->name('program-proofs.reject');
    Route::delete('program-proofs/{id}', [\App\Http\Controllers\Admin\ProgramProofController::class, 'destroy'])->name('program-proofs.destroy');

    // Certificate Management
    Route::resource('certificates', \App\Http\Controllers\Admin\CertificateController::class);

    // Promo Management
    Route::resource('promos', \App\Http\Controllers\Admin\PromoController::class);

    // Voucher Management
    Route::resource('vouchers', \App\Http\Controllers\Admin\VoucherController::class);

    // Article Management
    Route::resource('articles', \App\Http\Controllers\Admin\ArticleController::class);

    // Broadcast Management
    Route::resource('broadcasts', \App\Http\Controllers\Admin\BroadcastController::class);

    // Report Management
    Route::get('reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/export', [\App\Http\Controllers\Admin\ReportController::class, 'export'])->name('reports.export');
    Route::delete('reports/{id}', [\App\Http\Controllers\Admin\ReportController::class, 'destroy'])->name('reports.destroy');

    // Transaction Management
    Route::get('transactions', [\App\Http\Controllers\Admin\TransactionController::class, 'index'])->name('transactions.index');
    Route::get('transactions/export', [\App\Http\Controllers\Admin\TransactionController::class, 'export'])->name('transactions.export');
    Route::delete('transactions/{id}', [\App\Http\Controllers\Admin\TransactionController::class, 'destroy'])->name('transactions.destroy');
});

// Client/User Routes
Route::name('client.')->group(function () {
    // Home & Public Pages
    Route::get('/', function () {
        return view('client.layout.page.home');
    })->name('home');

    Route::get('/about', function () {
        return view('client.layout.page.about');
    })->name('about');

    Route::get('/kelas', function () {
        return view('client.layout.page.kelas');
    })->name('kelas');

    Route::get('/kelas/{id}', function () {
        return view('client.layout.page.DtailKelas');
    })->name('kelas.detail');

    Route::get('/event', function () {
        return view('client.layout.page.event');
    })->name('event');

    Route::get('/event/{id}', function () {
        return view('client.layout.page.dtailevent');
    })->name('event.detail');

    Route::get('/blog', function () {
        return view('client.layout.page.blog');
    })->name('blog');

    Route::get('/berita', function () {
        return view('client.layout.page.berita');
    })->name('berita');

    Route::get('/instruktur', function () {
        return view('client.layout.page.instruktur');
    })->name('instruktur');

    Route::get('/pembayaran', function () {
        return view('client.layout.page.pembayaran');
    })->name('pembayaran');

    // Authentication Pages
    Route::get('/login', function () {
        return view('client.layout.page.login.login');
    })->name('login');

    Route::get('/register', function () {
        return view('client.layout.page.login.create');
    })->name('register');

    Route::get('/reset-password', function () {
        return view('client.layout.page.login.reset');
    })->name('reset-password');

    Route::get('/dashboard', function () {
        return view('client.layout.page.dashboard');
    })->name('dashboard');
});
