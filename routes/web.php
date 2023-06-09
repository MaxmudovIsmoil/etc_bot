<?php

use App\Jobs\SendMailJob;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller\RunController;
use App\Http\Controllers\Controller\ApiController;
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


Route::get('login', [ApiController::class, 'login']);

Route::get('info', [ApiController::class, 'info']);


Route::get('send', function() {
    $details = [
        'full_name' => 'Ismoil',
        'address' => "Yashnobod tuzel 12/23",
        'phone' => 901234567,
    ];

    \Illuminate\Support\Facades\Mail::to('sales@gmail.com')->send(new \App\Mail\OrderShipped($details));
    dd('okey');
});
