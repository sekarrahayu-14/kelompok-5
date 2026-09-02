<?php
require_once __DIR__ . '/../../config/database.php';

abstract class BaseModel
{
    protected PDO $db;
    protected string $table;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    abstract public function all(): array;
    abstract public function find(int $id): ?array;
    abstract public function create(array $data): bool;
    abstract public function update(int $id, array $data): bool;
    abstract public function delete(int $id): bool;
}
