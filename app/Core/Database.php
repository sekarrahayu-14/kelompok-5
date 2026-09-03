<?php

class Database
{
    private static $instance = null;
    private $connection = null;
    private $configuration;

    public function __construct($configuration = null)
    {
        $this->configuration = $configuration ?? $this->loadConfiguration();
    }

    public function connect(): PDO
    {
        if ($this->connection instanceof PDO) {
            return $this->connection;
        }

        $host = $this->configuration['host'] ?? '127.0.0.1';
        $port = $this->configuration['port'] ?? '3306';
        $database = $this->configuration['database'] ?? 'db_sitoko';
        $username = $this->configuration['username'] ?? 'root';
        $password = $this->configuration['password'] ?? '';
        $charset = $this->configuration['charset'] ?? 'utf8mb4';
        $dsn = "mysql:host={$host};port={$port};dbname={$database};charset={$charset}";

        try {
            $this->connection = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $exception) {
            throw new RuntimeException('Koneksi database gagal.', 0, $exception);
        }

        return $this->connection;
    }

    public static function getInstance(?array $configuration = null): self
    {
        if (self::$instance === null) {
            self::$instance = new self($configuration);
        }

        return self::$instance;
    }

    private function loadConfiguration(): array
    {
        $configurationFile = dirname(__DIR__, 2) . '/config/database.php';
        $configuration = is_file($configurationFile) ? require $configurationFile : [];

        return is_array($configuration) ? $configuration : [];
    }
}
