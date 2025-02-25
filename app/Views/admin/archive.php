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
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="<?= base_url('admin/index') ?>"><i class="bi bi-house-door-fill"></i> Dashboard</a></li>
            <li><a href="<?= base_url('admin/events') ?>"><i class="bi bi-calendar-event"></i> Kelola Event</a></li>
            <li><a href="<?= base_url('admin/users') ?>"><i class="bi bi-people"></i> Pengguna</a></li>
            <li class="active"><a href="<?= base_url('admin/archive') ?>"><i class="bi bi-archive"></i> Archive</a></li>
            <li><a href="<?= base_url('admin/activity') ?>"><i class="bi bi-activity"></i> Activity Logs</a></li>
            <li><a href="/logout"><i class="bi bi-box-arrow-right"></i> Keluar</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h1>Data Diarsipkan</h1>

        <!-- Search dan Filter -->
        <div class="row align-items-center mb-4">
            <!-- Form Pencarian dan Filter -->
            <div class="col-md-9">
                <form action="<?= site_url('admin/archive/filterEvent'); ?>" method="get" class="row g-3">
                    <!-- Kolom Pencarian -->
                    <div class="col-md-3">
                        <input type="text" id="searchEvent" name="searchEvent" class="form-control"
                            placeholder="Cari nama event...">
                    </div>

                    <!-- Kolom Filter Kategori -->
                    <div class="col-md-2">
                        <select id="filterCategory" name="filterCategory" class="form-select">
                            <option value="">Semua Kategori</option>
                            <?php foreach ($categorys as $category): ?>
                                <option value="<?= $category['category']; ?>"><?= ucfirst($category['category']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Kolom Filter Status Event -->
                    <div class="col-md-2">
                        <select id="filterStatusEvent" name="filterStatusEvent" class="form-select">
                            <option value="">Semua Status Event</option>
                            <?php foreach ($eventStatus as $statusEvent): ?>
                                <option value="<?= $statusEvent['event_status']; ?>" <?= (isset($_GET['filterStatusEvent']) && $_GET['filterStatusEvent'] == $statusEvent['event_status']) ? 'selected' : ''; ?>>
                                    <?= ucfirst($statusEvent['event_status']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Tombol Cari -->
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
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
                <div class="table-responsive">
                    <table class="table table-striped" id="eventTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Image</th>
                                <th>Judul Event</th>
                                <th>Deskripsi</th>
                                <th>Tanggal</th>
                                <th>Harga Regular</th>
                                <th>Total Tiket</th>
                                <th>Kategori</th>
                                <th>Status Event</th>
                                <th>Restore</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($events) && is_array($events)): ?>
                                <?php $no = 1 + (($pager->getCurrentPage() - 1) * $pager->getPerPage()); ?>
                                <?php foreach ($events as $row): ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td>
                                            <?php if (!empty($row['event_image'])): ?>
                                                <img src="<?= base_url('uploads/' . $row['event_image']); ?>" width="100">
                                            <?php else: ?>
                                                Tidak ada gambar
                                            <?php endif; ?>
                                        </td>
                                        <td><?= ucfirst(esc($row['event_title'])); ?></td>
                                        <td>
                                            <span data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="<?= esc($row['event_description']); ?>">
                                                <?= (strlen($row['event_description']) > 50) ? substr($row['event_description'], 0, 25) . '...' : $row['event_description']; ?>
                                            </span>
                                        </td>
                                        <td><?= $row['event_date']; ?></td>
                                        <td><?= 'Rp ' . number_format($row['regular_price'], 0, ',', '.'); ?></td>
                                        <td><?= $row['total_tickets']; ?></td>
                                        <td><?= ucfirst($row['event_category']); ?></td>
                                        <td><?= ucfirst($row['event_status']); ?></td>
                                        <td>
                                            <form action="<?= base_url('admin/event/archive/' . $row['event_id']); ?>"
                                                method="post">
                                                <?= csrf_field(); ?>
                                                <input type="hidden" name="archived"
                                                    value="<?= ($row['archived'] == 1) ? 0 : 1; ?>">
                                                <button
                                                    class="btn btn-sm <?= ($row['archived'] == 1) ? 'btn-secondary' : 'btn-danger'; ?>"
                                                    type="submit">
                                                    <?= ($row['archived'] == 1) ? 'Restore' : 'Archived'; ?>
                                                </button>
                                            </form>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal"
                                                onclick="document.getElementById('deleteForm').action = '<?= base_url('admin/archive/delete/' . $row['event_id']); ?>';
                                                document.getElementById('eventTitle').innerText = '<?= esc($row['event_title']); ?>';">
                                                Hapus
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="13" class="text-center">Data tidak tersedia</td>
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
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus event <strong><span id="eventTitle"></span></strong> secara
                    permanen?
                </div>
                <div class="modal-footer">
                    <form id="deleteForm" method="post">
                        <?= csrf_field(); ?>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
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