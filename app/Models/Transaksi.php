<?php

class Transaksi extends BaseModel
{
    protected $table = 'transaksi';
    protected $primaryKey = 'id_transaksi';

    public function createTransaksi($kasirId, $pelangganId, $tanggal, $pembayaran, array $details)
    {
        $this->database->beginTransaction();
        try {
            $validatedDetails = $this->validateAndPriceDetails($details);
            $total = $this->hitungTotal($validatedDetails);
            $id = $this->create([
                'id_kasir' => $kasirId,
                'id_pelanggan' => $pelangganId,
                'tanggal_transaksi' => $tanggal,
                'total' => $total,
                'pembayaran' => $pembayaran,
                'kembalian' => $pembayaran - $total,
            ]);

            $detailModel = new DetailTransaksi($this->database);
            foreach ($validatedDetails as $detail) {
                $detailModel->simpanDetail($id, $detail['id_produk'], $detail['jumlah'], $detail['harga']);
            }
            $this->database->commit();

            return $id;
        } catch (Exception $exception) {
            $this->database->rollBack();
            throw $exception;
        }
    }

    public function getTransaksi($id = null)
    {
        return $id === null ? $this->all() : $this->find($id);
    }

    public function getDetail($id)
    {
        $statement = $this->database->prepare(
            "SELECT t.*, d.id_produk, d.jumlah, d.harga, d.subtotal, p.nama_produk
             FROM transaksi t
             INNER JOIN detail_transaksi d ON d.id_transaksi = t.id_transaksi
             INNER JOIN produk p ON p.id_produk = d.id_produk
             WHERE t.id_transaksi = :id"
        );
        $statement->execute(['id' => $id]);
        return $statement->fetchAll();
    }

    public function hitungTotal(array $details)
    {
        $total = 0;
        foreach ($details as $detail) {
            $total += (int) $detail['jumlah'] * (float) $detail['harga'];
        }

        return $total;
    }

    public function simpanDetail($transaksiId, $produkId, $jumlah, $harga)
    {
        $detailModel = new DetailTransaksi($this->database);
        return $detailModel->simpanDetail($transaksiId, $produkId, $jumlah, $harga);
    }

    private function validateAndPriceDetails(array $details)
    {
        $result = [];
        $produkModel = new Produk($this->database);
        foreach ($details as $detail) {
            $produkId = filter_var($detail['id_produk'] ?? null, FILTER_VALIDATE_INT);
            $jumlah = filter_var($detail['jumlah'] ?? null, FILTER_VALIDATE_INT);
            if ($produkId === false || $produkId === null || $jumlah === false || $jumlah <= 0) {
                throw new InvalidArgumentException('Produk atau jumlah tidak valid.');
            }
            $produk = $produkModel->getById($produkId);
            if ($produk === null || (int) $produk['stok'] < $jumlah) {
                throw new RuntimeException('Produk tidak tersedia atau stok tidak mencukupi.');
            }
            $result[] = [
                'id_produk' => $produkId,
                'jumlah' => $jumlah,
                'harga' => (float) $produk['harga'],
            ];
            $produkModel->kurangiStok($produkId, $jumlah);
        }
        return $result;
    }
}
