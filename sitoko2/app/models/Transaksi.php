<?php

class Transaksi extends Model
{
    protected string $table = "transaksi";

    public function generateNoStruk(): string
    {
        $prefix = "TX-" . date("ymd");
        $stmt   = $this->db->prepare("SELECT COUNT(*) AS jumlah FROM transaksi WHERE DATE(created_at) = CURDATE()");
        $stmt->execute();
        $count = (int) $stmt->fetch()["jumlah"] + 1;
        return $prefix . "-" . str_pad((string) $count, 4, "0", STR_PAD_LEFT);
    }

    public function riwayat(string $tanggalMulai = "", string $tanggalAkhir = "", $userId = null): array
    {
        $sql = "SELECT t.*, u.nama AS nama_kasir
                FROM transaksi t
                JOIN users u ON t.user_id = u.id
                WHERE 1=1";
        $params = [];

        if ($tanggalMulai !== "") {
            $sql     .= " AND DATE(t.created_at) >= ?";
            $params[] = $tanggalMulai;
        }
        if ($tanggalAkhir !== "") {
            $sql     .= " AND DATE(t.created_at) <= ?";
            $params[] = $tanggalAkhir;
        }
        if ($userId) {
            $sql     .= " AND t.user_id = ?";
            $params[] = $userId;
        }
        $sql .= " ORDER BY t.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function ringkasan(string $tanggalMulai, string $tanggalAkhir): array
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) AS total_transaksi, COALESCE(SUM(total),0) AS total_pendapatan
             FROM transaksi WHERE DATE(created_at) BETWEEN ? AND ?"
        );
        $stmt->execute([$tanggalMulai, $tanggalAkhir]);
        return $stmt->fetch();
    }

    public function pendapatanHarian(string $tanggalMulai, string $tanggalAkhir): array
    {
        $stmt = $this->db->prepare(
            "SELECT DATE(created_at) AS tanggal, SUM(total) AS pendapatan
             FROM transaksi WHERE DATE(created_at) BETWEEN ? AND ?
             GROUP BY DATE(created_at) ORDER BY tanggal ASC"
        );
        $stmt->execute([$tanggalMulai, $tanggalAkhir]);
        return $stmt->fetchAll();
    }

    public function produkTerlaris(string $tanggalMulai, string $tanggalAkhir)
    {
        $stmt = $this->db->prepare(
            "SELECT td.nama_produk, SUM(td.qty) AS total_qty
             FROM transaksi_detail td
             JOIN transaksi t ON td.transaksi_id = t.id
             WHERE DATE(t.created_at) BETWEEN ? AND ?
             GROUP BY td.nama_produk
             ORDER BY total_qty DESC LIMIT 1"
        );
        $stmt->execute([$tanggalMulai, $tanggalAkhir]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /**
     * Memproses satu transaksi penjualan secara atomik:
     * validasi stok -> simpan header -> simpan detail -> kurangi stok.
     * $items = [["produk_id" => 1, "qty" => 2], ...]
     */
    public function prosesPenjualan(array $items, float $bayar, int $userId): array
    {
        if (empty($items)) {
            throw new Exception("Keranjang masih kosong.");
        }

        $produkModel = new Produk();
        $detailModel = new TransaksiDetail();

        $total     = 0;
        $validated = [];

        foreach ($items as $item) {
            $produk = $produkModel->find($item["produk_id"]);
            $qty    = (int) $item["qty"];

            if (!$produk) {
                throw new Exception("Produk tidak ditemukan.");
            }
            if ($qty < 1) {
                throw new Exception("Jumlah produk tidak valid.");
            }
            if ((int) $produk["stok"] < $qty) {
                throw new Exception("Stok {$produk[nama_produk]} tidak mencukupi.");
            }

            $subtotal   = $produk["harga"] * $qty;
            $total     += $subtotal;
            $validated[] = ["produk" => $produk, "qty" => $qty, "subtotal" => $subtotal];
        }

        if ($bayar < $total) {
            throw new Exception("Uang yang dibayarkan kurang dari total belanja.");
        }

        $this->db->beginTransaction();
        try {
            $transaksiId = $this->create([
                "no_struk"  => $this->generateNoStruk(),
                "user_id"   => $userId,
                "total"     => $total,
                "bayar"     => $bayar,
                "kembalian" => $bayar - $total,
            ]);

            foreach ($validated as $v) {
                $detailModel->create([
                    "transaksi_id"  => $transaksiId,
                    "produk_id"     => $v["produk"]["id"],
                    "nama_produk"   => $v["produk"]["nama_produk"],
                    "harga_satuan"  => $v["produk"]["harga"],
                    "qty"           => $v["qty"],
                    "subtotal"      => $v["subtotal"],
                ]);
                $produkModel->kurangiStok($v["produk"]["id"], $v["qty"]);
            }

            $this->db->commit();
            return $this->find($transaksiId);
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
