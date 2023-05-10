<?php

namespace Adapters\MySqlAdapter;

use PDO;

class SqLite implements DBConnection
{
    public $connection;
    public function __construct()
    {
        if (is_null($this->connection)) {
            $caminhoBanco = __DIR__ . '/../../../shipping_companies.sqlite';
            $this->connection = new \PDO('sqlite:' . $caminhoBanco);
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }
    }

    public function query(string $statement, array $params = [])
    {
        $stmt = $this->connection->prepare($statement);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function close(): void
    {
        $this->connection = null;
    }

    public function lastInsertId()
    {
        return $this->connection->lastInsertId();
    }
}