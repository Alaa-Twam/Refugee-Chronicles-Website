<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group(function () {
    Route::get('/', [Pearls\Modules\CMS\Http\Controllers\PublicCMSController::class, 'index'])->name('home');
    Route::get('chronicles', [Pearls\Modules\CMS\Http\Controllers\PublicCMSController::class, 'chronicles'])->name('chronicles');
    Route::post('chronicles', [Pearls\Modules\CMS\Http\Controllers\PublicCMSController::class, 'chronicles'])->name('chronicles');
    Route::get('chronicles/{id}', [Pearls\Modules\CMS\Http\Controllers\PublicCMSController::class, 'showChronicle'])->name('show-chronicles');
    Route::get('about-us', [Pearls\Modules\CMS\Http\Controllers\PublicCMSController::class, 'aboutUs'])->name('about-us');
        Route::get('support-our-work', [Pearls\Modules\CMS\Http\Controllers\PublicCMSController::class, 'supportOurWork'])->name('support-our-work');

	Route::get('get-markers/{cityId}/{chronicleId?}', [Pearls\Modules\CMS\Http\Controllers\PublicCMSController::class, 'getMarkers'])->name('get-markers');
    Route::get('get-chronicle-by-marker/{chronicleId}', [Pearls\Modules\CMS\Http\Controllers\PublicCMSController::class, 'getChronicleByMarker'])->name('get-chronicle-by-marker');

    Route::group(['prefix' => 'cms'], function () {
        Route::resource('chronicles', Pearls\Modules\CMS\Http\Controllers\ChronicleController::class);
    });
});