<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi Owner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- BEGIN: CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/style.css'); ?>">
    <!-- END: CSS -->

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- BEGIN: JS -->
    <script src="<?= base_url('assets/js/admin/main.js'); ?>"></script>
    <!-- END: JS -->
</head>

<body>
    <div class="sidebar">
        <h2>Owner Panel</h2>
        <ul>
            <li><a href="<?= base_url('owner/index') ?>"><i class="bi bi-house-door-fill"></i>Dashboard</a></li>
            <li><a href="<?= base_url('owner/events') ?>"><i class="bi bi-calendar-event"></i> Kelola Event</a></li>
            <li><a href="<?= base_url('owner/users') ?>"><i class="bi bi-people"></i> Pengguna</a></li>
            <li class="active"><a href="<?= base_url('owner/transaksi') ?>"><i class="bi bi-receipt"></i> Transaksi</a>
            </li>
            <li><a href="<?= base_url('owner/activity') ?>"><i class="bi bi-activity"></i> Activity
                    Log</a></li>
            <li><a href="/logout"><i class="bi bi-box-arrow-right"></i> Keluar</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h1>Data Transaksi</h1>

        <!-- Filter & Sorting -->
        <form method="GET" class="row g-2 mb-3 align-items-end">
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="pending" <?= $statusFilter == 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="paid" <?= $statusFilter == 'paid' ? 'selected' : '' ?>>Dibayar</option>
                    <option value="failed" <?= $statusFilter == 'failed' ? 'selected' : '' ?>>Gagal</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Dari Tanggal</label>
                <input type="date" name="start_date" class="form-control" value="<?= esc($startDate) ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label">Sampai Tanggal</label>
                <input type="date" name="end_date" class="form-control" value="<?= esc($endDate) ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label">Urutkan</label>
                <select name="sort" class="form-select">
                    <option value="desc" <?= $sortOrder == 'desc' ? 'selected' : '' ?>>Terbaru</option>
                    <option value="asc" <?= $sortOrder == 'asc' ? 'selected' : '' ?>>Terlama</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <a href="<?= base_url('owner/transaksi/downloadExcel') . '?' . http_build_query($_GET) ?>"
                    class="btn btn-success w-100">
                    <i class="bi bi-file-earmark-excel"></i> Download Excel
                </a>
            </div>
        </form>

        <!-- Tabel Transaksi -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Event</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Jumlah Tiket</th>
                    <th>Total Harga</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($orders)): ?>
                    <tr>
                        <td colspan="8" class="text-center">Tidak ada transaksi.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($orders as $index => $order): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= esc($order['event_title']) ?></td>
                            <td><?= esc($order['display_name']) ?></td>
                            <td><?= esc($order['gmail']) ?></td>
                            <td><?= esc($order['quantity']) ?></td>
                            <td>Rp <?= number_format($order['total_price'], 0, ',', '.') ?></td>
                            <td>
                                <span
                                    class="badge bg-<?= $order['status'] == 'paid' ? 'success' : ($order['status'] == 'pending' ? 'warning' : 'danger') ?>">
                                    <?= ucfirst($order['status']) ?>
                                </span>
                            </td>
                            <td><?= date('d M Y H:i', strtotime($order['created_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Set default nilai untuk input date agar tidak kosong
            let startDate = "<?= esc($startDate) ?>";
            let endDate = "<?= esc($endDate) ?>";
            if (!startDate) document.querySelector("[name='start_date']").valueAsDate = null;
            if (!endDate) document.querySelector("[name='end_date']").valueAsDate = null;
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>