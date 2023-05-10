<?php

namespace Adapters\MySqlAdapter;

use PDO;

class MySqlAdapter implements DBConnection
{

    public $connection;

    public function __construct()
    {
        $host = $_ENV('DB_HOST');
        $port = $_ENV('DB_PORT');
        $database = $_ENV('DB_DATABASE');
        $this->connection = new PDO("mysql:host={$host}:{$port};dbname={$database}", $_ENV('DB_USERNAME'), $_ENV('DB_PASSWORD'));
    }

    public function query(string $statement, array $params = [])
    {
        $stmt = $this->connection->prepare($statement);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function lastInsertId()
    {
        return $this->connection->lastInsertId();
    }

    public function close(): void
    {
        $this->connection = null;
    }
}
