<?php
include '../database/connet.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        if (!isset($_POST['name']) || !isset($_POST['book_type']) || !isset($_POST['book_theme']) || !isset($_FILES['pdf_file'])) {
            throw new Exception("Semua field harus diisi!");
        }

        $name = $_POST['name'];
        $book_type = $_POST['book_type'];
        $book_theme = $_POST['book_theme'];
        
        // Validasi file PDF
        $pdf_file = $_FILES['pdf_file'];
        if ($pdf_file['type'] !== 'application/pdf') {
            throw new Exception("File harus berformat PDF!");
        }
        
        // Buat direktori upload jika belum ada
        $upload_dir = __DIR__ . "/uploads/";
        if (!file_exists($upload_dir)) {
            if (!mkdir($upload_dir, 0777, true)) {
                throw new Exception("Gagal membuat direktori upload!");
            }
        }

        $pdf_name = $pdf_file['name'];
        $pdf_tmp = $pdf_file['tmp_name'];
        $unique_name = time() . '_' . $pdf_name;
        $pdf_path = $upload_dir . $unique_name;

        if (!move_uploaded_file($pdf_tmp, $pdf_path)) {
            throw new Exception("Gagal mengupload file!");
        }
        
        // Generate a default preview image using GD
        try {
            // Create a default preview image
            $preview_name = time() . '_preview.jpg';
            $preview_path = $upload_dir . $preview_name;
            
            // Create a 600x800 image with white background
            $image = imagecreatetruecolor(600, 800);
            $white = imagecolorallocate($image, 255, 255, 255);
            imagefill($image, 0, 0, $white);
            
            // Add some text to the preview
            $black = imagecolorallocate($image, 0, 0, 0);
            $font_size = 20;
            
            // Draw PDF icon or placeholder
            imagefilledrectangle($image, 200, 200, 400, 400, $black);
            imagefilledrectangle($image, 210, 210, 390, 390, $white);
            
            // Add text
            $text = "PDF: " . $pdf_name;
            // Calculate text position to center it
            $text_box = imagettfbbox($font_size, 0, "arial", $text);
            $text_width = abs($text_box[4] - $text_box[0]);
            $text_height = abs($text_box[5] - $text_box[1]);
            $x = (600 - $text_width) / 2;
            $y = 450;
            
            // If GD has FreeType support, use TTF, otherwise use basic text
            if (function_exists('imagettftext')) {
                imagettftext($image, $font_size, 0, $x, $y, $black, "arial", $text);
            } else {
                imagestring($image, 5, $x, $y, $text, $black);
            }
            
            // Save the image
            imagejpeg($image, $preview_path, 90);
            imagedestroy($image);
            
        } catch (Exception $e) {
            unlink($pdf_path);
            throw new Exception("Gagal membuat preview: " . $e->getMessage());
        }

        // Simpan ke database
        $stmt = $conn->prepare("INSERT INTO books (name, book_type, book_theme, pdf_path, preview_path, pdf_name) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $name, $book_type, $book_theme, $unique_name, $preview_name, $pdf_name);
        
        if (!$stmt->execute()) {
            unlink($pdf_path);
            unlink($preview_path);
            throw new Exception("Gagal menyimpan ke database: " . $conn->error);
        }
        
        $stmt->close();
        
        header("Location: send.php?status=success&message=File berhasil diupload!");
        exit();

    } catch (Exception $e) {
        header("Location: send.php?status=error&message=" . urlencode($e->getMessage()));
        exit();
    }
}
?>
