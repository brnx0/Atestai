<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Projects\ProjectsController;
use App\Http\Controllers\Projects\SprintsController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Index');
    })->middleware('auth')->name('index');


Route::middleware('auth')->group(function () {
    Route::get('/perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/perfil', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/perfil', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware('auth')->group(function (){
    Route::get('/projetos', [ProjectsController::class, 'index'])->name('projects.index');
});

// Route::get('/sprint/{sprintCod}', [SprintsController::class, 'createArquivo'])->name('sprint.make');
require __DIR__.'/auth.php';
