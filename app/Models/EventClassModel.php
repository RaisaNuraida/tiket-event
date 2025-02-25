<?php

namespace App\Models;

use CodeIgniter\Model;

class EventClassModel extends Model
{
    protected $table          = 'events_class';
    protected $primaryKey     = 'id';
    protected $allowedFields  = [
        'class', 'price', 'description'
    ];
}