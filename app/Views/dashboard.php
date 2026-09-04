<?php
session_start();

header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

if (empty($_SESSION['kasir_id'])) {
    header('Location: /kelompok-5/app/Views/login.php');
    exit;
}

require_once dirname(__DIR__, 2) . '/app/Core/Database.php';
require_once dirname(__DIR__, 2) . '/app/Core/BaseModel.php';

foreach (glob(dirname(__DIR__, 2) . '/app/Models/*.php') as $file) {
    require_once $file;
}

$title = 'Dashboard';
$products = (new Produk())->getAll();
$transactions = (new Transaksi())->getTransaksi();

require __DIR__ . '/layout.php';
?>
<section class="hero"><div><p class="eyebrow">SITOKO / OPERASIONAL</p><h1>Selamat datang di SITOKO</h1><p>Ringkasan aktivitas koperasi sekolah dalam satu pandangan.</p></div><a class="button" href="/kelompok-5/transaksi">+ Transaksi baru</a></section>
<div class="stats"><article><span>Produk</span><strong><?= count($products ?? []) ?></strong><small>Terdaftar</small></article><article><span>Transaksi</span><strong><?= count($transactions ?? []) ?></strong><small>Riwayat tersimpan</small></article><article><span>Status</span><strong>Aktif</strong><small>Operasional koperasi</small></article></div>
<section class="panel"><div class="panel-head"><div><p class="eyebrow">AKTIVITAS</p><h2>Transaksi terbaru</h2></div><a href="/kelompok-5/laporan">Lihat laporan</a></div><div class="table-wrap"><table><thead><tr><th>ID</th><th>Tanggal</th><th>Total</th></tr></thead><tbody><?php foreach (array_slice($transactions ?? [], 0, 6) as $row): ?><tr><td>#<?= htmlspecialchars($row['id_transaksi']) ?></td><td><?= htmlspecialchars($row['tanggal_transaksi']) ?></td><td>Rp <?= number_format($row['total'], 0, ',', '.') ?></td></tr><?php endforeach; ?></tbody></table></div></section>
<?php require __DIR__ . '/footer.php'; ?>