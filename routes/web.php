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

Route::get('/', function () {
    return view('welcome');
});

Route::get('chunk', 'ChunkController@index')->name('chunk.index');
Route::post('chunk', 'ChunkController@store')->name('chunk.store');

Route::get('custom', 'CustomChunkController@index')->name('custom.index');
Route::post('custom', 'CustomChunkController@store')->name('custom.store');

Route::resources([
    'upload' => 'UploadController',
]);