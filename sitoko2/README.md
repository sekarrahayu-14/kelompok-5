# SITOKO - Sistem Informasi Kasir/POS Koperasi Sekolah

## Cara menjalankan
1. Extract folder `sitoko` ke `htdocs` (XAMPP) atau folder `www` (Laragon).
2. Import file `db_sitoko.sql` melalui phpMyAdmin.
3. Periksa `config/database.php`; default MySQL adalah user `root` tanpa password.
4. Jalankan Apache dan MySQL.
5. Buka `http://localhost/sitoko/public/produk`.

## API
- GET `http://localhost/sitoko/api/produk`
- GET `http://localhost/sitoko/api/produk/1`
- POST `http://localhost/sitoko/api/produk`
- PUT/PATCH `http://localhost/sitoko/api/produk/1`
- DELETE `http://localhost/sitoko/api/produk/1`
- Resource kategori: ganti `produk` menjadi `kategori`.

Untuk POST/PUT/PATCH, gunakan Header `Content-Type: application/json`.
