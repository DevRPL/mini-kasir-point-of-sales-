<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Mini - Minimarket</title>
<link rel="stylesheet" href="{{ asset('css/pos.css') }}?v={{ filemtime(public_path('css/pos.css')) }}">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
</head>
<body>
@php($isAdmin = auth()->user()->isAdmin())
<header>
  <h1>🛒 Mini</h1>
  <nav>
    <button class="tab on" data-t="pos">Kasir</button>
    @if($isAdmin)<button class="tab" data-t="prod">Produk &amp; Stok</button>@endif
    <button class="tab" data-t="lap">{{ $isAdmin ? 'Laporan & Tutup Kasir' : 'Tutup Kasir' }}</button>
    @if($isAdmin)<button class="tab" data-t="usr">Pengguna</button>@endif
  </nav>
  <div class="userbox">
  <span class="who" id="who"></span>
  @if($isAdmin)<button type="button" id="logout" style="background:rgba(255,255,255,.2);color:#fff;border:1px solid rgba(255,255,255,.4);padding:6px 12px;border-radius:8px;cursor:pointer;font-weight:600">Keluar</button>@endif
</div>
</header>
<main>
<section id="v-pos" class="pos">
  <div>
    <div class="card">
      <input id="q" placeholder="Cari nama / kategori, atau scan barcode lalu Enter…  (F2)" autocomplete="off">
      <div class="chips" id="chips"></div>
    </div>
    <div class="grid" id="grid" style="margin-top:10px"></div>
  </div>
  <div class="card cart">
    <div class="row"><b>Keranjang</b><button class="btn sec" id="clr">Kosongkan</button></div>
    <div class="items" id="items"></div>
    <div class="row"><span>Subtotal</span><span id="sub"></span></div>
    <div class="row" style="gap:6px"><select id="dt" style="width:80px"><option value="%">%</option><option value="Rp">Rp</option></select><input id="dv" inputmode="decimal" placeholder="Diskon"></div>
    <div class="row"><label><input type="checkbox" id="ppn" style="width:auto"> PPN 11%</label><span id="ppnv" class="mut"></span></div>
    <div class="row"><span>Diskon</span><span id="dsc"></span></div>
    <div class="row"><b>TOTAL</b><span class="tot" id="tot">Rp 0</span></div>
    <div class="pay">
      <label><input type="radio" name="m" value="Tunai" checked>💵 Tunai</label>
      <label><input type="radio" name="m" value="QRIS">📱 QRIS</label>
      <label><input type="radio" name="m" value="Kartu">💳 Kartu</label>
    </div>
    <div id="cashbox">
      <input id="paid" inputmode="numeric" placeholder="Uang diterima (Rp)">
      <div class="chips" id="quick"></div>
      <div class="row"><span>Kembalian</span><b id="chg">Rp 0</b></div>
    </div>
    <button class="btn" id="bayar" style="width:100%;padding:12px;font-size:16px">Bayar <kbd>F9</kbd></button>
  </div>
</section>

@if($isAdmin)
<section id="v-prod" class="hide">
  <div class="card">
    <div class="row"><input id="pq" placeholder="Cari produk…" style="max-width:320px"><button class="btn" id="add">+ Tambah Produk</button></div>
    <div class="wrap"><table class="tbl"><thead><tr><th>Kode</th><th>Nama</th><th>Kategori</th><th>H. Beli</th><th>H. Jual</th><th>Stok</th><th></th></tr></thead><tbody id="ptb"></tbody></table></div>
  </div>
</section>
@endif

<section id="v-lap" class="hide">
  @if($isAdmin)<div class="stats" id="stats"></div>@endif
  <div class="two">
    @if($isAdmin)<div class="card" id="c-top"><b>Produk Terlaris Hari Ini</b><div id="top"></div></div>@endif
    <div class="card"><b>Tutup Shift Kasir</b><div id="shift"></div></div>
  </div>
  <div class="card" style="margin-top:12px"><b>Transaksi Hari Ini</b><div class="wrap" id="trxs"></div></div>
  @if($isAdmin)<div class="card" id="c-hist" style="margin-top:12px"><b>Riwayat Tutup Shift</b><div class="wrap" id="hist"></div></div>@endif
</section>
@if($isAdmin)
<section id="v-usr" class="hide">
  <div class="card">
    <div class="row"><b>Manajemen Pengguna &amp; Hak Akses</b><button class="btn" id="uadd">+ Tambah Pengguna</button></div>
    <div class="wrap"><table class="tbl"><thead><tr><th>Nama</th><th>Email</th><th>Peran</th><th>Status</th><th></th></tr></thead><tbody id="utb"></tbody></table></div>
  </div>
</section>
@endif
</main>

@if($isAdmin)
<div class="modal" id="m-prod"><div class="card"><b id="mp-t">Produk</b><div class="frm" style="margin-top:8px">
  <label>Kode / Barcode</label><input id="f-code">
  <label>Nama Produk</label><input id="f-name">
  <label>Kategori</label><select id="f-cat"></select>
  <label>Harga Beli (Rp)</label><input id="f-cost" type="number" min="0">
  <label>Harga Jual (Rp)</label><input id="f-price" type="number" min="0">
  <label>Stok</label><input id="f-stock" type="number" min="0">
  <div class="row"><button class="btn sec" data-x="m-prod">Batal</button><button class="btn" id="f-save">Simpan</button></div>
</div></div></div>

<div class="modal" id="m-user"><div class="card"><b id="mu-t">Pengguna</b><div class="frm" style="margin-top:8px">
  <label>Nama</label><input id="u-name">
  <label>Email (untuk login)</label><input id="u-email" type="email">
  <label>Peran</label><select id="u-role"><option value="kasir">Kasir – hanya transaksi &amp; tutup kasir</option><option value="admin">Admin – akses penuh</option></select>
  <label id="u-pl">Kata sandi</label><input id="u-pass" type="password" autocomplete="new-password">
  <label style="display:flex;gap:6px;align-items:center"><input id="u-act" type="checkbox" style="width:auto" checked> Akun aktif</label>
  <div class="row"><button class="btn sec" data-x="m-user">Batal</button><button class="btn" id="u-save">Simpan</button></div>
</div></div></div>
@endif

<div class="modal" id="m-rcp"><div class="card">
  <div id="struk"></div>
  <div class="row" style="margin-top:10px"><button class="btn sec" id="pdf">Download PDF</button><button class="btn" id="prt">Cetak</button><button class="btn sec" data-x="m-rcp">Tutup</button></div>
</div></div>

<script>window.ME=@json(['id'=>auth()->id(),'name'=>auth()->user()->name,'role'=>auth()->user()->role]);</script>
<script src="{{ asset('js/pos.js') }}?v={{ filemtime(public_path('js/pos.js')) }}"></script>
</body>
</html>
