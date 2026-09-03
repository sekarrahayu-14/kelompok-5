<?php

class ProdukController extends Controller
{
    private $model;

    public function __construct(Produk $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        return $this->model->getAll();
    }

    public function create()
    {
        return ['form' => 'produk'];
    }

    public function store(array $input)
    {
        $data = $this->validate($input);
        return $this->model->createProduk($data['id_kategori'], $data['nama_produk'], $data['harga'], $data['stok']);
    }

    public function edit($id)
    {
        return $this->model->getById($id);
    }

    public function update($id, array $input)
    {
        $data = $this->validate($input);
        return $this->model->updateProduk($id, $data['id_kategori'], $data['nama_produk'], $data['harga'], $data['stok']);
    }

    public function delete($id)
    {
        return $this->model->deleteProduk($id);
    }

    private function validate(array $input)
    {
        $data = [
            'id_kategori' => filter_var($input['id_kategori'] ?? null, FILTER_VALIDATE_INT),
            'nama_produk' => trim($input['nama_produk'] ?? ''),
            'harga' => filter_var($input['harga'] ?? null, FILTER_VALIDATE_FLOAT),
            'stok' => filter_var($input['stok'] ?? null, FILTER_VALIDATE_INT),
        ];
        if ($data['id_kategori'] === false || $data['id_kategori'] === null || $data['nama_produk'] === '' ||
            $data['harga'] === false || $data['harga'] < 0 || $data['stok'] === false || $data['stok'] < 0) {
            throw new InvalidArgumentException('Data produk tidak valid.');
        }
        return $data;
    }
}
