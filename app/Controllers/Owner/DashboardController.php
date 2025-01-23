<?php

namespace App\Controllers\Owner;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function owner_index(): string
    {
        return view('owner/index');
    }
   }