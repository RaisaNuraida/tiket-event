<?php

namespace App\Controllers\Owner;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\EventModel;
use App\Models\OrderModel;
use App\Models\ActivityModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();
        $eventModel = new EventModel();
        $orderModel = new OrderModel();
        $activityModel = new ActivityModel();

        $userId = session()->get('user_id');
        $user = $userModel->find($userId);

        $lastLogin = $activityModel
            ->where('user_id', $userId)
            ->where('activity', 'Berhasil login')
            ->orderBy('created_at', 'DESC')
            ->first();

        $lastLoginDate = $lastLogin ? date('d-m-Y', strtotime($lastLogin['created_at'])) : 'Belum ada data';

        $totalEvents = $eventModel->countAllResults();
        $totalUsers = $userModel->countAllResults();
        $totalTicketsSold = $orderModel->selectSum('quantity')->first()['quantity'] ?? 0;
        $totalRevenue = $orderModel->selectSum('total_price')->first()['total_price'] ?? 0;

        $topEvents = $eventModel
            ->select('
                events.event_id, 
                events.event_title, 
                events.event_date, 
                events.event_status, 
                (events.tickets_regular + events.tickets_vip + events.tickets_vvip) as total_tickets,
                COALESCE(SUM(orders.quantity), 0) as total_sold
            ')
            ->join('orders', 'orders.event_id = events.event_id', 'left')
            ->groupBy('events.event_id')
            ->orderBy('total_sold', 'DESC')
            ->limit(5)
            ->find();

        $data = [
            'username' => $user['username'] ?? 'Pengguna',
            'lastLoginDate' => $lastLoginDate,
            'totalEvents' => $totalEvents,
            'totalUsers' => $totalUsers,
            'totalTicketsSold' => $totalTicketsSold,
            'totalRevenue' => $totalRevenue,
            'topEvents' => $topEvents
        ];

        return view('owner/index', $data);
    }
}