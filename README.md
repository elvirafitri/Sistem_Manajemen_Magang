# 🎓 Sistem Manajemen Magang

Aplikasi web berbasis Laravel untuk mengelola program magang, mencakup manajemen peserta, pembimbing, absensi, evaluasi, surat izin, dan sertifikat.

---

## ⚙️ Teknologi

| Stack | Versi |
|---|---|
| PHP | >= 8.2 |
| Laravel | 11.x |
| Database | MySQL |
| Web Server | Apache (XAMPP) |

---

## 🚀 Panduan Instalasi (Fresh Clone dari GitHub)

### Prasyarat

Pastikan kamu sudah menginstal:
- [XAMPP](https://www.apachefriends.org/) (dengan Apache & MySQL aktif)
- [Composer](https://getcomposer.org/)
- [Node.js & NPM](https://nodejs.org/)
- [Git](https://git-scm.com/)

---

### Langkah 1 — Clone Repository

```bash
git clone https://github.com/feliks2810/Sistem-Manajemen-Magang.git
cd Sistem-Manajemen-Magang
```

Atau jika menggunakan XAMPP, clone langsung ke folder `htdocs`:

```bash
cd C:\xampp\htdocs
git clone https://github.com/feliks2810/Sistem-Manajemen-Magang.git sistem-magang
cd sistem-magang
```

---

### Langkah 2 — Install Dependensi

```bash
composer install
npm install
```

---

### Langkah 3 — Buat File `.env`

Salin file konfigurasi contoh:

```bash
cp .env.example .env
```

Kemudian generate application key:

```bash
php artisan key:generate
```

---

### Langkah 4 — Konfigurasi Database MySQL

Buka file `.env` dan sesuaikan bagian database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sistem_magang
DB_USERNAME=root
DB_PASSWORD=
```

> **⚠️ Catatan Port XAMPP:**
> Jika MySQL XAMPP kamu berjalan di port selain `3306` (misalnya `3309`),
> sesuaikan nilai `DB_PORT` dengan port yang aktif di XAMPP Control Panel.

---

### Langkah 5 — Buat Database

Buka **phpMyAdmin** (`http://localhost/phpmyadmin`) lalu buat database baru:

```sql
CREATE DATABASE sistem_magang CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

**Atau** lewat terminal (sesuaikan path XAMPP kamu):

```bash
# Windows - XAMPP default port 3306
C:\xampp\mysql\bin\mysql.exe -u root -e "CREATE DATABASE sistem_magang CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Windows - XAMPP port 3309
C:\xampp\mysql\bin\mysql.exe -u root -P 3309 --protocol=TCP -e "CREATE DATABASE sistem_magang CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

---

### Langkah 6 — Jalankan Migrasi & Seeder

```bash
php artisan migrate --seed
```

Perintah ini akan:
- Membuat semua tabel di database
- Mengisi data awal (rubrik penilaian & akun admin)

---

### Langkah 7 — Build Asset Frontend

```bash
npm run dev
```

Atau untuk production:

```bash
npm run build
```

---

### Langkah 8 — Jalankan Aplikasi

Pastikan **Apache** dan **MySQL** sudah aktif di XAMPP, lalu buka browser:

```
http://localhost/sistem-magang/public
```

---

## 🔑 Akun Default

Setelah seeder berjalan, gunakan akun berikut untuk login:

| Role | Email | Password |
|---|---|---|
| Admin | `admin@admin.com` | `password` |

---

## 📁 Struktur Direktori Penting

```
sistem-magang/
├── app/
│   ├── Http/Controllers/    # Controller (Admin, Pembimbing, Peserta)
│   ├── Models/              # Eloquent Models
│   └── Services/            # Business logic services
├── database/
│   ├── migrations/          # Skema tabel database
│   └── seeders/             # Data awal (admin, rubrik)
├── resources/views/         # Template Blade
├── routes/web.php           # Definisi routing
└── public/                  # Asset publik
```

---

## 🗄️ Struktur Database

| Tabel | Keterangan |
|---|---|
| `users` | Akun pengguna (admin, pembimbing, peserta) |
| `peserta_profiles` | Profil lengkap peserta magang |
| `pembimbing_profiles` | Profil pembimbing |
| `attendances` | Data absensi harian |
| `leave_requests` | Pengajuan surat izin |
| `evaluations` | Evaluasi peserta oleh pembimbing |
| `rubrics` | Kriteria penilaian evaluasi |
| `evaluation_rubric_scores` | Nilai per kriteria |
| `certificates` | Sertifikat magang |
| `documents` | Dokumen pendukung |
| `settings` | Konfigurasi sistem |

---

## 🛠️ Troubleshooting

### ❌ Error: `SQLSTATE[HY000] [2002] Connection refused`
→ Pastikan MySQL sudah aktif di XAMPP Control Panel.

### ❌ Error: `Access denied for user 'root'@'localhost'`
→ Periksa `DB_USERNAME` dan `DB_PASSWORD` di file `.env`.

### ❌ Error: `No such file or directory` (SQLite)
→ Pastikan `DB_CONNECTION=mysql` di `.env`, bukan `sqlite`.

### ❌ Port sudah dipakai (port 3306)
→ Lihat port MySQL aktif di XAMPP Control Panel, lalu update `DB_PORT` di `.env`.

### ❌ `php artisan` tidak dikenali
→ Pastikan PHP ada di PATH, atau gunakan path penuh: `C:\xampp\php\php.exe artisan migrate --seed`

---
