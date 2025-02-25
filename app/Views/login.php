    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <link rel="stylesheet" href="assets/css/login.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="assets/css/login.css">
    </head>

    <body>
        <div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
            <div class="login-container">
                <h1 class="text-center">Login</h1>
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger error-message">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <form action="/login" method="post">
                    <?= csrf_field() ?>

                    <!-- Input Username -->
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>

                    <!-- Input Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>

                    <p class="text-center"><span>Lupa Password? Hubungi Admin</span></p>
                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary w-100" name="login" value="1">Login</button>
                </form>
            </div>
        </div>
        
        <!-- Menyertakan Bootstrap JS dan Popper.js dari CDN -->
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    </body>

    </html>
