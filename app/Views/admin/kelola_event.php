<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket Event</title>
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
            <li class="active"><a href="events"><i class="bi bi-calendar-event"></i> Kelola Event</a></li>
            <li><a href="users"><i class="bi bi-people"></i> Pengguna</a></li>
            <li><a href="settings"><i class="bi bi-gear"></i> Pengaturan</a></li>
            <li><a href="/logout"><i class="bi bi-box-arrow-right"></i> Keluar</a></li>
        </ul>
    </div>


    <div class="main-content">
        <h1>Kelola Event</h1>

        <!-- Search dan Filter -->
        <div class="row align-items-center mb-4">
            <div class="col-md-3 mb-2 mb-md-0">
                <input type="text" id="searchEvent" class="form-control" placeholder="Cari nama event...">
            </div>
            <div class="col-md-3 mb-2 mb-md-0">
                <select id="filterCategory" class="form-select">
                    <option value="">Semua Kategori</option>
                    <option value="konser">Musik</option>
                    <option value="seminar">Seminar</option>
                    <option value="workshop">Workshop</option>
                    <option value="expo">Expo</option>
                    <option value="olahraga">Olahraga/Kebugaran</option>
                </select>
            </div>
            <div class="col-md-3 mb-2 mb-md-0">
                <select id="filterStatus" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="upcoming">Event Mendatang</option>
                    <option value="past">Event Selesai</option>
                </select>
            </div>
            <div class="col-md-3 text-md-end">
                <!-- Tombol Tambah Event -->
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEventModal">Tambah
                    Event</button>
            </div>
        </div>

        <!-- Tabel Daftar Event dengan Pembungkus Responsif -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Data Event</h5>
                <div class="table-responsive">
                    <table class="table table-striped" id="eventTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Event</th>
                                <th>Kategori</th>
                                <th>Tanggal</th>
                                <th>Total Tiket</th>
                                <th>Tiket Terjual</th>
                                <th>Status</th>
                                <th>Created at</th>
                                <th>Updated at</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Konser Musik</td>
                                <td>Musik</td>
                                <td>2025-01-20</td>
                                <td>500</td>
                                <td>250</td>
                                <td id="statusText">Active</td>
                                <td>20/22/2025</td>
                                <td>20/22/2025</td>
                                <td class="d-flex justify-content-between">
                                    <button class="btn btn-sm btn-success flex-fill me-2" id="statusButton"
                                        onclick="toggleStatus()">Active</button>
                                    <button class="btn btn-sm btn-primary flex-fill me-2">Tinjau</button>
                                    <button class="btn btn-sm btn-warning flex-fill me-2"
                                        onclick="editEvent(this)">Edit</button>
                                    <button class="btn btn-sm btn-danger flex-fill me-2"
                                        onclick="deleteEvent(this)">Hapus</button>
                                </td>
                            </tr>
                            <!-- Baris tambahan akan ditambahkan dengan JavaScript -->
                        </tbody>
                    </table>
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
                    <form id="addEventForm">
                        <div class="mb-3">
                            <label for="eventName" class="form-label">Nama Event</label>
                            <input type="text" class="form-control" id="eventName" required>
                        </div>
                        <div class="mb-3">
                            <label for="eventDate" class="form-label">Tanggal</label>
                            <input type="date" class="form-control" id="eventDate" required>
                        </div>
                        <div class="mb-3">
                            <label for="eventTickets" class="form-label">Tiket Terjual</label>
                            <input type="number" class="form-control" id="eventTickets" required>
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
                    <form id="editEventForm">
                        <div class="mb-3">
                            <label for="editEventName" class="form-label">Nama Event</label>
                            <input type="text" class="form-control" id="editEventName" required>
                        </div>
                        <div class="mb-3">
                            <label for="editEventDate" class="form-label">Tanggal</label>
                            <input type="date" class="form-control" id="editEventDate" required>
                        </div>
                        <div class="mb-3">
                            <label for="editEventTickets" class="form-label">Tiket Terjual</label>
                            <input type="number" class="form-control" id="editEventTickets" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>