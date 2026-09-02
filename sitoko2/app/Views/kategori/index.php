<?php require __DIR__ . '/../layout/header.php'; ?>
<h1>Kategori Produk</h1>
<form method="post" action="/sitoko/kategori/simpan" class="inline-form">
<input name="nama_kategori" placeholder="Nama kategori" required>
<button type="submit">Tambah Kategori</button>
</form>
<table>
<tr><th>No</th><th>Nama Kategori</th><th>Aksi</th></tr>
<?php foreach ($data['kategori'] as $i => $item): ?>
<tr><td><?= $i + 1 ?></td><td><?= htmlspecialchars($item['nama_kategori']) ?></td><td><a href="/sitoko/kategori/hapus/<?= $item['id'] ?>" onclick="return confirm('Hapus kategori ini?')">Hapus</a></td></tr>
<?php endforeach; ?>
</table>
<?php require __DIR__ . '/../layout/footer.php'; ?>
