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
            <li><a href="/logout"><i class="bi bi-box-arrow-right"></i> Keluar</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h1>Daftar Pengguna</h1>

        <!-- Pencarian dan Filter -->
        <div class="row mb-4 align-items-center">
            <!-- Form Pencarian -->
            <div class="col-md-9">
                <form action="<?= site_url('admin/users/filter_users'); ?>" method="get" class="row g-2">
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
            <!-- Tombol Tambah Pengguna -->
            <div class="col-md-3 text-end">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    <i class="bi bi-person-plus-fill"></i> Tambah Pengguna
                </button>
            </div>
        </div>

        <!-- POP UP -->
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
                                    <td><?= ucfirst($row['role']); ?></td>
                                    <td id="statusText<?= $row['id']; ?>"><?= ucfirst($row['status']); ?></td>
                                    <td><?= $row['created_at']; ?></td>
                                    <td><?= $row['updated_at']; ?></td>
                                    <td>
                                        <form action="<?= base_url('admin/users/update_status/' . $row['id']); ?>" method="post"
                                            style="display:inline;">
                                            <?= csrf_field(); ?>
                                            <input type="hidden" name="status"
                                                value="<?= $row['status'] === 'active' ? 'inactive' : 'active'; ?>">
                                            <button
                                                class="btn btn-sm <?= $row['status'] === 'active' ? 'btn-success' : 'btn-danger'; ?>"
                                                type="submit">
                                                <?= ucfirst($row['status']); ?>
                                            </button>
                                        </form>
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#editUserModal"
                                            onclick="fillEditModal(<?= htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8'); ?>)">
                                            Edit
                                        </button>
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


    <!-- Modal Tambah Pengguna -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="addUserForm" method="POST" action="<?= base_url('admin/users/add_user'); ?>">
                    <?= csrf_field() ?>
                    <div class="modal-header">
                        <h5 class="modal-title" id="addUserModalLabel">Tambah Pengguna</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value=""></option>
                                <?php foreach ($roles as $role): ?>
                                    <option value="<?= $role['name']; ?>"><?= ucfirst($role['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit User -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editUserForm">
                    <?= csrf_field() ?>
                    <div class="modal-header">
                        <h5 class="modal-title" id="editUserModalLabel">Edit Pengguna</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="editUserId" name="id">
                        <div class="mb-3">
                            <label for="editUsername" class="form-label">Username</label>
                            <input type="text" class="form-control" id="editUsername" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="editEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="editEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="editRole" class="form-label">Role</label>
                            <select class="form-select" id="editRole" name="role" required>
                                <?php foreach ($roles as $role): ?>
                                    <option value="<?= $role['name']; ?>" <?= (isset($users['role']) && $role['name'] === $users['role']) ? 'selected' : ''; ?>>
                                        <?= ucfirst($role['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function fillEditModal(users) {
            document.getElementById('editUserId').value = users.id;
            document.getElementById('editUsername').value = users.username;
            document.getElementById('editEmail').value = users.email;
            document.getElementById('editRole').value = users.role;
        }

        document.getElementById('editUserForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            formData.append('csrf_token', '<?= csrf_hash(); ?>');  // CSRF token for security

            fetch('<?= base_url('admin/users/update_user'); ?>/' + document.getElementById('editUserId').value, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest', // Header for AJAX request
                },
                body: formData,
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Jika sukses, redirect ke halaman daftar pengguna dan tampilkan flash message sukses
                        window.location.href = '<?= base_url('admin/users'); ?>'; // Redirect ke halaman users
                    } else {
                        // Jika gagal, tampilkan error tanpa alert
                        // Anda dapat menampilkan pesan error di halaman menggunakan flashdata
                        console.log('Gagal memperbarui pengguna: ', data.errors);
                    }
                })
                .catch(error => {
                    // Tangani kesalahan jaringan atau kesalahan lain
                    console.error('Error:', error);
                });
        });

    </script>

    <!-- BEGIN: JS -->
    <script src="<?= base_url('assets/js/admin/main.js'); ?>"></script>
    <!-- END: JS -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>