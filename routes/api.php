<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\StatusesController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//mendapatkan semua jenis route api/resource dari patient controller dan model
Route::apiResource('patient', PatientController::class);

//mendapatkan semua jenis route api/resource dari statuses controller dan model
Route::apiResource('statuses', StatusesController::class);


//route pencarian nama
Route::get('/search/{request}', [PatientController::class, 'searchByName']);

//route pencarian positive covid
Route::get('/positive', [PatientController::class, 'searchByPositive']);

//route pencarian recovery
Route::get('/recovery', [PatientController::class, 'searchByRecovery']);

//route pencarian kematian akibat covid
Route::get('/death', [PatientController::class, 'searchByDeath']);