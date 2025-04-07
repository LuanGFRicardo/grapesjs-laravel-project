<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Editor de Newsletter - GrapesJS</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <!-- CSS GrapesJS -->
  <link href="{{ asset('vendor/grapesjs/css/grapes.min.css') }}" rel="stylesheet" />
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
    {{-- <div id="gjs">
      <div data-func="sql:registro"><p>Carregando dados...</p></div>
    </div> --}}
  </div>

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

        const carregarDados = () => {
          console.log("🔄 Recarregando dados SQL...");

          const wrapper = editor.getWrapper();
          const sqlContainers = wrapper.find('[data-func^="sql:"]');

          if (!sqlContainers.length) {
              console.warn("⚠️ Nenhum componente SQL encontrado.");
              return;
          }

          sqlContainers.forEach(container => {
              const funcValue = container.getAttributes()['data-func'];
              const [, tipo] = funcValue.split(':'); // exemplo: 'sql:registro' => tipo = 'registro'

              // Exemplo: você pode usar diferentes rotas ou lógica com base no tipo
              fetch(`http://localhost:8000/exibir_dados.php?tipo=${tipo}`)
                  .then(response => response.json())
                  .then(data => {
                      let html = "";
                      data.forEach(item => {
                          html += `<p>ID: ${item.registro}</p>`;
                      });

                      container.components(html);
                      console.log(`✅ Dados [${tipo}] atualizados com sucesso.`);
                  })
                  .catch(err => console.error(`❌ Erro ao carregar dados do tipo [${tipo}]:`, err));
          });
      };

      // ✅ Após carregar o editor
      editor.on('load', carregarDados);

      // ✅ Sempre que um novo componente for adicionado
      editor.on('component:add', component => {
        const attrs = component.getAttributes();
        const func = attrs['data-func'];

        if (func && func.startsWith('sql:')) {
            console.log(`🔍 Componente SQL detectado (${func}). Recarregando dados...`);
            carregarDados();
        }
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
                    if (attr?.startsWith('sql:')) {
                        console.log(`🧠 Componente SQL (${attr}) adicionado ou alterado`);
                        carregarDados();
                    }
                });
            },

            // Alternativamente, se quiser rodar logo ao ser adicionado:
            afterInit() {
                const attr = this.getAttributes()['data-func'];
                if (attr?.startsWith('sql:')) {
                    console.log(`🚀 Componente SQL (${attr}) inicializado`);
                    carregarDados();
                }
            }
        }
    });
  };
  </script>
</body>
</html>
