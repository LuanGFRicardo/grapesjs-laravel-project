<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Selecionar Template</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
  <div class="container mt-5">
    <h2 class="mb-4">Selecione um Template para Editar</h2>

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
  </div>

  <script>
    const form = document.getElementById('templateForm');
    const select = document.getElementById('templateSelect');

    form.addEventListener('submit', function(e) {
      e.preventDefault();
      const nome = select.value;
      if (!nome) {
        alert("Selecione um template!");
        return;
      }
      window.location.href = `/editor/${encodeURIComponent(nome)}`;
    });
  </script>
</body>
</html>
