<?php

namespace App\Controllers\Admin;
use App\Controllers\BaseController;

use App\Models\CategoryModel;
use App\Models\EventModel;
use App\Models\EventClassModel;
use App\Models\ActivityModel;
use App\Models\UserModel;

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

        return view('admin/kelola_event', $data);
    }

    /**
     * Summary of add_events
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function add_events()
    {
        $EventModel = new EventModel();

        $image = $this->request->getFile('event_image');
        $title = $this->request->getPost('event_title');
        $location = $this->request->getPost('event_lokasi');
        $description = $this->request->getPost('event_description');
        $preorder = $this->request->getPost('preorder_date');
        $date = $this->request->getPost('event_date');
        $category = $this->request->getPost('event_category');
        $regularPrice = $this->request->getPost('regular_price');
        $totalTicketsRegular = $this->request->getPost('tickets_regular');
        $totalTicketsVIP = $this->request->getPost('tickets_vip');
        $totalTicketsVVIP = $this->request->getPost('tickets_vvip');
        $status = 'active';

        if (!$image->isValid() || !$title || !$location || !$description || !$preorder || !$date || !$category || !$regularPrice || !$totalTicketsRegular || !$totalTicketsVIP || !$totalTicketsVVIP) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Semua field harus diisi.')
                ->with('modal', 'addEventModal');
        }

        $newName = $image->getRandomName();
        $image->move(FCPATH . 'uploads', $newName);

        $EventModel->save([
            'event_image' => $newName,
            'event_title' => $title,
            'location' => $location,
            'event_description' => $description,
            'event_preorder' => $preorder,
            'event_date' => $date,
            'event_category' => $category,
            'regular_price' => $regularPrice,
            'tickets_regular' => $totalTicketsRegular,
            'tickets_vip' => $totalTicketsVIP,
            'tickets_vvip' => $totalTicketsVVIP,
            'status' => $status,
        ]);

        $this->activityModel->logActivity(session()->get('user_id'), session()->get('username'), session()->get('role'), "Menambahkan event <strong>$title</strong>");

        return redirect()->to('admin/events')->with('success', 'Event berhasil ditambahkan');
    }

    /**
     * Summary of edit_events
     * @param mixed $eventId
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function edit_events($eventId)
    {
        if ($this->request->isAJAX()) {
            $EventModel = new EventModel();
            $CategoryModel = new CategoryModel();

            if (!$eventId) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Event ID tidak ditemukan.',
                ]);
            }

            $event = $EventModel->find($eventId);
            if (!$event) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Event tidak ditemukan.',
                ]);
            }

            $image = $this->request->getFile('event_image');
            $title = $this->request->getPost('event_title');
            $location = $this->request->getPost('event_location');
            $description = $this->request->getPost('event_description');
            $preorder = $this->request->getPost('preorder_date');
            $date = $this->request->getPost('event_date');
            $category = $this->request->getPost('event_category');
            $regularPrice = $this->request->getPost('regular_price');
            $ticketsRegular = $this->request->getPost('tickets_regular');
            $ticketsVip = $this->request->getPost('tickets_vip');
            $ticketsVvip = $this->request->getPost('tickets_vvip');

            $categoryExists = $CategoryModel->where('category', $category)->first();
            if (!$categoryExists) {
                return $this->response->setJSON([
                    'status' => false,
                    'message' => 'Kategori tidak valid.',
                ]);
            }

            $updateData = [
                'event_title' => $title,
                'location' => $location,
                'event_description' => $description,
                'event_preorder' => $preorder,
                'event_date' => $date,
                'event_category' => $category,
                'regular_price' => $regularPrice,
                'tickets_regular' => $ticketsRegular,
                'tickets_vip' => $ticketsVip,
                'tickets_vvip' => $ticketsVvip,
            ];

            if ($image && $image->isValid() && !$image->hasMoved()) {
                $newName = $image->getRandomName();
                $image->move(FCPATH . 'uploads', $newName);

                if ($event['event_image']) {
                    $oldImage = FCPATH . 'uploads/' . $event['event_image'];
                    if (file_exists($oldImage)) {
                        unlink($oldImage);
                    }
                }

                $updateData['event_image'] = $newName;
            }

            $EventModel->update($eventId, $updateData);

            $this->activityModel->logActivity(session()->get('user_id'), session()->get('username'), session()->get('role'), "Mengedit event <strong>$title</strong>");

            session()->setFlashdata('success', 'Event berhasil diperbarui.');
            return $this->response->setJSON([
                'status' => true,
                'redirect' => base_url('admin/events'),
            ]);

        }

        return $this->response->setJSON([
            'status' => false,
            'message' => 'Permintaan tidak valid.',
        ]);
    }

    /**
     * Summary of update_status
     * @param mixed $eventId
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function update_status($eventId)
    {
        $status = $this->request->getPost('status');

        if (!in_array($status, ['active', 'inactive'])) {
            return redirect()->back()->with('error', 'Status tidak valid.');
        }

        $EventModel = new EventModel();
        $EventStatus = $EventModel->find($eventId);

        if (!$EventStatus) {
            return redirect()->back()->with('error', 'Event tidak ditemukan.');
        }

        $EventTitle = $EventStatus['event_title'];

        $EventModel->update($eventId, ['status' => $status]);

        $this->activityModel->logActivity(session()->get('user_id'), session()->get('username'), session()->get('role'), "Mengubah status pengguna <strong>" . $EventTitle . "</strong> menjadi <strong>$status</strong>");

        return redirect()->back()->with('success', 'Status event <strong>' . ucfirst($EventTitle) . '</strong> berhasil diperbarui menjadi <strong>' . ucfirst($status) . '</strong>.');
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
            $message = "Event <strong>{$event['event_title']}</strong> $statusText.";

            $this->activityModel->logActivity(session()->get('user_id'), session()->get('username'), session()->get('role'), "Event <strong>{$event['event_title']}</strong> $statusText");

            return redirect()->back()->with('success', $message);
        }

        return redirect()->back()->with('error', 'Gagal memperbarui status arsip.');
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

        return view('admin/kelola_event', $data);
    }

    /**
     * Summary of add_category
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function add_category()
    {
        $categoryModel = new CategoryModel();

        $name = trim($this->request->getPost('category'));
        $description = trim($this->request->getPost('description'));

        if (empty($name) || empty($description)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Nama kategori dan deskripsi tidak boleh kosong.')
                ->with('modal', 'addCategoryForm');
        }

        if (strlen($name) < 3) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Nama kategori harus memiliki minimal 3 karakter.')
                ->with('modal', 'addCategoryForm');
        }

        $existingCategory = $categoryModel->where('category', $name)->first();
        if ($existingCategory) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Nama kategori sudah digunakan. Harap gunakan nama lain.')
                ->with('modal', 'addCategoryForm');
        }

        $categoryModel->insert([
            'category' => $name,
            'description' => $description,
        ]);

        $this->activityModel->logActivity(session()->get('user_id'), session()->get('username'), session()->get('role'), "Menambahkan kategori <strong>$name</strong>");

        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan');
    }

    /**
     * Summary of delete_category
     * @param mixed $id
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function delete_category($id)
    {
        $categoryModel = new CategoryModel();
        $category = $categoryModel->find($id);

        if ($category) {
            $name = $category['category'];
            $categoryModel->delete($id);
            $this->activityModel->logActivity(session()->get('user_id'), session()->get('username'), session()->get('role'), "Menghapus kategori <strong>$name</strong>");
            return redirect()->to('/admin/events')->with('success', 'Kategori berhasil dihapus')->with('showDeleteModal', true);
        } else {
            return redirect()->to('/admin/events')->with('error', 'Kategori tidak ditemukan');
        }
    }

    /**
     * Summary of add_class
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function add_class()
    {
        $EventClassModel = new EventClassModel();

        $class = $this->request->getPost('class');
        $price = $this->request->getPost('price');
        $description = $this->request->getPost('description');

        if (!$class || !$price || !$description) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Semua field harus diisi.')
                ->with('modal', 'addClassForm');
        }

        $existingClass = $EventClassModel->where('class', $class)->first();
        if ($existingClass) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Nama jenis tiket sudah ada. Harap gunakan yang berbeda.')
                ->with('modal', 'addClassForm');
        }

        $existingPrice = $EventClassModel->where('price', $price)->first();
        if ($existingPrice) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Harga jenis tiket sudah ada. Harap gunakan harga yang berbeda.')
                ->with('modal', 'addClassForm');
        }

        $EventClassModel->insert([
            'class' => $class,
            'price' => $price,
            'description' => $description,
        ]);

        $this->activityModel->logActivity(session()->get('user_id'), session()->get('username'), session()->get('role'), "Menambahkan jenis tiket <strong>$class</strong>");

        return redirect()->back()->with('success', 'Kelas berhasil ditambahkan');
    }

    /**
     * Summary of delete_class
     * @param mixed $id
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function delete_class($id)
    {
        $EventClassModel = new EventClassModel();
        $class = $EventClassModel->find($id);

        if ($class) {
            $name = $class['class'];
            $this->activityModel->logActivity(session()->get('user_id'), session()->get('username'), session()->get('role'), "Menghapus jenis tiket <strong>$name</strong>");
            $EventClassModel->delete($id);
            return redirect()->to('/admin/events')->with('success', 'Kelas berhasil dihapus')->with('ShowDeleteModal', true);
        } else {
            return redirect()->to('/admin/events')->with('error', 'Kelas tidak ditemukan');
        }
    }
}