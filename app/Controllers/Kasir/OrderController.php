<?php

namespace App\Controllers\Kasir;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\EventClassModel;
use App\Models\EventModel;
use App\Models\UserModel;
use App\Models\ActivityModel;
use CodeIgniter\Email\Email;

class OrderController extends BaseController
{
    protected $activityModel;

    public function __construct()
    {
        $this->activityModel = new ActivityModel();
    }

    public function orderTicket()
    {
        $session = session();

        $event_id = $this->request->getPost('event_id');
        $quantities = $this->request->getPost('quantities');
        $prices = $this->request->getPost('prices');

        if (!$event_id) {
            return redirect()->to(base_url('kasir/index'))->with('error', 'Event tidak ditemukan.');
        }

        $order_data = [];
        foreach ($quantities as $class => $quantity) {
            if ($quantity > 0) {
                $order_data[] = [
                    'event_id' => $event_id,
                    'class' => $class,
                    'price' => $prices[$class],
                    'quantity' => $quantity
                ];
            }
        }

        $session->set('order_data', $order_data);
        return redirect()->to(base_url('kasir/checkout'));
    }

    public function checkout()
    {
        $session = session();
        $order_data = $session->get('order_data');

        if (!$order_data || !isset($order_data[0]['event_id'])) {
            return redirect()->to(base_url('kasir/index'))->with('error', 'Silakan pilih tiket terlebih dahulu.');
        }

        $eventModel = new EventModel();
        $event_id = $order_data[0]['event_id'];
        $event = $eventModel->find($event_id);

        if (!$event) {
            return redirect()->to(base_url('kasir/index'))->with('error', 'Event tidak ditemukan.');
        }

        return view('kasir/checkout', ['order_data' => $order_data, 'event' => $event]);
    }

