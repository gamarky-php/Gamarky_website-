<?php
// Simple DB check script for local environment (debug-enabled)
$host = '127.0.0.1';
$db   = 'mardini';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
echo "DSN=".htmlentities($dsn)."\n";
try {
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    echo "CONNECTED\n";
    $stmt = $pdo->prepare('SELECT id, email, is_admin FROM users WHERE email = ? LIMIT 1');
    $ok = $stmt->execute(['admin@example.com']);
    echo "EXECUTE_OK=".($ok?1:0)."\n";
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        echo json_encode($row) . "\n";
    } else {
        echo "NOTFOUND\n";
    }
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
} catch (Throwable $t) {
    echo "ERROR_THROWABLE: " . $t->getMessage() . "\n";
}
