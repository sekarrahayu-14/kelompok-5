<?php

class Produk extends BaseModel
{
    protected $table = 'produk';
    protected $primaryKey = 'id_produk';

    public function getAll()
    {
        return $this->all();
    }

    public function getById($id)
    {
        return $this->find($id);
    }

    public function createProduk($kategoriId, $nama, $harga, $stok)
    {
        return $this->create([
            'id_kategori' => $kategoriId,
            'nama_produk' => $nama,
            'harga' => $harga,
            'stok' => $stok,
        ]);
    }

    public function updateProduk($id, $kategoriId, $nama, $harga, $stok)
    {
        return $this->update($id, [
            'id_kategori' => $kategoriId,
            'nama_produk' => $nama,
            'harga' => $harga,
            'stok' => $stok,
        ]);
    }

    public function deleteProduk($id)
    {
        return $this->delete($id);
    }

    public function kurangiStok($id, $jumlah)
    {
        $statement = $this->database->prepare(
            "UPDATE {$this->table} SET stok = stok - :jumlah
             WHERE {$this->primaryKey} = :id AND stok >= :jumlah_check"
        );
        $statement->execute(['jumlah' => $jumlah, 'id' => $id, 'jumlah_check' => $jumlah]);
        if ($statement->rowCount() !== 1) {
            throw new RuntimeException('Stok produk tidak mencukupi.');
        }
        return true;
    }
}
