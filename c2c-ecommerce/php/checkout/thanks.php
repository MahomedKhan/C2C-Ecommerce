<?php
session_start();

$customerName = "Valued Customer";
if (isset($_SESSION['user'])) {
    $customerName = htmlspecialchars($_SESSION['user']['name']);
}
?>
<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            background-color: #f2f2f2;
            font-family: Arial, sans-serif;
        }
        h1 {
            color: rgb(0, 0, 0);
            font-size: 2.5em;
            text-align: center;
            margin-top: 50px;
        }
        p {
            color: #333;
            font-size: 1.2em;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>Thank You, <?php echo $customerName; ?>!</h1>
    <p>Your order has been received and will be delivered soon.</p>
</body>
</html>
