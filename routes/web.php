<?php

use App\Http\Controllers\ApiKeyController;
use App\Http\Controllers\SubscriberController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('home');
})->middleware('apikey.present');

Route::prefix('apikeys')->name('apikeys.')->group(function () {
    Route::get('/create', [ApiKeyController::class, 'create'])->name('create');
    Route::post('/store', [ApiKeyController::class, 'store'])->name('store');
});

Route::get('subscribers/search', [SubscriberController::class, 'search'])
    ->middleware('apikey.present')
    ->name('subscribers.search');
Route::resource('subscribers', SubscriberController::class)->middleware('apikey.present');
