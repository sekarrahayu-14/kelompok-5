<?php require __DIR__ . '/../layout/header.php'; ?>
<h1>Transaksi Penjualan</h1>
<p><a class="button" href="/sitoko/penjualan/tambah">+ Transaksi Baru</a></p>
<table>
<tr><th>Kode</th><th>Tanggal</th><th>Kasir</th><th>Pelanggan</th><th>Total</th><th>Bayar</th><th>Kembalian</th></tr>
<?php foreach ($data['penjualan'] as $item): ?>
<tr><td><?= htmlspecialchars($item['kode_transaksi']) ?></td><td><?= $item['tanggal'] ?></td><td><?= htmlspecialchars($item['nama_kasir']) ?></td><td><?= htmlspecialchars($item['nama_pelanggan']) ?></td><td>Rp<?= number_format((float)$item['total'], 0, ',', '.') ?></td><td>Rp<?= number_format((float)$item['bayar'], 0, ',', '.') ?></td><td>Rp<?= number_format((float)$item['kembalian'], 0, ',', '.') ?></td></tr>
<?php endforeach; ?>
</table>
<?php require __DIR__ . '/../layout/footer.php'; ?>
