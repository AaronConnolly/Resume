<?php
$host = "localhost";
$username = "student";
$password = "C22410766";
$dbname = "Library";

try {
    // Create a new PDO instance with PostgreSQL connection
    $dsn = "pgsql:host=$host;port=5432;dbname=$dbname;";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>