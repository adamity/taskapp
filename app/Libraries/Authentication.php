<?php

namespace App\Libraries;

use App\Models\UserModel;

class Authentication
{
    private $user;

    public function login($email, $password)
    {
        $model = new UserModel;
        $user = $model->findByEmail($email);

        if (!$user) return false;
        if (!$user->verifyPassword($password)) return false;

        $session = session();
        $session->regenerate();
        $session->set('user_id', $user->id);

        return true;
    }

    public function logout()
    {
        session()->destroy();
    }

    public function getCurrentUser()
    {
        if (!$this->isLoggedIn()) return null;

        if (!$this->user) {
            $model = new UserModel;
            $this->user = $model->find(session()->get('user_id'));
        }

        return $this->user;
    }

    public function isLoggedIn()
    {
        return session()->has('user_id');
    }
}
