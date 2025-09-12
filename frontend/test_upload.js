const axios = require('axios');
const FormData = require('form-data');
const fs = require('fs');

async function run(){
  const form = new FormData();
  // Personal
  form.append('firstName', 'NodeUpload');
  form.append('lastName', 'Test');
  form.append('email', 'nodeupload@example.com');
  form.append('whatsapp', '+221770000000');
  form.append('handicap', 'none');

  // Location / registration
  form.append('country', 'tchad');
  form.append('city', "N'Djamena");
  form.append('registrationType', 'national');
  form.append('province', 'N\'Djamena');

  // Files (use placeholders created in public/uploads)
  form.append('passportImage', fs.createReadStream('./public/uploads/passport-test.png'));
  form.append('cinImage', fs.createReadStream('./public/uploads/cin-test.png'));

  try{
    const res = await axios.post('http://localhost:3000/participants/register', form, { headers: form.getHeaders(), maxContentLength: Infinity, maxBodyLength: Infinity });
    console.log('STATUS', res.status);
    console.log('DATA', res.data);
  }catch(e){
    console.error('ERR', e && e.response ? (e.response.data || e.response.statusText) : e.message);
  }
}

run();
