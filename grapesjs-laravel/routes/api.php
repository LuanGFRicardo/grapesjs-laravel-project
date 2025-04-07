<?php

use Illuminate\Support\Facades\Route;

Route::post('/pages', [GrapesEditorController::class, 'store']);
Route::get('/pages/{id}', [GrapesEditorController::class, 'show']);
Route::put('/pages/{id}', [GrapesEditorController::class, 'update']);
