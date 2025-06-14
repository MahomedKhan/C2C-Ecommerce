<?php
session_start();

if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'], $_POST['quantity'])) {
  $productId = intval($_POST['product_id']);
  $quantity = intval($_POST['quantity']);

  // If product already in cart, increase quantity
  if (isset($_SESSION['cart'][$productId])) {
    $_SESSION['cart'][$productId] += $quantity;
  } else {
    $_SESSION['cart'][$productId] = $quantity;
  }

  header("Location: ../cart_view.php"); // Redirect to cart view
  exit();
} else {
  header("Location: ../index.html");
  exit();
}
?>