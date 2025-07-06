# 📚 YAKIIN Teacher Payment System

> Sistem Informasi Pengelolaan Absensi dan Penggajian Guru untuk Yayasan YAKIIN

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/Bootstrap-5.1-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white" alt="Bootstrap">
  <img src="https://img.shields.io/badge/SQLite-003B57?style=for-the-badge&logo=sqlite&logoColor=white" alt="SQLite">
</p>

## 🎯 Tentang Proyek

YAKIIN Teacher Payment System adalah aplikasi web berbasis Laravel yang dirancang khusus untuk mengelola absensi dan penggajian guru di Yayasan YAKIIN. Sistem ini menggunakan teknologi kamera web untuk absensi mandiri guru dan menyediakan dashboard yang berbeda sesuai dengan peran pengguna.

### ✨ Fitur Utama

#### 🔐 **Sistem Keamanan & Authentication**
- ✅ Role-based access control (Admin, Bendahara, Guru)
- ✅ Middleware proteksi route berdasarkan role
- ✅ Enkripsi password dengan bcrypt
- ✅ Session management yang aman
- ✅ SQL Injection protection (Laravel ORM)
- ✅ CSRF Protection

#### 👥 **Manajemen Pengguna**
- ✅ **Admin:** Mengelola semua data guru, absensi, dan gaji
- ✅ **Bendahara:** Melihat data guru, absensi, dan mengelola gaji
- ✅ **Guru:** Melihat data pribadi, absensi, dan gaji mereka sendiri

#### 📊 **Dashboard Interaktif**
- ✅ Dashboard khusus Admin/Bendahara dengan statistik lengkap
- ✅ Dashboard khusus Guru dengan informasi personal
- ✅ Ringkasan data absensi dan gaji real-time

#### 👨‍🏫 **Manajemen Data Guru**
- ✅ CRUD lengkap untuk data guru (hanya Admin)
- ✅ Data lengkap: NIP, nama, alamat, mata pelajaran, gaji pokok
- ✅ Soft delete (nonaktifkan guru)
- ✅ Validasi data yang komprehensif

#### 📅 **Manajemen Absensi**
- ✅ Input absensi guru (Admin/Bendahara)
- ✅ Status absensi: Hadir, Tidak Hadir, Terlambat, Izin, Sakit
- ✅ Filter berdasarkan guru, bulan, dan tahun
- ✅ Guru hanya bisa melihat absensi sendiri

#### 📸 **Absensi Mandiri dengan Kamera** (Fitur Unggulan)
- ✅ Akses kamera web untuk absensi real-time
- ✅ Absen masuk & keluar dengan foto bukti
- ✅ Deteksi lokasi GPS otomatis
- ✅ Status otomatis (Hadir/Terlambat berdasarkan jam)
- ✅ Validasi waktu dan urutan absensi
- ✅ Mobile-responsive untuk smartphone

#### 💰 **Manajemen Gaji**
- ✅ Generate gaji otomatis berdasarkan absensi
- ✅ Perhitungan: Gaji Pokok + Tunjangan + Bonus - Potongan
- ✅ Status gaji: Draft, Approved, Paid
- ✅ Filter dan pencarian data gaji
- ✅ Laporan gaji detail

## 🚀 Instalasi dan Setup

### Prasyarat
- PHP 8.2 atau lebih tinggi
- Composer
- Node.js & NPM (opsional untuk development)
- Web server (Apache/Nginx) atau bisa menggunakan PHP built-in server

### Langkah Instalasi

1. **Clone Repository**
   ```bash
   git clone <repository-url>
   cd teacher-payment-system
   ```

2. **Install Dependencies**
   ```bash
   composer install
   ```

3. **Setup Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Setup Database**
   ```bash
   # Database sudah menggunakan SQLite (database/database.sqlite)
   php artisan migrate
   php artisan db:seed
   ```

5. **Setup Storage**
   ```bash
   php artisan storage:link
   ```

6. **Jalankan Server**
   ```bash
   php artisan serve
   ```

7. **Akses Aplikasi**
   Buka browser dan akses: `http://127.0.0.1:8000`

## 👤 Akun Default

| Role | Email | Password | Akses |
|------|-------|----------|--------|
| **Admin** | admin@yakiin.sch.id | admin123 | Full access (CRUD semua data) |
| **Bendahara** | bendahara@yakiin.sch.id | bendahara123 | View data guru, manage gaji |
| **Guru** | guru@yakiin.sch.id | guru123 | View data pribadi, absensi mandiri |

## 🎮 Cara Menggunakan

### Untuk Guru:
1. **Login** dengan akun guru
2. **Dashboard:** Lihat ringkasan absensi dan gaji
3. **Absensi Mandiri:** 
   - Klik menu "Absensi Mandiri"
   - Izinkan akses kamera
   - Klik "Absen Masuk" (pagi) atau "Absen Keluar" (sore)
   - Foto akan tersimpan otomatis
4. **Lihat Data:** Akses data absensi dan gaji pribadi

### Untuk Admin/Bendahara:
1. **Login** dengan akun admin/bendahara
2. **Dashboard:** Lihat statistik keseluruhan
3. **Kelola Guru:** Tambah, edit, lihat data guru (Admin only)
4. **Kelola Absensi:** Input manual absensi guru
5. **Kelola Gaji:** Generate dan manage gaji guru
6. **Lihat Foto Absensi:** Verifikasi foto absensi di detail attendance

## 🏗️ Struktur Database

