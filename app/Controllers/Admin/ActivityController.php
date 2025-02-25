<?php

namespace App\Controllers\Admin;

use App\Models\ActivityModel;
use App\Models\GroupModel;
use App\Models\UserModel;
use App\Controllers\BaseController;
use App\Models\EventModel;

class ActivityController extends BaseController
{
    public function index()
    {
        $activityModel = new ActivityModel();

        $searchUser = $this->request->getGet('searchUser');
        $searchRole = $this->request->getGet('searchRole');

        $perPage = 10;

        $query = $activityModel->orderBy('created_at', 'DESC');

        if (!empty($searchUser)) {
            $query->like('username', $searchUser);
        }

        if (!empty($searchRole)) {
            $query->where('role', $searchRole);
        }

        $data['activities'] = $query->paginate($perPage);
        $data['pager'] = $activityModel->pager;

        $data['roles'] = $activityModel->select('role')->distinct()->findAll();

        $data['searchUser'] = $searchUser;
        $data['searchRole'] = $searchRole;

        return view('admin/activity', $data);
    }
}