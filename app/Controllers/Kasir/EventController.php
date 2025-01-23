<?php

namespace App\Controllers\Kasir;

use App\Controllers\BaseController;

class EventController extends BaseController
{
    public function kasir_events(): string
    {
        return view('kasir/kelola_event');
    }
}