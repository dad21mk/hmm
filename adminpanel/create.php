<?php
include 'conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $salary = $_POST['salary'];
    $dob = date('Ymd', strtotime($_POST['dob']));
    
    $imageName = $_FILES['img']['name'];
    $tmpName = $_FILES['img']['tmp_name'];
    $uploadDir = 'uploads/';
    $img = time() . '_' . basename($imageName);
    move_uploaded_file($tmpName, $uploadDir . $img);
    
    $sql = "INSERT INTO task (name, img, address, salary, dob) VALUES ('$name', '$img', '$address', '$salary', '$dob')";

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}

