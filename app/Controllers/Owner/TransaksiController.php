<?php

namespace App\Controllers\Owner;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\EventModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class TransaksiController extends BaseController
{
    public function index()
    {
        $orderModel = new OrderModel();

        $statusFilter = $this->request->getGet('status');
        $sortOrder = $this->request->getGet('sort') ?? 'desc';
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');

        $orderQuery = $orderModel
            ->select('orders.*, events.event_title')
            ->join('events', 'events.event_id = orders.event_id', 'left');

        if (!empty($statusFilter)) {
            $orderQuery->where('orders.status', $statusFilter);
        }

        if (!empty($startDate) && !empty($endDate)) {
            $orderQuery->where('orders.created_at >=', $startDate)
                ->where('orders.created_at <=', $endDate . ' 23:59:59');
        }

        $orderQuery->orderBy('orders.created_at', $sortOrder);

        $orders = $orderQuery->findAll();

        $data = [
            'orders' => $orders,
            'statusFilter' => $statusFilter,
            'sortOrder' => $sortOrder,
            'startDate' => $startDate,
            'endDate' => $endDate
        ];

        return view('owner/transaksi', $data);
    }

    public function downloadExcel()
    {
        $orderModel = new OrderModel();

        $statusFilter = $this->request->getGet('status');
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');

        $orderQuery = $orderModel
            ->select('orders.*, events.event_title')
            ->join('events', 'events.event_id = orders.event_id', 'left');

        if (!empty($statusFilter)) {
            $orderQuery->where('orders.status', $statusFilter);
        }

        if (!empty($startDate) && !empty($endDate)) {
            $orderQuery->where('orders.created_at >=', $startDate)
                ->where('orders.created_at <=', $endDate . ' 23:59:59');
        }

        $orders = $orderQuery->findAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No')
            ->setCellValue('B1', 'Event')
            ->setCellValue('C1', 'Nama')
            ->setCellValue('D1', 'Email')
            ->setCellValue('E1', 'Jumlah Tiket')
            ->setCellValue('F1', 'Total Harga')
            ->setCellValue('G1', 'Status')
            ->setCellValue('H1', 'Tanggal');

        $row = 2;
        foreach ($orders as $index => $order) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $order['event_title']);
            $sheet->setCellValue('C' . $row, $order['display_name']);
            $sheet->setCellValue('D' . $row, $order['gmail']);
            $sheet->setCellValue('E' . $row, $order['quantity']);
            $sheet->setCellValue('F' . $row, $order['total_price']);
            $sheet->setCellValue('G' . $row, ucfirst($order['status']));
            $sheet->setCellValue('H' . $row, date('d M Y H:i', strtotime($order['created_at'])));
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'data_transaksi_' . date('Ymd_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}