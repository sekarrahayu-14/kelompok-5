<?php

class Laporan extends BaseModel
{
    protected $table = 'transaksi';
    protected $primaryKey = 'id_transaksi';

    public function laporanPenjualan($mulai = null, $selesai = null)
    {
        $sql = "SELECT t.*, k.nama_kasir, p.nama_pelanggan
            FROM transaksi t
            INNER JOIN kasir k ON k.id_kasir = t.id_kasir
            LEFT JOIN pelanggan p ON p.id_pelanggan = t.id_pelanggan";
        $parameters = [];
        if ($mulai !== null && $selesai !== null) {
            $sql .= ' WHERE t.tanggal_transaksi BETWEEN :mulai AND :selesai';
            $parameters = ['mulai' => $mulai, 'selesai' => $selesai];
        }
        $sql .= ' ORDER BY t.tanggal_transaksi DESC';
        $statement = $this->database->prepare($sql);
        $statement->execute($parameters);

        return $statement->fetchAll();
    }

    public function totalPenjualan($mulai = null, $selesai = null)
    {
        $result = $this->summary($mulai, $selesai);
        return (float) ($result['total_penjualan'] ?? 0);
    }

    public function jumlahTransaksi($mulai = null, $selesai = null)
    {
        $result = $this->summary($mulai, $selesai);
        return (int) ($result['jumlah_transaksi'] ?? 0);
    }

    private function summary($mulai, $selesai)
    {
        $sql = "SELECT COALESCE(SUM(total), 0) AS total_penjualan, COUNT(*) AS jumlah_transaksi FROM {$this->table}";
        $parameters = [];
        if ($mulai !== null && $selesai !== null) {
            $sql .= ' WHERE tanggal_transaksi BETWEEN :mulai AND :selesai';
            $parameters = ['mulai' => $mulai, 'selesai' => $selesai];
        }
        $statement = $this->database->prepare($sql);
        $statement->execute($parameters);

        return $statement->fetch();
    }
}
