<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UserModel;

class AuthController extends BaseController
{
    protected $user;

    public function __construct()
    {
        helper('form');
        $this->user = new UserModel();
    }

    public function login()
    {
        if ($this->request->getPost()) {
            $rules = [
                'username' => 'required|min_length[3]',
                'password' => 'required|min_length[7]',
            ];

            if (!$this->validate($rules)) {
                session()->setFlashdata('failed', $this->validator->listErrors());
                return redirect()->back()->withInput();
            }

            $username = $this->request->getVar('username');
            $password = $this->request->getVar('password');
            
            $dataUser = $this->user->where(['username' => $username])->first();

            if ($dataUser) {
                if (password_verify($password, $dataUser['password'])) {
                    session()->set([
                        'username' => $dataUser['username'],
                        'role' => $dataUser['role'],
                        'isLoggedIn' => TRUE
                    ]);
                    return redirect()->to(base_url('/'));
                }
                session()->setFlashdata('failed', 'Username & Password Salah');
                return redirect()->back();
            }
            session()->setFlashdata('failed', 'Username Tidak Ditemukan');
            return redirect()->back();
        }
        return view('v_login');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
