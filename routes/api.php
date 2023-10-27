<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(AuthController::class)->group(function()
{
    Route::post('login','login');
    Route::post('registration','registration');
});
Route::group(['middleware'=>'auth:api'],function()
{
    Route::group(['middleware'=>'is_admin'],function()
    {
        Route::post('/add-category',[CategoryController::class,'add_category']);
        Route::post('/add-product',[ProductsController::class,'products']);

    });
    
    Route::get('/category-list',[CategoryController::class,'get_category']);

    Route::get('/prod-list',[ProductsController::class,'index']);

});


