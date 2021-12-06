<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PemasukkanController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\PengeluaranController;

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

// Route::get('/', function () {
//     return view('login');
// });

// route::get('/',[LoginController::class,'index']);

// Route::get('/dashboard', function () {
//     return view('dashboard');
// });

// Route::get('/register', function () {
//     return view('register');
// });

// Route::get('/pengeluaran', function () {
//     return view('pengeluaran');
// });

// Route::get('{data_pengeluaran}', [PengeluaranController::class, 'index']);

// Route::group(['namespace' => 'App\Http\Controllers'],function(){
//     Route::get('/','LoginController@show')->name('login');

//     Route::group(['middleware' =>['guest']],function(){
            
//         Route::get('/register', 'RegisterController@show')->name('register.show');
//         Route::post('/register', 'RegisterController@register')->name('register.perform');

//         Route::get('/login', 'LoginController@show')->name('login.show');
//         Route::post('/login', 'LoginController@login')->name('login.perform');

//     });
// });

Route::get('register', [RegisterController::class, 'create'])->name('register');

Route::post('register', [RegisterController::class, 'store'])->name('register');

Route::get('/', [LoginController::class, 'create'])->name('login');
Route::post('/', [LoginController::class, 'postLogin'])->name('register');
Route::get('logout', [LoginController::class, 'logout'])->name('logout');


Route::group(['middleware' => ['auth']], function(){
    Route::get('/dashboard',[PengeluaranController::class, 'index'])->name('dashboard');

    //Pengeluaran
    Route::get('pengeluaran', [PengeluaranController::class, 'index'])->name('pengeluaran');
    Route::get('searchpengeluaran', [PengeluaranController::class, 'searchPengeluaran'])->name('searchpengeluaran');
    Route::get('exportexcel', [PengeluaranController::class, 'exportExcel'])->name('exportexcel');

    //Pemasukkan
    Route::get('pemasukan', [PemasukkanController::class, 'index'])->name('pemasukkan');
    Route::get('searchpemasukkan', [PemasukkanController::class, 'searchPemasukan'])->name('searchpemasukkan');
});