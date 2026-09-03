<?php

class LaporanController extends Controller
{
    private $model;

    public function __construct(Laporan $model)
    {
        $this->model = $model;
    }

    public function index($mulai = null, $selesai = null)
    {
        if (($mulai === null) !== ($selesai === null)) {
            throw new InvalidArgumentException('Periode laporan harus lengkap.');
        }
        return [
            'laporan' => $this->model->laporanPenjualan($mulai, $selesai),
            'total_penjualan' => $this->model->totalPenjualan($mulai, $selesai),
            'jumlah_transaksi' => $this->model->jumlahTransaksi($mulai, $selesai),
        ];
    }
}
