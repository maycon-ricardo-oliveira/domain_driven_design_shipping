<?php

namespace Adapters\MySqlAdapter;

interface DBConnection
{
    public function query(string $statement, array $params = []);
    public function close(): void;

    public function lastInsertId();

}
