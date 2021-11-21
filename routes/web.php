<?php

use App\Http\Controllers\RelayController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\TargetController;
use App\Http\Controllers\TemperatureController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
  return view('home');
});

Route::get('/relay', [RelayController::class, 'getRelayStatus']);
Route::get('/relay/all', [RelayController::class, 'getRelayStatuses']);

Route::get('/status', [StatusController::class, 'getStatus']);
Route::post('/status', [StatusController::class, 'setStatus']);
Route::get('/status/all', [StatusController::class, 'getStatuses']);

Route::get('/target', [TargetController::class, 'getTarget']);
Route::post('/target', [TargetController::class, 'setTarget']);

Route::get('/temperature', [TemperatureController::class, 'getTemperature']);
Route::post('/temperature', [TemperatureController::class, 'create']);
Route::get('/temperature/all', [TemperatureController::class, 'getTemperatures']);