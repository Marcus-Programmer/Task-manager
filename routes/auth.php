<?php

use Illuminate\Support\Facades\Route;

Route::get('login', function () {
    return redirect()->route('tasks.index');
})->name('login');

Route::get('register', function () {
    return redirect()->route('tasks.index');
})->name('register');

Route::post('logout', function () {
    auth()->logout();
    return redirect('/');
})->name('logout');