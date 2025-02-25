<?php

namespace App\Controllers\Owner;

use App\Controllers\BaseController;
use App\Models\GroupModel;
use App\Models\UserModel;
use App\Models\ActivityModel;


class UsersController extends BaseController
{
    protected $activityModel;

    public function __construct()
    {
        $this->activityModel = new ActivityModel();
    }

    /**
     * Summary of index
     * @return string
     */
    public function index()
    {

        $GroupModel = new GroupModel();
        $UserModel = new UserModel();

        $perPage = 10;
        $currentPage = $this->request->getVar('page') ? $this->request->getVar('page') : 1;

        $data['users'] = $UserModel->orderBy('username', 'ASC')->paginate($perPage, 'default', $currentPage);
        $data['pager'] = $UserModel->pager;
        $data['roles'] = $GroupModel->findAll();
        $data['status'] = [
            ['status' => 'active'],
            ['status' => 'inactive'],
        ];

        return view('owner/users', $data);
    }

    /**
     * Summary of filter_users
     * @return string
     */
    public function filter_users()
    {
        $searchUser = $this->request->getGet('searchUser');
        $role = $this->request->getGet('searchRole');
        $status = $this->request->getGet('status');

        $UserModel = new UserModel();
        $GroupModel = new GroupModel();

        if (!empty($searchUser)) {
            $UserModel->like('username', $searchUser);
        }

        if (!empty($role)) {
            $UserModel->where('role', $role);
        }

        if (!empty($status)) {
            $UserModel->where('status', $status);
        }

        $UserModel->orderBy('username', 'ASC');

        $perPage = 10;
        $currentPage = $this->request->getVar('page') ? $this->request->getVar('page') : 1;

        $data['users'] = $UserModel->paginate($perPage, 'default', $currentPage);

        $data['roles'] = $GroupModel->findAll();

        $data['status'] = [
            ['status' => 'active'],
            ['status' => 'inactive'],
        ];

        $data['pager'] = $UserModel->pager;

        return view('owner/users', $data);
    }
}