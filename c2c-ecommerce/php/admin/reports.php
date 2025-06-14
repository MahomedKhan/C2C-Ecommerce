<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
  header("Location: ../login.html");
  exit();
}

require_once '../php/db.php';

try {
  $userCount = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch(PDO::FETCH_ASSOC)['total'];
  $orderCount = $conn->query("SELECT COUNT(*) AS total FROM orders")->fetch(PDO::FETCH_ASSOC)['total'];
  $salesRow = $conn->query("SELECT SUM(total_price) AS total FROM orders")->fetch(PDO::FETCH_ASSOC);
  $salesTotal = $salesRow['total'] ?? 0;
} catch (PDOException $e) {
  die("Error generating reports: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Reports</title>
  <link href="../css/style.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

  <div class="container mt-5">
    <h2>Reports</h2>
    <div class="mt-4">
      <div class="alert alert-info">
        <strong>Total Users:</strong> <?= htmlspecialchars($userCount) ?>
      </div>
      <div class="alert alert-success">
        <strong>Total Orders:</strong> <?= htmlspecialchars($orderCount) ?>
      </div>
      <div class="alert alert-primary">
        <strong>Total Sales:</strong> R<?= number_format($salesTotal, 2) ?>
      </div>
    </div>
    <a href="dashboard.php" class="btn btn-secondary">← Back to Dashboard</a>
  </div>

</body>
</html>
