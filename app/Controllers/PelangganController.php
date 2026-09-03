<?php

class PelangganController extends Controller
{
    private $model;

    public function __construct(Pelanggan $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        return $this->model->getAll();
    }

    public function create()
    {
        return ['form' => 'pelanggan'];
    }

    public function store(array $input)
    {
        $data = $this->validate($input);
        return $this->model->createPelanggan($data['nama_pelanggan'], $data['no_telp'], $data['alamat']);
    }

    public function edit($id)
    {
        return $this->model->getById($id);
    }

    public function update($id, array $input)
    {
        $data = $this->validate($input);
        return $this->model->updatePelanggan($id, $data['nama_pelanggan'], $data['no_telp'], $data['alamat']);
    }

    public function delete($id)
    {
        return $this->model->deletePelanggan($id);
    }

    private function validate(array $input)
    {
        $data = [
            'nama_pelanggan' => trim($input['nama_pelanggan'] ?? ''),
            'no_telp' => trim($input['no_telp'] ?? ''),
            'alamat' => trim($input['alamat'] ?? ''),
        ];
        if ($data['nama_pelanggan'] === '') {
            throw new InvalidArgumentException('Nama pelanggan wajib diisi.');
        }
        return $data;
    }
}
