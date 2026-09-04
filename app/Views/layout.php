<!doctype html>
<html lang="id">
    <head><meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title ?? 'SITOKO') ?></title><link rel="stylesheet" href="/kelompok-5/public/assets/css/style.css">
</head>
<body>
    <aside class="sidebar"><a class="brand" href="/kelompok-5/">
    <b>SI</b><span>SITOKO<small>Koperasi sekolah</small></span></a>
    <nav>
        <a href="/kelompok-5/">Dashboard</a>
        <a href="/kelompok-5/public/index.php?route=/produk">Produk</a>
        <a href="/kelompok-5/public/index.php?route=/kategori">Kategori</a>
        <a href="/kelompok-5/public/index.php?route=/pelanggan">Pelanggan</a>
        <a href="/kelompok-5/public/index.php?route=/kasir">Kasir</a>
        <a href="/kelompok-5/public/index.php?route=/transaksi">Transaksi</a>
        <a href="/kelompok-5/public/index.php?route=/laporan">Laporan</a>
        <a href="/kelompok-5/app/Views/logout.php">Keluar</a>
    </nav>
</aside>
<main class="main"><header class="topbar"><button class="menu-toggle" type="button" aria-label="Buka menu">☰</button>
<span class="crumb">Koperasi Sekolah / <?= htmlspecialchars($title ?? '') ?></span>
<a class="profile" href="/kelompok-5/app/Views/logout.php">Keluar</a>
</header><div class="content"><?php if (!empty($_GET['success'])): ?><div class="notice success"><?= htmlspecialchars($_GET['success']) ?></div><?php endif; ?><?php if (!empty($_GET['error'])): ?><div class="notice error"><?= htmlspecialchars($_GET['error']) ?></div><?php endif; ?><?php if (!empty($error)): ?><div class="notice error"><?= htmlspecialchars($error) ?></div><?php endif; ?>