<?php
require_once '../php/db.php';

header('Content-Type: application/json');

$query = "SELECT id, title, description, price, image FROM products ORDER BY created_at DESC";
$result = $conn->query($query);

$products = [];

while ($row = $result->fetch_assoc()) {
  $products[] = $row;
}

echo json_encode($products);
?>