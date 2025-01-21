<?php

namespace App\Controllers;

use App\Models\UserModel;

class RegisterController extends BaseController
{
    public function index()
    {
        return view('register'); // Menampilkan form registrasi
    }

    public function register()
    {
        $UserModel = new UserModel();

        // Ambil data dari form
        $email = $this->request->getPost('email');
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Validasi input
        if (empty($email) || empty($username) || empty($password)) {
            return redirect()->back()->with('error', 'Username dan password harus diisi.');
        }

        // Hash password sebelum disimpan
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Simpan data user ke database
        $UserModel->save([
            'email' => $email,
            'username' => $username,
            'password_hash' => $hashedPassword, // Simpan password yang sudah di-hash
        ]);

        return redirect()->to('/login')->with('success', 'Registrasi berhasil. Silahkan login.');
    }
}
