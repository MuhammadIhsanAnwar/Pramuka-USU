# Tugas Lengkap - Perbaikan Pramuka USU

## Task 1: Route Dashboard Admin/User 404 saat deploy
- [ ] Fix root `.htaccess` untuk handle subfolder deployment
- [ ] Tambah dokumentasi/note tentang APP_URL di .env
- [ ] Jalankan route:cache untuk verifikasi

## Task 2: Ubah teks header & footer
- [ ] `resources/views/layouts/public.blade.php` - Ubah teks header & footer
- [ ] `resources/views/filament/components/brand.blade.php` - Ubah teks brand

## Task 3: Link reset password
- [ ] Perbaiki `resources/views/auth/passwords/reset.blade.php` - pastikan token & email benar
- [ ] Pastikan `APP_URL` dipakai untuk generate reset link

## Task 4: Header sticky (selalu tampil saat scroll)
- [ ] Header sudah `sticky top-0 z-50`, pastikan tidak ada yang override

## Task 5: Copyright footer
- [ ] Tambah copyright dinamis di `resources/views/layouts/public.blade.php`
