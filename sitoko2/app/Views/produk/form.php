<?php require __DIR__ . '/../layout/header.php'; ?>
<h1><?= htmlspecialchars($data['title']) ?></h1>
<form method="post" action="<?= $data['action'] ?>">
<label>Kategori</label><select name="kategori_id" required>
<?php foreach ($data['kategori'] as $k): ?>
<option value="<?= $k['id'] ?>" <?= (($data['produk']['kategori_id'] ?? '') == $k['id']) ? 'selected' : '' ?>><?= htmlspecialchars($k['nama_kategori']) ?></option>
<?php endforeach; ?>
</select>
<label>Kode Produk</label><input name="kode_produk" required value="<?= htmlspecialchars($data['produk']['kode_produk'] ?? '') ?>">
<label>Nama Produk</label><input name="nama_produk" required value="<?= htmlspecialchars($data['produk']['nama_produk'] ?? '') ?>">
<label>Harga</label><input type="number" name="harga" min="0" required value="<?= $data['produk']['harga'] ?? 0 ?>">
<label>Stok</label><input type="number" name="stok" min="0" required value="<?= $data['produk']['stok'] ?? 0 ?>">
<label>Satuan</label><input name="satuan" required value="<?= htmlspecialchars($data['produk']['satuan'] ?? 'pcs') ?>">
<button type="submit">Simpan</button>
</form>
<?php require __DIR__ . '/../layout/footer.php'; ?>
