<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\GroupModel;
use App\Models\UserModel;
use App\Models\ActivityModel;


class UsersController extends BaseController
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

        $GroupModel = new GroupModel();
        $UserModel = new UserModel();

        $perPage = 10;
        $currentPage = $this->request->getVar('page') ? $this->request->getVar('page') : 1;

        $data['users'] = $UserModel->orderBy('username', 'ASC')->paginate($perPage, 'default', $currentPage);
        $data['pager'] = $UserModel->pager;
        $data['roles'] = $GroupModel->findAll();
        $data['status'] = [
            ['status' => 'active'],
            ['status' => 'inactive'],
        ];

        return view('admin/users', $data);
    }

    /**
     * Summary of add_users
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function add_users()
    {
        $UserModel = new UserModel();
        $GroupModel = new GroupModel();

        $email = $this->request->getPost('email');
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $roleName = $this->request->getPost('role');
        $status = 'active';

        if (empty($email) || empty($username) || empty($password) || empty($roleName)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Semua field harus diisi.')
                ->with('modal', 'addUserModal');
        }

        if ($UserModel->where('username', $username)->first()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Username sudah digunakan, silakan pilih username lain.')
                ->with('modal', 'addUserModal');
        }

        if ($UserModel->where('email', $email)->first()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Email sudah digunakan, silakan pilih email lain.')
                ->with('modal', 'addUserModal');
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $role = $GroupModel->where('name', $roleName)->first();

        if (!$role) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Role tidak valid.')
                ->with('modal', 'addUserModal');
        }

        $UserModel->save([
            'email' => $email,
            'username' => $username,
            'password_hash' => $hashedPassword,
            'role' => $role['id'],
            'status' => $status,
        ]);

        $this->activityModel->logActivity(session()->get('user_id'), session()->get('username'), session()->get('role'), "Menambahkan pengguna <strong>$username</strong>");

        return redirect()->to('admin/users')->with('success', 'Pengguna berhasil ditambahkan');
    }

    /**
     * Summary of edit_users
     * @param mixed $id
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function edit_users($id)
    {
        $UserModel = new UserModel();
        $GroupModel = new GroupModel();

        $validation = $this->validate([
            'username' => 'required|min_length[3]|max_length[255]',
            'email' => 'required|valid_email',
            'role' => 'required',
            'password' => 'permit_empty|min_length[6]',
        ]);

        if (!$validation) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $user = $UserModel->find($id);
        if (!$user) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Pengguna tidak ditemukan.',
            ]);
        }

        $roleName = $this->request->getPost('role');
        $role = $GroupModel->where('name', $roleName)->first();
        if (!$role) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Role tidak valid.',
            ]);
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'role' => $role['id'],
        ];

        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
        }

        if (!$UserModel->update($id, $data)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal memperbarui pengguna.',
            ]);
        }

        $this->activityModel->logActivity(session()->get('user_id'), session()->get('username'), session()->get('role'), "Mengedit pengguna <strong>" . $user['username'] . "</strong>");

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Pengguna berhasil diperbarui.',
        ]);
    }

    /**
     * Summary of update_status
     * @param mixed $id
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function update_status($id)
    {
        $status = $this->request->getPost('status');

        if (!in_array($status, ['active', 'inactive'])) {
            return redirect()->back()->with('error', 'Status tidak valid.');
        }

        $UserModel = new UserModel();
        $user = $UserModel->find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'Pengguna tidak ditemukan.');
        }

        $username = $user['username'];

        $UserModel->update($id, ['status' => $status]);

        $this->activityModel->logActivity(session()->get('user_id'), session()->get('username'), session()->get('role'), "Mengubah status pengguna <strong>" . $user['username'] . "</strong> menjadi <strong>$status</strong>");

        return redirect()->back()->with('success', 'Status pengguna <strong>' . ucfirst($username) . '</strong> berhasil diperbarui menjadi <strong>' . ucfirst($status) . '</strong>.');
    }

    /**
     * Summary of filter_users
     * @return string
     */
    public function filter_users()
    {
        $searchUser = $this->request->getGet('searchUser');
        $role = $this->request->getGet('searchRole');
        $status = $this->request->getGet('status');

        $UserModel = new UserModel();
        $GroupModel = new GroupModel();

        if (!empty($searchUser)) {
            $UserModel->like('username', $searchUser);
        }

        if (!empty($role)) {
            $UserModel->where('role', $role);
        }

        if (!empty($status)) {
            $UserModel->where('status', $status);
        }

        $UserModel->orderBy('username', 'ASC');

        $perPage = 10;
        $currentPage = $this->request->getVar('page') ? $this->request->getVar('page') : 1;

        $data['users'] = $UserModel->paginate($perPage, 'default', $currentPage);

        $data['roles'] = $GroupModel->findAll();

        $data['status'] = [
            ['status' => 'active'],
            ['status' => 'inactive'],
        ];

        $data['pager'] = $UserModel->pager;

        return view('admin/users', $data);
    }
}