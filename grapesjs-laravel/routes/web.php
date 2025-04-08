<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GrapesEditorController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/editor', [GrapesEditorController::class, 'index']);
Route::post('/salvar-template', [GrapesEditorController::class, 'salvarTemplate']);
Route::get('/get-template/{title}', [GrapesEditorController::class, 'carregar']);
