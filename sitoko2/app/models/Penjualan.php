<?php
require_once __DIR__ . '/../Core/BaseModel.php';

class Penjualan extends BaseModel
{
    protected string $table = 'penjualan';

    public function all(): array
    {
        return $this->db->query("SELECT * FROM v_laporan_penjualan ORDER BY tanggal DESC")->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): bool
    {
        $this->db->beginTransaction();
        try {
            $produkStmt = $this->db->prepare('SELECT * FROM produk WHERE id = :id FOR UPDATE');
            $produkStmt->execute(['id' => (int) $data['produk_id']]);
            $produk = $produkStmt->fetch();

            if (!$produk || (int) $data['jumlah'] <= 0 || (int) $data['jumlah'] > (int) $produk['stok']) {
                throw new RuntimeException('Produk atau stok tidak valid.');
            }

            $jumlah = (int) $data['jumlah'];
            $total = (float) $produk['harga'] * $jumlah;
            $bayar = (float) $data['bayar'];
            if ($bayar < $total) {
                throw new RuntimeException('Pembayaran kurang.');
            }

            $kode = 'TRX-' . date('YmdHis') . '-' . random_int(10, 99);
            $stmt = $this->db->prepare('INSERT INTO penjualan (kode_transaksi, pengguna_id, pelanggan_id, total, bayar, kembalian) VALUES (:kode, :pengguna_id, :pelanggan_id, :total, :bayar, :kembalian)');
            $stmt->execute([
                'kode' => $kode,
                'pengguna_id' => (int) ($data['pengguna_id'] ?? 2),
                'pelanggan_id' => !empty($data['pelanggan_id']) ? (int) $data['pelanggan_id'] : null,
                'total' => $total,
                'bayar' => $bayar,
                'kembalian' => $bayar - $total
            ]);

            $penjualanId = (int) $this->db->lastInsertId();
            $detail = $this->db->prepare('INSERT INTO detail_penjualan (penjualan_id, produk_id, jumlah, harga, subtotal) VALUES (:penjualan_id, :produk_id, :jumlah, :harga, :subtotal)');
            $detail->execute(['penjualan_id' => $penjualanId, 'produk_id' => $produk['id'], 'jumlah' => $jumlah, 'harga' => $produk['harga'], 'subtotal' => $total]);

            $stok = $this->db->prepare('UPDATE produk SET stok = stok - :jumlah WHERE id = :id');
            $stok->execute(['jumlah' => $jumlah, 'id' => $produk['id']]);
            $this->db->commit();
            return true;
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) $this->db->rollBack();
            throw $e;
        }
    }

    public function update(int $id, array $data): bool { return false; }
    public function delete(int $id): bool { return false; }
}
