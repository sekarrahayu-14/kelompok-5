<?php

class KategoriController extends Controller
{
    private $model;

    public function __construct(Kategori $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        return $this->model->getAll();
    }

    public function create()
    {
        return ['form' => 'kategori'];
    }

    public function store(array $input)
    {
        return $this->model->createKategori($this->validate($input));
    }

    public function edit($id)
    {
        return $this->model->getById($id);
    }

    public function update($id, array $input)
    {
        return $this->model->updateKategori($id, $this->validate($input));
    }

    public function delete($id)
    {
        return $this->model->deleteKategori($id);
    }

    private function validate(array $input)
    {
        $nama = trim($input['nama_kategori'] ?? '');
        if ($nama === '') {
            throw new InvalidArgumentException('Nama kategori wajib diisi.');
        }
        return $nama;
    }
}
