<?php

namespace App\Controllers\Admin;
use App\Controllers\BaseController;

use App\Models\GroupModel;
use App\Models\UserModel;
use App\Models\CategoryModel;
use App\Models\EventClassModel;
use App\Models\ActivityModel;
use App\Models\EventModel;

class ArchiveController extends BaseController
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

        $events = $EventModel->where('archived', 1)
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


        return view('admin/archive', $data);
    }

    /**
     * Summary of archive
     * @param mixed $eventId
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function archive($eventId)
    {
        $eventModel = new EventModel();
        $event = $eventModel->find($eventId);

        if (!$event) {
            return redirect()->back()->with('error', 'Event tidak ditemukan.');
        }

        $newStatus = $this->request->getPost('archived');

        if ($newStatus === null) {
            return redirect()->back()->with('error', 'Tidak ada data yang dikirim.');
        }

        if ($eventModel->update($eventId, ['archived' => $newStatus])) {
            $statusText = ($newStatus == 1) ? 'archived' : 'restored';
            $message = "Event <strong>{$event['event_title']}</strong> berhasil $statusText.";

            $this->activityModel->logActivity(session()->get('user_id'), session()->get('username'), session()->get('role'), "Event <strong>{$event['event_title']}</strong> $statusText");

            return redirect()->back()->with('success', $message);
        }

        return redirect()->back()->with('error', 'Gagal memperbarui status arsip.');
    }

    /**
     * Summary of delete
     * @param mixed $eventId
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function delete($eventId)
    {
        $eventModel = new EventModel();
        $event = $eventModel->find($eventId);

        if (!$event) {
            return redirect()->to('admin/archive')->with('error', 'Event tidak ditemukan.');
        }

        if ($eventModel->delete($eventId)) {
            $name = $event['event_title'];
            $this->activityModel->logActivity(session()->get('user_id'), session()->get('username'), session()->get('role'), "Menghapus event <strong>$name</strong>");
            return redirect()->to('admin/archive')->with('success', 'Event berhasil dihapus.');
        } else {
            return redirect()->to('admin/archive')->with('error', 'Gagal menghapus event.');
        }
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

        $EventModel->where('archived', 1);

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

        $data['events'] = $EventModel->where('archived', 1)
            ->orderBy('event_title', 'ASC')
            ->paginate($perPage, 'default', $currentPage);

        foreach ($data['events'] as &$event) {
            $event['total_tickets'] = $event['tickets_regular'] + $event['tickets_vip'] + $event['tickets_vvip'];
        }

        $data['pager'] = $EventModel->pager;
        $data['categorys'] = (new CategoryModel())->findAll();
        $data['eventclass'] = $EventClassModel->findAll(); // Tambahkan ini
        $data['eventStatus'] = [
            ['event_status' => 'upcoming'],
            ['event_status' => 'ongoing'],
            ['event_status' => 'completed'],
        ];
        $data['status'] = [
            ['status' => 'active'],
            ['status' => 'inactive'],
        ];

        return view('admin/archive', $data);
    }
}