### Tabel Utama:
- **users:** Data pengguna dan role
- **teachers:** Data detail guru
- **attendances:** Data absensi dengan foto dan lokasi
- **salaries:** Data gaji dan perhitungan

### Relasi:
- User hasOne Teacher
- Teacher hasMany Attendances
- Teacher hasMany Salaries

## 🔧 Teknologi yang Digunakan

### Backend:
- **Laravel 12:** Framework PHP modern
- **SQLite:** Database ringan dan portable
- **Laravel ORM:** Object-Relational Mapping
- **Laravel Middleware:** Route protection
- **Laravel Storage:** File management

### Frontend:
- **Bootstrap 5:** CSS framework responsive
- **FontAwesome 6:** Icon library
- **JavaScript (Vanilla):** Camera API dan AJAX
- **Blade Templates:** Laravel templating engine

### Fitur Browser:
- **MediaDevices API:** Akses kamera web
- **Geolocation API:** Deteksi lokasi GPS
- **Canvas API:** Capture dan manipulasi gambar
- **Local Storage:** Menyimpan preferensi user

## 📱 Fitur Mobile-Friendly

- ✅ Responsive design untuk semua ukuran layar
- ✅ Touch-friendly interface
- ✅ Akses kamera depan smartphone
- ✅ Geolocation support
- ✅ Optimized untuk penggunaan mobile

## 🔒 Keamanan

### Implementasi Keamanan:
- **Password Hashing:** Bcrypt encryption
- **CSRF Protection:** Token validation
- **SQL Injection Prevention:** Laravel ORM
- **XSS Protection:** Blade templating
- **Role-based Access Control:** Custom middleware
- **Session Security:** Secure session management

### Validasi Data:
- Server-side validation
- Client-side validation
- File upload validation
- Image format validation

## 📊 Fitur Pelaporan

- **Dashboard Statistics:** Real-time data overview
- **Attendance Reports:** Filter by date, teacher, status
- **Salary Reports:** Monthly salary calculations
- **Photo Verification:** Visual attendance proof
- **Location Tracking:** GPS coordinates logging

## 🛠️ Development

### Untuk Developer:

1. **Setup Development Environment**
   ```bash
   composer install --dev
   php artisan migrate:fresh --seed
   ```

2. **Run Tests** (jika tersedia)
   ```bash
   php artisan test
   ```

3. **Code Style**
   - Mengikuti PSR-12 standard
   - Laravel best practices
   - Clean code principles

## 📄 Lisensi

Proyek ini dikembangkan untuk Yayasan YAKIIN. Semua hak cipta dilindungi.

## 🚨 Troubleshooting

### Masalah Umum dan Solusi

#### **Kamera tidak dapat diakses**
```bash
# Pastikan browser mengizinkan akses kamera
# Chrome: Settings → Privacy and Security → Site Settings → Camera
# Firefox: Preferences → Privacy & Security → Permissions → Camera
# Safari: Preferences → Websites → Camera
```

#### **Foto absensi tidak tersimpan**
```bash
# Periksa permission storage
sudo chmod -R 755 storage/
sudo chmod -R 755 bootstrap/cache/

# Link storage ke public
php artisan storage:link

# Clear cache
php artisan cache:clear
php artisan config:clear
```

#### **Database error saat migration**
```bash
# Hapus database dan buat ulang
rm database/database.sqlite
touch database/database.sqlite
php artisan migrate:fresh --seed
```

#### **Session expired atau login gagal**
```bash
# Generate new application key
php artisan key:generate

# Clear semua cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

#### **Gambar tidak muncul di detail absensi**
```bash
# Pastikan storage link sudah dibuat
php artisan storage:link

# Periksa permission
sudo chown -R www-data:www-data storage/
sudo chmod -R 755 storage/
```

## 🔧 Konfigurasi Advanced

### Setup untuk Production

1. **Environment Production**
   ```bash
   # Update .env untuk production
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://yourdomain.com
   ```

2. **Optimize untuk Performance**
   ```bash
   composer install --optimize-autoloader --no-dev
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

3. **Setup HTTPS (Recommended)**
   - Gunakan SSL certificate
   - Update APP_URL ke https://
   - Kamera web memerlukan HTTPS untuk production

### Database Backup
```bash
# Backup SQLite database
cp database/database.sqlite backup/database_$(date +%Y%m%d).sqlite

# Backup dengan data
php artisan db:dump --gzip
```

## 📊 Monitoring dan Logs

### Lokasi Log File
```bash
# Application logs
storage/logs/laravel.log

# Web server logs (tergantung server)
/var/log/apache2/access.log
/var/log/nginx/access.log
```

### Debug Mode
```bash
# Enable debug (hanya untuk development)
APP_DEBUG=true

# Disable debug (untuk production)
APP_DEBUG=false
```

## 📄 Lisensi

Proyek ini dikembangkan untuk Yayasan YAKIIN. Semua hak cipta dilindungi.

## 🤝 Kontributor

- **Developer:** Ahmad Nathan Firdaus
- **Client:** Yayasan YAKIIN
- **Framework:** Laravel Team

## 📞 Support

Untuk bantuan teknis atau pertanyaan, silakan hubungi:
- **Email:** [contact@yakiin.sch.id]
- **Developer:** [your-email@domain.com]

---

<p align="center">
  <strong>🎓 Dibuat dengan ❤️ untuk Yayasan YAKIIN</strong><br>
  <em>Memudahkan pengelolaan absensi dan penggajian guru dengan teknologi modern</em>
</p>
