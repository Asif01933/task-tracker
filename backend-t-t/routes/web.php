<?php

use App\Livewire\Auth\RegisterPage;
use App\Livewire\LandingPage;
use App\Livewire\Auth\LoginPage;
use Illuminate\Support\Facades\Route;
Route::get('/', function () {
    return view('welcome');
});


Route::get('/', LandingPage::class);
Route::get('/login', LoginPage::class)->name('login');
Route::get('/register', RegisterPage::class)->name('register');