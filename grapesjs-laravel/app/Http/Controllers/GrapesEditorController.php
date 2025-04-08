<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Page;

class GrapesEditorController extends Controller
{
    public function index()
    {
        return view('grapes-editor');
    }

    public function salvarTemplate(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'html' => 'nullable|string',
                'css' => 'nullable|string',
                'gjs_json' => 'nullable|string',
            ]);
    
            \Log::info('Dados validados:', $validated);
    
            Page::updateOrCreate(
                ['nome' => $validated['title']],
                [
                    'html' => $validated['html'] ?? '',
                    'projeto' => $validated['gjs_json'] ?? ''
                ]
            );            
    
            return response()->json(['success' => true]);
        } catch (\Illuminate\Validation\ValidationException $ve) {
            \Log::error('Erro de validação ao salvar template:', $ve->errors());
            return response()->json(['error' => 'Erro de validação.', 'details' => $ve->errors()], 422);
        } catch (\Exception $e) {
            \Log::error('Erro ao salvar template:', ['erro' => $e->getMessage()]);
            return response()->json(['error' => 'Erro ao salvar template.'], 500);
        }
    }    

    public function carregar($title)
    {
        $page = Page::where('nome', $title)->first();

        if (!$page) {
            return response()->json(['error' => 'Template não encontrado'], 404);
        }

        return response()->json([
            'html' => $page->html,
            'css' => $page->css,
            'gjs_json' => $page->projeto ?? '{}', // nome esperado pelo JS
        ]);
    }
}
