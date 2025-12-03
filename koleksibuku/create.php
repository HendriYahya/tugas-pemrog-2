<?php
require 'config.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title  = trim($_POST['title']);
    $author = trim($_POST['author']);
    $year   = trim($_POST['year']);
    $status = trim($_POST['status']);
    $rating = trim($_POST['rating']);
    $notes  = trim($_POST['notes']);

    if ($title === '') {
        $errors[] = 'Judul wajib diisi';
    }
    if ($author === '') {
        $errors[] = 'Penulis wajib diisi';
    }

    if ($year !== '' && !ctype_digit($year)) {
        $errors[] = 'Tahun harus berupa angka';
    }

    if ($rating !== '' && !ctype_digit($rating)) {
        $errors[] = 'Rating harus berupa angka';
    }

    if (empty($errors)) {
        $stmt = $conn->prepare('INSERT INTO books (title, author, year, status, rating, notes) VALUES (?, ?, ?, ?, ?, ?)');
        $yearVal   = $year === ''   ? null : (int) $year;
        $ratingVal = $rating === '' ? null : (int) $rating;

        $stmt->bind_param('ssisis', $title, $author, $yearVal, $status, $ratingVal, $notes);
        $stmt->execute();
        $stmt->close();

        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF8">
    <title>Tambah Buku</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">

    <div class="header-bar">
        <div class="header-title">
            Tambah buku
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

    <h2>Form tambah buku</h2>

    <?php if (!empty($errors)): ?>
        <div class="error-list">
            <ul>
                <?php foreach ($errors as $e): ?>
                    <li><?php echo htmlspecialchars($e); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="" method="post">
        <div class="form-group">
            <label>Judul</label>
            <input type="text" name="title" required value="<?php echo isset($title) ? htmlspecialchars($title) : ''; ?>">
        </div>

        <div class="form-group">
            <label>Penulis</label>
            <input type="text" name="author" required value="<?php echo isset($author) ? htmlspecialchars($author) : ''; ?>">
        </div>

        <div class="form-group">
            <label>Tahun</label>
            <input type="number" name="year" value="<?php echo isset($year) ? htmlspecialchars($year) : ''; ?>">
        </div>

        <div class="form-group">
            <label>Status</label>
            <input type="text" name="status" placeholder="belum dibaca sedang dibaca selesai" value="<?php echo isset($status) ? htmlspecialchars($status) : ''; ?>">
        </div>

        <div class="form-group">
            <label>Rating</label>
            <input type="number" name="rating" min="1" max="5" value="<?php echo isset($rating) ? htmlspecialchars($rating) : ''; ?>">
        </div>

        <div class="form-group">
            <label>Catatan</label>
            <textarea name="notes"><?php echo isset($notes) ? htmlspecialchars($notes) : ''; ?></textarea>
        </div>

        <button class="btn btn-blue" type="submit">Simpan</button>
        <a href="index.php" class="btn btn-gray">Kembali</a>
    </form>
</div>
</body>
</html>
