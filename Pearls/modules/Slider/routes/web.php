<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group(function () {
    Route::group(['prefix' => ''], function () {
        Route::resource('sliders', Pearls\Modules\Slider\Http\Controllers\SliderController::class);
    });
});