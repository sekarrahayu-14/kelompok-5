<?php
session_start();

if (!empty($_SESSION['kasir_id'])) {
    header('Location: /kelompok-5/app/Views/dashboard.php');
    exit;
}

$loginError = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once dirname(__DIR__, 2) . '/app/Core/Database.php';
    require_once dirname(__DIR__, 2) . '/app/Core/BaseModel.php';
    require_once dirname(__DIR__, 2) . '/app/Models/Kasir.php';

    $username = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    $user = (new Kasir())->verifyLogin($username, $password);

    if ($user) {
        $_SESSION['kasir_id'] = (int) $user['id_kasir'];
        $_SESSION['kasir_username'] = $user['username'];
        $_SESSION['kasir_nama'] = $user['nama_kasir'];
        header('Location: /kelompok-5/app/Views/dashboard.php');
        exit;
    }

    $loginError = 'Username atau password salah';
}

$title = 'Masuk';
?>
<!doctype html>
<html lang="id">
<head><meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Masuk | SITOKO</title><link rel="stylesheet" href="/kelompok-5/public/assets/css/style.css">
</head>
<body class="login-page">
<form class="login-card" method="post" action="/kelompok-5/app/Views/login.php">
    <?php if (!empty($loginError)): ?>
        <div class="notice error"><?= htmlspecialchars($loginError) ?></div>
    <?php endif; ?>
    <div class="brand"><b>SI</b><span>SITOKO<small>Koperasi sekolah</small></span></div>
    <p class="eyebrow">RUANG KASIR</p>
    <h1>Masuk ke akun Anda</h1>
    <label>Username<input name="username" required></label>
    <label>Password<input type="password" name="password" required></label>
    <button class="button" type="submit">Masuk ke dashboard</button>
</form>
</body>
</html>