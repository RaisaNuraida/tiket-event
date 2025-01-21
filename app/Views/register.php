<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Registrasi</title>
</head>

<body>
    <form action="/register" method="post">
        <?= csrf_field() ?>
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div>
            <button type="submit">Daftar</button>
        </div>

        <!-- Menampilkan pesan kesalahan atau sukses -->
        <?= session()->getFlashdata('error') ? '<div>' . session()->getFlashdata('error') . '</div>' : '' ?>
        <?= session()->getFlashdata('success') ? '<div>' . session()->getFlashdata('success') . '</div>' : '' ?>
    </form>
</body>

</html>