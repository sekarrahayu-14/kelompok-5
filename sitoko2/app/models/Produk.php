<?php
require_once __DIR__ . '/../Core/BaseModel.php';

class Produk extends BaseModel
{
    private int $id;
    private string $kodeProduk;
    private string $namaProduk;
    private float $harga;
    private int $stok;
    protected string $table = 'produk';

    public function getNamaProduk(): string { return $this->namaProduk; }
    public function setNamaProduk(string $namaProduk): void { $this->namaProduk = $namaProduk; }
    public function getStok(): int { return $this->stok; }
    public function setStok(int $stok): void { $this->stok = $stok; }

    public function all(): array
    {
        $sql = "SELECT p.*, k.nama_kategori FROM produk p JOIN kategori_produk k ON k.id = p.kategori_id ORDER BY p.id DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO {$this->table} (kategori_id, kode_produk, nama_produk, harga, stok, satuan) VALUES (:kategori_id, :kode_produk, :nama_produk, :harga, :stok, :satuan)";
        return $this->db->prepare($sql)->execute($this->payload($data));
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE {$this->table} SET kategori_id = :kategori_id, kode_produk = :kode_produk, nama_produk = :nama_produk, harga = :harga, stok = :stok, satuan = :satuan WHERE id = :id";
        $payload = $this->payload($data);
        $payload['id'] = $id;
        return $this->db->prepare($sql)->execute($payload);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    private function payload(array $data): array
    {
        return [
            'kategori_id' => (int) ($data['kategori_id'] ?? 0),
            'kode_produk' => trim($data['kode_produk'] ?? ''),
            'nama_produk' => trim($data['nama_produk'] ?? ''),
            'harga' => (float) ($data['harga'] ?? 0),
            'stok' => (int) ($data['stok'] ?? 0),
            'satuan' => trim($data['satuan'] ?? 'pcs')
        ];
    }
}
