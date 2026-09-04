<?php
session_start();

header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

$loginUrl = '/kelompok-5/app/Views/login.php';
$dashboardUrl = '/kelompok-5/app/Views/dashboard.php';
$loginError = null;

if (!empty($_SESSION['kasir_id'])) {
	header('Location: ' . $dashboardUrl);
	exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	require_once dirname(__DIR__, 2) . '/app/Core/Database.php';
	require_once dirname(__DIR__, 2) . '/app/Core/BaseModel.php';
	require_once dirname(__DIR__, 2) . '/app/Models/Kasir.php';

	$username = trim((string) ($_POST['username'] ?? ''));
	$password = (string) ($_POST['password'] ?? '');
	$user = (new Kasir())->verifyLogin($username, $password);

	if ($user) {
		session_regenerate_id(true);
		$_SESSION['kasir_id'] = (int) $user['id_kasir'];
		$_SESSION['kasir_username'] = $user['username'];
		$_SESSION['kasir_nama'] = $user['nama_kasir'] ?? $user['nama'] ?? '';
		header('Location: ' . $dashboardUrl);
		exit;
	}

	$loginError = 'Username atau password salah';
}
?>
<!doctype html>
<html lang="id">
<head><meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Masuk | SITOKO</title><link rel="stylesheet" href="/kelompok-5/public/assets/css/style.css">
</head>
<body class="login-page">
<form class="login-card" method="post" action="<?= htmlspecialchars($loginUrl) ?>">
	<?php if ($loginError !== null): ?>
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
