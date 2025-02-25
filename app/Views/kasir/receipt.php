<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembelian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
        }

        .receipt-container {
            width: 350px;
            padding: 15px;
            border: 2px solid #000;
            background: #fff;
        }

        .receipt-title {
            text-align: center;
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 5px;
        }

        .receipt-info {
            font-size: 14px;
            margin-bottom: 10px;
        }

        .receipt-table {
            width: 100%;
            font-size: 14px;
        }

        .receipt-table th,
        .receipt-table td {
            padding: 3px;
            text-align: left;
        }

        .total-section {
            font-size: 14px;
            margin-top: 10px;
        }

        .text-right {
            text-align: right;
        }

        /* Sembunyikan tombol saat mencetak */
        @media print {
            .print-buttons {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="container d-flex justify-content-center mt-3">
        <div class="receipt-container">
            <div class="receipt-title">Lontar Event</div>
            <p class="text-center">JL. Arief Rahman Hakim No 11, Cigadung, Subang <br> Telp: 0896-0434-9692</p>

            <hr>

            <p class="text-center"><span><?= esc(session('transaction_id')) ?></span></p>

            <hr>
            <p><span>Nama:</span> <span class="text-right"><?= esc(session('display_name')) ?></span></p>
            <p><span>Tanggal Pembelian:</span> <span
                    class="text-right"><?= date('d-m-Y H:i', strtotime(session('created_at'))) ?></span></p>

            <hr>

            <table class="receipt-table">
                <tbody>
                    <?php foreach (session('orders') as $order): ?>
                        <tr>
                            <td colspan="3"><strong><?= esc($order['event_title']) ?> - <?= esc($order['class']) ?></strong>
                            </td>
                        </tr>
                        <tr>
                            <td><?= esc($order['quantity']) ?> x Rp. <?= number_format($order['price'], 0, ',', '.') ?></td>
                            <td class="text-right">Rp. <?= number_format($order['total_price'], 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <hr>

            <div class="total-section">
                <p><span>Subtotal:</span> <span class="text-right">Rp.
                        <?= number_format(session('payment'), 0, ',', '.') ?></span></p>
                <p><span>Bayar:</span> <span class="text-right">Rp.
                        <?= number_format(session('payment'), 0, ',', '.') ?></span></p>
                <p><span>Kembalian:</span> <span class="text-right">Rp.
                        <?= number_format(session('change'), 0, ',', '.') ?></span></p>
            </div>

            <hr>

            <p class="text-center">Terima kasih telah membeli tiket di Lontar Event!</p>
        </div>
    </div>

    <!-- Tombol di luar struk -->
    <div class="text-center mt-3 print-buttons">
        <button onclick="printReceipt()" class="btn btn-primary">Cetak Struk</button>
        <a href="<?= base_url('kasir/orders') ?>" class="btn btn-secondary">Selesai</a>
    </div>

    <script>
        function printReceipt() {
            window.print();
            setTimeout(() => {
                printTickets();
            }, 1000);
        }

        function printTickets() {
            const tickets = <?= json_encode(session('orders')) ?>;
            let index = 0;

            function printNextTicket() {
                if (index < tickets.length) {
                    const ticket = tickets[index];
                    const url = "<?= base_url('kasir/print_ticket/') ?>" + ticket.ticket_code;
                    const newWindow = window.open(url, "_blank");

                    newWindow.onload = function () {
                        newWindow.print();
                        setTimeout(() => {
                            newWindow.close();
                            index++;
                            printNextTicket();
                        }, 2000);
                    };
                } else {
                    window.location.href = "<?= base_url('kasir/orders') ?>";
                }
            }

            printNextTicket();
        }

        window.onload = function () {
            printReceipt();
        };
    </script>
</body>

</html>