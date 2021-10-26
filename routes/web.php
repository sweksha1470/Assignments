<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestQueueEmails;
use App\Http\Controllers\DateRangeFilterController;
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
Route::get('/dashboard',[DateRangeFilterController::class,'index'])->middleware(['auth'])->name('dashboard');
Route::post('/daterange/fetch_data', [DateRangeFilterController::class,'fetch_data'])->middleware(['auth'])->name('daterange.fetch_data');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth'])->name('dashboard');
Route::post('sending-queue-emails',[TestQueueEmails::class,'sendTestEmails'])->name('sendingQueueEmails');
Route::get('email',[TestQueueEmails::class,'sendEmail']);
require __DIR__.'/auth.php';
