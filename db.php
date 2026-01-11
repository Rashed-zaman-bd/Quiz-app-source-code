<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quiz_db";

try {
    $dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8mb4";

    $pdo = new PDO($dsn, $username, $password);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>