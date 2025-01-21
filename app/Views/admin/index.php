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
            <li class="active"><a href="index"><i class="bi bi-house-door-fill"></i> Dashboard</a></li>
            <li><a href="events"><i class="bi bi-calendar-event"></i> Kelola Event</a></li>
            <li><a href="users"><i class="bi bi-people"></i> Pengguna</a></li>
            <li><a href="settings"><i class="bi bi-gear"></i> Pengaturan</a></li>
            <li><a href="logout"><i class="bi bi-box-arrow-right"></i> Keluar</a></li>
        </ul>
    </div>


    <div class="main-content">

        <!-- Statistik -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Event</h5>
                        <h3>25</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Tiket Terjual</h5>
                        <h3>1,230</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5 class="card-title">Pendapatan</h5>
                        <h3>Rp 12,500,000</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <h5 class="card-title">Pengguna</h5>
                        <h3>320</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search dan Filter -->
        <div class="row mb-4">
            <div class="col-md-6">
                <input type="text" id="searchEvent" class="form-control" placeholder="Cari nama event...">
            </div>
            <div class="col-md-6">
                <select id="filterEvent" class="form-select">
                    <option value="">Semua Event</option>
                    <option value="upcoming">Event Mendatang</option>
                    <option value="past">Event Selesai</option>
                </select>
            </div>
        </div>

        <!-- Daftar Event Terbaru -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Event Terbaru</h5>
                        <button class="btn btn-success mb-3" onclick="exportData()">Export Data Penjualan</button>
                        <table class="table" id="eventTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Event</th>
                                    <th>Tanggal</th>
                                    <th>Tiket Terjual</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Konser Musik</td>
                                    <td>2025-01-20</td>
                                    <td>500</td>
                                    <td>Mendatang</td>
                                    <td><a href="#" class="btn btn-sm btn-primary">Detail</a></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Workshop Coding</td>
                                    <td>2024-12-25</td>
                                    <td>300</td>
                                    <td>Selesai</td>
                                    <td><a href="#" class="btn btn-sm btn-primary">Detail</a></td>
                                </tr>
                                <!-- Tambahkan lebih banyak event sesuai kebutuhan -->
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