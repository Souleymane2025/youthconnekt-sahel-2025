const ejs = require('ejs');
const fs = require('fs');
const path = require('path');

const file = path.join(__dirname, 'views', 'pages', 'registration.ejs');
const outHtml = path.join(__dirname, 'debug_render_output.html');
const outErr = path.join(__dirname, 'debug_render_error.txt');

try {
  const src = fs.readFileSync(file, 'utf8');
  console.log('SRC_LEN', src.length);
  console.log('SRC_PREVIEW', src.slice(0,200));
} catch (e) {
  console.error('READ_ERR', e.message);
  fs.writeFileSync(outErr, 'READ_ERR\n' + String(e.stack));
  process.exit(1);
}

try {
  // Simple literal render test
  const literal = ejs.render('<p>hello-ejs</p>');
  console.log('LITERAL_RENDER:', literal.slice(0,50));
} catch (e) {
  console.error('LITERAL_ERR', e.message);
  fs.appendFileSync(outErr, '\nLITERAL_ERR\n' + String(e.stack));
}

// Render file with filename option so includes work
ejs.renderFile(file, { title: 'Debug', page: 'registration' }, { filename: file }, (err, str) => {
  if (err) {
    console.error('RENDER_ERR', err && err.stack ? err.stack : err);
    fs.writeFileSync(outErr, 'RENDER_ERR\n' + (err && err.stack ? err.stack : String(err)));
    process.exit(1);
  }
  fs.writeFileSync(outHtml, str, 'utf8');
  console.log('OUT_LEN', Buffer.byteLength(str, 'utf8'));
  console.log('OUT_PREVIEW', str.slice(0,500));
});
