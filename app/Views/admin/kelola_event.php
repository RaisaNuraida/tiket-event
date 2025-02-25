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
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="<?= base_url('admin/index') ?>"><i class="bi bi-house-door-fill"></i> Dashboard</a></li>
            <li class="active"><a href="<?= base_url('admin/events') ?>"><i class="bi bi-calendar-event"></i> Kelola
                    Event</a></li>
            <li><a href="<?= base_url('admin/users') ?>"><i class="bi bi-people"></i> Pengguna</a></li>
            <li><a href="<?= base_url('admin/archive') ?>"><i class="bi bi-archive"></i> Archive</a></li>
            <li><a href="<?= base_url('admin/activity') ?>"><i class="bi bi-activity"></i> Activity Logs</a></li>
            <li><a href="/logout"><i class="bi bi-box-arrow-right"></i> Keluar</a></li>
        </ul>
    </div>


    <div class="main-content">
        <h1>Kelola Event</h1>

        <!-- Search dan Filter -->
        <div class="row align-items-center mb-4">
            <!-- Form Pencarian dan Filter -->
            <div class="col-md-9">
                <form action="<?= site_url('admin/events/filterEvent'); ?>" method="get" class="row g-3">
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

            <!-- Tombol Tambah Jenis Tiket -->
            <div class="col-md-3 text-md-end">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#classEventModal">
                    <i class="bi bi-plus-lg"></i> Jenis Tiket
                </button>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#categoryEventModal">
                    <i class="bi bi-plus-lg"></i> Category
                </button>
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
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Data Event</h5>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEventModal">
                        <i class="bi bi-plus-lg"></i> Tambah Event
                    </button>
                </div>

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
                                <th>Update Status</th>
                                <th>Update Event</th>
                                <th>Arsip</th>
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
                                        <td>
                                            <form action="<?= base_url('admin/event/update_status/' . $row['event_id']); ?>"
                                                method="post" style="display: inline;">
                                                <?= csrf_field(); ?>
                                                <input type="hidden" name="status"
                                                    value="<?= ($row['status'] === 'active') ? 'inactive' : 'active'; ?>">
                                                <button
                                                    class="btn btn-sm <?= ($row['status'] === 'active') ? 'btn-success' : 'btn-danger'; ?>"
                                                    type="submit">
                                                    <?= ucfirst($row['status']); ?>
                                                </button>
                                            </form>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-warning flex-fill me-2"
                                                onclick="fillEditModal(<?= htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8'); ?>)"
                                                data-bs-toggle="modal" data-bs-target="#editEventModal">
                                                Update
                                            </button>
                                        </td>
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

    <!-- Modal Category -->
    <div class="modal fade" id="categoryEventModal" tabindex="-1" aria-labelledby="categoryEventModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="categoryEventModalLabel">Kelola Kategori Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form Tambah Category -->
                    <form id="addCategoryForm" action="<?= site_url('admin/events/add_category'); ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="input-group mb-3">
                            <div class="me-3">
                                <input type="text" name="category" id="categoryName" class="form-control"
                                    placeholder="Nama Kategori Baru" required>
                            </div>
                            <div class="me-3">
                                <input type="text" name="description" id="categoryDescription" class="form-control"
                                    placeholder="Deskripsi Kategori Baru" required>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary">Tambah</button>
                            </div>
                        </div>
                    </form>

                    <!-- Daftar Kategori -->
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Kategori</th>
                                <th>Deskripsi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <?php foreach ($categorys as $category): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= ucwords(esc($category['category'])); ?></td>
                                    <td><?= ucwords(esc($category['description'])); ?></td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                            data-target="#deleteModal">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Hapus Category-->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Penghapusan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus kategori ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <form action="<?= site_url('admin/events/delete_category/' . $category['id']); ?>" method="post"
                        class="d-inline">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Jenis Tiket -->
    <div class="modal fade" id="classEventModal" tabindex="-1" aria-labelledby="classEventModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="classEventModalLabel">Kelola Jenis Tiket Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form Tambah Jenis Tiket -->
                    <form id="addClassForm" action="<?= site_url('admin/events/add_class'); ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="row g-2 align-items-center">
                            <div class="col-md-3">
                                <input type="text" name="class" id="className" class="form-control"
                                    placeholder="Nama Jenis Tiket Baru" required>
                            </div>
                            <div class="col-md-3">
                                <input type="number" step="0.01" class="form-control" id="classPrice" name="price"
                                    placeholder="Harga Jenis Tiket Baru" required>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="description" id="classDescription" class="form-control"
                                    placeholder="Deskripsi Jenis Tiket Baru" required>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">Tambah</button>
                            </div>
                        </div>
                    </form>

                    <!-- Daftar Jenis Tiket -->
                    <table class="table table-striped mt-3">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Jenis Tiket</th>
                                <th>Harga</th>
                                <th>Deskripsi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <?php foreach ($eventclass as $class): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= strtoupper(esc($class['class'])); ?></td>
                                    <td><?= 'Rp ' . number_format($class['price'], 0, ',', '.'); ?></td>
                                    <td><?= ucwords(esc($class['description'])); ?></td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                            data-target="#deleteClassModal">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Hapus Jenis Tiket-->
    <div class="modal fade" id="deleteClassModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Penghapusan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus Jenis Tiket ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <form action="<?= site_url('admin/events/delete_class/' . $class['id']); ?>" method="post"
                        class="d-inline">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Event -->
    <div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEventModalLabel">Tambah Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addEventForm" enctype="multipart/form-data" method="post"
                        action="<?= base_url('admin/event/add'); ?>">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label for="eventImage" class="form-label">Gambar Event</label>
                            <input type="file" class="form-control" id="eventImage" name="event_image" accept="image/*"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="eventTitle" class="form-label">Judul Event</label>
                            <input type="text" class="form-control" id="eventTitle" name="event_title" required>
                        </div>
                        <div class="mb-3">
                            <label for="eventLokasi" class="form-label">Lokasi</label>
                            <input type="text" class="form-control" id="eventLokasi" name="event_lokasi" required>
                        </div>
                        <div class="mb-3">
                            <label for="eventDescription" class="form-label">Deskripsi Event</label>
                            <textarea class="form-control" id="eventDescription" name="event_description"
                                rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="preorderDate" class="form-label">Tanggal Pre-Order</label>
                            <input type="datetime-local" class="form-control" id="preorderDate" name="preorder_date"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="eventDate" class="form-label">Tanggal Event</label>
                            <input type="datetime-local" class="form-control" id="eventDate" name="event_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="eventCategory" class="form-label">Kategori Event</label>
                            <select class="form-control" name="event_category" id="eventCategory" required>
                                <option value=""></option>
                                <?php foreach ($categorys as $category): ?>
                                    <option value="<?= $category['category']; ?>"><?= ucfirst($category['category']); ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="regularPrice" class="form-label">Harga</label>
                            <input type="number" step="0.01" class="form-control" id="regularPrice"
                                name="regular_price">
                        </div>
                        <div class="mb-3">
                            <label for="totalTicketsRegular" class="form-label">Total Tiket Regular</label>
                            <input type="number" class="form-control" id="totalTicketsRegular" name="tickets_regular"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="totalTicketsVIP" class="form-label">Total Tiket VIP</label>
                            <input type="number" class="form-control" id="totalTicketsVIP" name="tickets_vip" required>
                        </div>
                        <div class="mb-3">
                            <label for="totalTicketsVVIP" class="form-label">Total Tiket VVIP</label>
                            <input type="number" class="form-control" id="totalTicketsVVIP" name="tickets_vvip"
                                required>
                        </div>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Event -->
    <div class="modal fade" id="editEventModal" tabindex="-1" aria-labelledby="editEventModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editEventModalLabel">Edit Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editEventForm" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <input type="hidden" id="eventId" name="event_id">
                        <input type="hidden" id="oldEventImage" name="old_event_image">
                        <div class="mb-3">
                            <label for="eventImage" class="form-label">Gambar Event</label>
                            <input type="file" class="form-control" id="eventImage" name="event_image" accept="image/*">
                            <img id="previewImage" src="" width="100" class="mt-2">
                        </div>
                        <div class="mb-3">
                            <label for="editEventTitle" class="form-label">Judul Event</label>
                            <input type="text" class="form-control" id="editEventTitle" name="event_title" required>
                        </div>
                        <div class="mb-3">
                            <label for="editEventLocation" class="form-label">Lokasi</label>
                            <input type="text" class="form-control" id="editEventLocation" name="event_location"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="editEventDescription" class="form-label">Deskripsi Event</label>
                            <textarea class="form-control" id="editEventDescription" name="event_description" rows="3"
                                required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="editPreorderDate" class="form-label">Tanggal Pre-Order</label>
                            <input type="datetime-local" class="form-control" id="editPreorderDate" name="preorder_date"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="editEventDate" class="form-label">Tanggal Event</label>
                            <input type="datetime-local" class="form-control" id="editEventDate" name="event_date"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="editEventCategory" class="form-label">Kategori Event</label>
                            <select class="form-control" id="editEventCategory" name="event_category" required>
                                <option value=""></option>
                                <?php foreach ($categorys as $category): ?>
                                    <option value="<?= $category['category']; ?>"><?= ucfirst($category['category']); ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editRegularPrice" class="form-label">Harga Regular</label>
                            <input type="number" step="0.01" class="form-control" id="editRegularPrice"
                                name="regular_price">
                        </div>
                        <div class="mb-3">
                            <label for="editTotalTicketsRegular" class="form-label">Total Tiket Regular</label>
                            <input type="number" class="form-control" id="editTotalTicketsRegular"
                                name="tickets_regular" required>
                        </div>
                        <div class="mb-3">
                            <label for="editTotalTicketsVIP" class="form-label">Total Tiket VIP</label>
                            <input type="number" class="form-control" id="editTotalTicketsVIP" name="tickets_vip"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="editTotalTicketsVVIP" class="form-label">Total Tiket VVIP</label>
                            <input type="number" class="form-control" id="editTotalTicketsVVIP" name="tickets_vvip"
                                required>
                        </div>
                        <button type="button" class="btn btn-primary" id="submitEditEvent">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function fillEditModal(eventData) {
            document.getElementById('eventId').value = eventData.event_id;
            document.getElementById('editEventTitle').value = eventData.event_title;
            document.getElementById('editEventLocation').value = eventData.location;
            document.getElementById('editEventDescription').value = eventData.event_description;
            document.getElementById('editPreorderDate').value = eventData.event_preorder;
            document.getElementById('editEventDate').value = eventData.event_date;
            document.getElementById('editEventCategory').value = eventData.event_category;
            document.getElementById('editRegularPrice').value = eventData.regular_price;
            document.getElementById('editTotalTicketsRegular').value = eventData.tickets_regular;
            document.getElementById('editTotalTicketsVIP').value = eventData.tickets_vip;
            document.getElementById('editTotalTicketsVVIP').value = eventData.tickets_vvip;

            document.getElementById('oldEventImage').value = eventData.event_image;

            if (eventData.event_image) {
                document.getElementById('previewImage').src = '<?= base_url('uploads/'); ?>' + eventData.event_image;
            } else {
                document.getElementById('previewImage').src = '';
            }
        }

        $(document).ready(function () {
            $('#submitEditEvent').on('click', function (e) {
                e.preventDefault();

                var formData = new FormData($('#editEventForm')[0]);
                var eventId = document.getElementById('eventId').value;

                $.ajax({
                    url: '<?= base_url('admin/event/edit_events/') ?>' + eventId,
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        if (response.status) {
                            // Jika response memiliki redirect, maka arahkan ke halaman tersebut
                            if (response.redirect) {
                                window.location.href = response.redirect;
                            } else {
                                alert('Event berhasil diperbarui.');
                                location.reload();
                            }
                        } else {
                            alert(response.message || 'Terjadi kesalahan saat mengupdate event.');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error(xhr.responseText);
                        alert('Terjadi kesalahan saat mengupdate event.');
                    }
                });
            });
        });

        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        function archiveEvent(eventId) {
            if (confirm("Apakah Anda yakin ingin mengarsipkan event ini?")) {
                let form = document.createElement("form");
                form.method = "POST";
                form.action = "<?= base_url('admin/event/archive/'); ?>" + eventId;
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>

    <?php if (session('modal') == 'addEventModal'): ?>
        <script>
            var myModal = new bootstrap.Modal(document.getElementById('addEventModal'));
            myModal.show();
        </script>
    <?php endif; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Bootstrap JS dan jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>

</body>

</html>