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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/data/getTotalByDistrict', [App\Http\Controllers\HomeController::class, 'getTotalByDistrict'])->name('getTotalGroupByDistrict');
Route::get('/data/getTotalBydoctrine', [App\Http\Controllers\HomeController::class, 'getTotalBydoctrine'])->name('getTotalBydoctrine');
Route::get('/data/getTotalBySex', [App\Http\Controllers\HomeController::class, 'getTotalBySex'])->name('getTotalBySex');
Route::get('/data/getTotalByCountry', [App\Http\Controllers\HomeController::class, 'getTotalByCountry'])->name('getTotalByCountry');
Route::get('/data/getDistrictCenters', [App\Http\Controllers\HomeController::class, 'getDistrictCenters'])->name('getDistrictCenters');



Route::get('/electors/get', [App\Http\Controllers\ElectorsController::class, 'get'])->name('get_electors');

Route::get('/electors', [App\Http\Controllers\ElectorsController::class, 'index'])->name('electors');
Route::get('/electors/edit/{id}', [App\Http\Controllers\ElectorsController::class, 'edit'])->name('edit_elector');
Route::post('/electors/edit/{id}', [App\Http\Controllers\ElectorsController::class, 'edit'])->name('edit_elector');
Route::post('/electors/saveElectionCenter', [App\Http\Controllers\ElectorsController::class, 'saveElectionCenter'])->name('saveElectionCenter');

Route::get('/ElectoralLists', [App\Http\Controllers\ElectoralListsController::class, 'index'])->name('electoral_lists');

Route::post('/ElectoralLists/add', [App\Http\Controllers\ElectoralListsController::class, 'add'])->name('add_electoral_list');
Route::get('/ElectoralLists/add', [App\Http\Controllers\ElectoralListsController::class, 'add'])->name('add_electoral_list');
Route::post('/ElectoralLists/edit/{id}', [App\Http\Controllers\ElectoralListsController::class, 'edit'])->name('edit_electoral_list');
Route::get('/ElectoralLists/edit/{id}', [App\Http\Controllers\ElectoralListsController::class, 'edit'])->name('edit_electoral_list');

Route::get('/ConcadidatesLists', [App\Http\Controllers\ConcadidatesController::class, 'index'])->name('concadidates_list');
Route::post('/Concadidates/add', [App\Http\Controllers\ConcadidatesController::class, 'add'])->name('add_concadidate');
Route::get('/Concadidates/add', [App\Http\Controllers\ConcadidatesController::class, 'add'])->name('add_concadidate');
Route::post('/Concadidates/edit/{id}', [App\Http\Controllers\ConcadidatesController::class, 'edit'])->name('edit_concadidate');
Route::get('/Concadidates/edit/{id}', [App\Http\Controllers\ConcadidatesController::class, 'edit'])->name('edit_concadidate');

Route::get('/UsersLists', [App\Http\Controllers\UsersController::class, 'list'])->name('users_list');
Route::post('/Users/add', [App\Http\Controllers\UsersController::class, 'add'])->name('add_user');
Route::get('/Users/add', [App\Http\Controllers\UsersController::class, 'add'])->name('add_user');
Route::post('/Users/edit/{id}', [App\Http\Controllers\UsersController::class, 'edit'])->name('edit_user');
Route::get('/Users/edit/{id}', [App\Http\Controllers\UsersController::class, 'edit'])->name('edit_user');

Route::get('/api/voters', [App\Http\Controllers\ApiController::class, 'voters'])->name('voters_api');
Route::get('/api/votersByTowns', [App\Http\Controllers\ApiController::class, 'votersByTowns'])->name('votersByTowns_api');

