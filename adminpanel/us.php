<?php
include 'conn.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $address = trim($_POST['address']);
    $salary = trim($_POST['salary']);
    $dob = trim($_POST['dob']);
    $imgFilename = '';

    // Check if image file was uploaded without errors
    if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['img']['tmp_name'];
        $fileName = $_FILES['img']['name'];
        $fileSize = $_FILES['img']['size'];
        $fileType = $_FILES['img']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $allowedfileExtensions = array('jpg', 'jpeg', 'png', 'gif');

        if (in_array($fileExtension, $allowedfileExtensions)) {
            // Sanitize file name
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $uploadFileDir = './uploads/';
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0755, true);
            }
            $dest_path = $uploadFileDir . $newFileName;

            if(move_uploaded_file($fileTmpPath, $dest_path)) {
                $imgFilename = $newFileName;
            } else {
                $message = 'Error moving uploaded file.';
            }
        } else {
            $message = 'Upload failed. Allowed file types: ' . implode(', ', $allowedfileExtensions);
        }
    } else {
        // No image uploaded, optional to set default image or empty
        $imgFilename = '';
    }

    if (empty($message)) {
        // Insert employee data
        $stmt = $conn->prepare("INSERT INTO task (name, address, salary, dob, img) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $address, $salary, $dob, $imgFilename);

        if ($stmt->execute()) {
            $message = '';
        } else {
            $message = 'Database insert failed: ' . $conn->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Employee Data</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
            padding: 20px;
        }
        form {
            background: #fff;
            padding: 20px 25px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            max-width: 500px;
            margin: auto;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: 600;
            color: #555;
        }
        input[type="text"],
        input[type="number"],
        input[type="date"],
        input[type="file"] {
            width: 100%;
            padding: 10px 12px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 14px;
        }
        button {
            margin-top: 20px;
            width: 100%;
            background-color: #007bff;
            border: none;
            color: white;
            padding: 12px;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-weight: 700;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message {
            text-align: center;
            margin-top: 15px;
            font-weight: 600;
            color: #d6336c;
        }
        .message.success {
            color: #28a745;
        }
    </style>
</head>
<body>

<form method="post" enctype="multipart/form-data" novalidate>
    <h1>inpo</h1>
    <?php if ($message): ?>
        <div class="message <?= strpos($message, 'successfully') !== false ? 'success' : '' ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <label for="name">Nama destinasi</label>
    <input type="text" name="name" id="name" required />

    <label for="address">url[alamat]</label>
    <input type="text" name="address" id="address" required />

    <label for="salary">harga</label>
    <input type="text" name="salary" id="salary" required />

    <label for="dob">Tanggal rilis</label>
    <input type="date" name="dob" id="dob" required />

    <label for="img">Foto file</label>
    <input type="file" name="img" id="img" accept=".jpg,.jpeg,.png,.gif" />

    <button type="submit">Simpan</button>
</form>

</body>
</html>

