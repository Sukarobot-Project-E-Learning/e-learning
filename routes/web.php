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

// Admin Login Routes (Public)
Route::prefix('admin')->name('admin.')->group(function () {
    // Redirect /admin to /admin/login (if not logged in) or /admin/dashboard (if logged in as admin)
    Route::get('/', function () {
        if (auth()->check() && auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('admin.login');
    });
    
    Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'showAdminLoginForm'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'adminLogin']);
});

// Instructor Login Routes (Public)
Route::prefix('instructor')->name('instructor.')->group(function () {
    // Redirect /instructor to /instructor/login (if not logged in) or /instructor/dashboard (if logged in as instructor)
    Route::get('/', function () {
        if (auth()->check() && auth()->user()->role === 'trainer') {
            return redirect()->route('instructor.dashboard');
        }
        return redirect()->route('instructor.login');
    });
    
    Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'showInstructorLoginForm'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'instructorLogin']);
});

// User Login Routes (Public)
Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'showUserLoginForm'])->name('login');
Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'userLogin']);

// Logout Route
Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Admin Routes (Protected)
// Note: Using only 'admin' middleware because it already checks auth
Route::prefix('admin')->name('admin.')->middleware([\App\Http\Middleware\EnsureUserIsAdmin::class])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // User Management (Students - role='user')
    Route::get('users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::post('users', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
    Route::put('users/{id}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
    Route::delete('users/{id}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');

    // Admin Management (role='admin')
    Route::get('admins', [\App\Http\Controllers\Admin\UserController::class, 'indexAdmins'])->name('admins.index');
    Route::post('admins', [\App\Http\Controllers\Admin\UserController::class, 'storeAdmin'])->name('admins.store');
    Route::put('admins/{id}', [\App\Http\Controllers\Admin\UserController::class, 'updateAdmin'])->name('admins.update');
    Route::delete('admins/{id}', [\App\Http\Controllers\Admin\UserController::class, 'destroyAdmin'])->name('admins.destroy');

    // Instructors List Management (role='instructor')
    Route::get('instructors-list', [\App\Http\Controllers\Admin\UserController::class, 'indexInstructors'])->name('instructors-list.index');
    Route::post('instructors-list', [\App\Http\Controllers\Admin\UserController::class, 'storeInstructor'])->name('instructors-list.store');
    Route::put('instructors-list/{id}', [\App\Http\Controllers\Admin\UserController::class, 'updateInstructor'])->name('instructors-list.update');
    Route::delete('instructors-list/{id}', [\App\Http\Controllers\Admin\UserController::class, 'destroyInstructor'])->name('instructors-list.destroy');

    // Program Management
    Route::resource('programs', \App\Http\Controllers\Admin\ProgramController::class);
    
    // Program Approval Management (Persetujuan Program dari Instruktur)
    Route::get('program-approvals', [\App\Http\Controllers\Admin\ProgramApprovalController::class, 'index'])->name('program-approvals.index');
    Route::get('program-approvals/{id}', [\App\Http\Controllers\Admin\ProgramApprovalController::class, 'show'])->name('program-approvals.show');
    Route::post('program-approvals/{id}/approve', [\App\Http\Controllers\Admin\ProgramApprovalController::class, 'approve'])->name('program-approvals.approve');
    Route::post('program-approvals/{id}/reject', [\App\Http\Controllers\Admin\ProgramApprovalController::class, 'reject'])->name('program-approvals.reject');

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
    
    // Quiz/Tugas Management (Admin juga bisa buat)
    Route::get('quizzes', [\App\Http\Controllers\Admin\QuizController::class, 'index'])->name('quizzes.index');
    Route::get('quizzes/create', [\App\Http\Controllers\Admin\QuizController::class, 'create'])->name('quizzes.create');
    Route::post('quizzes', [\App\Http\Controllers\Admin\QuizController::class, 'store'])->name('quizzes.store');
    Route::get('quizzes/{id}', [\App\Http\Controllers\Admin\QuizController::class, 'show'])->name('quizzes.show');
    Route::get('quizzes/{id}/edit', [\App\Http\Controllers\Admin\QuizController::class, 'edit'])->name('quizzes.edit');
    Route::put('quizzes/{id}', [\App\Http\Controllers\Admin\QuizController::class, 'update'])->name('quizzes.update');
    Route::delete('quizzes/{id}', [\App\Http\Controllers\Admin\QuizController::class, 'destroy'])->name('quizzes.destroy');
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

    // Authentication Pages (Login sudah didefinisikan di atas, tidak perlu duplikat)
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

// Instructor Routes (Protected)
// Note: Using only 'instructor' middleware because it already checks auth
Route::prefix('instructor')->name('instructor.')->middleware([\App\Http\Middleware\EnsureUserIsInstructor::class])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Instructor\DashboardController::class, 'index'])->name('dashboard');
    
    // Profile Management
    Route::get('/profile', [\App\Http\Controllers\Instructor\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\Instructor\ProfileController::class, 'update'])->name('profile.update');
    
    // Program Management
    Route::resource('programs', \App\Http\Controllers\Instructor\ProgramController::class);
    
    // Quiz/Tugas Management
    Route::get('quizzes', [\App\Http\Controllers\Instructor\QuizController::class, 'index'])->name('quizzes.index');
    Route::get('quizzes/create', [\App\Http\Controllers\Instructor\QuizController::class, 'create'])->name('quizzes.create');
    Route::post('quizzes', [\App\Http\Controllers\Instructor\QuizController::class, 'store'])->name('quizzes.store');
    Route::get('quizzes/{id}', [\App\Http\Controllers\Instructor\QuizController::class, 'show'])->name('quizzes.show');
    Route::get('quizzes/{id}/edit', [\App\Http\Controllers\Instructor\QuizController::class, 'edit'])->name('quizzes.edit');
    Route::put('quizzes/{id}', [\App\Http\Controllers\Instructor\QuizController::class, 'update'])->name('quizzes.update');
    Route::delete('quizzes/{id}', [\App\Http\Controllers\Instructor\QuizController::class, 'destroy'])->name('quizzes.destroy');
});
