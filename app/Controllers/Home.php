<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('welcome_message');
    }

    public function admin_index(): string
    {
        return view('admin/index');
    }

    public function admin_events(): string
    {
        return view('admin/kelola_event');
    }
}
