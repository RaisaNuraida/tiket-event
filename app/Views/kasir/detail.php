<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/kasir/navbar.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/kasir/detail.css') ?>">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand logo" href="<?= base_url('kasir/index') ?>">Lontar Event</a>

            <div class="mx-auto search-container">
                <form class="d-flex" method="get" action="<?= base_url('kasir/index') ?>">
                    <input class="form-control me-2" type="search" name="search" placeholder="Cari Event"
                        aria-label="Search" value="<?= esc($search ?? '') ?>">
                    <button class="btn btn-outline-light" type="submit">Cari</button>
                </form>
            </div>

            <div class="d-flex align-items-center">
                <a class="btn create-event me-2" href="<?= base_url('kasir/index') ?>">Events</a>
                <a class="btn create-event me-2" href="<?= base_url('kasir/orders') ?>">Orders</a>

                <?php if (isset($user)): ?>
                    <!-- Username tanpa dropdown -->
                    <span class="kasir-username"><?= ucfirst($user['username'] ?? 'Kasir') ?></span>
                    <a class="btn logout-btn ms-3" href="<?= base_url('logout') ?>"><i
                            class="bi bi-box-arrow-in-right"></i></a>
                <?php else: ?>
                    <a class="btn login" href="<?= base_url('login') ?>">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>


    <!-- Main Content -->
    <div class="container mt-4 mb-4">
        <div class="event-container">
            <!-- Gambar & Deskripsi di kiri -->
            <div class="col-md-8">
                <!-- Gambar Event -->
                <div class="event-image">
                    <img src="<?= base_url('uploads/' . $event['event_image']) ?>"
                        alt="<?= esc($event['event_title']) ?>">
                </div>

                <!-- Tabs untuk Deskripsi dan Pilih Tiket -->
                <ul class="nav nav-tabs mt-3" id="eventTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="desc-tab" data-bs-toggle="tab" data-bs-target="#desc"
                            type="button" role="tab"><i class="bi-text-left"></i> Deskripsi</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="ticket-tab" data-bs-toggle="tab" data-bs-target="#ticket"
                            type="button" role="tab"><i class="bi bi-ticket-detailed"></i> Pilih Tiket</button>
                    </li>
                </ul>

                <div class="tab-content mt-3" id="eventTabContent">
                    <!-- Tab Deskripsi -->
                    <div class="tab-pane fade show active" id="desc" role="tabpanel">
                        <p><?= ucwords($event['event_category']) ?></p>
                        <p><strong>Deskripsi</strong></p>
                        <p><?= $event['event_description'] ?></p>
                    </div>

                    <!-- Tab Pilih Tiket -->
                    <div class="tab-pane fade" id="ticket" role="tabpanel">
                        <div class="row">
                            <form action="<?= base_url(relativePath: 'kasir/order_ticket') ?>" method="post">
                                <?= csrf_field(); ?>
                                <input type="hidden" name="event_id" value="<?= $event['event_id'] ?>">

                                <?php foreach ($ticket_classes as $ticket): ?>
                                    <?php
                                    $total_price = $event['regular_price'] + $ticket['price'];
                                    $current_time = date('Y-m-d H:i:s');
                                    $preorder_time = $event['event_preorder'];
                                    $is_preorder_active = $current_time >= $preorder_time;

                                    if ($ticket['class'] == 'regular') {
                                        $stock_available = $event['tickets_regular'];
                                    } elseif ($ticket['class'] == 'vip') {
                                        $stock_available = $event['tickets_vip'];
                                    } elseif ($ticket['class'] == 'vvip') {
                                        $stock_available = $event['tickets_vvip'];
                                    } else {
                                        $stock_available = 0;
                                    }
                                    ?>

                                    <div class="col-12 mb-3"> <!-- Agar tampil ke bawah -->
                                        <div class="card border-0 shadow-sm">
                                            <div class="card-body">
                                                <h5 class="card-title text-primary">
                                                    <i class="bi bi-ticket-perforated-fill"></i>
                                                    <?= ucfirst($ticket['class']) ?>
                                                </h5>
                                                <p class="card-text"><?= $ticket['description'] ?></p>
                                                <p class="fw-bold">Harga: Rp <?= number_format($total_price, 0, ',', '.') ?>
                                                </p>
                                                <p class="text-danger"><strong>Stok Tersisa: <span
                                                            id="stock_<?= $ticket['class'] ?>"><?= $stock_available ?></span></strong>
                                                </p>

                                                <?php if ($is_preorder_active): ?>
                                                    <input type="hidden" name="prices[<?= $ticket['class'] ?>]"
                                                        value="<?= $total_price ?>">

                                                    <div class="d-flex align-items-center">
                                                        <button type="button" class="btn btn-outline-danger btn-sm me-2"
                                                            onclick="updateQuantity('<?= $ticket['class'] ?>', -1, <?= $stock_available ?>)">
                                                            <i class="bi bi-dash-lg"></i>
                                                        </button>
                                                        <input type="text" name="quantities[<?= $ticket['class'] ?>]"
                                                            id="quantity_<?= $ticket['class'] ?>"
                                                            class="form-control text-center" value="0" style="width: 50px;"
                                                            readonly>
                                                        <button type="button" class="btn btn-outline-success btn-sm ms-2"
                                                            onclick="updateQuantity('<?= $ticket['class'] ?>', 1, <?= $stock_available ?>)">
                                                            <i class="bi bi-plus-lg"></i>
                                                        </button>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="alert alert-warning p-2 text-center">
                                                        <small><i class="bi bi-hourglass-top"></i> Penjualan Dimulai: <br>
                                                            <strong><?= date('l, d F Y H:i', strtotime($preorder_time)) ?>
                                                                WIB</strong>
                                                        </small>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                <?php endforeach; ?>

                                <div class="col-12 text-center mt-3">
                                    <div class="d-flex justify-content-between m-3">
                                        <h5>Total Harga:</h5>
                                        <h5><span id="total_price">Rp 0</span></h5>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-cart-plus"></i> Pesan Sekarang
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="event-details">
                    <p><strong><?= $event['event_title'] ?></strong></p>
                    <p><i class="bi bi-calendar3"></i> <?= date('d M Y', strtotime($event['event_date'])) ?></p>
                    <p><i class="bi bi-clock-fill"></i> <?= date('H:i:s', strtotime($event['event_date'])) ?></p>
                    <p><i class="bi bi-geo-alt-fill"></i> <?= esc($event['location']) ?></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateQuantity(ticketClass, change, maxStock) {
            let quantityInput = document.getElementById('quantity_' + ticketClass);
            let currentValue = parseInt(quantityInput.value);
            let newValue = currentValue + change;

            if (newValue >= 0 && newValue <= maxStock) {
                quantityInput.value = newValue;
            }
        }

        function updateQuantity(ticketClass, change, maxStock) {
            let quantityInput = document.getElementById('quantity_' + ticketClass);
            let currentValue = parseInt(quantityInput.value);
            let newValue = currentValue + change;

            if (newValue >= 0 && newValue <= maxStock) {
                quantityInput.value = newValue;
            }

            calculateTotalPrice();
        }

        function calculateTotalPrice() {
            let totalPrice = 0;

            <?php foreach ($ticket_classes as $ticket): ?>
                let price_<?= $ticket['class'] ?> = <?= $event['regular_price'] + $ticket['price'] ?>;
                let quantity_<?= $ticket['class'] ?> = parseInt(document.getElementById('quantity_<?= $ticket['class'] ?>').value);
                totalPrice += price_<?= $ticket['class'] ?> * quantity_<?= $ticket['class'] ?>;
            <?php endforeach; ?>

            document.getElementById('total_price').innerText = 'Rp ' + totalPrice.toLocaleString('id-ID');
        }
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>