<?php

use App\Http\Controllers\APIController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/{sensor:plant_name}', [APIController::class, 'show']);
Route::get('/{sensor:plant_name}/set_point', [APIController::class, 'set_point']);
Route::get('/{sensor:plant_name}/temperature', [APIController::class, 'get_temperature']);
Route::get('/{sensor:plant_name}/vibration', [APIController::class, 'get_vibration']);
Route::get('/{sensor:plant_name}/current', [APIController::class, 'get_current']);

Route::post('temperature', [APIController::class, 'post_temperature']);
Route::post('vibration', [APIController::class, 'post_vibration']);
Route::post('current', [APIController::class, 'post_current']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
