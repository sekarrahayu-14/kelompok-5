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

    public function findByUsername($username)
    {
        $statement = $this->database->prepare(
            'SELECT * FROM kasir WHERE username = :username LIMIT 1'
        );
        $statement->execute(['username' => trim($username)]);

        $record = $statement->fetch();
        return $record === false ? null : $record;
    }

    public function verifyLogin($username, $password)
    {
        $user = $this->findByUsername($username);
        if (!$user) {
            return null;
        }

        $storedHash = (string) ($user['password'] ?? '');
        if (!password_verify($password, $storedHash)) {
            return null;
        }

        return $user;
    }
}
