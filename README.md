# Kasir Mini – POS Minimarket (Laravel 9 + MySQL) + Login & Hak Akses

## Instalasi baru
1. composer create-project laravel/laravel:^9.0 pos-minimarket
2. Salin isi folder ini ke project (timpa file yang ada).
3. CREATE DATABASE pos_minimarket;  lalu isi .env (DB_CONNECTION=mysql, DB_DATABASE, DB_USERNAME, DB_PASSWORD)
4. config/app.php: 'timezone' => 'Asia/Jakarta', 'locale' => 'id'
5. php artisan migrate --seed
6. php artisan serve  ->  http://127.0.0.1:8000  (otomatis diarahkan ke /login)

## Upgrade dari versi tanpa login
   php artisan migrate
   php artisan db:seed --class=UserSeeder      (JANGAN db:seed penuh: akan mereset stok produk contoh)

## Akun awal (GANTI kata sandinya!)
   admin@minimarket.test / admin12345
   kasir@minimarket.test / kasir12345

## Hak akses
| Fitur                               | Admin | Kasir |
|-------------------------------------|:-----:|:-----:|
| Halaman Kasir / transaksi / struk   |  ✔   |  ✔   |
| Lihat daftar produk (untuk jualan)  |  ✔   |  ✔   |
| Tambah / edit / hapus produk, stok  |  ✔   |  ✘   |
| Tutup kasir (shift sendiri)         |  ✔   |  ✔   |
| Daftar transaksi hari ini           | semua | milik sendiri |
| Omzet, laba, terlaris, riwayat shift|  ✔   |  ✘   |
| Kelola pengguna & peran             |  ✔   |  ✘   |
Hak akses ditegakkan di server (middleware EnsureRole), bukan hanya disembunyikan di tampilan.
