<?php

namespace App\Controllers\Owner;
use App\Controllers\BaseController;

use App\Models\CategoryModel;
use App\Models\EventModel;
use App\Models\EventClassModel;
use App\Models\ActivityModel;

class EventController extends BaseController
{
    protected $activityModel;

    public function __construct()
    {
        $this->activityModel = new ActivityModel();
    }

    /**
     * Summary of index
     * @return string
     */
    public function index()
    {

        $CategoryModel = new CategoryModel();
        $EventClassModel = new EventClassModel();
        $EventModel = new EventModel();

        $EventModel->updateEventStatus();

        $perPage = 10;
        $currentPage = $this->request->getVar('page') ? $this->request->getVar('page') : 1;

        $events = $EventModel->where('archived', 0)
            ->orderBy('event_title', 'ASC')
            ->paginate($perPage, 'default', $currentPage);

        foreach ($events as &$event) {
            $event['total_tickets'] = $event['tickets_regular'] + $event['tickets_vip'] + $event['tickets_vvip'];
        }

        $data['events'] = $events;
        $data['pager'] = $EventModel->pager;
        $data['eventclass'] = $EventClassModel->findAll();
        $data['categorys'] = $CategoryModel->findAll();
        $data['eventStatus'] = [
            ['event_status' => 'upcoming'],
            ['event_status' => 'ongoing'],
            ['event_status' => 'completed'],
        ];
        $data['status'] = [
            ['status' => 'active'],
            ['status' => 'inactive'],
        ];

        return view('owner/events', $data);
    }

    /**
     * Summary of filter_events
     * @return string
     */
    public function filter_events()
    {
        $searchEvent = $this->request->getGet('searchEvent');
        $category = $this->request->getGet('filterCategory');
        $StatusEvent = $this->request->getGet('filterStatusEvent');
        $status = $this->request->getGet('filterStatus');

        $EventModel = new EventModel();
        $EventClassModel = new EventClassModel();

        if (!empty($searchEvent)) {
            $EventModel->like('event_title', $searchEvent);
        }

        if (!empty($category)) {
            $EventModel->like('event_category', $category);
        }

        if (!empty($StatusEvent)) {
            $EventModel->like('event_status', $StatusEvent);
        }

        if (!empty($status)) {
            $EventModel->like('status', $status);
        }

        $EventModel->orderBy('event_title', 'ASC');

        $perPage = 10;
        $currentPage = $this->request->getVar('page') ? $this->request->getVar('page') : 1;

        $data['events'] = $EventModel->paginate($perPage, 'default', $currentPage);

        foreach ($data['events'] as &$event) {
            $event['total_tickets'] = $event['tickets_regular'] + $event['tickets_vip'] + $event['tickets_vvip'];
        }

        $data['pager'] = $EventModel->pager;
        $data['categorys'] = (new CategoryModel())->findAll();
        $data['eventclass'] = $EventClassModel->findAll();
        $data['eventStatus'] = [
            ['event_status' => 'upcoming'],
            ['event_status' => 'ongoing'],
            ['event_status' => 'completed'],
        ];
        $data['status'] = [
            ['status' => 'active'],
            ['status' => 'inactive'],
        ];

        return view('owner/events', $data);
    }
}