<?php

class DetailTransaksi extends BaseModel
{
    protected $table = 'detail_transaksi';
    protected $primaryKey = 'id_detail';

    public function simpanDetail($transaksiId, $produkId, $jumlah, $harga)
    {
        return $this->create([
            'id_transaksi' => $transaksiId,
            'id_produk' => $produkId,
            'jumlah' => $jumlah,
            'harga' => $harga,
            'subtotal' => (int) $jumlah * (float) $harga,
        ]);
    }

    public function getByTransaksi($transaksiId)
    {
        $statement = $this->database->prepare(
            "SELECT * FROM {$this->table} WHERE id_transaksi = :id_transaksi"
        );
        $statement->execute(['id_transaksi' => $transaksiId]);

        return $statement->fetchAll();
    }
}
