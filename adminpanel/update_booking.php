<?php
require_once 'conn.php';
session_start();

if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = $_GET['id'];
    $status = $_GET['status'];
    
    // Update booking status
    $stmt = $conn->prepare("UPDATE bookings SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);
    
    if ($stmt->execute()) {
        header("Location: index.php?success=booking_updated");
    } else {
        header("Location: index.php?error=update_failed");
    }
} else {
    header("Location: index.php?error=invalid_params");
}
exit();
?> 