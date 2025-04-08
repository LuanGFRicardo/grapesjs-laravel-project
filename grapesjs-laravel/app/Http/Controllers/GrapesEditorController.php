<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Page;

class GrapesEditorController extends Controller
{
    public function index($template)
    {
        return view('grapes-editor', ['template' => $template]);
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

    public function menu()
    {
        $templates = Page::all(['id', 'nome']);
        return view('grapes-editor-menu', compact('templates'));
    }

    public function criarTemplate(Request $request)
    {
        $nome = trim($request->input('nome'));
    
        if (!$nome) {
            return response()->json(['error' => 'Nome inválido'], 400);
        }
    
        if (Page::where('nome', $nome)->exists()) {
            return response()->json(['error' => 'Template já existe'], 400);
        }
    
        $htmlPadrao = '<div class="container"><h1>Novo Template Criado</h1><p>Comece aqui...</p></div>';
    
        $gjsJson = json_encode([
            'assets' => [],
            'styles' => [],
            'pages' => [
                [
                    'name' => $nome,
                    'styles' => [],
                    'frames' => [[
                        'component' => [
                            'tagName' => 'div',
                            'components' => [
                                ['type' => 'text', 'content' => 'Novo template']
                            ]
                        ]
                    ]]
                ]
            ]
        ], JSON_UNESCAPED_UNICODE);
    
        Page::create([
            'nome' => $nome,
            'html' => $htmlPadrao,
            'projeto' => $gjsJson
        ]);
    
        return response()->json(['success' => true, 'nome' => $nome]);
    }
}
