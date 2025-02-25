<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Owner</title>
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
            <li class="active"><a href="<?= base_url('owner/index') ?>"><i
                        class="bi bi-house-door-fill"></i>Dashboard</a></li>
            <li><a href="<?= base_url('owner/events') ?>"><i class="bi bi-calendar-event"></i> Kelola Event</a></li>
            <li><a href="<?= base_url('owner/users') ?>"><i class="bi bi-people"></i> Pengguna</a></li>
            <li><a href="<?= base_url('owner/transaksi') ?>"><i class="bi bi-receipt"></i> Transaksi</a></li>
            <li><a href="<?= base_url('owner/activity') ?>"><i class="bi bi-activity"></i> Activity Log</a></li>
            <li><a href="/logout"><i class="bi bi-box-arrow-right"></i> Keluar</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h1>Dashboard Owner</h1>

        <div class="row mb-2">
            <div class="col-md-12">
                <div class="alert alert-info">
                    <h5>Halo, <?= ucfirst($username); ?>!</h5>
                    <p>Selamat datang kembali! Kamu terakhir kali login pada
                        <strong><?= esc($lastLoginDate); ?></strong>.
                    </p>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-white" style="background-color: #1E293B;">
                    <div class="card-body">
                        <h5 class="card-title" style="color: #FFD700;">Total Event</h5>
                        <h3 style="color: #F8FAFC;"><?= esc($totalEvents); ?></h3>
                        <a style="font-size: 12px; color: #CBD5E1;" href="<?= base_url('owner/events') ?>">Detail</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white" style="background-color: #FFD700;">
                    <div class="card-body">
                        <h5 class="card-title" style="color: #0F172A;">Tiket Terjual</h5>
                        <h3 style="color: #0F172A;"><?= esc($totalTicketsSold); ?></h3>
                        <a style="font-size: 12px; color: #0F172A;" href="<?= base_url('owner/transaksi') ?>">Detail</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white" style="background-color: #0F172A;">
                    <div class="card-body">
                        <h5 class="card-title" style="color: #FFD700;">Pendapatan</h5>
                        <h3 style="color: #F8FAFC;">Rp <?= number_format($totalRevenue, 0, ',', '.'); ?></h3>
                        <a style="font-size: 12px; color: #CBD5E1;" href="<?= base_url('owner/transaksi') ?>">Detail</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white" style="background-color: #FFD700;">
                    <div class="card-body">
                        <h5 class="card-title" style="color: #0F172A;">Total Pengguna</h5>
                        <h3 style="color: #0F172A;"><?= esc($totalUsers); ?></h3>
                        <a style="font-size: 12px; color: #0F172A;" href="<?= base_url('owner/users') ?>">Detail</a>
                    </div>
                </div>
            </div>
        </div>


        <!-- Daftar Event Terlaris -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Event Terlaris</h5>
                        <table class="table" id="eventTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Event</th>
                                    <th>Tanggal</th>
                                    <th>Total Tiket</th>
                                    <th>Tiket Terjual</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($topEvents as $index => $event): ?>
                                    <tr>
                                        <td><?= $index + 1; ?></td>
                                        <td><?= esc($event['event_title']); ?></td>
                                        <td><?= esc($event['event_date']); ?></td>
                                        <td><?= esc($event['total_tickets']); ?></td>
                                        <td><?= esc($event['total_sold']); ?></td>
                                        <td><?= ucfirst($event['event_status']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($topEvents)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada data event.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>