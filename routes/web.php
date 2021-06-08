<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\TaskController;

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

Route::get('/', [TaskController::class, 'index'])->name('index');
Route::post('/store', [TaskController::class, 'store'])->name('task.store');
Route::get('/update', [TaskController::class, 'updateStatus'])->name('task.update');



