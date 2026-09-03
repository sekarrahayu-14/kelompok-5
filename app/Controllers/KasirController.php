<?php

class KasirController extends Controller
{
    private $model;

    public function __construct(Kasir $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        return $this->model->getAll();
    }

    public function create()
    {
        return ['form' => 'kasir'];
    }

    public function store(array $input)
    {
        $data = $this->validate($input, true);
        return $this->model->createKasir($data['nama_kasir'], $data['username'], $data['password']);
    }

    public function edit($id)
    {
        return $this->model->getById($id);
    }

    public function update($id, array $input)
    {
        $data = $this->validate($input, false);
        return $this->model->updateKasir($id, $data['nama_kasir'], $data['username'], $data['password']);
    }

    public function delete($id)
    {
        return $this->model->deleteKasir($id);
    }

    private function validate(array $input, $passwordRequired)
    {
        $data = [
            'nama_kasir' => trim($input['nama_kasir'] ?? ''),
            'username' => trim($input['username'] ?? ''),
            'password' => $input['password'] ?? '',
        ];
        if ($data['nama_kasir'] === '' || $data['username'] === '' || ($passwordRequired && $data['password'] === '')) {
            throw new InvalidArgumentException('Data kasir tidak valid.');
        }
        return $data;
    }
}
