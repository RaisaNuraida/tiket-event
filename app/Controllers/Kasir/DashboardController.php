<?php

namespace App\Controllers\Kasir;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function kasir_index(): string
    {
        return view('kasir/index');
    }
}