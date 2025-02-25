<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Orders</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/kasir/navbar.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/kasir/style.css') ?>">
</head>

<body>
    <div class="navbar">
        <div class="container">
            <a class="navbar-brand logo" href="<?= base_url('kasir/index') ?>">Lontar Event</a>

            <div class="mx-auto search-container">
                <form class="d-flex" method="get" action="<?= base_url('kasir/orders') ?>">
                    <input class="form-control me-2" type="search" name="search" placeholder="Cari Transaksi"
                        aria-label="Search" value="<?= esc($search ?? '') ?>">
                    <button class="btn btn-outline-light" type="submit">Cari</button>
                </form>
            </div>

            <div class="d-flex align-items-center">
                <a class="btn create-event me-2" href="<?= base_url('kasir/index') ?>">Events</a>
                <a class="btn create-event me-2" href="<?= base_url('kasir/orders') ?>">Orders</a>

                <?php if (session()->has('user_id')): ?>
                    <!-- Tampilkan username langsung -->
                    <span class="kasir-username"><?= ucfirst(esc($username)) ?></span>
                    <a class="btn logout-btn ms-3" href="<?= base_url('logout') ?>"><i
                            class="bi bi-box-arrow-in-right"></i></a>
                <?php else: ?>
                    <a class="btn login" href="<?= base_url('login') ?>">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

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

    <!-- Main Content -->
    <div class="container mt-4">
        <h3>Daftar Orders</h3>

        <!-- Form Pencarian -->
        <form method="get" action="<?= base_url('kasir/orders') ?>" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control rounded-start shadow-sm border-primary"
                    placeholder="Cari Nama, Email, Kode Tiket, atau Transaction ID" value="<?= esc($search ?? '') ?>">

                <button type="submit" class="btn btn-primary shadow-sm">Cari</button>
                <a href="<?= base_url('kasir/orders') ?>" class="btn btn-secondary shadow-sm">Reset</a>
            </div>
        </form>

        <!-- Tabel Orders -->
        <table class="table table-bordered mt-3">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Transaction ID</th>
                    <th>Nama Event</th>
                    <th>Nama Pemesan</th>
                    <th>Email</th>
                    <th>Kode Tiket</th>
                    <th>Kelas</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Total</th>
                    <th>Total Transaksi</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($orders)): ?>
                    <tr>
                        <td colspan="13" class="text-center">Tidak ada pesanan</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($orders as $key => $order): ?>
                        <tr>
                            <td><?= $key + 1 ?></td>
                            <td><?= esc($order['transaction_id']) ?></td>
                            <td><?= esc($order['event_title']) ?></td>
                            <td><?= esc($order['display_name']) ?></td>
                            <td><?= esc($order['gmail']) ?></td>
                            <td><?= esc($order['ticket_code']) ?></td>
                            <td><?= strtoupper(esc($order['class'])) ?></td>
                            <td>Rp <?= number_format($order['price'], 0, ',', '.') ?></td>
                            <td><?= esc($order['quantity']) ?></td>
                            <td>Rp <?= number_format($order['total_price'], 0, ',', '.') ?></td>
                            <td><strong>Rp <?= number_format($order['total_transaction'], 0, ',', '.') ?></strong></td>
                            <td>
                                <span class="badge bg-<?= $order['status'] == 'paid' ? 'success' : 'warning' ?>">
                                    <?= esc(ucfirst($order['status'])) ?>
                                </span>
                            </td>
                            <td>
                                <a class="btn btn-warning btn-sm">
                                    Cetak
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>

        </table>
        <!-- Pagination -->
        <div class="pagination-container mb-5">
            <ul class="pagination justify-content-center">
                <?= $pager->links(); ?>
            </ul>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>