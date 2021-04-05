<?php

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

Route::get("/", function () {
    return view('main');
});

Route::get("/department", "App\Http\Controllers\DepartmentController@index");
Route::get("/department/create", "App\Http\Controllers\DepartmentController@create");
Route::post("/department/store", "App\Http\Controllers\DepartmentController@store");
Route::get("/department/edit/{dept}", "App\Http\Controllers\DepartmentController@edit");
Route::post("/department/update", "App\Http\Controllers\DepartmentController@update");
Route::delete("/department/delete", "App\Http\Controllers\DepartmentController@delete");

Route::get("/position", "App\Http\Controllers\PositionController@index");
Route::get("/position/create", "App\Http\Controllers\PositionController@create");
Route::post("/position/store", "App\Http\Controllers\PositionController@store");
Route::get("/position/edit/{pos}", "App\Http\Controllers\PositionController@edit");
Route::post("/position/update", "App\Http\Controllers\PositionController@update");
Route::delete("/position/delete", "App\Http\Controllers\PositionController@delete");

Route::get("/admin", "App\Http\Controllers\UserController@admin");
Route::get("/manager", "App\Http\Controllers\UserController@manager");
Route::get("/user/edit/{user}", "App\Http\Controllers\UserController@edit");
Route::post("/user/update", "App\Http\Controllers\UserController@update");


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
