<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['product_id'])) {
  $productId = intval($_GET['product_id']);

  if (isset($_SESSION['cart'][$productId])) {
    unset($_SESSION['cart'][$productId]);
  }

  header("Location: ../cart_view.php");
  exit();
} else {
  header("Location: ../index.html");
  exit();
}
?>