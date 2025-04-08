<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Editor de Newsletter - GrapesJS</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- CSS GrapesJS -->
  <link href="{{ asset('vendor/grapesjs/css/grapes.min.css') }}" rel="stylesheet" />

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />

  <style>
    html, body {
      margin: 0;
      padding: 0;
      height: 100%;
    }
    #gjs {
      height: 100vh;
      border: 3px solid #444;
    }
  </style>
</head>
<body>

  <div id="gjs">
  </div>
  <button onclick="salvarTemplate()">💾 Salvar</button>
  <button onclick="carregarTemplate('pagina-home')">📂 Carregar</button>  

  <!-- ✅ JS do GrapesJS -->
  <script src="{{ asset('vendor/grapesjs/js/grapes.min.js') }}"></script>
  <script src="{{ asset('vendor/grapesjs/js/grapesjs-preset-webpage.min.js') }}"></script>
  <script src="{{ asset('vendor/grapesjs/js/grapesjs-plugin-forms.min.js') }}"></script>
  <script src="{{ asset('vendor/grapesjs/js/grapesjs-custom-code.min.js') }}"></script>
  <script src="{{ asset('vendor/grapesjs/js/grapesjs-navbar.min.js') }}"></script>
  <script src="{{ asset('vendor/grapesjs/js/grapesjs-tabs.min.js') }}"></script>
  <script src="{{ asset('vendor/grapesjs/js/grapesjs-tooltip.min.js') }}"></script>
  <script src="{{ asset('vendor/grapesjs/js/grapesjs-touch.min.js') }}"></script>
  <script src="{{ asset('vendor/grapesjs/js/grapesjs-typed.min.js') }}"></script>
  <script src="{{ asset('vendor/grapesjs/js/grapesjs-style-bg.min.js') }}"></script>
  <script src="{{ asset('vendor/grapesjs/js/grapesjs-preset-newsletter.min.js') }}"></script>
  <script src="{{ asset('vendor/grapesjs/js/grapesjs-custom-block.js') }}"></script>

  <script>
    const salvarTemplate = () => {
      const htmlLimpo = getCleanHtml();

      fetch('http://127.0.0.1:8000/salvar-template', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
          title: 'pagina-home',
          html: htmlLimpo,
          css: editor.getCss(),
          gjs_json: JSON.stringify(editor.getProjectData())
        })
      })
      .then(async res => {
        if (!res.ok) {
          const erroTexto = await res.text(); // captura conteúdo da resposta
          throw new Error(`Erro HTTP ${res.status}: ${erroTexto.slice(0, 500)}`);
        }
        return res.json();
      })
      .then(data => {
        console.log('✅ Template salvo com sucesso:', data);
        alert('✅ Template salvo com sucesso!');
      })
      .catch(err => {
        console.error("❌ Erro ao salvar:", err);
        alert('❌ Erro ao salvar template.');
      });
    };
  
    const carregarTemplate = (nome) => {
      fetch('http://127.0.0.1:8000/get-template/pagina-home')
      .then(async res => {
        if (!res.ok) {
          const html = await res.text();
          throw new Error(`Resposta inesperada: ${html.slice(0, 200)}`);
        }
        return res.json();
      })
      .then(data => {
        if (!data.gjs_json) {
          throw new Error("Campo 'gjs_json' não recebido ou vazio.");
        }

        editor.setComponents(data.html || '');
        editor.setStyle(data.css || '');
        editor.loadProjectData(JSON.parse(data.gjs_json));
      })
      .catch(err => console.error("❌ Erro ao carregar:", err));
    };
  
    const getCleanHtml = () => {
      const wrapper = editor.getWrapper();
      const sqlContainers = wrapper.find('[data-func^="sql:"]');
      sqlContainers.forEach(c => c.components('<p>Carregando dados...</p>'));
      return editor.getHtml();
    };
  
    window.onload = () => {
      window.editor = grapesjs.init({
        height: '100%',
        storageManager: false,
        container: '#gjs',
        fromElement: true,
        plugins: ['gjs-preset-newsletter', 
        'grapesjs-preset-webpage', 
        'grapesjs-plugin-forms', 
        'grapesjs-custom-code', 
        'grapesjs-navbar', 
        'grapesjs-tabs', 
        'grapesjs-tooltip', 
        'grapesjs-touch', 
        'grapesjs-typed', 
        'grapesjs-style-bg',
        'gjs-custom-blocks'],
        pluginsOpts: {
          'grapesjs-preset-newsletter': {
            modalLabelImport: 'Paste all your code here below and click import',
            modalLabelExport: 'Copy the code and use it wherever you want',
            importPlaceholder: '<table class="table"><tr><td class="cell">Hello world!</td></tr></table>',
            cellStyle: {
              'font-size': '12px',
              'font-weight': 300,
              'vertical-align': 'top',
              color: 'rgb(111, 119, 125)',
              margin: 0,
              padding: 0,
            }
          },
          inlineCss: true,
          codeViewerTheme: 'material'                
        },
      });

      let carregarTimeout = null;
      const carregarDadosDebounced = () => {
        clearTimeout(carregarTimeout);
        carregarTimeout = setTimeout(carregarDados, 100);
      };
  
      const carregarDados = () => {
        const wrapper = editor.getWrapper();
        if (!wrapper) {
          console.error("❌ Wrapper ainda não está disponível.");
          return;
        }

        const sqlContainers = wrapper.find('[data-func^="sql:"]');
        sqlContainers.forEach(container => {
          const funcValue = container.getAttributes()['data-func'];
          const [, tipo] = funcValue.split(':');

          fetch(`http://localhost:8000/exibir_dados.php?tipo=${tipo}`)
            .then(r => r.json())
            .then(data => {
              let html = "";
              data.forEach(item => html += `<p>ID: ${item.registro}</p>`);
              container.components(html);
            })
            .catch(err => console.error(`❌ Erro no tipo [${tipo}]:`, err));
        });
      };
  
      editor.on('load', carregarDadosDebounced);

      editor.on('component:add', component => {
        const func = component.getAttributes()['data-func'];
        if (func?.startsWith('sql:')) carregarDadosDebounced();
      });

      editor.DomComponents.addType('sql-componente', {
        model: {
          defaults: {
            tagName: 'div',
            droppable: true,
            editable: false,
            attributes: { class: 'sql-bloco' },
          },
          init() {
            this.on('change', () => {
              const attr = this.getAttributes()['data-func'];
              if (attr?.startsWith('sql:')) carregarDadosDebounced();
            });
          },
          afterInit() {
            const attr = this.getAttributes()['data-func'];
            if (attr?.startsWith('sql:')) carregarDadosDebounced();
          }
        }
      });
  
      // ✅ Carregar template ao iniciar
      carregarTemplate('pagina-home');
    };
  </script>
  
</body>
</html>
