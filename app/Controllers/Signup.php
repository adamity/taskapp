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

        if ($model->insert($user)) {
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
}
