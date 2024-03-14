<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppController;
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


// Route::get('/dashboard', function () {
//     return view('admin.index');
// });

Route::get('/api/users', [UserController::class,'index'])->name('getUser');
Route::post('/api/users', [UserController::class,'store'])->name('insertUser');
Route::get('{view}', AppController::class)->where('view','(.*)');