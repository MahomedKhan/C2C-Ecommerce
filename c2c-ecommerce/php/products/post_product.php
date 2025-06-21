<?php
session_start();
require_once '../php/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $userId = $_SESSION['user_id'] ?? null;

  if (!$userId) {
    echo "Unauthorized access.";
    exit();
  }

  $title = $conn->real_escape_string($_POST['title']);
  $description = $conn->real_escape_string($_POST['description']);
  $price = floatval($_POST['price']);
  $image = '';

  // Upload image
  if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $imageName = basename($_FILES['image']['name']);
    $targetDir = '../images/';
    $targetFile = $targetDir . time() . "_" . $imageName;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
      $image = basename($targetFile);
    }
  }

  $stmt = $conn->prepare("INSERT INTO products (user_id, title, description, price, image, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
  $stmt->bind_param("issds", $userId, $title, $description, $price, $image);

  if ($stmt->execute()) {
    header("Location: ../my_products.php?posted=success");
    exit();
  } else {
    echo "Error: " . $stmt->error;
  }

  $stmt->close();
} else {
  header("Location: ../post_product.html");
  exit();
}
?>
