<?php

namespace App\Controllers;

use App\Models\UserModel;

class AdminController extends BaseController
{
    public function admin_index(): string
    {
        return view('admin/index');
    }
    public function admin_events(): string
    {
        return view('admin/kelola_event');
    }
    public function admin_users(): string
    {
        $userModel = new UserModel();
        
        $data['users'] = $userModel->findAll();
        
        return view('admin/users', $data);
    }

}