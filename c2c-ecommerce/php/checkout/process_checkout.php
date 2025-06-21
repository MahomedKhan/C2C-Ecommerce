<?php
session_start();
require_once '../php/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $userId = $_SESSION['user_id'] ?? null;
  $cart = $_SESSION['cart'] ?? [];

  if (!$userId || empty($cart)) {
    header("Location: ../login.html");
    exit();
  }

  $shipping = $conn->real_escape_string($_POST['shipping_address']);
  $billing = $conn->real_escape_string($_POST['billing_address']);
  $method = $conn->real_escape_string($_POST['shipping_method']);
  $createdAt = date('Y-m-d H:i:s');

  $totalPrice = 0;

  // Get product info and calculate total
  $ids = implode(",", array_keys($cart));
  $result = $conn->query("SELECT id, price FROM products WHERE id IN ($ids)");

  $products = [];
  while ($row = $result->fetch_assoc()) {
    $pid = $row['id'];
    $qty = $cart[$pid];
    $price = $row['price'];
    $total = $price * $qty;
    $products[] = ['id' => $pid, 'qty' => $qty, 'price' => $price];
    $totalPrice += $total;
  }

  // Insert order
  $stmt = $conn->prepare("INSERT INTO orders (user_id, total_price, shipping_method, shipping_address, billing_address, created_at) VALUES (?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("idssss", $userId, $totalPrice, $method, $shipping, $billing, $createdAt);
  $stmt->execute();
  $orderId = $stmt->insert_id;

  // Insert order items
  $itemStmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
  foreach ($products as $item) {
    $itemStmt->bind_param("iiid", $orderId, $item['id'], $item['qty'], $item['price']);
    $itemStmt->execute();
  }

  $stmt->close();
  $itemStmt->close();

  // Clear cart
  unset($_SESSION['cart']);

  header("Location: ../thanks.php?order_id=$orderId");
  exit();
} else {
  header("Location: ../index.html");
  exit();
}
?>
