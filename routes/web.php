<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MMovieController;
use App\Http\Controllers\UUserController;

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

Route::get('/movies', [MMovieController::class, 'search_movie_by_keyword']);
Route::get('/movies/{movie_id}', [MMovieController::class, 'search_movie_by_id']);

Route::get('/favorites', [UUserController::class, 'get_movies_marked_as_favorite']);
Route::post('/favorites', [UUserController::class, 'mark_as_favorite_movie']);

Route::post('/movies/add', [MMovieController::class, 'add_movie_master_entity']);
Route::post('/users/register', [UUserController::class, 'register_user_entity']);


