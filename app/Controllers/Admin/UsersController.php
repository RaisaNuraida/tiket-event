<?php

namespace App\Controllers\Admin;

use App\Models\GroupModel;
use App\Models\UserModel;
use App\Controllers\BaseController;

class UsersController extends BaseController
{
    /**
     * Summary of admin_users
     * @return string
     */
    public function admin_users()
    {
        $GroupModel = new GroupModel();
        $UserModel = new UserModel();

        // Menentukan jumlah data per halaman
        $perPage = 10;  // Misalnya 10 data per halaman
        $currentPage = $this->request->getVar('page') ? $this->request->getVar('page') : 1;

        // Ambil data pengguna dan urutkan berdasarkan username secara ascending
        $data['users'] = $UserModel->orderBy('username', 'ASC')->paginate($perPage, 'default', $currentPage);

        // Ambil total data untuk pagination
        $data['pager'] = $UserModel->pager;

        // Ambil data roles untuk dropdown
        $data['roles'] = $GroupModel->findAll();

        // Siapkan data status untuk dropdown
        $data['status'] = [
            ['status' => 'active'],
            ['status' => 'inactive'],
        ];

        // Kirim data ke view
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

        // Ambil data dari form
        $email = $this->request->getPost('email');
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $roleName = $this->request->getPost('role');
        $status = 'active';

        // Validasi input
        if (empty($email) || empty($username) || empty($password) || empty($roleName)) {
            return redirect()->back()
                ->withInput() // Menyimpan data input
                ->with('error', 'Semua field harus diisi.')
                ->with('modal', 'addUserModal'); // Menandai modal
        }

        // Periksa apakah username sudah ada
        if ($UserModel->where('username', $username)->first()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Username sudah digunakan. Silakan pilih username lain.')
                ->with('modal', 'addUserModal');
        }

        // Periksa apakah email sudah ada
        if ($UserModel->where('email', $email)->first()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Email sudah digunakan. Silakan pilih email lain.')
                ->with('modal', 'addUserModal');
        }

        // Hash password sebelum disimpan
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Ambil ID role berdasarkan nama role dari tabel auth_groups
        $role = $GroupModel->where('name', $roleName)->first();

        if (!$role) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Role tidak valid.')
                ->with('modal', 'addUserModal');
        }

        // Simpan data user ke database
        $UserModel->save([
            'email' => $email,
            'username' => $username,
            'password_hash' => $hashedPassword, // Simpan password yang sudah di-hash
            'role' => $role['id'],
            'status' => $status,
        ]);

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

        // Cari pengguna berdasarkan ID
        $user = $UserModel->find($id);
        if (!$user) {
            session()->setFlashdata('error', 'Pengguna tidak ditemukan.');
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Pengguna tidak ditemukan.'
            ]);
        }

        // Validasi input
        $validation = $this->validate([
            'username' => 'required|min_length[3]|max_length[255]',
            'email' => 'required|valid_email',
            'role' => 'required',
        ]);

        if (!$validation) {
            session()->setFlashdata('error', 'Validasi gagal.');
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $this->validator->getErrors()
            ]);
        }

        // Ambil nama role dari input
        $roleName = $this->request->getPost('role');

        // Cari ID role berdasarkan nama
        $role = $GroupModel->where('name', $roleName)->first();
        if (!$role) {
            session()->setFlashdata('error', 'Role tidak valid.');
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Role tidak valid.'
            ]);
        }

        // Siapkan data untuk diupdate
        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'role' => $role['id'], // Simpan ID role, bukan nama
        ];

        // Update data di database
        $UserModel->update($id, $data);

        session()->setFlashdata('success', 'Pengguna berhasil diperbarui.');

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Pengguna berhasil diperbarui.'
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

        // Validasi input
        if (!in_array($status, ['active', 'inactive'])) {
            return redirect()->back()->with('error', 'Status tidak valid.');
        }

        $UserModel = new UserModel();
        $user = $UserModel->find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'Pengguna tidak ditemukan.');
        }

        // Ambil username sebelum status diperbarui
        $username = $user['username'];

        // Update status
        $UserModel->update($id, ['status' => $status]);

        return redirect()->back()->with('success', 'Status pengguna <strong>' . $username . '</strong> berhasil diperbarui menjadi <strong>' . ucfirst($status) . '</strong>.');
    }

    /**
     * Summary of filter_users
     * @return string
     */
    public function filter_users()
    {
        $searchUser = $this->request->getGet('searchUser'); // Input nama pengguna
        $role = $this->request->getGet('searchRole');       // Input role pengguna
        $status = $this->request->getGet('status');         // Input status pengguna

        $UserModel = new UserModel();
        $GroupModel = new GroupModel();

        // Filter berdasarkan nama pengguna jika diisi
        if (!empty($searchUser)) {
            $UserModel->like('username', $searchUser);
        }

        // Filter berdasarkan role jika diisi
        if (!empty($role)) {
            $UserModel->where('role', $role); // Langsung cocokkan dengan nama role
        }

        // Filter berdasarkan status jika diisi
        if (!empty($status)) {
            $UserModel->where('status', $status);
        }

        // Urutkan berdasarkan username secara ascending
        $UserModel->orderBy('username', 'ASC');

        // Menentukan jumlah data per halaman
        $perPage = 10;
        $currentPage = $this->request->getVar('page') ? $this->request->getVar('page') : 1;

        // Ambil data pengguna yang sudah difilter dengan paginate()
        $data['users'] = $UserModel->paginate($perPage, 'default', $currentPage);

        // Ambil data roles dari tabel GroupModel
        $data['roles'] = $GroupModel->findAll();

        // Siapkan data status untuk dropdown
        $data['status'] = [
            ['status' => 'active'],
            ['status' => 'inactive'],
        ];

        // Ambil data pager untuk pagination
        $data['pager'] = $UserModel->pager;

        // Kirim data ke view
        return view('admin/users', $data);
    }
}