<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Entities\UserEntity;
use App\Models\UserModel;

class Users extends BaseController
{
    private $model;

    public function __construct()
    {
        $this->model = new UserModel;
    }

    public function index()
    {
        $users = $this->model->orderBy('id')->paginate();

        return view('Admin/Users/index', [
            'users' => $users,
            'pager' => $this->model->pager,
        ]);
    }

    public function show($id)
    {
        $user = $this->getUserOr404($id);
        return view('Admin/Users/show', ['user' => $user]);
    }

    public function getUserOr404($id)
    {
        $user = $this->model->where('id', $id)->first();

        if (!$user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Task with id $id not found");
        }

        return $user;
    }

    public function new()
    {
        return view('Admin/Users/new', ['user' => new UserEntity]);
    }

    public function create()
    {
        $user = new UserEntity($this->request->getPost());

        if ($this->model->insert($user)) {
            return redirect()->to('/admin/users/show/' . $this->model->insertID)
                ->with('info', 'User created successfully');
        } else {
            return redirect()->back()
                ->with('errors', $this->model->errors())
                ->with('warning', 'Invalid data')
                ->withInput();
        }
    }

    public function edit($id)
    {
        $user = $this->getUserOr404($id);
        return view('Admin/Users/edit', ['user' => $user]);
    }

    public function update($id)
    {
        $user = $this->getUserOr404($id);

        $post = $this->request->getPost();
        if (empty($post['password'])) {
            $this->model->disablePasswordValidation();
            unset($post['password']);
            unset($post['password_confirmation']);
        }

        $user->fill($post);

        if (!$user->hasChanged()) {
            return redirect()->back()
                ->with('errors', $this->model->errors())
                ->with('warning', 'Nothing to update')
                ->withInput();
        }

        if ($this->model->save($user)) {
            return redirect()->to("/admin/users/show/$id")
                ->with('info', 'User updated successfully');
        } else {
            return redirect()->back()
                ->with('errors', $this->model->errors())
                ->with('warning', 'Invalid data')
                ->withInput();
        }
    }

    public function delete($id)
    {
        $user = $this->getUserOr404($id);

        if ($this->request->getMethod() === 'post') {
            $this->model->delete($id);

            return redirect()->to("/admin/users")
                ->with('info', 'User deleted');
        }

        return view('Admin/Users/delete', ['user' => $user]);
    }
}
