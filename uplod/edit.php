<?php
include '../database/connet.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        if (!isset($_POST['id']) || !isset($_POST['name']) || !isset($_POST['book_type']) || !isset($_POST['book_theme'])) {
            throw new Exception("Semua field harus diisi!");
        }

        $id = $_POST['id'];
        $name = $_POST['name'];
        $book_type = $_POST['book_type'];
        $book_theme = $_POST['book_theme'];
        
        // Jika ada file baru
        if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['size'] > 0) {
            $pdf_file = $_FILES['pdf_file'];
            
            if ($pdf_file['type'] !== 'application/pdf') {
                throw new Exception("File harus berformat PDF!");
            }
            
            // Ambil informasi file lama
            $stmt = $conn->prepare("SELECT pdf_path, preview_path FROM books WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            
            // Hapus file lama
            $old_pdf = __DIR__ . "/uploads/" . $row['pdf_path'];
            $old_preview = __DIR__ . "/uploads/" . $row['preview_path'];
            if (file_exists($old_pdf)) unlink($old_pdf);
            if (file_exists($old_preview)) unlink($old_preview);
            
            // Upload file baru
            $pdf_name = $pdf_file['name'];
            $pdf_tmp = $pdf_file['tmp_name'];
            $unique_name = time() . '_' . $pdf_name;
            $pdf_path = __DIR__ . "/uploads/" . $unique_name;
            
            if (!move_uploaded_file($pdf_tmp, $pdf_path)) {
                throw new Exception("Gagal mengupload file!");
            }
            
            // Generate preview baru using GD
            $preview_name = time() . '_preview.jpg';
            $preview_path = __DIR__ . "/uploads/" . $preview_name;
            
            // Create a 600x800 image with white background
            $image = imagecreatetruecolor(600, 800);
            $white = imagecolorallocate($image, 255, 255, 255);
            imagefill($image, 0, 0, $white);
            
            // Add some text to the preview
            $black = imagecolorallocate($image, 0, 0, 0);
            
            // Draw PDF icon or placeholder
            imagefilledrectangle($image, 200, 200, 400, 400, $black);
            imagefilledrectangle($image, 210, 210, 390, 390, $white);
            
            // Add text
            $text = "PDF: " . $pdf_name;
            $font_size = 20;
            
            // If GD has FreeType support, use TTF, otherwise use basic text
            if (function_exists('imagettftext')) {
                // Calculate text position to center it
                $text_box = imagettfbbox($font_size, 0, "arial", $text);
                $text_width = abs($text_box[4] - $text_box[0]);
                $x = (600 - $text_width) / 2;
                $y = 450;
                imagettftext($image, $font_size, 0, $x, $y, $black, "arial", $text);
            } else {
                imagestring($image, 5, 200, 450, $text, $black);
            }
            
            // Save the image
            imagejpeg($image, $preview_path, 90);
            imagedestroy($image);
            
            // Update database dengan file baru
            $stmt = $conn->prepare("UPDATE books SET name=?, book_type=?, book_theme=?, pdf_path=?, preview_path=?, pdf_name=? WHERE id=?");
            $stmt->bind_param("ssssssi", $name, $book_type, $book_theme, $unique_name, $preview_name, $pdf_name, $id);
        } else {
            // Update database tanpa file baru
            $stmt = $conn->prepare("UPDATE books SET name=?, book_type=?, book_theme=? WHERE id=?");
            $stmt->bind_param("sssi", $name, $book_type, $book_theme, $id);
        }
        
        if (!$stmt->execute()) {
            throw new Exception("Gagal memperbarui data!");
        }
        
        header("Location: send.php?status=success&message=Data berhasil diperbarui!");
        exit();
        
    } catch (Exception $e) {
        header("Location: send.php?status=error&message=" . urlencode($e->getMessage()));
        exit();
    }
}

// Form edit
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();
    
    if (!$book) {
        header("Location: send.php?status=error&message=Buku tidak ditemukan!");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Buku</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"], select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h2>Edit Buku</h2>
    <form action="edit.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $book['id']; ?>">
        
        <div class="form-group">
            <label>Nama Buku:</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($book['name']); ?>" required>
        </div>
        
        <div class="form-group">
            <label>Tipe Buku:</label>
            <input type="text" name="book_type" value="<?php echo htmlspecialchars($book['book_type']); ?>" required>
        </div>
        
        <div class="form-group">
            <label>Tema Buku:</label>
            <input type="text" name="book_theme" value="<?php echo htmlspecialchars($book['book_theme']); ?>" required>
        </div>
        
        <div class="form-group">
            <label>File PDF Baru (Opsional):</label>
            <input type="file" name="pdf_file" accept=".pdf">
            <small>Biarkan kosong jika tidak ingin mengubah file PDF</small>
        </div>
        
        <div class="form-group">
            <input type="submit" value="Simpan Perubahan">
        </div>
    </form>
</body>
</html>
