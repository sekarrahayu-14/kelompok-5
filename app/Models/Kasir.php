<?php

class Kasir extends BaseModel
{
    protected $table = 'kasir';
    protected $primaryKey = 'id_kasir';

    public function getAll()
    {
        return $this->all();
    }

    public function getById($id)
    {
        return $this->find($id);
    }

    public function createKasir($nama, $username, $password)
    {
        return $this->create([
            'nama_kasir' => $nama,
            'username' => $username,
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ]);
    }

    public function updateKasir($id, $nama, $username, $password = null)
    {
        $data = ['nama_kasir' => $nama, 'username' => $username];
        if ($password !== null && $password !== '') {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        return $this->update($id, $data);
    }

    public function deleteKasir($id)
    {
        return $this->delete($id);
    }
}
