<?php require __DIR__ . '/../layout/header.php'; ?>
<h1>Data Produk</h1>
<p><a class="button" href="/sitoko/produk/tambah">+ Tambah Produk</a></p>
<table>
<tr><th>No</th><th>Kode</th><th>Produk</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Satuan</th><th>Aksi</th></tr>
<?php foreach ($data['produk'] as $i => $item): ?>
<tr>
<td><?= $i + 1 ?></td><td><?= htmlspecialchars($item['kode_produk']) ?></td><td><?= htmlspecialchars($item['nama_produk']) ?></td><td><?= htmlspecialchars($item['nama_kategori']) ?></td><td>Rp<?= number_format((float)$item['harga'], 0, ',', '.') ?></td><td><?= $item['stok'] ?></td><td><?= htmlspecialchars($item['satuan']) ?></td>
<td><a href="/sitoko/produk/edit/<?= $item['id'] ?>">Edit</a> | <a href="/sitoko/produk/hapus/<?= $item['id'] ?>" onclick="return confirm('Hapus produk ini?')">Hapus</a></td>
</tr>
<?php endforeach; ?>
</table>
<?php require __DIR__ . '/../layout/footer.php'; ?>
