<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->group(function() {
    Route::get('v2/usuarios', [UserController::class, 'showUsers']);

    //ANIMAL

    Route::post('v2/ingresar-cantones',[ UserController::class,'ingresarCanton']);
    Route::post('v2/cantones-update/{id}',[ UserController::class,'updateCanton']);
    Route::get('v2/cantones-edit/{id}',[ UserController::class,'editCanton']);
    Route::post('v2/cantones-destroy/{id}',[ UserController::class,'destroyCanton']);
    Route::get('v2/cantones-show',[ UserController::class,'showCanton']);  // muestra todos los animales
    /* -------- */
    Route::get('v2/tipos-show',[ UserController::class,'showTipos']);  // muestra todos los tipos
});

Route::post('v2/register', [UserController::class, 'register']);

Route::post('v2/login', [UserController::class, 'login']);
