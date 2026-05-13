# 💰 Sistem Informasi Kas Kelas

**Sistem manajemen kas kelas berbasis Web** yang dirancang untuk transparansi keuangan dan kemudahan pengelolaan iuran siswa. Dilengkapi dengan fitur **Logika FIFO (First In First Out)** untuk pembayaran otomatis dan validasi saldo real-time.

---

## 🎯 Tujuan Aplikasi

Aplikasi ini dibuat untuk memecahkan masalah pengelolaan uang kas kelas yang sering dihadapi sekolah:

✅ **Transparansi Keuangan** - Setiap siswa dan wali kelas dapat melihat alur uang kas secara jelas  
✅ **Pembayaran Otomatis (FIFO)** - Bendahara hanya perlu input nominal, siswa otomatis terbayar dari minggu paling awal  
✅ **Validasi Saldo** - Sistem mencegah pengeluaran jika saldo kas tidak mencukupi  
✅ **Multi-User Dashboard** - Setiap role (Bendahara, Wali Kelas, Ketua Kelas, Murid) memiliki dashboard spesifik  
✅ **History Transaksi** - Laporan lengkap pemasukan dan pengeluaran yang terorganisir  
✅ **Keamanan Session** - Halaman dilindungi berdasarkan role user dengan login authentication

---

## 🚀 Fitur Utama

### 1. **Multi-Role Access Control**

- **Bendahara**: Kelola kas masuk/keluar, lihat dashboard finansial
- **Wali Kelas**: Monitor status kas kelas, kelola aktivasi/nonaktifasi siswa
- **Ketua Kelas**: Mode pantau untuk transparansi keuangan kelas
- **Murid**: Lihat status pembayaran pribadi dan transparansi pengeluaran kelas

### 2. **Logika Pembayaran FIFO Cerdas**

Bendahara hanya perlu input nominal pembayaran, sistem secara otomatis mengisi tunggakan minggu-minggu sebelumnya:

- M-1 → M-2 → M-3 → M-4 (diisi berurutan sesuai urutan awal)
- Nominal dibagi otomatis ke seluruh minggu yang belum terbayar
- Menghindari error manual dalam pencatatan

### 3. **Manajemen Kas Real-Time**

- **Pemasukan**: Catat kas masuk dari siswa per minggu atau kategori lain
- **Pengeluaran**: Catat pengeluaran kelas dengan validasi saldo
- **Status Kas**: Monitor progress pembayaran siswa per bulan dengan progress bar
- **Detail Kas**: Laporan transaksi kronologis untuk audit dan transparansi

### 4. **Dashboard Intuitif per Role**

- **Dashboard Bendahara**: Total saldo, grafik kas, partisipasi pembayaran, riwayat transaksi
- **Dashboard Wali Kelas**: Statistik kelas, progress pembayaran, grafik pemasukan-pengeluaran
- **Dashboard Ketua Kelas**: Mode pantau untuk transparansi (read-only)
- **Dashboard Murid**: Status pembayaran pribadi + transparansi pengeluaran kelas

### 5. **Notifikasi Interaktif**

- Menggunakan **SweetAlert2** untuk feedback modern dan user-friendly
- Success alert, error alert dengan pesan spesifik
- Auto-dismiss untuk notifikasi sukses (2 detik)

### 6. **Keamanan Data**

- Session-based authentication dengan role checking
- User dapat di-aktifkan/non-aktifkan oleh Wali Kelas
- Prepared statements untuk mencegah SQL injection
- Validasi input di setiap proses transaksi

---

## 🛠️ Tech Stack

| Layer          | Technology                  |
| -------------- | --------------------------- |
| **Backend**    | PHP 8.x (Native)            |
| **Database**   | MySQL (mysqli)              |
| **Frontend**   | Bootstrap 5, HTML5, CSS3    |
| **Chart**      | Chart.js (visualisasi data) |
| **Notifikasi** | SweetAlert2                 |
| **Icons**      | Bootstrap Icons             |

---

## 📦 Instalasi & Setup

### 1. **Clone atau Download Project**

```bash
git clone https://github.com/Xixixixiy/Project-Kas-Kelas.git
cd projectKasKelas
```

### 2. **Setup Database**

```bash
# Buka phpMyAdmin atau MySQL client
# Import file database:
mysql -u root -p < db_kas_v2.sql
```

### 3. **Konfigurasi Database**

Edit file `config/database.php`:

```php
$servername = "localhost";
$username = "root";          // Sesuaikan username MySQL Anda
$password = "";              // Sesuaikan password MySQL Anda
$db_name = "db_kas_v2";
$base_url = "http://localhost/projectKasKelas/";
```

### 4. **Jalankan Aplikasi**

