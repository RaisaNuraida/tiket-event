<?php

namespace App\Controllers;

use App\Models\UserModel;

class LoginController extends BaseController
{
    public function index()
    {
        return view('login'); // Tampilkan form login
    }

    public function login()
    {
        $UserModel = new UserModel();
        $login = $this->request->getPost('login');

        if ($login) {
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');

            // Cari pengguna berdasarkan username
            $user = $UserModel->where('username', $username)->first();

            if ($user) {
                // Cek apakah status pengguna aktif
                if ($user['status'] === 'inactive') {
                    session()->setFlashdata('error', 'Akun Anda tidak aktif.');
                    return redirect()->to('/login');
                }

                // Verifikasi password
                if (password_verify($password, $user['password_hash'])) {
                    // Password benar, set session
                    $sessionData = [
                        'username' => $user['username'],
                        'role' => $user['role'], // Jika ada role
                        'logged_in' => true,
                    ];
                    session()->set($sessionData);

                    // Redirect ke halaman berdasarkan role
                    switch ($user['role']) {
                        case 'admin':
                            return redirect()->to('/admin/index');
                        case 'kasir':
                            return redirect()->to('/kasir/index');
                        case 'owner':
                            return redirect()->to('/owner/index');
                        default:
                            session()->setFlashdata('error', 'Role tidak valid.');
                            return redirect()->to('/login');
                    }
                } else {
                    // Password salah
                    session()->setFlashdata('error', 'Password salah.');
                    return redirect()->to('/login');
                }
            } else {
                // Username tidak ditemukan
                session()->setFlashdata('error', 'Username tidak ditemukan.');
                return redirect()->to('/login');
            }
        }

        return view('welcome_page');
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/login');
    }
}
