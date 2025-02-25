<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\EventModel;
use App\Models\ActivityModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();
        $eventModel = new EventModel();
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
        $totalActiveEvents = $eventModel->where(['status' => 'active', 'archived' => 0])->countAllResults();
        $totalUsers = $userModel->countAllResults();
        $totalActiveUsers = $userModel->where('status', 'active')->countAllResults();

        $latestEvents = $eventModel->getLatestEvents(5);

        $data = [
            'username' => $user['username'] ?? 'Pengguna',
            'lastLoginDate' => $lastLoginDate,
            'totalEvents' => $totalEvents,
            'totalActiveEvents' => $totalActiveEvents,
            'totalUsers' => $totalUsers,
            'totalActiveUsers' => $totalActiveUsers,
            'latestEvents' => $latestEvents 
        ];

        return view('admin/index', $data);
    }
}
