const express = require('express');
const app = express();
const path = require('path');

app.use((req, res, next) => {
    res.setHeader('Access-Control-Allow-Origin', '*');
    res.setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE');
    res.setHeader('Access-Control-Allow-Headers', 'Content-Type');
    next();
  });

app.use(express.static(path.join(__dirname, 'assets')));

const PORT = 8081;
app.listen(PORT, () => {
    console.log(`Servidor de assets rodando em http://localhost:${PORT}/assets`);
});
