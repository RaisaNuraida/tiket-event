<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengguna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- BEGIN: CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/style.css'); ?>">
    <!-- END: CSS -->

    <!-- BEGIN: JS -->
    <script src="<?= base_url('assets/js/admin/main.js'); ?>"></script>
    <!-- END: JS -->
</head>

<body>
    <div class="sidebar">
        <button class="hamburger" onclick="toggleSidebar()">☰</button> <!-- Tombol hamburger -->
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="index"><i class="bi bi-house-door-fill"></i> Dashboard</a></li>
            <li><a href="events"><i class="bi bi-calendar-event"></i> Kelola Event</a></li>
            <li class="active"><a href="users"><i class="bi bi-people"></i> Pengguna</a></li>
            <li><a href="settings"><i class="bi bi-gear"></i> Pengaturan</a></li>
            <li><a href="logout"><i class="bi bi-box-arrow-right"></i> Keluar</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h1>Daftar Pengguna</h1>

        <!-- Pencarian dan Filter -->
        <div class="row mb-4 align-items-center">
            <div class="col-md-4">
                <input type="text" id="searchUser" class="form-control" placeholder="Cari nama pengguna...">
            </div>
            <div class="col-md-4">
                <select id="filterUser" class="form-select">
                    <option value="">Semua Pengguna</option>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                    <option value="user">Owner</option>
                </select>
            </div>
            <!-- Tombol Tambah Pengguna -->
            <div class="col-md-4 text-end">
                <button class="btn btn-primary" onclick="addUser()">
                    <i class="bi bi-person-plus-fill"></i> Tambah Pengguna
                </button>
            </div>
        </div>

        <!-- Tabel Pengguna -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Data Pengguna</h5>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Dibuat pada</th>
                            <th>Terakhir diupdate</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($users)): ?>
                        <?php $no = 1; ?>
                        <?php foreach ($users as $row): ?>
                        <tr>
                            <td><?= $no++; ?> </td>
                            <td><?= $row['username']; ?></td>
                            <td><?= $row['email']; ?></td>
                            <td><?= $row['role']; ?></td>
                            <td id="statusText"><?= $row['status']; ?></td>
                            <td><?= $row['created_at']; ?></td>
                            <td><?= $row['updated_at']; ?></td>
                            <td>
                                <button class="btn btn-sm btn-success flex-fill me-2" id="statusButton"
                                    onclick="toggleStatus()">Active</button>
                                <button class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i> Edit</button>
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Hapus</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">No data found</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>