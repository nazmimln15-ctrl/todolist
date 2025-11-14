# To Do List 

Teknologi: HTML, CSS, JavaScript, PHP, MySQL

## Structure project
- db.sql
- config.php
- index.php
- edit.php
- delete.php
- toggle.php
- assets/css/style.css
- assets/js/main.js

## How to install
1. Pindahkan folder `todolist` ke direktori web server (mis. C:\laragon\www\todolist).
2. Import `db.sql` ke MySQL.
3. Sesuaikan `config.php` dengan credential MySQL.
4. Akses http://localhost/todolist/index.php

## Fitur
- Tambah task
- Edit task
- Hapus task
- Toggle status done/pending
- Validasi client-side melalui `assets/js/main.js`

## Catatan
- Pastikan PHP dan MySQL berjalan.
- Untuk produksi, amankan credential dan tambahkan CSRF protection.
