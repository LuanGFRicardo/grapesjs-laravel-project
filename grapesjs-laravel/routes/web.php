<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GrapesEditorController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/editor', [GrapesEditorController::class, 'index']);

