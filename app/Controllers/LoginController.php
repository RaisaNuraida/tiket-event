<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ActivityModel;

class LoginController extends BaseController
{
    public function index()
    {
        return view('login');
    }

    public function login()
    {
        $UserModel = new UserModel();
        $ActivityLogModel = new ActivityModel();
        $login = $this->request->getPost('login');

        if ($login) {
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');

            $user = $UserModel->where('username', $username)->first();

            if ($user) {
                if ($user['status'] === 'inactive') {
                    session()->setFlashdata('error', 'Akun Anda tidak aktif.');
                    $ActivityLogModel->logActivity($user['id'], $user['username'], $user['role'], 'Gagal login - Account inactive');
                    return redirect()->to('/login');
                }

                if (password_verify($password, $user['password_hash'])) {
                    $sessionData = [
                        'user_id' => $user['id'],
                        'username' => $user['username'],
                        'role' => $user['role'],
                        'logged_in' => true,
                    ];
                    session()->set($sessionData);

                    $ActivityLogModel->logActivity($user['id'], $user['username'], $user['role'], 'Berhasil login');

                    switch ($user['role']) {
                        case 'admin':
                            return redirect()->to('/admin/index');
                        case 'kasir':
                            return redirect()->to('/kasir/index');
                        case 'owner':
                            return redirect()->to('/owner/index');
                        default:
                            session()->setFlashdata('error', 'Role tidak valid.');
                            $ActivityLogModel->logActivity($user['id'], $user['username'], $user['role'], 'Gagal login - Invalid role');
                            return redirect()->to('/login');
                    }
                } else {
                    session()->setFlashdata('error', 'Password salah.');
                    $ActivityLogModel->logActivity($user['id'], $user['username'], $user['role'], 'Gagal login - Password Salah');
                    return redirect()->to('/login');
                }
            } else {
                session()->setFlashdata('error', 'Username tidak ditemukan.');
                $ActivityLogModel->logActivity(null, $username, null, 'Gagal login - Username tidak ditemukan');
                return redirect()->to('/login');
            }
        }

        return view('welcome_page');
    }

    public function logout()
    {
        $session = session();
        $username = session()->get('username');
        $role = session()->get('role');
        $session->destroy();

        $ActivityLogModel = new ActivityModel();
        $ActivityLogModel->logActivity(0, $username, $role, 'Logged out');

        return redirect()->to('/login');
    }
}
