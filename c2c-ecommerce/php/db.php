<?php
// db.php

$host = 'sql301.infinityfree.com';
$dbname = 'if0_39103206_c2c_platform';
$username = 'if0_39103206';
$password = 'Taahir04';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Optional: echo "Connected successfully";
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
