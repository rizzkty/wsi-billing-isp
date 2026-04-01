<?php
// config/database.example.php
// Salin file ini menjadi config/database.php
// lalu isi sesuai konfigurasi lokal kamu

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'ISI_PASSWORD_DISINI');
define('DB_NAME', 'billing_isp');
define('DB_PORT', 3306);

function getDB(): mysqli {
    static $conn = null;
    if ($conn === null) {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
        if ($conn->connect_error) {
            error_log('DB Error: ' . $conn->connect_error);
            die('Koneksi database gagal.');
        }
        $conn->set_charset('utf8mb4');
    }
    return $conn;
}
