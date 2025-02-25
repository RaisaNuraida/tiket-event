<?php

namespace App\Models;

use CodeIgniter\Model;

class EventModel extends Model
{
    protected $table = 'events';
    protected $primaryKey = 'event_id';
    protected $allowedFields = [
        'event_image',
        'event_title',
        'location',
        'event_description',
        'event_preorder',
        'event_date',
        'event_category',
        'regular_price',
        'tickets_regular',
        'tickets_vip',
        'tickets_vvip',
        'status',
        'event_status',
        'archived',
        'created_at',
        'updated_at'
    ];

    public function getTotalTicketsByEvent($eventId)
    {
        return $this->select('event_id, (tickets_regular + tickets_vip + tickets_vvip) as total_tickets')
            ->where('event_id', $eventId)
            ->first();
    }

    public function updateEventStatus()
    {
        $currentTime = date('Y-m-d H:i:s');

        $this->where('event_preorder >', $currentTime)
            ->set(['event_status' => 'upcoming'])
            ->update();

        $this->where('event_preorder <=', $currentTime)
            ->where('event_date >', $currentTime)
            ->set(['event_status' => 'ongoing'])
            ->update();

        $this->where('event_date <=', $currentTime)
            ->set(['event_status' => 'completed'])
            ->set(['status' => 'inactive'])
            ->update();
    }
    
    public function getLatestEvents($limit = 5)
    {
        return $this->orderBy('event_date', 'DESC')
            ->where('archived', 0) 
            ->limit($limit)
            ->find();
    }

}