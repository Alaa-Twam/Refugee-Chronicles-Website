<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group(function () {
    Route::group(['prefix' => ''], function () {
        Route::resource('cities', Pearls\Modules\City\Http\Controllers\CityController::class, ['only' => ['index', 'edit', 'update']]);
    });
});