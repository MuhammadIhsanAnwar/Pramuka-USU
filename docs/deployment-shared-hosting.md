# Deployment shared hosting

Halaman panel Filament di aplikasi ini menggunakan rute `/admin` dan `/user`.
Rute tersebut didaftarkan oleh:

- `app/Providers/Filament/AdminPanelProvider.php`
- `app/Providers/Filament/UserPanelProvider.php`
- `bootstrap/providers.php`

Setelah mengunggah atau menarik versi kode baru ke hosting, jalankan perintah
berikut dari root proyek Laravel (folder yang berisi file `artisan`):

```sh
composer install --no-dev --optimize-autoloader
sh scripts/refresh-production-cache.sh
```

Jika hosting tidak menyediakan `composer`, pastikan folder `vendor` yang sesuai
dengan `composer.lock` juga sudah diunggah, lalu jalankan perintah kedua saja.

Skrip akan menghapus cache lama, mendaftarkan ulang package dan provider, lalu
membangun ulang cache konfigurasi serta rute. Output akhirnya harus memuat,
setidaknya:

```text
GET|HEAD  admin
GET|HEAD  admin/login
GET|HEAD  user
GET|HEAD  user/login
```

Pengaturan hosting yang diperlukan:

1. Document root domain/subdomain harus diarahkan ke folder `public` proyek.
   Bila panel hosting tidak memungkinkan, gunakan `.htaccess` pada root proyek
   dan `public/.htaccess` yang sudah tersedia dalam repository ini.
2. PHP CLI dan PHP web server harus menggunakan versi yang memenuhi
   `composer.json` (minimal PHP 8.3).
3. `.env` produksi harus memakai URL final dan cache tidak boleh dibawa dari
   komputer lokal:

   ```dotenv
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://pramukausu.neoverse.my.id
   SESSION_SECURE_COOKIE=true
   SESSION_DOMAIN=null
   ```

Setelah deployment, buka `/admin` dan `/user` dalam jendela incognito. Pengguna
yang belum masuk harus dialihkan ke `/admin/login` atau `/user/login`, bukan
menerima halaman 404.
