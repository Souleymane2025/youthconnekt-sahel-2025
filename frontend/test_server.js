const http = require('http');
const PORT = process.env.PORT || 3000;

const server = http.createServer((req, res) => {
  res.writeHead(200, {'Content-Type': 'text/plain'});
  res.end('test server running');
});

server.listen(PORT, () => {
  console.log(`TEST SERVER listening on port ${PORT}`);
});

process.on('SIGINT', () => {
  console.log('SIGINT received, closing test server');
  server.close(() => process.exit(0));
});

process.on('uncaughtException', (err) => {
  console.error('UNCAUGHT EXCEPTION', err);
});

setInterval(() => {}, 1000); // keep event loop busy
