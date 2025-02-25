<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'order_id';
    protected $allowedFields = [
        'event_id',
        'display_name',
        'gmail',
        'transaction_id',
        'ticket_code',
        'class',
        'price',
        'payment',
        'change',
        'quantity',
        'total_price',
        'user_id',
        'status',
        'created_at',
        'updated_at'
    ];
}