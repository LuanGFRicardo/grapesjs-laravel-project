<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GrapesEditorController extends Controller
{
    public function index()
    {
        return view('grapes-editor');
    }
}
