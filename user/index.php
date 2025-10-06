<?php
include '../database/connet.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>curcur online</title>
    <link rel="stylesheet" href="../style/cheat.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        header {
            background: var(--color-bg);
            color: white;
            padding: 20px 0;
            justify-content: center;
            align-items: center;
        }
        .head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            background-color: azure;
            padding: 10px 20px;
        }
        .head img {
            width: 50px;
            height: 50px;
            
        }
        main {
            margin: 20px;
        }
        .navbar ul {
            list-style: none;
            padding: 0;
            gap:30px;
            display: flex;
            color: var(--color-head);
        }
        .loga1{
            display: flex;
            justify-content: center;
            margin: 29px;
            gap: 40px;
        }
        .jg{
            width: 200px;
            height: 200px;
        }
        .jg img{
            width: 100%;
            color: var(--text-color);
        }
        .loga2 {
           
           position: sticky;
           top: 100px;
        }
        .but {
            display: flex;
        }
        .pajangan {
            display: flex;
            gap: 20px;
            width: 100%;
            max-width: 100vw;
            height: 200px;
            overflow-x: auto;
            white-space: nowrap;
            padding-bottom: 10px;
            box-sizing: border-box;
        }
        .pajangan img {
            flex: 0 0 auto;
            width: 180px;
            height: 180px;
            object-fit: cover;
            border-radius: 10px;
        }
        .floating-img {
            position: fixed;
            bottom: 30px; /* Geser ke atas/bawah */
            right: 30px;  /* Geser ke kiri/kanan */
            width: 80px;
            height: 80px;
            z-index: 1000;
            object-fit: contain;
            transition: all 0.3s;
        }
        .floating-img:hover {
            filter: brightness(0.8);
            cursor: pointer;
        }
        
        /* Tambahan style untuk buku */
        .book-item {
            position: relative;
            display: inline-block;
            margin-right: 20px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        
        .book-item:hover {
            transform: scale(1.05);
        }
        
        .book-info {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 10px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .book-item:hover .book-info {
            opacity: 1;
        }
        
        .book-info h4 {
            margin: 0;
            font-size: 14px;
            margin-bottom: 5px;
        }
        
        .book-info p {
            margin: 0;
            font-size: 12px;
        }
        
        .jg {
            text-align: center;
            transition: transform 0.3s ease;
        }
        
        .jg:hover {
            transform: scale(1.05);
        }
        
        .jg img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .jg p {
            margin-top: 10px;
            font-weight: bold;
            color: var(--color-head);
        }
    </style>
</head>
<body>
    <header>
        <div class="head">
            <img src="../jpg/logo.svg" alt="">
            <div class="navbar">
                <ul>
                    <li><a href="home.php">Home</a></li>
                    <li><a href="about.php">About</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
            </div>
            <p>
                <a href="login.php">login</a>
            </p>
        </div>
        <div class="head2">
            <img src="../jpg/zalx.jpg" alt="" style="width: 50%;">
            <h1>Welcome to Spam Ilmu</h1>
            <p>Your source for the latest in tech and programming.</p>
        </div>
    </header>
    <main>
        <hr>
        <section class="loga1">
            <?php
            // Ambil 3 buku terbaru
            $featured_books = $conn->query("SELECT * FROM books ORDER BY id DESC LIMIT 3");
            while($book = $featured_books->fetch_assoc()): ?>
                <div class="jg">
                    <img src="../uplod/uploads/<?php echo htmlspecialchars($book['preview_path']); ?>" alt="<?php echo htmlspecialchars($book['name']); ?>">
                    <p><?php echo htmlspecialchars($book['name']); ?></p>
                </div>
            <?php endwhile; ?>
        </section>
        <!-- <section class="loga2">
            <div class="but">
                <h3>start you program</h3>
                <button>letsgo</button>
            </div>
        </section> -->
        <section>
            <h2>Latest Articles</h2>
            <article>
                <h3>Understanding PHP</h3>
                <p>PHP is a popular general-purpose scripting language that is especially suited to web development.</p>
            </article>
            <article>
                <h3>Getting Started with HTML</h3>
                <p>HTML is the standard markup language for documents designed to be displayed in a web browser.</p>
            </article>
            <article>
                <h3>CSS Basics</h3>
                <p>CSS is a style sheet language used for describing the presentation of a document written in HTML or XML.</p>
            </article>
        </section>
        <section class="pajangan">
            <?php
            // Ambil semua buku untuk ditampilkan di scroll horizontal
            $all_books = $conn->query("SELECT * FROM books ORDER BY id DESC");
            while($book = $all_books->fetch_assoc()): ?>
                <div class="book-item">
                    <img src="../uplod/uploads/<?php echo htmlspecialchars($book['preview_path']); ?>" 
                         alt="<?php echo htmlspecialchars($book['name']); ?>"
                         onclick="window.location.href='../uplod/uploads/<?php echo htmlspecialchars($book['pdf_path']); ?>'">
                    <div class="book-info">
                        <h4><?php echo htmlspecialchars($book['name']); ?></h4>
                        <p><?php echo htmlspecialchars($book['book_type']); ?></p>
                    </div>
                </div>
            <?php endwhile; ?>
        </section>
        
        <section class="santuna">

        </section>
    </main>
</body>
</html>