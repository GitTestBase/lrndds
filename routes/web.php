<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


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
    return view('welcome');
});
Route::get('/check', function () {
    return view('notification');
});
Route::post('/save-token', [AuthController::class, 'saveToken'])->name('save-token');
Route::post('/send-notification', [AuthController::class, 'sendNotification'])->name('send.notification');
