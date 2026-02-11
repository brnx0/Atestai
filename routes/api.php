<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Projects\SprintsController;

Route::get('/sprints/{sprintCod}', [SprintsController::class, 'index'])->name('sprint.index');
Route::get('/user', [SprintsController::class, 'store'])->name('getUsuarios');




// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
