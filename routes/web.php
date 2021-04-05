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
Route::get("/department/edit/{id}", "App\Http\Controllers\DepartmentController@edit");
Route::post("/department/update/{id}", "App\Http\Controllers\DepartmentController@update");
Route::delete("/department/delete/{id}", "App\Http\Controllers\DepartmentController@delete");

Route::get("/position", "App\Http\Controllers\PositionController@index");
Route::get("/position/create", "App\Http\Controllers\PositionController@create");
Route::post("/position/store", "App\Http\Controllers\PositionController@store");
Route::get("/position/edit/{id}", "App\Http\Controllers\PositionController@edit");
Route::post("/position/update/{id}", "App\Http\Controllers\PositionController@update");
Route::delete("/position/delete/{id}", "App\Http\Controllers\PositionController@delete");

Route::get("/admin", "App\Http\Controllers\UserController@admin");
Route::get("/manager", "App\Http\Controllers\UserController@manager");
Route::get("/listManagers", "App\Http\Controllers\UserController@listManagers");
Route::get("/listUsers", "App\Http\Controllers\UserController@listUsers");
Route::get("/user/settings/{id}", "App\Http\Controllers\UserController@settings");
Route::post("/user/updatePersonalInformation/{id}", "App\Http\Controllers\UserController@updatePersonalInformation");
Route::get("/user/edit/{id}/{row_id}", "App\Http\Controllers\UserController@edit");
Route::post("/user/update/{id}/{row_id}", "App\Http\Controllers\UserController@update");
Route::delete("/user/delete/{id}", "App\Http\Controllers\UserController@delete");


Route::get('/dashboard', "App\Http\Controllers\UserController@dashboard")->name("dashboard");

require __DIR__.'/auth.php';
