const ejs = require('ejs');
const path = require('path');
const fs = require('fs');

const file = path.join(__dirname, 'views', 'pages', 'registration.ejs');

ejs.renderFile(file, { title: 'Test', page: 'registration' }, {}, function(err, str) {
  if (err) {
    console.error('ERR', err);
    fs.writeFileSync(path.join(__dirname, 'render_error.txt'), String(err.stack || err));
    process.exit(1);
  }
  fs.writeFileSync(path.join(__dirname, 'render_output.html'), str);
  console.log('RENDER_LEN:' + Buffer.byteLength(str, 'utf8'));
  console.log('RENDER_PREVIEW:\n' + str.slice(0,500));
});
