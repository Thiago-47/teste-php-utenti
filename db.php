<?php
// Tenta ler a variável DATABASE_URL (se houver) ou usa os dados locais padrão
$dbUrl = getenv('DATABASE_URL');

if ($dbUrl) {
    $dbopts = parse_url($dbUrl);
    $host = $dbopts["host"];
    $port = $dbopts["port"];
    $user = $dbopts["user"];
    $pass = $dbopts["pass"];
    $db   = ltrim($dbopts["path"], '/');
} else {
    // Configurações padrão para quem for testar localmente
    $host = getenv('DB_HOST') ?: 'localhost';
    $port = getenv('DB_PORT') ?: '5432';
    $db   = getenv('DB_NAME') ?: 'sistema_usuarios';
    $user = getenv('DB_USER') ?: 'postgres';
    $pass = getenv('DB_PASS') ?: 'postgres';
}

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$db;";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Errore di connessione al database: " . $e->getMessage());
}
?>