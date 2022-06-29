# Laravel 8 Starter Project Using Stisla Admin Template

## Feature List
1. Admin Template via [Stisla](https://github.com/stisla/stisla)
2. Authentication via [Laravel Fortify](https://github.com/laravel/fortify)
3. Role and Permission via [Spatie Laravel Permission](https://github.com/spatie/laravel-permission)
4. Backup via [Spatie Laravel Backup](https://github.com/spatie/laravel-backup)
5. Dynamic Menu based on Role
6. Mobile Rest Api Auth via [Laravel Sanctum](https://github.com/laravel/sanctum)

## Screenshots
Check [Screenshots](screenshots/screenshots.md)

## Contributor
1. [Putra Prima Arhandi](https://github.com/siubie)
2. Muhammad Afdal
3. Dewi Okta
4. Evina Dinda
5. [Ardhanika](https://github.com/ardhanika)
6. Jaya Mahendra

## Instalasi

1. copy file .env.example menjadi .env
2. ubah db_database di file .env
3. jalankan ``php artisan key:generate``
4. jalankan ``composer install``
5. jalankan ``composer require barryvdh/laravel-dompdf``
untuk membuat file pdf pada menu laporan management
6. jalankan ``php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"``
7. jalankan ``npm i``
8. jalankan ``php artisan migrate:refresh --seed``
saya sudah membuat seeder berupa menu group, menu item, book, peminjaman, pengembalian, dan denda. Untuk tampilan gambar pada list book perlu diedit manual pada button edit yang tersedia dengan memilih file gambar yang ingin diupload. sudah saya test pada windows dan linux berjalan lancar.

## Additional Fitur

1. laporan pdf pada menu laporan management (menu bagian bawah sendiri)
2. unit testing
3. crud 
4. upload images dan file pdf

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
