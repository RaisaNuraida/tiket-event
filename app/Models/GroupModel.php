<?php

namespace App\Models;

use CodeIgniter\Model;

class GroupModel extends Model
{
    protected $table          = 'auth_groups';
    protected $primaryKey     = 'id';
    protected $allowedFields  = [
        'name', 'description'
    ];
}