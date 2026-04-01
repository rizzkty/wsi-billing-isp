-- =============================================
-- Database: billing_isp
-- =============================================

CREATE DATABASE IF NOT EXISTS billing_isp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE billing_isp;

-- Tabel users
CREATE TABLE IF NOT EXISTS users (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nama        VARCHAR(100) NOT NULL,
    username    VARCHAR(50)  NOT NULL UNIQUE,
    password    VARCHAR(255) NOT NULL,
    role        ENUM('pemilik','admin','teknisi') NOT NULL DEFAULT 'teknisi',
    aktif       TINYINT(1) NOT NULL DEFAULT 1,
    dibuat_oleh INT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (dibuat_oleh) REFERENCES users(id) ON DELETE SET NULL
);

-- Tabel log_aktivitas
CREATE TABLE IF NOT EXISTS log_aktivitas (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    user_id    INT NOT NULL,
    aksi       VARCHAR(255) NOT NULL,
    keterangan TEXT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insert akun pemilik default
-- Password: pemilik123 (ganti setelah login pertama!)
INSERT INTO users (nama, username, password, role, aktif, dibuat_oleh)
VALUES (
    'Pemilik ISP',
    'pemilik',
    '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'pemilik',
    1,
    NULL
);
-- Password hash di atas = "password" (untuk testing)
-- Ganti dengan: php -r "echo password_hash('passwordbaru', PASSWORD_BCRYPT, ['cost'=>12]);"
