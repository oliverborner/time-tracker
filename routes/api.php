<?php
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TimeEntryController;
use App\Http\Controllers\MonthClosureController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::post('/login', [AuthController::class, 'login']);

// Authenticated user info
Route::middleware('auth:sanctum')->get('/user', [AuthController::class, 'me']);


Route::get('/projects', [ProjectController::class, 'index']);
 
Route::middleware('auth:sanctum')->group(function () {
    //Route::get('/projects', [ProjectController::class, 'index']);
    Route::post('/projects', [ProjectController::class, 'store']);
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy']);
});


Route::middleware('auth:sanctum')->group(function(){

    // Time Entries
    Route::get('/projects/{project}/entries', [TimeEntryController::class,'index']);
    Route::post('/projects/{project}/entries', [TimeEntryController::class, 'store']);
    Route::put('/entries/{entry}', [TimeEntryController::class,'update']);
    Route::delete('/entries/{entry}', [TimeEntryController::class,'destroy']);

    // Month Closures
    Route::post('/month-closures', [MonthClosureController::class, 'store']);
    Route::get('/month-closures', [MonthClosureController::class, 'index']); 
    Route::get('/month-closures/{id}', [MonthClosureController::class, 'show']); 
  });




