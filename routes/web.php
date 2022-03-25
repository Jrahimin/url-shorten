<?php

use App\Http\Controllers\Api\UrlShortenerController;
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
    return view('app');
});

Route::get('/{shortCode}', [UrlShortenerController::class, 'getFullUrlFromCodeAndRedirect']);
Route::get('/{subDomain}/{shortCode}', [UrlShortenerController::class, 'getFullUrlFromCodeDomainAndRedirect']);
