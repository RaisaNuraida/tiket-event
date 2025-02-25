<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pembayaran Tiket</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/kasir/navbar.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/kasir/style.css') ?>">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand logo" href="<?= base_url('kasir/index') ?>">Lontar Event</a>

            <div class="mx-auto search-container">
                <form class="d-flex" method="get" action="<?= base_url('kasir/index') ?>">
                    <input class="form-control me-2" type="search" name="search" placeholder="Cari Event"
                        aria-label="Search" value="<?= esc($search ?? '') ?>">
                    <button class="btn btn-outline-light" type="submit">Cari</button>
                </form>
            </div>

            <div class="d-flex align-items-center">
                <a class="btn create-event me-2" href="<?= base_url('kasir/index') ?>">Events</a>
                <a class="btn create-event me-2" href="<?= base_url('kasir/orders') ?>">Orders</a>

                <?php $session = session(); ?>
                <?php if ($session->has('user_id')): ?>
                    <!-- Username tanpa dropdown -->
                    <span class="kasir-username"><?= ucfirst($session->get('username') ?? 'Kasir') ?></span>
                    <a class="btn logout-btn ms-3" href="<?= base_url('logout') ?>"><i
                            class="bi bi-box-arrow-in-right"></i></a>
                <?php else: ?>
                    <a class="btn login" href="<?= base_url('login') ?>">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container mt-5">

        <!-- Flash Data -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success'); ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error'); ?>
            </div>
        <?php endif; ?>
        
        <div class="mt-5">
            <h3>Konfirmasi Pembayaran Tiket</h3>

            <form action="<?= base_url('kasir/submit_order') ?>" method="post">
                <?= csrf_field(); ?>

                <div class="d-flex justify-content-center">
                    <div class="m-1">
                        <label for="display_name" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" style="width: 600px;" name="display_name" required>
                    </div>
                    <div class="m-1">
                        <label for="gmail" class="form-label">Email</label>
                        <input type="email" class="form-control" style="width: 600px;" name="gmail" required>
                    </div>
                </div>

                <div class="m-5">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nama Event</th>
                                <th>Jenis Tiket</th>
                                <th>Harga</th>
                                <th>Jumlah Tiket</th>
                                <th>Total Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $totalHarga = 0; ?>
                            <?php foreach ($order_data as $order): ?>
                                <tr>
                                    <td><?= esc($event['event_title']) ?></td>
                                    <td><?= strtoupper(esc($order['class'])) ?></td>
                                    <td>Rp <?= number_format($order['price'], 0, ',', '.') ?></td>
                                    <td><?= esc($order['quantity']) ?> Tiket</td>
                                    <td>Rp <?= number_format($order['price'] * $order['quantity'], 0, ',', '.') ?></td>
                                </tr>
                                <?php $totalHarga += $order['price'] * $order['quantity']; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-around">
                        <div class="m-1" style="width: 500px;">
                            <span><strong>Total</strong></span>
                            <input type="text" class="form-control"
                                value="Rp <?= number_format($totalHarga, 0, ',', '.') ?>" readonly>
                        </div>
                        <div class="m-1" style="width: 500px;">
                            <span><strong>Bayar</strong></span>
                            <input type="number" class="form-control" id="bayar" name="payment" placeholder="Rp"
                                required>
                        </div>
                        <div class="m-1" style="width: 500px;">
                            <span><strong>Kembalian</strong></span>
                            <input type="text" class="form-control" id="kembalian" placeholder="Rp" readonly>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-evenly">
                    <button type="submit" class="btn btn-primary"><strong>Lanjutkan Pembayaran</strong></button>
                    <a href="<?= base_url('kasir/detail/' . $event['event_id']) ?>" class="btn btn-danger">
                        <strong>Batalkan Pesanan</strong>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('bayar').addEventListener('input', function () {
            let totalHarga = <?= $totalHarga ?>;
            let bayar = parseInt(this.value) || 0;
            let kembalian = bayar - totalHarga;
            document.getElementById('kembalian').value = "Rp " + new Intl.NumberFormat('id-ID').format(kembalian);
        });
    </script>
</body>

</html>