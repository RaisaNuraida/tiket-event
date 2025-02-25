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
            <li class="active"><a href="<?= base_url('owner/users') ?>"><i class="bi bi-people"></i> Pengguna</a></li>
            <li><a href="<?= base_url('owner/transaksi') ?>"><i class="bi bi-receipt"></i> Transaksi</a></li>
            <li><a href="<?= base_url('owner/activity') ?>"><i class="bi bi-activity"></i> Activity Log</a></li>
            <li><a href="/logout"><i class="bi bi-box-arrow-right"></i> Keluar</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h1>Daftar Pengguna</h1>

        <!-- Pencarian dan Filter -->
        <div class="row mb-4 align-items-center">
            <!-- Form Pencarian -->
            <div class="col-md-9">
                <form action="<?= site_url('owner/users/filter_users'); ?>" method="get" class="row g-2">
                    <div class="col-md-4">
                        <input type="text" id="searchUser" name="searchUser" class="form-control"
                            placeholder="Cari nama pengguna...">
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="searchRole" name="searchRole">
                            <option value="">Semua Role</option>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?= $role['name']; ?>"><?= ucfirst($role['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="status" id="status" class="form-select">
                            <option value="">Status</option>
                            <?php foreach ($status as $statusOption): ?>
                                <option value="<?= $statusOption['status']; ?>"><?= ucfirst($statusOption['status']); ?>
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

        <!-- Tabel Pengguna -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Data Pengguna</h5>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Dibuat pada</th>
                            <th>Terakhir diupdate</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($users)): ?>
                            <?php $no = 1 + (($pager->getCurrentPage('default') - 1) * 10); ?>
                            <?php foreach ($users as $row): ?>
                                <tr>
                                    <td><?= $no++; ?> </td>
                                    <td><?= $row['username']; ?></td>
                                    <td><?= $row['email']; ?></td>
                                    <td><?= ucfirst($row['role']); ?></td>
                                    <td id="statusText<?= $row['id']; ?>"><?= ucfirst($row['status']); ?></td>
                                    <td><?= $row['created_at']; ?></td>
                                    <td><?= $row['updated_at']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center">No data found</td>
                            </tr>
                        <?php endif; ?>
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