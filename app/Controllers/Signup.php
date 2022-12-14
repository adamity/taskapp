<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use \App\Models\UserModel;
use \App\Entities\UserEntity;

class Signup extends BaseController
{
    public function new()
    {
        return view('Signup/new');
    }

    public function create()
    {
        $user = new UserEntity($this->request->getPost());
        $model = new UserModel;

        $user->startActivation();

        if ($model->insert($user)) {
            $this->sendActivationEmail($user);
            return redirect()->to("/signup/success");
        } else {
            return redirect()->back()
                ->with('errors', $model->errors())
                ->with('warning', 'Invalid data')
                ->withInput();
        }
    }

    public function success()
    {
        return view('Signup/success');
    }

    public function sendActivationEmail($user)
    {
        $email = service('email');

        $email->setTo($user->email);
        $email->setSubject('Account Activation');

        $message = view('Signup/activation_email', [
            'token' => $user->token,
        ]);

        $email->setMessage($message);
        $email->send();
    }

    public function activate($token)
    {
        $model = new UserModel;
        $model->activateByToken($token);
        return view('Signup/activated');
    }
}
