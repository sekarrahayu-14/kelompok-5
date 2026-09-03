<?php

class Pelanggan extends BaseModel
{
    protected $table = 'pelanggan';
    protected $primaryKey = 'id_pelanggan';

    public function getAll()
    {
        return $this->all();
    }

    public function getById($id)
    {
        return $this->find($id);
    }

    public function createPelanggan($nama, $telepon, $alamat)
    {
        return $this->create([
            'nama_pelanggan' => $nama,
            'no_telp' => $telepon,
            'alamat' => $alamat,
        ]);
    }

    public function updatePelanggan($id, $nama, $telepon, $alamat)
    {
        return $this->update($id, [
            'nama_pelanggan' => $nama,
            'no_telp' => $telepon,
            'alamat' => $alamat,
        ]);
    }

    public function deletePelanggan($id)
    {
        return $this->delete($id);
    }
}
