<?php
include '../database/connet.php';

// Ambil semua data buku
$result = $conn->query("SELECT * FROM books ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manajemen Buku PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .book-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .book-card {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
        }
        .book-preview {
            width: 100%;
            height: 200px;
            object-fit: cover;
            margin-bottom: 10px;
        }
        .status-message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }
        .action-buttons a {
            padding: 5px 10px;
            text-decoration: none;
            color: white;
            border-radius: 3px;
        }
        .edit-btn {
            background-color: #ffc107;
        }
        .delete-btn {
            background-color: #dc3545;
        }
        .upload-form {
            margin-bottom: 30px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"], input[type="file"] {
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
    <div class="container">
        <h1>Manajemen Buku PDF</h1>
        
        <?php if (isset($_GET['status']) && isset($_GET['message'])): ?>
            <div class="status-message <?php echo $_GET['status']; ?>">
                <?php echo htmlspecialchars($_GET['message']); ?>
            </div>
        <?php endif; ?>

        <!-- Form Upload -->
        <div class="upload-form">
            <h2>Upload Buku Baru</h2>
            <form action="creat.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Nama Buku:</label>
                    <input type="text" name="name" required>
                </div>
                
                <div class="form-group">
                    <label>Tipe Buku:</label>
                    <input type="text" name="book_type" required>
                </div>
                
                <div class="form-group">
                    <label>Tema Buku:</label>
                    <input type="text" name="book_theme" required>
                </div>
                
                <div class="form-group">
                    <label>File PDF:</label>
                    <input type="file" name="pdf_file" accept=".pdf" required>
                </div>
                
                <input type="submit" value="Upload Buku">
            </form>
        </div>

        <!-- Tampilan Buku -->
        <div class="book-grid">
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="book-card">
                    <img src="uploads/<?php echo htmlspecialchars($row['preview_path']); ?>" alt="Preview" class="book-preview">
                    <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                    <p>Tipe: <?php echo htmlspecialchars($row['book_type']); ?></p>
                    <p>Tema: <?php echo htmlspecialchars($row['book_theme']); ?></p>
                    <div class="action-buttons">
                        <a href="edit.php?id=<?php echo $row['id']; ?>" class="edit-btn">Edit</a>
                        <a href="delete.php?id=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Apakah Anda yakin ingin menghapus buku ini?')">Hapus</a>
                        <a href="uploads/<?php echo htmlspecialchars($row['pdf_path']); ?>" target="_blank" style="background-color: #007bff;">Baca</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
