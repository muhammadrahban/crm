<?php

use App\Http\Controllers\api\cargocontroller;
use App\Http\Controllers\api\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\usercontroller;
use App\Http\Controllers\api\userlistcontroller;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/register', [usercontroller::class, 'store']);
Route::post('/login', [usercontroller::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::resource('cargo', cargocontroller::class);
    Route::resource('order', OrderController::class);
    Route::get('customersearch', [OrderController::class, 'CustomerSearch']);

    Route::post('cargosearch', [cargocontroller::class, 'search']);
    Route::post('cargodetail', [cargocontroller::class, 'cargoDetail']);

    Route::get('UserList', [userlistcontroller::class, 'index']);
    Route::get('UserList/{id}', [userlistcontroller::class, 'show']);
    Route::post('UserCreate', [userlistcontroller::class, 'create']);
    Route::put('changeStatus/{user}', [userlistcontroller::class, 'changeStatus']);
    Route::get('itemActivity/{order}', [userlistcontroller::class, 'get_itemActivity']);
    Route::delete('destroyUser/{id}', [userlistcontroller::class, 'destroyUser']);
    Route::get('listItems', [userlistcontroller::class, 'listItems']);
    Route::get('singleItem/{id}', [userlistcontroller::class, 'singleItem']);

    Route::put('updateitem/{item}', [userlistcontroller::class, 'updateitem']);
    Route::put('orderstatus/{id}', [OrderController::class, 'orderstatus']);
    Route::post('ordersearch', [OrderController::class, 'OrderSearch']);

    Route::post('customerlist', [userlistcontroller::class, 'customerlist']);
    Route::get('customerorder/{id}', [userlistcontroller::class, 'customerorder']);

    Route::post('/logout', [usercontroller::class, 'logout']);
});
