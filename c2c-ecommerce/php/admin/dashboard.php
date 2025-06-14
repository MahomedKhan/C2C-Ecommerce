<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
  header("Location: ../login.html");
  exit();
}

require_once '../php/db.php';

// Count stats using PDO
try {
    $userStmt = $conn->query("SELECT COUNT(*) AS total FROM users");
    $userCount = $userStmt->fetch(PDO::FETCH_ASSOC)['total'];

    $productStmt = $conn->query("SELECT COUNT(*) AS total FROM products");
    $productCount = $productStmt->fetch(PDO::FETCH_ASSOC)['total'];

    $orderStmt = $conn->query("SELECT COUNT(*) AS total FROM orders");
    $orderCount = $orderStmt->fetch(PDO::FETCH_ASSOC)['total'];
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link href="../css/style.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

  <div class="container mt-5">
    <h2>Admin Dashboard</h2>
    <div class="row">
      <div class="col-md-4">
        <div class="card text-white bg-primary mb-3">
          <div class="card-header">Total Users</div>
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($userCount) ?></h5>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-white bg-success mb-3">
          <div class="card-header">Products</div>
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($productCount) ?></h5>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-white bg-warning mb-3">
          <div class="card-header">Orders</div>
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($orderCount) ?></h5>
          </div>
        </div>
      </div>
    </div>
    <a href="manage_users.php" class="btn btn-outline-secondary">Manage Users</a>
    <a href="reports.php" class="btn btn-outline-secondary">View Reports</a>
  </div>

</body>
</html>
