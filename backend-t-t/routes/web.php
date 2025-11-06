<?php


use App\Livewire\Dashboard\DashboardPage;
use App\Livewire\LandingPage;
use App\Livewire\Auth\LoginPage;
use App\Livewire\Auth\RegisterPage;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/', LandingPage::class);
Route::get('/login', LoginPage::class)->name('login');
Route::get('/register', RegisterPage::class)->name('register');

//authenticated routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', DashboardPage::class)->name('dashboard');
});