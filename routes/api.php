<?php

use App\Http\Controllers\API\MotorController;
use App\Http\Controllers\API\PanelController;
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

Route::get('panel_sensor', [PanelController::class, 'index']);
Route::get('panel_sensor/{sensor:plant_name}', [PanelController::class, 'show']);
Route::get('panel_sensor/{sensor:plant_name}/temperature', [PanelController::class, 'get_temperature']);
Route::post('panel_sensor/temperature', [PanelController::class, 'post_temperature']);
Route::get('panel_sensor/{sensor:plant_name}/monitoring', [PanelController::class, 'sensor_monitoring']);

Route::get('motor_sensor', [MotorController::class, 'index']);
Route::get('motor_sensor/{sensor:plant_name}', [MotorController::class, 'show']);
Route::get('motor_sensor/{sensor:plant_name}/temperature', [MotorController::class, 'get_temperature']);
Route::post('motor_sensor/temperature', [MotorController::class, 'post_temperature']);
Route::get('motor_sensor/{sensor:plant_name}/vibration', [MotorController::class, 'get_vibration']);
Route::post('motor_sensor/vibration', [MotorController::class, 'post_vibration']);
Route::get('motor_sensor/{sensor:plant_name}/current', [MotorController::class, 'get_current']);
Route::post('motor_sensor/current', [MotorController::class, 'post_current']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
