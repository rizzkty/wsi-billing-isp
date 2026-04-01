<?php
// includes/auth.php

require_once __DIR__ . '/../config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_httponly' => true,
        'cookie_samesite' => 'Strict',
        'use_strict_mode'  => true,
    ]);
}

// ─── Regenerasi token CSRF ────────────────────────────────────────────────────
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_verify(): void {
    $token = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(403);
        die('Token tidak valid. Refresh halaman dan coba lagi.');
    }
}

// ─── Login ────────────────────────────────────────────────────────────────────
function login(string $username, string $password): array {
    $db   = getDB();
    $stmt = $db->prepare(
        "SELECT id, nama, username, password, role, aktif FROM users WHERE username = ? LIMIT 1"
    );
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$user || !$user['aktif']) {
        return ['sukses' => false, 'pesan' => 'Username atau password salah.'];
    }

    if (!password_verify($password, $user['password'])) {
        return ['sukses' => false, 'pesan' => 'Username atau password salah.'];
    }

    // Buat sesi baru setelah login
    session_regenerate_id(true);
    $_SESSION['user_id']   = $user['id'];
    $_SESSION['nama']      = $user['nama'];
    $_SESSION['username']  = $user['username'];
    $_SESSION['role']      = $user['role'];
    $_SESSION['login_at']  = time();

    log_aktivitas($user['id'], 'LOGIN', 'Login berhasil');

    return ['sukses' => true, 'role' => $user['role']];
}

// ─── Logout ───────────────────────────────────────────────────────────────────
function logout(): void {
    if (!empty($_SESSION['user_id'])) {
        log_aktivitas($_SESSION['user_id'], 'LOGOUT', 'Logout');
    }
    $_SESSION = [];
    session_destroy();
    header('Location: /billing-isp/login.php');
    exit;
}

// ─── Cek login ────────────────────────────────────────────────────────────────
function cekLogin(): void {
    if (empty($_SESSION['user_id'])) {
        header('Location: /billing-isp/login.php');
        exit;
    }
    // Timeout sesi 8 jam
    if (time() - ($_SESSION['login_at'] ?? 0) > 28800) {
        logout();
    }
}

// ─── Cek role ─────────────────────────────────────────────────────────────────
/**
 * Hentikan akses jika role tidak diizinkan.
 * Contoh: cekRole(['pemilik', 'admin'])
 */
function cekRole(array $roles_diizinkan): void {
    cekLogin();
    if (!in_array($_SESSION['role'] ?? '', $roles_diizinkan, true)) {
        http_response_code(403);
        include __DIR__ . '/403.php';
        exit;
    }
}

function roleSaat(): string {
    return $_SESSION['role'] ?? '';
}

function isPemilik(): bool { return roleSaat() === 'pemilik'; }
function isAdmin(): bool   { return in_array(roleSaat(), ['pemilik', 'admin'], true); }

// ─── Redirect setelah login berdasarkan role ──────────────────────────────────
function redirectDashboard(): void {
    $map = [
        'pemilik'  => '/billing-isp/pages/pemilik/dashboard.php',
        'admin'    => '/billing-isp/pages/admin/dashboard.php',
        'teknisi'  => '/billing-isp/pages/teknisi/dashboard.php',
    ];
    $tujuan = $map[$_SESSION['role'] ?? ''] ?? '/login.php';
    header("Location: $tujuan");
    exit;
}

// ─── Log aktivitas ────────────────────────────────────────────────────────────
function log_aktivitas(int $user_id, string $aksi, string $keterangan = ''): void {
    try {
        $db   = getDB();
        $ip   = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $stmt = $db->prepare(
            "INSERT INTO log_aktivitas (user_id, aksi, keterangan, ip_address) VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param('isss', $user_id, $aksi, $keterangan, $ip);
        $stmt->execute();
        $stmt->close();
    } catch (Exception $e) {
        error_log('Log error: ' . $e->getMessage());
    }
}
