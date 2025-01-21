<?php

namespace App\Controllers;

class KasirController extends BaseController
{
    public function kasir_index(): string
    {
        return view('kasir/index');
    }
    public function kasir_events(): string
    {
        return view('kasir/kelola_event');
    }
}