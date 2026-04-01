# Billing ISP — Sistem Login Multi-Role

Sistem login PHP murni (MySQLi) dengan 3 level hak akses untuk manajemen ISP.

## Role & Hak Akses

| Role | Buat User | Kelola Pelanggan | Kelola Pembayaran | Kelola Jaringan |
|------|:---------:|:----------------:|:-----------------:|:---------------:|
| **Pemilik** | ✅ | ✅ | ✅ | ✅ |
| **Admin**   | ❌ | ✅ | ✅ | ✅ |
| **Teknisi** | ❌ | ✅ (lihat) | ❌ | ✅ |

> Hanya **Pemilik** yang dapat membuat akun baru.

---

## Cara Setup Lokal (Anggota Tim)

### 1. Clone repository
```bash
git clone https://github.com/username/billing-isp.git
cd billing-isp
```

### 2. Buat database
```bash
mysql -u root -p < database.sql
```

### 3. Konfigurasi database
```bash
cp config/database.example.php config/database.php
# Edit config/database.php sesuai konfigurasi lokal kamu
```

### 4. Jalankan di server lokal
Taruh folder project di:
- **Garuda/Arch Linux**: `/srv/http/billing-isp/`
- **Ubuntu/Debian**: `/var/www/html/billing-isp/`
- **Windows XAMPP**: `C:/xampp/htdocs/billing-isp/`

### 5. Akses browser
```
http://localhost/billing-isp/login.php
```

### Akun default (untuk testing)
| Username | Password | Role |
|----------|----------|------|
| `pemilik` | `password` | Pemilik |

> ⚠️ **Ganti password segera setelah login pertama!**

---

## Struktur Folder

```
billing-isp/
├── config/
│   ├── database.php          ← TIDAK di-push (ada di .gitignore)
│   └── database.example.php  ← Template untuk anggota tim
├── includes/
│   ├── auth.php              ← Fungsi login, logout, cek role
│   └── 403.php               ← Halaman akses ditolak
├── pages/
│   ├── pemilik/dashboard.php ← Dashboard + manajemen user
│   ├── admin/dashboard.php
│   └── teknisi/dashboard.php
├── login.php
├── logout.php
├── database.sql              ← Struktur tabel + akun default
├── .gitignore
└── README.md
```

---

## Workflow Tim (Git)

```bash
# Sebelum mulai kerja, selalu pull dulu
git pull origin dev

# Buat branch fitur kamu
git checkout -b feature/nama-fitur

# Setelah selesai
git add .
git commit -m "feat: deskripsi perubahan"
git push origin feature/nama-fitur

# Buat Pull Request di GitHub → ke branch dev
```

---

## Keamanan yang Sudah Diterapkan

- ✅ Password di-hash dengan `bcrypt` (cost 12)
- ✅ Proteksi CSRF di setiap form POST
- ✅ Session di-regenerate setelah login
- ✅ Prepared statements (anti SQL injection)
- ✅ Output di-escape dengan `htmlspecialchars()`
- ✅ Session timeout 8 jam
- ✅ Log aktivitas login/logout/tambah user
