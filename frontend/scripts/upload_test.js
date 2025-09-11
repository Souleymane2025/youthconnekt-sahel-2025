const axios = require('axios');
const FormData = require('form-data');
const fs = require('fs');
const path = require('path');

(async () => {
  try {
    const filePath = path.resolve(__dirname, '..', 'public', 'tmp', 'dummy.png');
    if (!fs.existsSync(filePath)) {
      console.error('File not found:', filePath);
      process.exit(2);
    }

    const form = new FormData();
    form.append('firstName', 'FileTest');
    form.append('lastName', 'User');
    form.append('email', 'filetest+2025@example.com');
    form.append('country', 'TestLand');
    form.append('city', 'TST');
    form.append('type', 'participant');
  // multer in the frontend expects 'passportImage' (see registration form and router)
  form.append('passportImage', fs.createReadStream(filePath));

    const url = (process.env.FRONTEND_BASE_URL || 'http://localhost:3000') + '/participants/register';
    console.log('Posting to', url, 'with file', filePath);

    const resp = await axios.post(url, form, { headers: form.getHeaders(), maxContentLength: Infinity, maxBodyLength: Infinity, timeout: 15000 });
    console.log('POST status', resp.status);
    console.log('Response data:', resp.data);
  } catch (err) {
    if (err && err.response) {
      console.error('Upload failed, status', err.response.status, 'data', err.response.data);
    } else {
      console.error('Upload error', err.message || err);
    }
    process.exit(1);
  }
})();
