# 💰 Sistem Informasi Kas Kelas (Gemini-Cash)

Sistem manajemen kas kelas berbasis Web yang dirancang untuk transparansi keuangan antara Bendahara, Murid, dan Wali Kelas. Dilengkapi dengan fitur **Logika FIFO** untuk pembayaran iuran mingguan secara otomatis.

## 🚀 Fitur Utama

- **Multi-Role Access**: Login untuk Bendahara (Admin), Wali Kelas, Ketua Kelas, dan Murid.
- **Logika Pembayaran FIFO**: Bendahara cukup memasukkan nominal, sistem secara otomatis melunasi tunggakan minggu-minggu sebelumnya.
- **Validasi Saldo**: Mencegah pengeluaran jika saldo kas kelas tidak mencukupi.
- **Notifikasi Interaktif**: Menggunakan **SweetAlert2** untuk feedback yang lebih modern.
- **History Transaksi**: Laporan pemasukan dan pengeluaran yang terorganisir per kelas.
- **Keamanan Session**: Proteksi halaman berdasarkan role user.

## 🛠️ Tech Stack

- **PHP 8.x** (Native)
- **MySQL** (Database)
- **Bootstrap 5** (UI Framework)
- **SweetAlert2** (Notifikasi)
- **Bootstrap Icons**

## 📦 Instalasi

1. **Clone Repository**
   ```bash
   git clone [https://github.com/username-kamu/nama-repo.git](https://github.com/username-kamu/nama-repo.git)