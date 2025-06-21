<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'], $_POST['quantity'])) {
  $productId = intval($_POST['product_id']);
  $quantity = intval($_POST['quantity']);

  if ($quantity > 0) {
    $_SESSION['cart'][$productId] = $quantity;
  } else {
    unset($_SESSION['cart'][$productId]);
  }

  header("Location: ../cart_view.php");
  exit();
} else {
  header("Location: ../index.html");
  exit();
}
?>