<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Selecionar Template</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
  <div class="container mt-5">
    <h2 class="mb-4">Selecione um Template para Editar</h2>
  
    {{-- Formulário para editar um template existente --}}
    <form id="templateForm" method="GET" action="">
      <div class="row g-3 align-items-center">
        <div class="col-auto">
          <select id="templateSelect" class="form-select" name="template">
            <option value="">-- Escolha um template --</option>
            @foreach ($templates as $template)
              <option value="{{ $template->nome }}">{{ $template->nome }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-auto">
          <button type="submit" class="btn btn-primary">Editar</button>
        </div>
      </div>
    </form>
  
    <hr class="my-4">
  
    {{-- Formulário para criar um novo template --}}
    <h4>Criar Novo Template</h4>
    <form id="createTemplateForm">
      <div class="row g-3 align-items-center">
        <div class="col-auto">
          <input type="text" id="novoTemplateNome" class="form-control" placeholder="Nome do novo template" required>
        </div>
        <div class="col-auto">
          <button type="submit" class="btn btn-success">Criar Template</button>
        </div>
      </div>
    </form>
  </div>
  
  <script>
    // Redirecionar para editor ao selecionar template existente
    document.getElementById('templateForm').addEventListener('submit', function(e) {
      e.preventDefault();
      const nome = document.getElementById('templateSelect').value;
      if (!nome) return alert("Selecione um template!");
      window.location.href = `/editor/${encodeURIComponent(nome)}`;
    });
  
    // Criar novo template
    document.getElementById('createTemplateForm').addEventListener('submit', async function(e) {
      e.preventDefault();
      const nome = document.getElementById('novoTemplateNome').value.trim();
  
      if (!nome) return alert("Digite um nome para o novo template!");
  
      try {
        const res = await fetch('/criar-template', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({ nome })
        });
  
        if (!res.ok) throw new Error("Erro ao criar template");
  
        const data = await res.json();
        window.location.href = `/editor/${encodeURIComponent(nome)}`;
      } catch (err) {
        console.error(err);
        alert('Erro ao criar template!');
      }
    });
  </script>  
</body>
</html>
