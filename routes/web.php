<?php

use App\Http\Controllers\admin\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('client.layout.page.home');
});
Route::get('/about',[HomeController::class, 'index']);
Route::get('/kelas', function () {
    return view('client.layout.page.kelas');
});
Route::get('/event', function () {
    return view('client.layout.page.event');
});
Route::get('/blog', function () {
    return view('client.layout.page.blog');
});
Route::get('/about', function () {
    return view('client.layout.page.about');
});
Route::get('/dtail_kelas', function () {
    return view('client.layout.page.DtailKelas');
});
Route::get('/pembayaran', function () {
    return view('client.layout.page.pembayaran');
});
Route::get('/dtail_event', function () {
    return view('client.layout.page.dtailevent');
});
Route::get('/berita', function () {
    return view('client.layout.page.berita');
});
Route::get('/login', function () {
    return view('client.layout.page.login.login');
});
Route::get('/reset', function () {
    return view('client.layout.page.login.reset');
});
Route::get('/create', function () {
    return view('client.layout.page.login.create');
});
Route::get('/instruktur', function () {
    return view('client.layout.page.instruktur');
});
Route::get('/dashboard', function () {
    return view('client.layout.page.dashboard');
});



