<?php

namespace App\Controllers\Kasir;

use App\Controllers\BaseController;
use App\Models\EventModel;
use App\Models\EventClassModel;
use App\Models\UserModel;

class DetailController extends BaseController
{
    public function index($event_id)
    {
        $session = session();
        $eventModel = new EventModel();
        $classModel = new EventClassModel();
        $userModel = new UserModel();

        $userId = $session->get('user_id');
        $user = $userId ? $userModel->find($userId) : null;

        
        $event = $eventModel->find($event_id);
        if (!$event) {
            return redirect()->to(base_url('kasir'))->with('error', 'Event tidak ditemukan.');
        }

        $ticket_classes = $classModel->findAll();

        $data = [
            'event' => $event,
            'ticket_classes' => $ticket_classes,
            'user' => $user,
        ];

        return view('kasir/detail', $data);
    }
}
