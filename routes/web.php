<?php

use App\Geo;
use App\Temperatura;
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
    // Artisan::call('cache:clear');
    // Artisan::call('config:clear');
    // Artisan::call('config:cache');
    // Artisan::call('storage:link');
    // Artisan::call('key:generate');
    // Artisan::call('migrate:fresh --seed');
});


Route::get('/migeo', function () {
    $geo=Geo::first();
    $data = array('geo' => $geo );
    return view('migeo',$data);
    // Artisan::call('cache:clear');
    // Artisan::call('config:clear');
    // Artisan::call('config:cache');
    // Artisan::call('storage:link');
    // Artisan::call('key:generate');
    // Artisan::call('migrate:fresh --seed');
});
Route::get('/obtener-lat-lng', 'Estaticas@obtenerLatitudLongitud')->name('obtenerLatLng');
Route::group(['middleware' => 'cors'], function()
{
    Route::get('/ingresar-datos/{tem}/{pul}/{lat}/{lng}', 'Estaticas@ingresarDatos')->name('ingresarDatos');
});


Auth::routes(['register' => false]);

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/temperaturas', 'HomeController@obtenerTemperatura')->name('temperaturas');

Route::get('/pulsos', 'HomeController@obtenerPulsos')->name('pulsos');




