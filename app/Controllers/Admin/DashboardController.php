<?php

namespace App\Controllers\Admin;

use App\Models\GroupModel;
use App\Models\UserModel;
use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    /**
     * MASUK PAGE
     */
    public function admin_index(): string
    {
        return view('admin/index');
    }
}