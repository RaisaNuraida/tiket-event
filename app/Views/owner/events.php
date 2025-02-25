<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
            <li><a href="<?= base_url('owner/index') ?>"><i class="bi bi-house-door-fill"></i> Dashboard</a></li>
            <li class="active"><a href="<?= base_url('owner/events') ?>"><i class="bi bi-calendar-event"></i> Event</a>
            </li>
            <li><a href="<?= base_url('owner/users') ?>"><i class="bi bi-people"></i> Pengguna</a></li>
            <li><a href="<?= base_url('owner/transaksi') ?>"><i class="bi bi-receipt"></i> Transaksi</a></li>
            <li><a href="<?= base_url('owner/activity') ?>"><i class="bi bi-activity"></i> Activity
                    Log</a></li>
            <li><a href="/logout"><i class="bi bi-box-arrow-right"></i> Keluar</a></li>
        </ul>
    </div>


    <div class="main-content">
        <h1>Kelola Event</h1>

        <!-- Search dan Filter -->
        <div class="row align-items-center mb-4">
            <!-- Form Pencarian dan Filter -->
            <div class="col-md-9">
                <form action="<?= site_url('owner/events/filterEvent'); ?>" method="get" class="row g-3">
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

                    <!-- Kolom Filter Status -->
                    <div class="col-md-2">
                        <select id="filterStatus" name="filterStatus" class="form-select">
                            <option value="">Semua Status</option>
                            <?php foreach ($status as $statusOption): ?>
                                <option value="<?= $statusOption['status']; ?>"><?= ucfirst($statusOption['status']); ?>
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
                <h5 class="card-title">Data Event</h5>
             
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
                                <th>Status</th>
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
                                        <td><?= ucfirst($row['status']); ?></td>
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Bootstrap JS dan jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>

</body>

</html>