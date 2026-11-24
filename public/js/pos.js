const CATS=["Makanan","Minuman","Kebutuhan Harian","Perlengkapan Mandi","Snack"],LOW=10;
const $=s=>document.querySelector(s),$$=s=>[...document.querySelectorAll(s)];
const rp=n=>"Rp "+Math.round(n).toLocaleString("id-ID");
const esc=s=>String(s).replace(/[&<>"]/g,c=>({"&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;"}[c]));
const dt=v=>new Date(v).toLocaleString("id-ID",{dateStyle:"medium",timeStyle:"short"});
const H={Accept:"application/json","Content-Type":"application/json","X-CSRF-TOKEN":$('meta[name=csrf-token]').content};
async function api(url,method="GET",body){
  const r=await fetch("/api/"+url,{method,headers:H,body:body?JSON.stringify(body):undefined});
  const j=await r.json().catch(()=>({}));
  if(r.status===401||r.status===419){location.href="/login";throw new Error("Sesi berakhir, silakan masuk kembali.")}
  if(!r.ok)throw new Error(j.errors?Object.values(j.errors)[0][0]:(j.message||"Terjadi kesalahan"));
  return j}
const tryDo=async f=>{try{return await f()}catch(e){alert(e.message)}};

let P=[],cart=[],cat="Semua",editing=null,last=null,shift=null;
const find=id=>P.find(p=>p.id===id);

/* ---------- paging (reusable) ---------- */
const PCFG={grid:{sizes:[24,48,96]},prod:{sizes:[10,25,50,100]},trx:{sizes:[10,25,50,100]},hist:{sizes:[10,25,50,100]}},PAGE={};
const pg=k=>PAGE[k]??={p:1,n:PCFG[k].sizes[0]};
function slice(k,a){const s=pg(k),pages=Math.max(1,Math.ceil(a.length/s.n));if(s.p>pages)s.p=pages;return a.slice((s.p-1)*s.n,s.p*s.n)}
function pageNums(p,t){if(t<=7)return Array.from({length:t},(_,i)=>i+1);const r=[1];if(p>3)r.push("…");for(let i=Math.max(2,p-1);i<=Math.min(t-1,p+1);i++)r.push(i);if(p<t-2)r.push("…");r.push(t);return r}
function mountBar(k,h,total,redraw){
  if(!total){h.innerHTML="";return}
  const s=pg(k),pages=Math.max(1,Math.ceil(total/s.n)),a=(s.p-1)*s.n+1,b=Math.min(total,s.p*s.n);
  const nb=(go,label,dis,on)=>`<button class="${on?"on":""}" data-go="${go}" ${dis?"disabled":""}>${label}</button>`;
  h.className="pgn";
  h.innerHTML=`<span class="mut">Menampilkan ${a}–${b} dari ${total}</span><div class="pgb"><select aria-label="Jumlah per halaman">${PCFG[k].sizes.map(n=>`<option value="${n}" ${n===s.n?"selected":""}>${n} / hal</option>`).join("")}</select>`
   +nb(1,"«",s.p===1)+nb(s.p-1,"‹",s.p===1)
   +pageNums(s.p,pages).map(n=>n==="…"?'<span class="mut">…</span>':nb(n,n,false,n===s.p)).join("")
   +nb(s.p+1,"›",s.p===pages)+nb(pages,"»",s.p===pages)+"</div>";
  h.querySelectorAll("[data-go]").forEach(x=>x.onclick=()=>{s.p=+x.dataset.go;redraw()});
  h.querySelector("select").onchange=e=>{s.n=+e.target.value;s.p=1;redraw()}}
function barHost(id,after){let h=document.getElementById(id);if(!h){h=document.createElement("div");h.id=id;after.after(h)}return h}
function drawTbl(k,sel,head,rows){const el=$(sel);if(!el)return;
  const draw=()=>{el.innerHTML=`<div class="wrap">${tbl(head,slice(k,rows))}</div><div></div>`;mountBar(k,el.lastElementChild,rows.length,draw)};draw()}

/* ---------- navigasi ---------- */
function tab(t){$$(".tab").forEach(b=>b.classList.toggle("on",b.dataset.t===t));
  ["pos","prod","lap","usr"].forEach(x=>{const v=$("#v-"+x);if(v)v.classList.toggle("hide",x!==t)});
  if(t==="pos"){loadP();$("#q").focus()}if(t==="prod")rProd();if(t==="usr")rUsers();if(t==="lap")rLap()}
$$(".tab").forEach(b=>b.onclick=()=>tab(b.dataset.t));
$$("[data-x]").forEach(b=>b.onclick=()=>$("#"+b.dataset.x).classList.remove("on"));
function rWho(){$("#who").innerHTML=`${esc(ME.name)} · <b>${ME.role==="admin"?"Admin":"Kasir"}</b>`}
async function logout(){
  try{await fetch("/logout",{method:"POST",headers:H})}catch(_){}
  location.href="/login"}
  if($("#logout"))$("#logout").onclick=()=>{if(confirm("Keluar dari aplikasi?"))logout()};

/* ---------- kasir ---------- */
async function loadP(){P=await api("products");rGrid();rCart()}
function rGrid(){
  $("#chips").innerHTML=["Semua",...CATS].map(c=>`<span class="chip ${c===cat?"on":""}" data-c="${c}">${c}</span>`).join("");
  $$(".chip").forEach(e=>e.onclick=()=>{cat=e.dataset.c;pg("grid").p=1;rGrid()});
  const q=$("#q").value.trim().toLowerCase();
  const L=P.filter(p=>(cat==="Semua"||p.category===cat)&&(!q||p.name.toLowerCase().includes(q)||p.code.includes(q)||p.category.toLowerCase().includes(q)));
  $("#grid").innerHTML=slice("grid",L).map(p=>`<button class="pr" data-k="${p.id}" ${p.stock<=0?"disabled":""}><b>${esc(p.name)}</b><span class="p">${rp(p.price)}</span><small>${esc(p.code)}</small>${p.stock<=0?'<span class="badge out">Habis</span>':p.stock<=LOW?`<span class="badge">Stok menipis: ${p.stock}</span>`:`<small>Stok ${p.stock}</small>`}</button>`).join("")||'<p class="mut">Produk tidak ditemukan.</p>';
  $$(".pr").forEach(b=>b.onclick=()=>addCart(+b.dataset.k));mountBar("grid",barHost("pg-grid",$("#grid")),L.length,rGrid)}
function addCart(id){const p=find(id);if(!p)return;const c=cart.find(i=>i.id===id),n=(c?c.qty:0)+1;
  if(n>p.stock){alert("Stok "+p.name+" tidak cukup (sisa "+p.stock+")");return}
  c?c.qty=n:cart.push({id,qty:1});rCart()}
function calc(){const sub=cart.reduce((s,i)=>s+find(i.id).price*i.qty,0),v=parseFloat($("#dv").value)||0;
  let d=$("#dt").value==="%"?sub*Math.min(v,100)/100:v;d=Math.round(Math.min(Math.max(d,0),sub));
  const tax=$("#ppn").checked?Math.round((sub-d)*.11):0;return{sub,d,tax,total:sub-d+tax}}
const paidVal=()=>parseInt($("#paid").value.replace(/\D/g,""))||0,meth=()=>$("input[name=m]:checked").value;
function rCart(){
  cart=cart.filter(i=>find(i.id));
  $("#items").innerHTML=cart.map(i=>{const p=find(i.id);return`<div class="it"><b>${esc(p.name)}</b><b style="text-align:right">${rp(p.price*i.qty)}</b>
  <span class="mut">${rp(p.price)}</span><span class="q" style="justify-content:flex-end"><button data-a="-" data-k="${i.id}">−</button><b>${i.qty}</b><button data-a="+" data-k="${i.id}">+</button><button data-a="x" data-k="${i.id}" title="Hapus">🗑</button></span></div>`}).join("")||'<p class="mut c">Keranjang kosong. Scan barcode untuk mulai.</p>';
  $$("#items [data-a]").forEach(b=>b.onclick=()=>{const i=cart.find(x=>x.id===+b.dataset.k);
    if(b.dataset.a==="+")return addCart(i.id);if(b.dataset.a==="-"&&--i.qty>0)return rCart();cart=cart.filter(x=>x!==i);rCart()});
  const t=calc();$("#sub").textContent=rp(t.sub);$("#dsc").textContent="− "+rp(t.d);$("#ppnv").textContent=t.tax?rp(t.tax):"";$("#tot").textContent=rp(t.total);
  const cash=meth()==="Tunai";$("#cashbox").classList.toggle("hide",!cash);
  const ch=paidVal()-t.total;$("#chg").textContent=rp(Math.max(ch,0));$("#chg").className=ch<0&&paidVal()?"neg":"ok";
  $("#quick").innerHTML=t.total?[t.total,...[20000,50000,100000].filter(x=>x>t.total)].map(x=>`<span class="chip" data-v="${x}">${x===t.total?"Uang pas":rp(x)}</span>`).join(""):"";
  $$("#quick .chip").forEach(c=>c.onclick=()=>{$("#paid").value=(+c.dataset.v).toLocaleString("id-ID");rCart()});
  $("#bayar").disabled=!cart.length||(cash&&paidVal()<t.total)}
["dt","dv","ppn"].forEach(i=>$("#"+i).oninput=rCart);
$$("input[name=m]").forEach(r=>r.onchange=rCart);
$("#paid").oninput=e=>{const n=e.target.value.replace(/\D/g,"");e.target.value=n?(+n).toLocaleString("id-ID"):"";rCart()};
$("#clr").onclick=()=>{cart=[];rCart()};
$("#q").oninput=()=>{pg("grid").p=1;rGrid()};
$("#q").onkeydown=e=>{if(e.key!=="Enter")return;const v=e.target.value.trim();if(!v)return;
  let p=P.find(x=>x.code===v);if(!p){const L=P.filter(x=>x.name.toLowerCase().includes(v.toLowerCase()));if(L.length===1)p=L[0]}
  if(p){addCart(p.id);e.target.value="";rGrid()}else beep()};
function beep(){try{const a=new AudioContext(),o=a.createOscillator();o.connect(a.destination);o.frequency.value=200;o.start();setTimeout(()=>o.stop(),150)}catch(e){}}
document.addEventListener("keydown",e=>{if(e.key==="F2"){e.preventDefault();tab("pos");$("#q").focus()}
  if(e.key==="F9"){e.preventDefault();if(!$("#bayar").disabled)checkout()}
  if(e.key==="Escape")$$(".modal").forEach(m=>m.classList.remove("on"))});
$("#bayar").onclick=checkout;
function checkout(){$("#bayar").disabled=true;
  tryDo(async()=>{
    const t=await api("transactions","POST",{items:cart.map(i=>({product_id:i.id,qty:i.qty})),discount_type:$("#dt").value,discount_value:parseFloat($("#dv").value)||0,
      ppn:$("#ppn").checked,method:meth(),paid:meth()==="Tunai"?paidVal():null});
    cart=[];$("#dv").value="";$("#paid").value="";$("#ppn").checked=false;await loadP();showRcp(t)
  }).finally(rCart)}

/* ---------- struk ---------- */
function lines(t){const L=[["c","MINIMARKET Point OF sales"],["c","Jl. Contoh No. 1, Bandung"],["h"],["l","No: "+t.invoice],["l",dt(t.created_at)],["l","Kasir: "+t.cashier],["h"]];
  t.items.forEach(i=>{L.push(["l",i.name]);L.push(["r",`  ${i.qty} x ${rp(i.price)}`,rp(i.qty*i.price)])});
  L.push(["h"],["r","Subtotal",rp(t.subtotal)]);if(+t.discount)L.push(["r","Diskon","-"+rp(t.discount)]);if(+t.tax)L.push(["r","PPN 11%",rp(t.tax)]);
  L.push(["r","TOTAL",rp(t.total)],["r","Bayar ("+t.method+")",rp(t.paid)],["r","Kembalian",rp(t.change_amount)],["h"],["c","Terima kasih, selamat berbelanja!"]);return L}
function showRcp(t){last=t;$("#struk").innerHTML=lines(t).map(l=>l[0]==="h"?"<hr>":l[0]==="r"?`<div class="r"><span>${esc(l[1])}</span><span>${l[2]}</span></div>`:`<div class="${l[0]==="c"?"c":""}">${esc(l[1])}</div>`).join("");$("#m-rcp").classList.add("on")}
$("#prt").onclick=()=>window.print();
$("#pdf").onclick=()=>{if(!window.jspdf){alert("Library PDF belum termuat. Gunakan Cetak lalu pilih Simpan sebagai PDF.");return}
  const L=lines(last),d=new window.jspdf.jsPDF({unit:"mm",format:[80,20+L.length*4.5]});d.setFont("courier");d.setFontSize(8);let y=8;
  L.forEach(l=>{if(l[0]==="h")d.text("-".repeat(42),4,y);else if(l[0]==="c")d.text(l[1],40,y,{align:"center"});else if(l[0]==="r"){d.text(l[1],4,y);d.text(l[2],76,y,{align:"right"})}else d.text(l[1],4,y);y+=4.5});
  d.save(last.invoice+".pdf")};

/* ---------- produk ---------- */
if($("#f-cat"))$("#f-cat").innerHTML=CATS.map(c=>`<option>${c}</option>`).join("");
function rProd(){const q=$("#pq").value.toLowerCase();
  const L=P.filter(p=>!q||p.name.toLowerCase().includes(q)||p.code.includes(q));
  $("#ptb").innerHTML=slice("prod",L).map(p=>`<tr><td>${esc(p.code)}</td><td>${esc(p.name)}</td><td>${p.category}</td><td>${rp(p.cost_price)}</td><td>${rp(p.price)}</td>
  <td>${p.stock} ${p.stock<=0?'<span class="badge out">Habis</span>':p.stock<=LOW?'<span class="badge">Menipis</span>':""}</td>
  <td style="white-space:nowrap"><button class="btn sec" data-e="${p.id}">Edit</button> <button class="btn red" data-d="${p.id}">Hapus</button></td></tr>`).join("");
  $$("[data-e]").forEach(b=>b.onclick=()=>openP(+b.dataset.e));
  $$("[data-d]").forEach(b=>b.onclick=()=>{const p=find(+b.dataset.d);if(confirm("Hapus "+p.name+"?"))tryDo(async()=>{await api("products/"+p.id,"DELETE");await loadP();rProd()})});
  const t=$("#ptb").closest("table");mountBar("prod",barHost("pg-prod",t.parentElement.classList.contains("wrap")?t.parentElement:t),L.length,rProd)}
if($("#pq")){$("#pq").oninput=()=>{pg("prod").p=1;rProd()};$("#add").onclick=()=>openP(null)}
function openP(id){editing=id;const p=id?find(id):{code:"",name:"",category:CATS[0],cost_price:"",price:"",stock:""};
  $("#mp-t").textContent=id?"Edit Produk":"Tambah Produk";$("#f-code").value=p.code;$("#f-name").value=p.name;$("#f-cat").value=p.category;
  $("#f-cost").value=p.cost_price;$("#f-price").value=p.price;$("#f-stock").value=p.stock;$("#m-prod").classList.add("on");$("#f-code").focus()}
if($("#f-save"))$("#f-save").onclick=()=>tryDo(async()=>{
  const o={code:$("#f-code").value.trim(),name:$("#f-name").value.trim(),category:$("#f-cat").value,cost_price:+$("#f-cost").value,price:+$("#f-price").value,stock:+$("#f-stock").value};
  await api(editing?"products/"+editing:"products",editing?"PUT":"POST",o);$("#m-prod").classList.remove("on");await loadP();rProd()});

/* ---------- laporan ---------- */
const tbl=(h,r)=>r.length?`<table class="tbl"><thead><tr>${h.map(x=>`<th>${x}</th>`).join("")}</tr></thead><tbody>${r.map(x=>`<tr>${x.map(c=>`<td>${esc(c)}</td>`).join("")}</tr>`).join("")}</tbody></table>`:'<p class="mut">Belum ada data.</p>';
function rLap(){tryDo(async()=>{
  const adm=ME.role==="admin";
  const [R,T,S,Hs]=await Promise.all([adm?api("report"):0,api("transactions"),api("shift"),adm?api("shift/history"):[]]);shift=S;
  if(adm)$("#stats").innerHTML=[["Omzet Hari Ini",rp(R.omzet)],["Jumlah Transaksi",R.trx_count],["Estimasi Laba Kotor",rp(R.profit)],["Stok Menipis",R.low_stock+" produk"]].map(s=>`<div class="card stat"><span>${s[0]}</span><b>${s[1]}</b></div>`).join("");
  if(adm)$("#top").innerHTML=R.top.map((e,i)=>`<div class="row"><span>${i+1}. ${esc(e.name)}</span><b>${e.qty} pcs</b></div>`).join("")||'<p class="mut">Belum ada penjualan.</p>';
  $("#shift").innerHTML=`<p class="mut">Shift dimulai ${dt(S.started_at)} · ${S.trx_count} transaksi</p>
  <div class="row"><span>Modal awal kas</span><b>${rp(S.opening_cash)}</b></div><div class="row"><span>Penjualan tunai</span><b>${rp(S.cash_total)}</b></div>
  <div class="row"><span>Non-tunai (QRIS/Kartu)</span><b>${rp(S.noncash_total)}</b></div><div class="row"><span>Kas seharusnya</span><b>${rp(S.expected_cash)}</b></div>
  <input id="cnt" inputmode="numeric" placeholder="Uang fisik di laci kas (Rp)" style="margin:6px 0"><div class="row"><span>Selisih kas</span><b id="sel">-</b></div>
  <button class="btn red" id="close" style="width:100%">Tutup Shift</button>`;
  let counted=null;$("#cnt").oninput=e=>{const n=e.target.value.replace(/\D/g,"");e.target.value=n?(+n).toLocaleString("id-ID"):"";counted=n?+n:null;
    const diff=counted===null?null:counted-S.expected_cash;$("#sel").textContent=diff===null?"-":(diff>0?"+":"")+rp(diff);$("#sel").className=diff===0?"ok":"neg"};
  $("#close").onclick=()=>{if(counted===null){alert("Isi uang fisik di laci kas.");return}if(!confirm("Tutup shift sekarang? Setelah ditutup Anda akan otomatis keluar."))return;
    const nx=parseInt(prompt("Modal awal shift berikutnya (Rp):","200000"))||0;
    tryDo(async()=>{await api("shift/close","POST",{counted_cash:counted,next_opening:nx});await logout()})};
  drawTbl("trx","#trxs",["Invoice","Jam","Kasir","Metode","Total"],T.map(t=>[t.invoice,dt(t.created_at),t.cashier,t.method,rp(t.total)]));
  if(adm)drawTbl("hist","#hist",["Kasir","Mulai","Selesai","Trx","Tunai","Non-tunai","Selisih"],Hs.map(s=>[s.cashier,dt(s.started_at),dt(s.closed_at),s.trx_count,rp(s.cash_total),rp(s.noncash_total),rp(s.difference)]))})}

/* ---------- pengguna (admin) ---------- */
let U=[],editU=null;
function rUsers(){tryDo(async()=>{U=await api("users");
  $("#utb").innerHTML=U.map(u=>`<tr><td>${esc(u.name)}</td><td>${esc(u.email)}</td><td>${u.role==="admin"?"Admin":"Kasir"}</td><td>${u.is_active?'<span class="ok">Aktif</span>':'<span class="neg">Nonaktif</span>'}</td>
  <td style="white-space:nowrap"><button class="btn sec" data-ue="${u.id}">Edit</button> ${u.id!==ME.id?`<button class="btn red" data-ud="${u.id}">Hapus</button>`:""}</td></tr>`).join("");
  $$("[data-ue]").forEach(b=>b.onclick=()=>openU(+b.dataset.ue));
  $$("[data-ud]").forEach(b=>b.onclick=()=>{const u=U.find(x=>x.id===+b.dataset.ud);if(confirm("Hapus pengguna "+u.name+"?"))tryDo(async()=>{await api("users/"+u.id,"DELETE");rUsers()})})})}
function openU(id){editU=id;const u=id?U.find(x=>x.id===id):{name:"",email:"",role:"kasir",is_active:true};
  $("#mu-t").textContent=id?"Edit Pengguna":"Tambah Pengguna";$("#u-name").value=u.name;$("#u-email").value=u.email;$("#u-role").value=u.role;$("#u-act").checked=u.is_active;
  $("#u-pass").value="";$("#u-pl").textContent=id?"Kata sandi baru (kosongkan jika tidak diubah)":"Kata sandi (min. 6 karakter)";$("#m-user").classList.add("on");$("#u-name").focus()}
if($("#uadd")){$("#uadd").onclick=()=>openU(null);
$("#u-save").onclick=()=>tryDo(async()=>{const o={name:$("#u-name").value.trim(),email:$("#u-email").value.trim(),role:$("#u-role").value,is_active:$("#u-act").checked};
  const p=$("#u-pass").value;if(p)o.password=p;
  await api(editU?"users/"+editU:"users",editU?"PUT":"POST",o);$("#m-user").classList.remove("on");rUsers()})}

/* pengaman tambahan: selain admin, buang menu & panel khusus admin */
if(ME.role!=="admin")$$("[data-t=prod],[data-t=usr],#v-prod,#v-usr,#stats,#c-top,#c-hist,#m-prod,#m-user").forEach(e=>e.remove());
rWho();tryDo(async()=>{await loadP();$("#q").focus()});
