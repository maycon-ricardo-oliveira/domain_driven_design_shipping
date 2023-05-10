<?php


// Caminho para o arquivo do banco de dados SQLite
$databaseFile = 'shipping_companies.sqlite';

// Verifica se o arquivo do banco de dados já existe
if (!file_exists($databaseFile)) {
    // Cria uma conexão com o banco de dados
    $pdo = new PDO('sqlite:' . $databaseFile);

    // Cria as tabelas necessárias
    $pdo->exec("
        CREATE TABLE shipping_companies (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            country TEXT NOT NULL
        );
        
        CREATE TABLE shipping_company_zipcode_ranges (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            shipping_company_id INTEGER NOT NULL,
            min_zipcode TEXT NOT NULL,
            max_zipcode TEXT NOT NULL,
            delivery_time INTEGER NOT NULL,
            price FLOAT NOT NULL,
            FOREIGN KEY (shipping_company_id) REFERENCES shipping_companies (id)
        );
    ");

    echo "Banco de dados criado com sucesso." . PHP_EOL;
} else {
    echo "O banco de dados já existe." . PHP_EOL;
}
