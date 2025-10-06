<?php
require_once 'conn.php';
session_start();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Get the image filename before deleting
    $stmt = $conn->prepare("SELECT image FROM destinations WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $destination = $result->fetch_assoc();
        $image_path = "../uploads/" . $destination['image'];
        
        // Delete the destination from database
        $stmt = $conn->prepare("DELETE FROM destinations WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            // If deletion successful, delete the image file
            if (file_exists($image_path)) {
                unlink($image_path);
            }
            header("Location: index.php?success=2");
        } else {
            header("Location: index.php?error=2");
        }
    } else {
        header("Location: index.php?error=3");
    }
} else {
    header("Location: index.php");
}
exit();
?> 