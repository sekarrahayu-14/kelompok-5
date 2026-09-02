<?php
require_once __DIR__ . '/../Core/BaseModel.php';

class KategoriProduk extends BaseModel
{
    private int $id;
    private string $namaKategori;
    protected string $table = 'kategori_produk';

    public function getNamaKategori(): string
    {
        return $this->namaKategori;
    }

    public function setNamaKategori(string $namaKategori): void
    {
        $this->namaKategori = $namaKategori;
    }

    public function all(): array
    {
        return $this->db->query("SELECT * FROM {$this->table} ORDER BY nama_kategori ASC")->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (nama_kategori) VALUES (:nama_kategori)");
        return $stmt->execute(['nama_kategori' => trim($data['nama_kategori'])]);
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET nama_kategori = :nama_kategori WHERE id = :id");
        return $stmt->execute(['id' => $id, 'nama_kategori' => trim($data['nama_kategori'])]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
