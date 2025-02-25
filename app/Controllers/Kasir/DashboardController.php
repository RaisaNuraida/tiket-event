<?php

namespace App\Controllers\Kasir;

use App\Controllers\BaseController;
use App\Models\EventModel;
use App\Models\UserModel;
use App\Models\ActivityModel;

class DashboardController extends BaseController
{

    protected $activityModel;

    public function __construct()
    {
        $this->activityModel = new ActivityModel();
    }
    public function kasir_index()
    {
        $session = session();
        $userModel = new UserModel();

        $userId = $session->get('user_id');
        $user = $userId ? $userModel->find($userId) : null;

        $eventModel = new EventModel();
        $search = $this->request->getGet('search'); 

        $perPage = 10; 
        $currentPage = $this->request->getVar('page') ?? 1;

        $query = $eventModel->where('status', 'active')->where('archived', 0);

        if (!empty($search)) {
            $query->like('event_title', $search);
        }

        $events = $query->paginate($perPage, 'default', $currentPage);

        $data = [
            'events' => $events,
            'pager' => $eventModel->pager,
            'search' => $search,
            'user' => $user,
        ];

        return view('kasir/index', $data);
    }
}
