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
    <link rel="stylesheet" href="<?= base_url('assets/css/kasir/style.css') ?>">
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
    <div class="container mt-4">
        <div class="row">
            <!-- Event List -->
            <section class="col-md-12">
                <div class="row">
                    <?php foreach ($events as $event): ?>
                        <div class="col-md-3 mb-4">
                            <div class="card event-card">
                                <a href="<?= base_url('kasir/detail/' . $event['event_id']) ?>">
                                    <img src="<?= base_url('uploads/' . $event['event_image']) ?>" class="card-img-top"
                                        alt="<?= esc($event['event_title']) ?>">
                                </a>
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <a href="<?= base_url('kasir/detail/' . $event['event_id']) ?>">
                                            <?= esc($event['event_title']) ?>
                                        </a>
                                    </h5>
                                    <p class="event-meta"><i class="bi bi-calendar3"></i>
                                        <?= date('d M Y', strtotime($event['event_date'])) ?></p>
                                    <p class="event-meta"><i class="bi bi-geo-alt-fill"></i> <?= esc($event['location']) ?>
                                    </p>
                                    <p class="price"><strong>Rp
                                            <?= number_format($event['regular_price'], 0, ',', '.') ?></strong></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <!-- Pagination -->
                    <div class="pagination-container mb-5">
                        <ul class="pagination justify-content-center">
                            <?= $pager->links(); ?>
                        </ul>
                    </div>

                </div>
            </section>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>