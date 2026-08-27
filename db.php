<?php
$dbUrl = getenv('DATABASE_URL');

if ($dbUrl) {
    $dbopts = parse_url($dbUrl);
    $host = $dbopts["host"];
    $port = $dbopts["port"];
    $user = $dbopts["user"];
    $pass = $dbopts["pass"];
    $db   = ltrim($dbopts["path"], '/');
} else {
    die("DATABASE_URL non configurata.");
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