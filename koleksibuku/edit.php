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
$errors = [];

// ambil data buku
$stmt = $conn->prepare('SELECT * FROM books WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$book = $result->fetch_assoc();
$stmt->close();

if (!$book) {
    die('Buku tidak ditemukan');
}

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
        $stmt = $conn->prepare('UPDATE books SET title = ?, author = ?, year = ?, status = ?, rating = ?, notes = ? WHERE id = ?');
        $yearVal   = $year === ''   ? null : (int) $year;
        $ratingVal = $rating === '' ? null : (int) $rating;

        $stmt->bind_param('ssisisi', $title, $author, $yearVal, $status, $ratingVal, $notes, $id);
        $stmt->execute();
        $stmt->close();

        header('Location: detail.php?id=' . $id);
        exit;
    }
} else {
    // isi form dengan data lama
    $title  = $book['title'];
    $author = $book['author'];
    $year   = $book['year'];
    $status = $book['status'];
    $rating = $book['rating'];
    $notes  = $book['notes'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF8">
    <title>Edit Buku</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">

    <div class="header-bar">
        <div class="header-title">
            Edit buku
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

    <h2>Form edit buku</h2>

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
            <input type="text" name="title" required value="<?php echo htmlspecialchars($title); ?>">
        </div>

        <div class="form-group">
            <label>Penulis</label>
            <input type="text" name="author" required value="<?php echo htmlspecialchars($author); ?>">
        </div>

        <div class="form-group">
            <label>Tahun</label>
            <input type="number" name="year" value="<?php echo htmlspecialchars($year); ?>">
        </div>

        <div class="form-group">
            <label>Status</label>
            <input type="text" name="status" value="<?php echo htmlspecialchars($status); ?>">
        </div>

        <div class="form-group">
            <label>Rating</label>
            <input type="number" name="rating" min="1" max="5" value="<?php echo htmlspecialchars($rating); ?>">
        </div>

        <div class="form-group">
            <label>Catatan</label>
            <textarea name="notes"><?php echo htmlspecialchars($notes); ?></textarea>
        </div>

        <button class="btn btn-blue" type="submit">Simpan perubahan</button>
        <a href="detail.php?id=<?php echo $id; ?>" class="btn btn-gray">Batal</a>
    </form>
</div>
</body>
</html>
