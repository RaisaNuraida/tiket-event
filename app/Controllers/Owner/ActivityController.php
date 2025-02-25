<?php

namespace App\Controllers\Owner;

use App\Models\ActivityModel;
use App\Controllers\BaseController;

class ActivityController extends BaseController
{
    public function index()
    {
        $ActivityModel = new ActivityModel();

        $searchUser = $this->request->getGet('searchUser');
        $searchRole = $this->request->getGet('searchRole');

        $query = $ActivityModel->orderBy('created_at', 'DESC');

        if (!empty($searchUser)) {
            $query->like('username', $searchUser);
        }

        if (!empty($searchRole)) {
            $query->where('role', $searchRole);
        }

        $perPage = 10;
        $data['activities'] = $query->paginate($perPage);
        $data['pager'] = $ActivityModel->pager;
        $data['roles'] = $ActivityModel->select('role')->distinct()->findAll();
        $data['searchUser'] = $searchUser;
        $data['searchRole'] = $searchRole;

        return view('owner/activity', $data);
    }
}