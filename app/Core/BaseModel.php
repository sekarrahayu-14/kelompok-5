<?php

abstract class BaseModel
{
    protected $database;
    protected $table;
    protected $primaryKey = 'id';

    public function __construct(PDO $database = null)
    {
        $this->database = $database ?? Database::getInstance()->connect();
    }

    public function all(): array
    {
        $statement = $this->database->prepare("SELECT * FROM {$this->table}");
        $statement->execute();

        return $statement->fetchAll();
    }

    public function find($id)
    {
        $statement = $this->database->prepare(
            "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id LIMIT 1"
        );
        $statement->execute(['id' => $id]);
        $record = $statement->fetch();

        return $record === false ? null : $record;
    }

    public function create(array $data)
    {
        $columns = array_keys($data);
        $placeholders = array_map(static function ($column) {
            return ":{$column}";
        }, $columns);
        $columnList = implode(', ', $columns);
        $placeholderList = implode(', ', $placeholders);
        $statement = $this->database->prepare(
            "INSERT INTO {$this->table} ({$columnList}) VALUES ({$placeholderList})"
        );
        $statement->execute($data);

        return $this->database->lastInsertId();
    }

    public function update($id, array $data)
    {
        $assignments = [];
        foreach (array_keys($data) as $column) {
            $assignments[] = "{$column} = :{$column}";
        }

        $data['__id'] = $id;
        $statement = $this->database->prepare(
            "UPDATE {$this->table} SET " . implode(', ', $assignments) .
            " WHERE {$this->primaryKey} = :__id"
        );

        return $statement->execute($data);
    }

    public function delete($id)
    {
        $statement = $this->database->prepare(
            "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id"
        );

        return $statement->execute(['id' => $id]);
    }
}
