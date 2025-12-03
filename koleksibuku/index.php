<?php
require 'config.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// proses hapus
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $id = (int) $_POST['delete_id'];
    $stmt = $conn->prepare('DELETE FROM books WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
    header('Location: index.php');
    exit;
}

// ambil semua buku
$result = $conn->query('SELECT * FROM books ORDER BY id DESC');

// data ringkas untuk tampilan
$totalBuku = $result->num_rows;

$selesai = $conn->query("SELECT COUNT(*) AS jml FROM books WHERE status = 'selesai'");
$jumlahSelesai = $selesai ? (int) $selesai->fetch_assoc()['jml'] : 0;

$sedang = $conn->query("SELECT COUNT(*) AS jml FROM books WHERE status = 'sedang dibaca'");
$jumlahSedang = $sedang ? (int) $sedang->fetch_assoc()['jml'] : 0;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Beranda Koleksi Buku</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">

    <div class="header-bar">
        <div class="header-title">
            Koleksi Buku Pribadi
        </div>
        <div class="header-user">
            Masuk sebagai
            <strong><?php echo htmlspecialchars($_SESSION['user']['username']); ?></strong>
            |
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="hero">
        <div class="hero-content">
            <div class="hero-title">
                Selamat datang di rak buku digital kamu
            </div>
            <div class="hero-subtitle">
                Simpan bacaan terbaikmu, lanjutkan dari halaman terakhir, dan catat hal penting setiap kali kamu membaca. Rak digital ini dibuat khusus untuk menemani perjalanan literasimu.
            </div>
            <div class="hero-actions">
                <a href="create.php" class="btn btn-green">Tambah Buku Baru</a>
                <a href="#daftar-buku" class="btn btn-outline">Lihat daftar buku</a>
            </div>
        </div>
    </div>

    <div class="summary-cards">
        <div class="summary-card">
            <strong>Total buku</strong><br>
            <?php echo $totalBuku; ?>
        </div>
        <div class="summary-card">
            <strong>Selesai dibaca</strong><br>
            <?php echo $jumlahSelesai; ?>
        </div>
        <div class="summary-card">
            <strong>Sedang dibaca</strong><br>
            <?php echo $jumlahSedang; ?>
        </div>
    </div>

    <h2 id="daftar-buku" style="margin-top:5px;">Daftar buku</h2>

    <div class="nav">
        <a href="create.php" class="btn btn-green">Tambah buku</a>
    </div>

    <?php if ($result->num_rows === 0): ?>
        <p>Belum ada buku di koleksi kamu</p>
    <?php else: ?>
        <table>
            <tr>
                <th>Judul</th>
                <th>Penulis</th>
                <th>Tahun</th>
                <th>Status</th>
                <th>Rating</th>
                <th>Aksi</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo htmlspecialchars($row['author']); ?></td>
                    <td><?php echo htmlspecialchars($row['year']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                    <td><?php echo htmlspecialchars($row['rating']); ?></td>
                    <td>
                        <a href="detail.php?id=<?php echo $row['id']; ?>" class="btn btn-blue">Detail</a>
                        <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-gray">Edit</a>
                        <form action="index.php" method="post" style="display:inline" onsubmit="return confirm('Yakin hapus buku ini?');">
                            <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                            <button type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php endif; ?>

</div>
</body>
</html>
