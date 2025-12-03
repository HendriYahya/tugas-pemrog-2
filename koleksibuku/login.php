<?php
require 'config.php';

// kalau sudah login langsung ke beranda
if (isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim(isset($_POST['username']) ? $_POST['username'] : '');
    $password = trim(isset($_POST['password']) ? $_POST['password'] : '');

    // cek login sederhana
    if ($username === 'admin' && $password === 'admin123') {
        // pakai array() supaya cocok dengan PHP versi lama juga
        $_SESSION['user'] = array(
            'username' => $username
        );

        header('Location: index.php');
        exit;
    } else {
        $error = 'Username atau password salah';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF8">
    <title>Login Koleksi Buku</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="login-card">
    <h2 style="margin-top:0; margin-bottom:10px;">Login Koleksi Buku</h2>
    <p style="font-size:14px; color:#555; margin-top:0;">
        Masuk untuk mengelola koleksi buku pribadi
    </p>

    <?php if ($error): ?>
        <div class="error-list">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <form method="post" action="login.php">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" required value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>">
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <button class="btn btn-blue" type="submit">Login</button>
    </form>

    <p style="font-size:12px; color:#777; margin-top:15px;">
        Contoh  
        Username admin  
        Password admin123
    </p>
</div>

</body>
</html>
