<?php

use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;

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

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('home');

    Route::resource('worker', WorkerController::class);
    Route::resource('product', ProductController::class);
    Route::resource('category', CategoryController::class);

});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/test-notification/{id}', function ($id) {
    $admin   = User::first();
    // return $admin;
    $message = "Rider has been collected an order ";
    $notify_detail = [
        'from'      =>  $id,
        'to'        =>  $admin->id,
        'message'   =>  $message,
    ];
    Notification::create($notify_detail);
    $notification = new Notification;
    $from       =  'Rehmat-e-Sheeren';
    // $users = User::all();

    $notification->toSingleDevice($admin->device_token, $from,$message,null,null);
    return $notification;
    // $notification->toMultipleDevice($users, $from,$message,null,null);
})->name('test-notification');
