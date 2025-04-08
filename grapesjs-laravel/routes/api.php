<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GrapesEditorController;

Route::post('/pages', [GrapesEditorController::class, 'store']);
Route::get('/pages/{id}', [GrapesEditorController::class, 'show']);
Route::put('/pages/{id}', [GrapesEditorController::class, 'update']);
Route::post('/salvar-template', [GrapesEditorController::class, 'salvarTemplate']);
Route::get('/get-template/{title}', [GrapesEditorController::class, 'carregar']);