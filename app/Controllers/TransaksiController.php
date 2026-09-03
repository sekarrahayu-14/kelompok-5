<?php

class TransaksiController extends Controller
{
    private $model;

    public function __construct(Transaksi $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        return $this->model->getTransaksi();
    }

    public function create()
    {
        return ['form' => 'transaksi'];
    }

    public function store(array $input)
    {
        $data = $this->validate($input);
        return $this->model->createTransaksi(
            $data['id_kasir'],
            $data['id_pelanggan'],
            $data['tanggal_transaksi'],
            $data['pembayaran'],
            $data['details']
        );
    }

    public function detail($id)
    {
        return $this->model->getTransaksi($id);
    }

    private function validate(array $input)
    {
        $kasir = filter_var($input['id_kasir'] ?? null, FILTER_VALIDATE_INT);
        $pelanggan = filter_var($input['id_pelanggan'] ?? null, FILTER_VALIDATE_INT);
        $pembayaran = filter_var($input['pembayaran'] ?? null, FILTER_VALIDATE_FLOAT);
        $details = $input['details'] ?? [];
        if ($kasir === false || $kasir === null || $pelanggan === false || $pelanggan === null ||
            $pembayaran === false || $pembayaran < 0 || !is_array($details) || count($details) === 0) {
            throw new InvalidArgumentException('Data transaksi tidak valid.');
        }
        foreach ($details as $detail) {
            if (filter_var($detail['id_produk'] ?? null, FILTER_VALIDATE_INT) === false ||
                filter_var($detail['jumlah'] ?? null, FILTER_VALIDATE_INT) < 1 ||
                filter_var($detail['harga'] ?? null, FILTER_VALIDATE_FLOAT) < 0) {
                throw new InvalidArgumentException('Detail transaksi tidak valid.');
            }
        }
        $total = $this->model->hitungTotal($details);
        if ($pembayaran < $total) {
            throw new InvalidArgumentException('Pembayaran kurang dari total transaksi.');
        }
        return [
            'id_kasir' => $kasir,
            'id_pelanggan' => $pelanggan,
            'tanggal_transaksi' => $input['tanggal_transaksi'] ?? date('Y-m-d H:i:s'),
            'pembayaran' => $pembayaran,
            'details' => $details,
        ];
    }
}
