# Kasir Mini – POS Minimarket (Laravel 9 + MySQL) + Login & Hak Akses

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
