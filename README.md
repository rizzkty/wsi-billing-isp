
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


