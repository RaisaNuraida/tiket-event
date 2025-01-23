<?php

namespace App\Controllers\Admin;

use App\Models\GroupModel;
use App\Models\UserModel;
use App\Controllers\BaseController;

class UsersController extends BaseController
{
    /**
     * MEMANGGIL DATA USERS
     * KEDALAM TABEL USERS
     * DAN HALAMAN USERS
     */
    public function admin_users()
    {
        $GroupModel = new GroupModel();

        $UserModel = new UserModel();

        // Ambil semua data pengguna
        $data['users'] = $UserModel->findAll();

        // Ambil daftar role dari database (tanpa duplikasi)
        $data['roles'] = $GroupModel->findAll();

        // Kirim data pengguna dan roles ke view
        return view('admin/users', $data);
    }

    /**
     * MENAMBAH USERS
     * DI HALAMAN USERS
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
            return redirect()->back()->with('error', 'Semua field harus diisi.');
        }

        // Hash password sebelum disimpan
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Ambil ID role berdasarkan nama role dari tabel auth_groups
        $role = $GroupModel->where('name', $roleName)->first();

        if (!$role) {
            return redirect()->back()->with('error', 'Role tidak valid.');
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

        return redirect()->back()->with('success', 'Status pengguna <strong>' . $username . '</strong> berhasil diperbarui.');
    }
}