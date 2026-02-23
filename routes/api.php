<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get("/test-me", function (){
    return "Hello from Laravel";
});

use App\Http\Controllers\CalculationController;

Route::get('/calculations', [CalculationController::class, 'index']);
Route::post('/calculations', [CalculationController::class, 'store']);
Route::delete('/calculations/{calculation}', [CalculationController::class, 'destroy']);
Route::delete('/calculations', [CalculationController::class, 'destroyAll']);
