<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group(function () {
    Route::get('/dashboard', [Pearls\User\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('/users', 'Pearls\User\Http\Controllers\UserController', ['except' => ['show']]);
    Route::resource('roles', 'Pearls\User\Http\Controllers\RolesController', ['except' => ['show']]);

    Route::get('login', 'Pearls\User\Http\Controllers\Auth\LoginController@showLoginForm')->name('login');
    Route::post('login', 'Pearls\User\Http\Controllers\Auth\LoginController@login');
    Route::post('logout', 'Pearls\User\Http\Controllers\Auth\LoginController@logout')->name('logout');
});