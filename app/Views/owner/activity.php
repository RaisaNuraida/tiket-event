<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_hash(); ?>">
    <title>Pengguna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- BEGIN: CSS -->
    <link rel="stylesheet" href="<?= base_url(relativePath: 'assets/css/admin/style.css'); ?>">
    <!-- END: CSS -->

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

</head>

<body>
    <div class="sidebar">
        <h2>Owner Panel</h2>
        <ul>
            <li><a href="<?= base_url('owner/index') ?>"><i class="bi bi-house-door-fill"></i> Dashboard</a></li>
            <li><a href="<?= base_url('owner/events') ?>"><i class="bi bi-calendar-event"></i> Event</a></li>
            <li><a href="<?= base_url('owner/users') ?>"><i class="bi bi-people"></i> Pengguna</a></li>
            <li><a href="<?= base_url('owner/transaksi') ?>"><i class="bi bi-receipt"></i> Transaksi</a></li>
            <li class="active"><a href="<?= base_url('owner/activity') ?>"><i class="bi bi-activity"></i> Activity
                    Log</a></li>
            <li><a href="/logout"><i class="bi bi-box-arrow-right"></i> Keluar</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h1>Activity Logs</h1>

        <!-- Pencarian dan Filter -->
        <div class="row mb-4 align-items-center">
            <!-- Form Pencarian -->
            <div class="col-md-9">
                <form action="<?= site_url('owner/activity'); ?>" method="get" class="row g-2">
                    <div class="col-md-3">
                        <input type="text" id="searchUser" name="searchUser" class="form-control"
                            placeholder="Cari nama username..." value="<?= esc($searchUser ?? '') ?>">
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="searchRole" name="searchRole">
                            <option value="">Semua Role</option>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?= esc($role['role']); ?>" <?= ($searchRole ?? '') == $role['role'] ? 'selected' : '' ?>>
                                    <?= ucfirst(esc($role['role'])); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Cari
                        </button>
                    </div>
                </form>
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

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">History Activity Logs</h5>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Activity</th>
                            <th>IP Address</th>
                            <th>User Agent</th>
                            <th>Dibuat pada</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($activities as $activity): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= ucfirst(esc($activity['username'])); ?></td>
                                <td><?= ucfirst($activity['role']); ?></td>
                                <td><?= $activity['activity']; ?></td>
                                <td><?= $activity['ip_address']; ?></td>
                                <td><?= $activity['user_agent']; ?></td>
                                <td><?= $activity['created_at']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="pagination-container">
                    <ul class="pagination justify-content-center">
                        <?= $pager->links(); ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- BEGIN: JS -->
    <script src="<?= base_url('assets/js/admin/main.js'); ?>"></script>
    <!-- END: JS -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>