```bash
# Copy folder ke htdocs
cp -r projectKasKelas /xampp/htdocs/

# Akses via browser
http://localhost/projectKasKelas/
```

---

## 📋 Struktur Folder

```
projectKasKelas/
├── config/
│   └── database.php          # Konfigurasi koneksi database
├── actions/
│   ├── proses_login.php      # Autentikasi user
│   ├── proses_tambah.php     # Proses input transaksi masuk (FIFO)
│   ├── proses_keluar.php     # Proses input transaksi keluar
│   ├── update_status_siswa.php # Aktifkan/nonaktifkan siswa
│   └── logout.php            # Logout user
├── layout/
│   ├── navbar.php            # Navigasi utama
│   └── notifikasi.php        # SweetAlert2 notifikasi
├── bendahara/
│   ├── dashboard.php         # Dashboard bendahara
│   ├── transaksi_masuk.php   # Form input kas masuk
│   └── transaksi_keluar.php  # Form input kas keluar
├── wali_kelas/
│   ├── dashboard.php         # Dashboard wali kelas
│   └── kelola_siswa.php      # Manage aktivasi siswa
├── ketua_kelas/
│   └── dashboard.php         # Dashboard ketua kelas (mode pantau)
├── murid/
│   └── dashboard.php         # Dashboard siswa
├── login.php                 # Halaman login
├── status_kas.php            # Status pembayaran per bulan
├── detail_kas.php            # Laporan detail transaksi
├── db_kas_v2.sql             # Database dump
└── README.md                 # File dokumentasi ini
```

---

## 🔐 Akun Default untuk Testing

Setelah import database, gunakan akun berikut untuk login:

| Role            | NISN/NIK | Password | Fungsi                      |
| --------------- | -------- | -------- | --------------------------- |
| **Bendahara**   | 001      | 123456   | Kelola kas masuk/keluar     |
| **Wali Kelas**  | 002      | 123456   | Monitor kelas, kelola siswa |
| **Ketua Kelas** | 003      | 123456   | Mode pantau transparansi    |
| **Murid**       | 101-110  | 123456   | Lihat status pembayaran     |

**⚠️ PENTING**: Ganti password semua akun setelah setup untuk keamanan!

---

## 💡 Cara Menggunakan

### **Skenario 1: Siswa Membayar Kas**

1. **Bendahara Login** → Pilih "Kelola Kas" → "Pemasukkan"
2. **Isi Form**:
   - Kategori: "Uang Kas"
   - Murid: Pilih nama siswa
   - Nominal: Input jumlah uang (misal 50.000 untuk bayar 2 minggu)
   - Bulan & Tahun: Bulan pembayaran
   - Minggu: Pilih minggu terakhir yang dibayar (misal M-2, maka M-1 & M-2 otomatis terisi)
3. **Klik Simpan** → Sistem otomatis mengisi M-1 & M-2
4. **Notifikasi**: "Berhasil mencatat 2 minggu!"

### **Skenario 2: Pengeluaran untuk Keperluan Kelas**

1. **Bendahara Login** → Pilih "Kelola Kas" → "Pengeluaran"
2. **Isi Form**:
   - Kategori: Pilih kategori (misal "Alat Tulis")
   - Nominal: Input jumlah pengeluaran
   - Keterangan: Deskripsi pengeluaran (misal "Beli spidol & penghapus")
3. **Sistem Validasi**: Otomatis cek saldo, jika < nominal → error
4. **Klik Simpan** → Dicatat dengan tanggal & waktu otomatis

### **Skenario 3: Wali Kelas Monitoring**

1. **Wali Kelas Login** → Lihat Dashboard
2. **Cek Statistik**:
   - Total saldo kelas
   - Progress pembayaran siswa (%)
   - Grafik perbandingan pemasukan-pengeluaran
3. **Menu "Kelola Siswa"**:
   - Lihat daftar siswa aktif
   - Aktifkan atau nonaktifkan siswa (status "Aktif" = bisa login)

### **Skenario 4: Siswa Cek Status Pembayaran**

1. **Siswa Login** → Dashboard Murid
2. **Lihat**:
   - Total kas yang sudah dibayar
   - Alert jika ada tunggakan (berapa minggu, nominal)
   - Riwayat pembayaran pribadi
   - Transparansi pengeluaran kelas (tabel 5 transaksi terakhir)
3. **Menu "Status Kas"**: Filter per bulan, lihat status pembayaran semua siswa

### **Skenario 5: Ketua Kelas Pantau Transparansi**

1. **Ketua Kelas Login** → Dashboard (Mode Pantau)
2. **Akses Terbatas** ke:
   - Dashboard (baca saja, tidak bisa edit)
   - Status Kas (baca saja)
   - Detail Kas (baca saja, lihat semua transaksi)