    public function submitOrder()
    {
        $session = session();
        $orderModel = new OrderModel();
        $eventModel = new EventModel();
        $activityModel = new ActivityModel();
        $email = \Config\Services::email();
        $order_data = $session->get('order_data');

        if (!$order_data) {
            return redirect()->to(base_url('kasir/index'))->with('error', 'Terjadi kesalahan, silakan ulangi pemesanan.');
        }

        $display_name = $this->request->getPost('display_name');
        $gmail = $this->request->getPost('gmail');
        $payment = $this->request->getPost('payment');
        $user_id = $session->get('user_id') ?? null;
        $transaction_id = 'TRX' . time() . strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 4));
        $created_at = date('Y-m-d H:i:s');

        $orders = [];
        $ticket_details = '';

        foreach ($order_data as $order) {
            $event = $eventModel->find($order['event_id']);
            if (!$event) {
                return redirect()->to(base_url('kasir/index'))->with('error', 'Event tidak ditemukan.');
            }

            $change = $payment - ($order['price'] * $order['quantity']);

            if ($payment < $order['price'] ) {
                return redirect()->to(base_url('kasir/checkout'))->with('error', 'Uang Tidak boleh kurang');
            }

            for ($i = 0; $i < $order['quantity']; $i++) {
                $ticket_code = strtoupper($order['class']) . strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6));

                $order_id = $orderModel->insert([
                    'transaction_id' => $transaction_id,
                    'event_id' => $order['event_id'],
                    'display_name' => $display_name,
                    'gmail' => $gmail,
                    'ticket_code' => $ticket_code,
                    'class' => $order['class'],
                    'price' => $order['price'],
                    'payment' => $payment,
                    'change' => $change,
                    'quantity' => 1,
                    'total_price' => $order['price'],
                    'user_id' => $user_id,
                    'status' => 'paid',
                    'created_at' => $created_at
                ], true);

                $orders[] = [
                    'event_title' => $event['event_title'],
                    'ticket_code' => $ticket_code,
                    'class' => strtoupper($order['class']),
                    'price' => $order['price'],
                    'quantity' => 1,
                    'total_price' => $order['price']
                ];

                $ticket_details .= "<p><strong>Event:</strong> {$event['event_title']}<br>
                                <strong>Kode Tiket:</strong> {$ticket_code}<br>
                                <strong>Kelas:</strong> " . strtoupper($order['class']) . "<br>
                                <strong>Harga:</strong> Rp" . number_format($order['price'], 0, ',', '.') . "<br>
                                </p><hr>";
            }

            $class = strtolower($order['class']);
            $eventModel->update($order['event_id'], [
                'tickets_' . $class => $event['tickets_' . $class] - $order['quantity']
            ]);
        }

        $session->remove('order_data');

        $activityModel->logActivity(
            $session->get('user_id'),
            $session->get('username'),
            'kasir',
            "Melakukan transaksi tiket dengan ID: $transaction_id"
        );

        $session->set('ticket_codes', array_column($orders, 'ticket_code'));

        $email->setTo($gmail);
        $email->setFrom('raisanuraida11@gmail.com', 'Event Ticketing');
        $email->setSubject('Konfirmasi Pembelian Tiket - ' . $transaction_id);
        $email->setMessage("
        <h2>Terima Kasih, {$display_name}!</h2>
        <p>Berikut adalah detail tiket Anda:</p>
        {$ticket_details}
        <p><strong>Total Pembayaran:</strong> Rp" . number_format($payment, 0, ',', '.') . "</p>
        <p><strong>Kembalian:</strong> Rp" . number_format($change, 0, ',', '.') . "</p>
        <p>Harap simpan email ini sebagai bukti pembelian tiket Anda.</p>
    ");

        if ($email->send()) {
            return redirect()->to(base_url('kasir/print_receipt'))
                ->with('orders', $orders)
                ->with('transaction_id', $transaction_id)
                ->with('display_name', $display_name)
                ->with('created_at', $created_at)
                ->with('payment', $payment)
                ->with('change', $change)
                ->with('success', 'Tiket berhasil dikirim ke email Anda!');
        } else {
            return redirect()->to(base_url('kasir/print_receipt'))
                ->with('orders', $orders)
                ->with('transaction_id', $transaction_id)
                ->with('display_name', $display_name)
                ->with('created_at', $created_at)
                ->with('payment', $payment)
                ->with('change', $change)
                ->with('error', 'Tiket berhasil dibuat, tetapi gagal dikirim ke email.');
        }
    }

    public function printReceipt()
    {
        return view('kasir/receipt');
    }

    public function printTicket($ticket_code)
    {
        $session = session();
        $ticket_codes = $session->get('ticket_codes');

        if (!$ticket_codes) {
            return redirect()->to(base_url('kasir/orders'))->with('error', 'Tidak ada tiket untuk dicetak.');
        }

        $orderModel = new OrderModel();
        $activityModel = new ActivityModel();

        $tickets = $orderModel
            ->select('orders.*, events.event_title, events.event_date, events.location, events.event_image')
            ->join('events', 'events.event_id = orders.event_id', 'left')
            ->whereIn('orders.ticket_code', $ticket_codes)
            ->findAll();

        if (!$tickets) {
            return redirect()->to(base_url('kasir/orders'))->with('error', 'Tiket tidak ditemukan.');
        }

        $activityModel->logActivity(
            $session->get('user_id'),
            $session->get('username'),
            'kasir',
            "Mencetak tiket dengan kode: " . implode(', ', $ticket_codes)
        );

        return view('kasir/print_ticket', ['tickets' => $tickets]);
    }

    public function orders()
    {
        $session = session();
        $orderModel = new OrderModel();

        $perPage = 10;
        $currentPage = $this->request->getVar('page') ?? 1;
        $search = $this->request->getGet('search');

        $query = $orderModel->select('orders.transaction_id, 
                                  ANY_VALUE(events.event_title) as event_title, 
                                  SUM(orders.total_price) as total_transaction, 
                                  ANY_VALUE(orders.display_name) as display_name, 
                                  ANY_VALUE(orders.gmail) as gmail, 
                                  ANY_VALUE(orders.ticket_code) as ticket_code, 
                                  ANY_VALUE(orders.class) as class, 
                                  ANY_VALUE(orders.price) as price, 
                                  ANY_VALUE(orders.quantity) as quantity, 
                                  ANY_VALUE(orders.total_price) as total_price, 
                                  ANY_VALUE(orders.status) as status, 
                                  MAX(orders.created_at) as latest_order')
            ->join('events', 'events.event_id = orders.event_id', 'left')
            ->groupBy('orders.transaction_id')
            ->orderBy('latest_order', 'DESC');

        if (!empty($search)) {
            $query->groupStart()
                ->like('orders.display_name', $search)
                ->orLike('orders.gmail', $search)
                ->orLike('orders.ticket_code', $search)
                ->orLike('orders.transaction_id', $search)
                ->groupEnd();
        }

        $orders = $query->paginate($perPage, 'default', $currentPage);

        $data['username'] = $session->get('username') ?? 'Kasir';
        $data['orders'] = $orders;
        $data['pager'] = $orderModel->pager;
        $data['search'] = $search;

        return view('kasir/orders', $data);
    }
}