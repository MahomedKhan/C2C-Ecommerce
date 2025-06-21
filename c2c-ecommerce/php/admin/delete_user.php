<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
  header("Location: ../login.html");
  exit();
}

if (isset($_GET['user_id'])) {
  require_once '../php/db.php';

  $user_id = intval($_GET['user_id']);

  try {
    // Prevent deleting other admins (optional safeguard)
    $stmt = $conn->prepare("DELETE FROM users WHERE id = :id AND role != 'admin'");
    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
  } catch (PDOException $e) {
    die("Delete failed: " . $e->getMessage());
  }
}

header("Location: manage_users.php");
exit();
?>
