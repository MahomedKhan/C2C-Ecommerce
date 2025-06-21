<?php
session_start();
require_once '../php/db.php'; // DB connection for pulling product details

$cart = $_SESSION['cart'] ?? [];
$items = [];
$totalPrice = 0.00;

if (!empty($cart)) {
  $ids = implode(",", array_keys($cart));
  $result = $conn->query("SELECT id, title, price, image FROM products WHERE id IN ($ids)");

  while ($row = $result->fetch_assoc()) {
    $productId = $row['id'];
    $row['quantity'] = $cart[$productId];
    $row['subtotal'] = $row['price'] * $row['quantity'];
    $totalPrice += $row['subtotal'];
    $items[] = $row;
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Cart</title>
  <link rel="stylesheet" href="css/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <h2>Your Shopping Cart</h2>
    <?php if (empty($items)): ?>
      <p class="text-muted">Your cart is empty.</p>
      <a href="index.html" class="btn btn-primary">Continue Shopping</a>
    <?php else: ?>
      <form action="checkout.php" method="post">
        <table class="table table-bordered mt-4">
          <thead class="table-light">
            <tr>
              <th>Image</th>
              <th>Product</th>
              <th>Price</th>
              <th>Quantity</th>
              <th>Subtotal</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($items as $item): ?>
              <tr>
                <td><img src="images/<?= $item['image'] ?>" alt="<?= htmlspecialchars($item['title']) ?>" width="50"></td>
                <td><?= htmlspecialchars($item['title']) ?></td>
                <td>R<?= number_format($item['price'], 2) ?></td>
                <td>
                  <form action="cart/update_cart.php" method="post" class="d-flex">
                    <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                    <input type="number" name="quantity" value="<?= $item['quantity'] ?>" class="form-control form-control-sm me-2" min="1">
                    <button type="submit" class="btn btn-sm btn-info">Update</button>
                  </form>
                </td>
                <td>R<?= number_format($item['subtotal'], 2) ?></td>
                <td>
                  <a href="cart/remove_from_cart.php?product_id=<?= $item['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Remove this item?')">Remove</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <div class="text-end">
          <h5>Total: <strong>R<?= number_format($totalPrice, 2) ?></strong></h5>
          <a href="checkout.php" class="btn btn-success">Proceed to Checkout</a>
        </div>
      </form>
    <?php endif; ?>
  </div>
</body>
</html>