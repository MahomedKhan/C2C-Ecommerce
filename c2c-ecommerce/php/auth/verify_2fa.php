<?php
require_once '../db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['verify_user'], $_POST['otp'])) {
    $userId = $_SESSION['verify_user'];
    $otp = trim($_POST['otp']);

    $stmt = $conn->prepare("SELECT id, role FROM users WHERE id = ? AND otp = ?");
    $stmt->execute([$userId, $otp]);

    $user = $stmt->fetch();

    if ($user) {
        // OTP verified
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role']; // Allow admin dashboard access if applicable
        unset($_SESSION['verify_user']);

        // Optionally clear OTP to prevent reuse
        $clearOtpStmt = $conn->prepare("UPDATE users SET otp = NULL WHERE id = ?");
        $clearOtpStmt->execute([$user['id']]);

        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid OTP']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
