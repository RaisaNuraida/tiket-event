<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Tiket</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            text-align: center;
        }

        .ticket-container {
            width: 350px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            border: 2px solid #333;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            page-break-inside: avoid; /* Mencegah tiket terpotong */
        }

        /* Jika ada lebih dari satu tiket, beri page-break sebelum tiket kedua dan seterusnya */
        .ticket-container:not(:first-child) {
            break-before: page;
        }

        .event-image {
            width: 100%;
            border-radius: 8px;
        }

        .ticket-title {
            font-size: 18px;
            font-weight: bold;
            margin: 10px 0;
        }

        .ticket-info {
            font-size: 14px;
            margin-bottom: 5px;
        }

        .barcode {
            font-size: 18px;
            font-weight: bold;
            background: #000;
            color: #fff;
            padding: 10px;
            border-radius: 5px;
            margin-top: 15px;
            display: inline-block;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .print-buttons {
            margin-top: 20px;
            text-align: center;
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(255, 255, 255, 0.9);
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            page-break-inside: avoid; /* Mencegah tombol pindah ke halaman baru */
        }

        .print-button {
            background: #28a745;
            color: #fff;
            border: none;
            padding: 8px 15px;
            font-size: 14px;
            cursor: pointer;
            border-radius: 5px;
        }

        .print-button:hover {
            background: #218838;
        }

        .btn-secondary {
            background: #6c757d;
            color: #fff;
            border: none;
            padding: 8px 15px;
            font-size: 14px;
            cursor: pointer;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-left: 10px;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        /* Sembunyikan tombol saat mencetak */
        @media print {
            .print-buttons {
                display: none !important;
            }

            .barcode {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                color: #fff !important;
                background-color: #000 !important;
            }

            .ticket-container {
                box-shadow: none;
                border: 1px solid #000;
            }
        }
    </style>
</head>

<body>
    <?php foreach ($tickets as $index => $ticket): ?>
        <div class="ticket-container">
            <?php if (!empty($ticket['event_image'])): ?>
                <img src="<?= base_url('uploads/' . $ticket['event_image']) ?>" alt="Event Image" class="event-image">
            <?php endif; ?>

            <div class="ticket-title"><?= esc($ticket['event_title']) ?></div>
            <p class="ticket-info"><strong>Lokasi:</strong> <?= esc($ticket['location']) ?></p>
            <p class="ticket-info"><strong>Tanggal:</strong> <?= date('d M Y', strtotime($ticket['event_date'])) ?></p>
            <p class="ticket-info"><strong>Kelas:</strong> <?= strtoupper($ticket['class']) ?></p>
            <p class="barcode"><?= esc($ticket['ticket_code']) ?></p>
            <p class="ticket-info">Harap tunjukkan tiket ini saat masuk.</p>
        </div>
    <?php endforeach; ?>

    <!-- Tombol tetap di layar dan tidak ikut tercetak -->
    <div class="print-buttons">
        <button class="print-button" onclick="window.print();">Cetak Semua Tiket</button>
        <a href="<?= base_url('kasir/orders') ?>" class="btn-secondary">Selesai</a>
    </div>

    <script>
        window.onload = function () {
            setTimeout(function () {
                window.print();
            }, 500);
        };
    </script>

</body>

</html>
