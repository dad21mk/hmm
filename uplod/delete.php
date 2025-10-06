<?php
include '../database/connet.php';

if (isset($_GET['id'])) {
    try {
        $id = $_GET['id'];
        
        // Ambil informasi file sebelum dihapus
        $stmt = $conn->prepare("SELECT pdf_path, preview_path FROM books WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            throw new Exception("Buku tidak ditemukan!");
        }
        
        $row = $result->fetch_assoc();
        $pdf_path = __DIR__ . "/uploads/" . $row['pdf_path'];
        $preview_path = __DIR__ . "/uploads/" . $row['preview_path'];
        
        // Hapus file fisik
        if (file_exists($pdf_path)) {
            unlink($pdf_path);
        }
        if (file_exists($preview_path)) {
            unlink($preview_path);
        }
        
        // Hapus dari database
        $stmt = $conn->prepare("DELETE FROM books WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if (!$stmt->execute()) {
            throw new Exception("Gagal menghapus dari database!");
        }
        
        header("Location: send.php?status=success&message=Buku berhasil dihapus!");
        exit();
        
    } catch (Exception $e) {
        header("Location: send.php?status=error&message=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    header("Location: send.php?status=error&message=ID tidak ditemukan!");
    exit();
}
?>
