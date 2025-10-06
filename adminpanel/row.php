<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="row.css">
</head>
<body>
    <div class="mt-5">
    <table class="table table-bordered">
        <tbody>
            <?php
            include 'conn.php';
            $sql = "SELECT * FROM task";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $imagePath = "uploads/" . $row['img'];
                    $imageExists = !empty($row['img']) && file_exists($imagePath);
                    $imageSrc = $imageExists ? htmlspecialchars($imagePath) : "image/no-image.png";
                    $altText = $imageExists ? htmlspecialchars($row['name']) : "Tidak ada gambar";
                    
                    echo "<tr>
                            <td>
                                <img src='$imageSrc' alt='$altText' class='img rounded' width='100%' height='80'>
                            </td>
                            <td>" . htmlspecialchars($row['name']) . "</td>
                            <td>" . htmlspecialchars($row['address']) . "</td>
                            <td>" . htmlspecialchars($row['salary']) . "</td>
                            <td>" . htmlspecialchars($row['dob']) . "</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5' class='text-center'>belum ada data maseh</td></tr>";
            }
            $conn->close();
            ?>
        </tbody>
    </table>
</div>

</body>
</html>

