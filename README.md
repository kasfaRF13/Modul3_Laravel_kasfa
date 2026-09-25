# Activity Manager

Aplikasi manajemen kegiatan sederhana menggunakan Laravel 11.

## Cara Menjalankan Proyek Lokal
1. Clone repository ini.
2. Jalankan `composer install`
3. Salin file environment: `cp .env.example .env`
4. Generate key aplikasi: `php artisan key:generate`
5. Jalankan migrasi dan seeder database: `php artisan migrate --seed`
6. Nyalakan server lokal: `php artisan serve`