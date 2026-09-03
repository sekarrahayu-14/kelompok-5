<?php
require __DIR__ . '/config/database.php';

$host = $config['host'] ?? '127.0.0.1';
$port = $config['port'] ?? '3306';
$db   = $config['database'] ?? 'db_sitoko';
$user = $config['username'] ?? 'root';
$pass = $config['password'] ?? 'rpl12345';
$charset = $config['charset'] ?? 'utf8mb4';

$dsn = "mysql:host={$host};port={$port};dbname={$db};charset={$charset}";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS kasir (
            id_kasir INT AUTO_INCREMENT PRIMARY KEY,
            nama VARCHAR(100) NOT NULL,
            username VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            level VARCHAR(50) NOT NULL DEFAULT 'admin',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");

    $cols = $pdo->query("SHOW COLUMNS FROM kasir")->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('nama_kasir', $cols, true)) {
        $pdo->exec("ALTER TABLE kasir ADD COLUMN nama_kasir VARCHAR(100) NULL AFTER nama");
    }

    $hash = password_hash('1', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare(
        "INSERT INTO kasir (nama, nama_kasir, username, password, level, created_at)
         VALUES (:nama, :nama_kasir, :username, :password, :level, NOW())
         ON DUPLICATE KEY UPDATE
            nama = VALUES(nama),
            nama_kasir = VALUES(nama_kasir),
            password = VALUES(password),
            level = VALUES(level),
            created_at = NOW()"
    );

    $stmt->execute([
        ':nama' => 'Admin',
        ':nama_kasir' => 'Admin',
        ':username' => 'admin',
        ':password' => $hash,
        ':level' => 'admin',
    ]);

    echo "Seed kasir berhasil dibuat/updated untuk username admin dengan password hash.\n";
    echo "Password hash: {$hash}\n";
} catch (Throwable $e) {
    echo 'ERROR: ' . $e->getMessage() . PHP_EOL;
    exit(1);
}
