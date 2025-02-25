<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\UserModel; 

class ActivityModel extends Model
{
    protected $table          = 'activity_logs';
    protected $primaryKey     = 'id';
    protected $allowedFields  = [
        'user_id', 'username', 'role', 'activity', 'ip_address', 'user_agent', 'created_at'
    ];
    
    public function logActivity($userId, $username, $role, $activity)
    {
        $data = [
            'user_id'    => $userId,
            'username'   => $username,
            'role'       => $role,
            'activity'   => $activity,
            'ip_address' => $this->getIPAddress(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown'
        ];
        return $this->insert($data);
    }

    private function getIPAddress()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }
}
