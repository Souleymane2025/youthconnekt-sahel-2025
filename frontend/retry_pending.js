const axios = require('axios');
(async ()=>{
  try{
    const res = await axios.post('http://localhost:3000/admin/retry-pending?secret=dev_secret');
    console.log('RETRY', res.data);
  }catch(e){
    console.error('ERR', e && e.response ? e.response.data : e.message);
  }
})();
