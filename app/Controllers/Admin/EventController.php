<?php

namespace App\Controllers\Admin;

use App\Models\GroupModel;
use App\Models\UserModel;
use App\Controllers\BaseController;

class EventController extends BaseController
{
    public function admin_events(): string
    {
        return view('admin/kelola_event');
    }
}