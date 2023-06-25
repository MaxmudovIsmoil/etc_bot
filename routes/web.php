<?php

use App\Http\Controllers\Controller\AuthController;
use App\Http\Controllers\RunController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller\OrderController;
use App\Http\Controllers\Controller\ClientController;

//use Mail;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::post('/webhook', [RunController::class, '__invoke']);


Route::get('login', [AuthController::class, 'showLogin']);
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::get('order', [OrderController::class, 'index'])->name('order.index');

Route::get('client', [ClientController::class, 'index'])->name('client.index');

//Route::get('send', function() {
//    $details = [
//        'full_name' => 'Maxmudov Ismoil',
//        'address' => "Yashnobod Olmos 12/23 test",
//        'phone' => 901234567,
//    ];
//    \Illuminate\Support\Facades\Mail::to('I.Maxmudov@etc.uz')->send(new \App\Mail\OrderShipped($details));
//    dd('ok');
//});
