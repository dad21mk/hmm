<?php
require_once 'conn.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $name = $_POST['name'];
    $location = $_POST['location'];
    $price = $_POST['price'];
    $ticket_count = $_POST['ticket_count'];
    $description = $_POST['description'];
    
    // Handle file upload
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../uploads/";
        $file_extension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $new_filename = "destination_" . time() . "." . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        // Check if image file is a actual image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if($check !== false) {
            // Check file size (max 5MB)
            if ($_FILES["image"]["size"] <= 5000000) {
                // Allow certain file formats
                if(in_array($file_extension, ["jpg", "jpeg", "png", "gif"])) {
                    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                        $image = $new_filename;
                    } else {
                        die("Sorry, there was an error uploading your file.");
                    }
                } else {
                    die("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
                }
            } else {
                die("Sorry, your file is too large. Maximum size is 5MB.");
            }
        } else {
            die("File is not an image.");
        }
    }
    
    if ($id) {
        // Update existing destination
        if ($image) {
            // If there's a new image
            $query = "UPDATE destinations SET name = ?, location = ?, price = ?, ticket_count = ?, description = ?, image = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssidssi", $name, $location, $price, $ticket_count, $description, $image, $id);
        } else {
            // If no new image
            $query = "UPDATE destinations SET name = ?, location = ?, price = ?, ticket_count = ?, description = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssidsi", $name, $location, $price, $ticket_count, $description, $id);
        }
    } else {
        // Insert new destination
        $stmt = $conn->prepare("INSERT INTO destinations (name, location, price, ticket_count, description, image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssidss", $name, $location, $price, $ticket_count, $description, $image);
    }
    
    if ($stmt->execute()) {
        header("Location: index.php?success=1");
    } else {
        header("Location: index.php?error=1");
    }
    exit();
}
?> 