3. **Gunakan untuk**: Laporan ke orang tua & siswa tentang keuangan kelas

---

## 🔍 Penjelasan Logika FIFO

**FIFO = First In First Out** → Bayar tunggakan minggu paling lama dulu

**Contoh Kasus**:

- Siswa A belum bayar M-1, M-2, M-3, M-4
- Iuran mingguan = Rp 5.000
- Siswa A bayar Rp 20.000

**Proses FIFO**:

```
Input: 20.000 untuk minggu M-4
Lubang kosong: M-1, M-2, M-3, M-4 (4 minggu)
Nominal per minggu: 20.000 ÷ 4 = 5.000

Hasil:
✅ M-1: Rp 5.000
✅ M-2: Rp 5.000
✅ M-3: Rp 5.000
✅ M-4: Rp 5.000
Total tercatat: Rp 20.000 (lunas!)
```

**Keuntungan**:

- ✅ Tidak ada error pencatatan manual
- ✅ Tunggakan lama langsung terbayar
- ✅ Bendahara tidak perlu berhitung sendiri
- ✅ Transparansi jelas untuk setiap minggu

---

## 📊 Screenshots (Deskripsi)

### Login Page

- Design modern dengan gradient background
- Input NISN/NIK & Password
- Responsive di mobile

### Dashboard Bendahara

- Widget Total Saldo (warna biru)
- Widget Partisipasi Pembayaran (progress bar)
- Grafik Doughnut Pemasukan vs Pengeluaran
- Tabel Transaksi Terakhir

### Form Pemasukkan (FIFO)

- Dropdown Kategori, Murid, Bulan, Tahun
- Button Minggu (M-1, M-2, M-3, M-4) yang dinamis
- Tombol Minggu yang sudah terbayar: disabled & hijau
- Validasi real-time

### Status Kas

- Progress bar status pembayaran per siswa
- Filter bulan dengan dropdown
- Indikator lunas (100%) dengan warna berbeda

### Dashboard Murid

- Alert tunggakan (merah) atau pembayaran aman (hijau)
- Tabel riwayat pembayaran pribadi
- Card transparansi pengeluaran kelas

---

## 🐛 Troubleshooting

| Masalah                       | Solusi                                                                  |
| ----------------------------- | ----------------------------------------------------------------------- |
| **Database connection error** | Cek konfigurasi di `config/database.php`, pastikan MySQL running        |
| **Login failed**              | Pastikan database sudah diimport, periksa NISN & password               |
| **Form validation error**     | Pastikan semua field diisi, nominal > 0                                 |
| **Saldo tidak cukup**         | Cek transaksi pengeluaran vs pemasukan, mungkin pengeluaran lebih besar |
| **Session expired**           | Login ulang, session valid selama browser tidak ditutup                 |

---

## 📝 Catatan Pengembang

### Keamanan

- ⚠️ Gunakan **prepared statements** jika ingin upgrade ke mysqli prepared
- ⚠️ Hash password dengan **password_hash()** untuk production
- ⚠️ Implementasi HTTPS untuk keamanan data dalam transit

### Pengembangan Lanjut

- 🔄 Integrasi SMS/Email notifikasi
- 📱 Mobile app dengan Flutter/React Native
- 📈 Export laporan ke PDF/Excel
- 🔐 Two-factor authentication (2FA)
- 💳 Integrasi pembayaran online (Midtrans, GCash)
- 📊 Dashboard analytics yang lebih detail

---

## 📞 Support & Kontribusi

Jika ada pertanyaan, bug report, atau ide pengembangan:

1. **Buat Issue** di repository GitHub
2. **Pull Request** untuk kontribusi fitur baru
3. **Dokumentasi**: Update README jika ada perubahan

---

## 📄 Lisensi

Project ini menggunakan lisensi **MIT License**.  
Silakan gunakan, modifikasi, dan distribusikan sesuai kebutuhan Anda.

---

## ✨ Credits

**Dibuat dengan ❤️ untuk transparansi keuangan kelas**

Terima kasih kepada:

- **Bootstrap 5** - UI Framework
- **Chart.js** - Data visualization
- **SweetAlert2** - Interactive alerts
- **Bootstrap Icons** - Icon set

---

## 📅 Changelog

### v2.0 (Current)

- ✅ Struktur kode rapi dengan dokumentasi lengkap
- ✅ Login page yang lebih menarik
- ✅ Dashboard improved dengan icons
- ✅ Navbar yang responsive
- ✅ Notifikasi yang lebih baik

### v1.0

- Initial release dengan fitur dasar FIFO

---

**Last Updated**: May 13, 2026  
**Version**: 2.0  
**Status**: ✅ Production Ready
