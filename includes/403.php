<?php
// includes/403.php
http_response_code(403);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Akses Ditolak</title>
    <style>
        body { font-family: sans-serif; display: flex; align-items: center; justify-content: center;
               height: 100vh; margin: 0; background: #f8f9fa; }
        .box { text-align: center; padding: 2rem; background: #fff; border-radius: 12px;
               box-shadow: 0 2px 16px rgba(0,0,0,.08); }
        h1 { color: #dc3545; font-size: 3rem; margin: 0; }
        p  { color: #6c757d; }
        a  { color: #0d6efd; text-decoration: none; }
    </style>
</head>
<body>
    <div class="box">
        <h1>403</h1>
        <p>Kamu tidak punya izin untuk mengakses halaman ini.</p>
        <a href="javascript:history.back()">← Kembali</a>
    </div>
</body>
</html>
