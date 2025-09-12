const http = require('http');
const urls = ['http://127.0.0.1:8000/api/healthz','http://127.0.0.1:8000/api/participants'];
(async ()=>{
  for (const u of urls){
    console.log('GET',u);
    await new Promise(res=>{
      http.get(u, r=>{
        let b=''; r.on('data',c=>b+=c); r.on('end',()=>{ console.log(b); res(); });
      }).on('error',e=>{ console.error('ERR',e.message); res(); });
    });
  }
})();
