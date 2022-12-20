<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\Authentication;
use App\Models\UserModel;

class Login extends BaseController
{
    public function new()
    {
        return view('Login/new');
    }

    public function create()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $auth = service('auth');
        if ($auth->login($email, $password)) {
            $redirect_url = session('redirect_url') ?? '/';
            unset($_SESSION['redirect_url']);

            return redirect()->to($redirect_url)
                ->with('info', 'Login successful');
        } else {
            return redirect()->back()
                ->with('warning', 'Invalid login')
                ->withInput();
        }
    }

    public function delete()
    {
        service('auth')->logout();
        return redirect()->to('/logout/showLogoutMessage');
    }

    public function showLogoutMessage()
    {
        return redirect()->to('/')
            ->with('info', 'Logout successful');
    }
}
