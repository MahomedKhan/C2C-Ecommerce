<?php
require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $passwordRaw = $_POST['password'];

    // Basic validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email format.']);
        exit();
    }

    if (strlen($passwordRaw) < 6) {
        echo json_encode(['status' => 'error', 'message' => 'Password must be at least 6 characters.']);
        exit();
    }

    // Check for existing user
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Email already registered.']);
        exit();
    }

    $passwordHash = password_hash($passwordRaw, PASSWORD_DEFAULT);
    $otp = rand(100000, 999999); // Simulated 2FA

    // Insert user
    $stmt = $conn->prepare("INSERT INTO users (email, password, otp) VALUES (?, ?, ?)");
    $stmt->execute([$email, $passwordHash, $otp]);

    echo json_encode(['status' => 'success', 'otp' => $otp]);
}
?>
