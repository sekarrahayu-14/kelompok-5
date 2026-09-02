<?php require __DIR__ . '/../layout/header.php'; ?>
<h1>Transaksi Baru</h1>
<form method="post" action="/sitoko/penjualan/simpan">
<label>Produk</label><select name="produk_id" required>
<?php foreach ($data['produk'] as $p): ?>
<option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nama_produk']) ?> - Rp<?= number_format((float)$p['harga'], 0, ',', '.') ?> (stok: <?= $p['stok'] ?>)</option>
<?php endforeach; ?>
</select>
<label>Jumlah</label><input type="number" name="jumlah" min="1" value="1" required>
<label>Bayar</label><input type="number" name="bayar" min="0" required>
<input type="hidden" name="pengguna_id" value="2">
<button type="submit">Simpan Transaksi</button>
</form>
<?php require __DIR__ . '/../layout/footer.php'; ?>
