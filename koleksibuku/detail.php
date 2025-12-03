<?php
require 'config.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = (int) $_GET['id'];

$stmt = $conn->prepare('SELECT * FROM books WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$book = $result->fetch_assoc();
$stmt->close();

if (!$book) {
    die('Buku tidak ditemukan');
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF8">
    <title>Detail Buku</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">

    <div class="header-bar">
        <div class="header-title">
            Detail buku
        </div>
        <div class="header-user">
            <?php if (isset($_SESSION['user'])): ?>
                Masuk sebagai
                <strong><?php echo htmlspecialchars($_SESSION['user']['username']); ?></strong>
                |
                <a href="logout.php">Logout</a>
            <?php endif; ?>
        </div>
    </div>

    <h1><?php echo htmlspecialchars($book['title']); ?></h1>

    <p>Penulis
        <strong><?php echo htmlspecialchars($book['author']); ?></strong>
    </p>
    <p>Tahun
        <strong><?php echo htmlspecialchars($book['year']); ?></strong>
    </p>
    <p>Status
        <strong><?php echo htmlspecialchars($book['status']); ?></strong>
    </p>
    <p>Rating
        <strong><?php echo htmlspecialchars($book['rating']); ?></strong>
    </p>

    <h3>Catatan</h3>
    <p><?php echo nl2br(htmlspecialchars($book['notes'])); ?></p>

    <div class="nav">
        <a href="index.php" class="btn btn-gray">Kembali ke daftar</a>
        <a href="edit.php?id=<?php echo $book['id']; ?>" class="btn btn-blue">Edit</a>
    </div>
</div>
</body>
</html>
