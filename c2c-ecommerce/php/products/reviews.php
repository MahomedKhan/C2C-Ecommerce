<?php
session_start();
require_once '../php/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['product_id'])) {
  $productId = intval($_GET['product_id']);

  $stmt = $conn->prepare("SELECT r.id, r.user_id, u.username, r.rating, r.comment, r.created_at 
                          FROM reviews r
                          JOIN users u ON r.user_id = u.id
                          WHERE r.product_id = ?
                          ORDER BY r.created_at DESC");
  $stmt->bind_param("i", $productId);
  $stmt->execute();
  $result = $stmt->get_result();

  $reviews = [];
  while ($row = $result->fetch_assoc()) {
    $reviews[] = $row;
  }

  echo json_encode($reviews);
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'], $_POST['rating'], $_POST['comment'])) {
  $userId = $_SESSION['user_id'] ?? null;
  if (!$userId) {
    echo json_encode(['error' => 'Unauthorized']);
    exit();
  }

  $productId = intval($_POST['product_id']);
  $rating = intval($_POST['rating']);
  $comment = $conn->real_escape_string($_POST['comment']);

  $stmt = $conn->prepare("INSERT INTO reviews (product_id, user_id, rating, comment, created_at) VALUES (?, ?, ?, ?, NOW())");
  $stmt->bind_param("iiis", $productId, $userId, $rating, $comment);

  if ($stmt->execute()) {
    echo json_encode(['success' => true]);
  } else {
    echo json_encode(['error' => 'Failed to submit review']);
  }

  exit();
}

echo json_encode(['error' => 'Invalid request']);
exit();
?>
