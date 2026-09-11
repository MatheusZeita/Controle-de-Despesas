<?php

declare(strict_types=1);

function getDatabase(): PDO
{
    static $database = null;

    if ($database instanceof PDO) {
        return $database;
    }

    $projectRoot = dirname(__DIR__, 2);
    $localConfigPath = __DIR__ . DIRECTORY_SEPARATOR . 'database.local.php';
    $localConfig = is_file($localConfigPath) ? require $localConfigPath : [];
    $configValue = static function (string $key, string $default = '') use ($localConfig): string {
        $environmentValue = getenv($key);
        return $environmentValue !== false && $environmentValue !== ''
            ? (string) $environmentValue
            : (string) ($localConfig[$key] ?? $default);
    };

    $driver = strtolower($configValue('DB_DRIVER', 'sqlite'));

    if ($driver === 'mysql') {
        $host = $configValue('DB_HOST');
        $port = $configValue('DB_PORT', '3306');
        $databaseName = $configValue('DB_NAME');
        $username = $configValue('DB_USER');
        $password = $configValue('DB_PASSWORD');

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
