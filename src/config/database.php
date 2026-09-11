<?php

declare(strict_types=1);

function getDatabase(): PDO
{
    static $database = null;

    if ($database instanceof PDO) {
        return $database;
    }

    $projectRoot = dirname(__DIR__, 2);
    $driver = strtolower((string) (getenv('DB_DRIVER') ?: 'sqlite'));

    if ($driver === 'mysql') {
        $host = (string) getenv('DB_HOST');
        $port = (string) (getenv('DB_PORT') ?: '3306');
        $databaseName = (string) getenv('DB_NAME');
        $username = (string) getenv('DB_USER');
        $password = (string) getenv('DB_PASSWORD');

        if ($host === '' || $databaseName === '' || $username === '') {
            throw new RuntimeException('As configurações do MySQL não foram definidas.');
        }

        $dsn = "mysql:host={$host};port={$port};dbname={$databaseName};charset=utf8mb4";
        $schemaPath = $projectRoot . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'schema.mysql.sql';
        $database = new PDO($dsn, $username, $password);
    } else {
        $databasePath = $projectRoot . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'database.sqlite';
        $schemaPath = $projectRoot . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'schema.sql';
        $database = new PDO('sqlite:' . $databasePath);
    }

    $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $database->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $schema = file_get_contents($schemaPath);

    if ($schema === false) {
        throw new RuntimeException('Não foi possível carregar o esquema do banco de dados.');
    }

    $database->exec($schema);

    return $database;
}
