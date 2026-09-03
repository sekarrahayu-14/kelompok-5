<!doctype html>
<html lang="id">
    <head><meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title ?? 'SITOKO') ?></title><link rel="stylesheet" href="/kelompok-5/public/assets/css/style.css">
</head>
<body>
    <aside class="sidebar"><a class="brand" href="/kelompok-5/app/Views/dashboard.php">
    <b>SI</b><span>SITOKO<small>Koperasi sekolah</small></span></a>
    <nav>
        <a href="/kelompok-5/app/Views/dashboard.php">Dashboard</a>
        <a href="/kelompok-5/app/Views/produk.php">Produk</a>
        <a href="/kelompok-5/app/Views/kategori.php">Kategori</a>
        <a href="/kelompok-5/app/Views/pelanggan.php">Pelanggan</a>
        <a href="/kelompok-5/app/Views/kasir.php">Kasir</a>
        <a href="/kelompok-5/app/Views/transaksi.php">Transaksi</a>
        <a href="/kelompok-5/app/Views/laporan.php">Laporan</a>
     
    </nav>
</aside>
<main class="main"><header class="topbar"><button class="menu-toggle" type="button" aria-label="Buka menu">☰</button>
<span class="crumb">Koperasi Sekolah / <?= htmlspecialchars($title ?? '') ?></span>
<a class="profile" href="/kelompok-5/app/Views/logout.php">Keluar</a>
</header><div class="content"><?php if (!empty($_GET['success'])): ?><div class="notice success"><?= htmlspecialchars($_GET['success']) ?></div><?php endif; ?><?php if (!empty($error)): ?><div class="notice error"><?= htmlspecialchars($error) ?></div><?php endif; ?>