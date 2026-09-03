<?php

class Kategori extends BaseModel
{
    protected $table = 'kategori';
    protected $primaryKey = 'id_kategori';

    public function getAll()
    {
        return $this->all();
    }

    public function getById($id)
    {
        return $this->find($id);
    }

    public function createKategori($nama)
    {
        return $this->create(['nama_kategori' => $nama]);
    }

    public function updateKategori($id, $nama)
    {
        return $this->update($id, ['nama_kategori' => $nama]);
    }

    public function deleteKategori($id)
    {
        return $this->delete($id);
    }
}
