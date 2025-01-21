<?php

namespace App\Controllers;

class OwnerController extends BaseController
{
    public function owner_index(): string
    {
        return view('owner/index');
    }
   }