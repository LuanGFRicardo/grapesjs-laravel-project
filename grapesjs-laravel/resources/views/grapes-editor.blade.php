<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Editor de Newsletter - GrapesJS</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <!-- CSS GrapesJS -->
  <link href="https://unpkg.com/grapesjs/dist/css/grapes.min.css" rel="stylesheet"/>
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

  <div id="gjs"></div>

  <!-- ✅ JS do GrapesJS -->
  <script src="https://unpkg.com/grapesjs"></script>

  <!-- ✅ Plugin preset-newsletter: CUIDADO COM A ORDEM -->
  <script src="https://unpkg.com/grapesjs-preset-newsletter@0.2.1/dist/grapesjs-preset-newsletter.min.js"></script>

  <script>
    const editor = grapesjs.init({
      container: '#gjs',
      height: '100%',
      fromElement: false,
      plugins: ['gjs-preset-newsletter'],
      pluginsOpts: {
        'gjs-preset-newsletter': {
          modalTitleImport: 'Importar HTML',
          inlineCss: true,
          codeViewerTheme: 'material',
        }
      }
    });

    // Corrige atributos inválidos que causam o InvalidCharacterError
    editor.on('component:add', component => {
      sanitizeAttributes(component);
    });

    editor.on('component:update', component => {
      sanitizeAttributes(component);
    });

    function sanitizeAttributes(component) {
      const attrs = component.getAttributes();
      const validAttrName = /^[a-zA-Z_:][a-zA-Z0-9_:.-]*$/;

      Object.keys(attrs).forEach(attr => {
        if (!validAttrName.test(attr)) {
          component.removeAttributes([attr]);
        }
      });
    }
  </script>

</body>
</html>
