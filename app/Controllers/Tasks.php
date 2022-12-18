<?php

namespace App\Controllers;

use \App\Models\TaskModel;

class Tasks extends BaseController
{
    public function index()
    {
        $model = new TaskModel;
        $data = $model->findAll();

        return view('Tasks/index', ['tasks' => $data]);
    }

    public function show($id)
    {
        $model = new TaskModel;
        $data = $model->find($id);
        return view('Tasks/show', ['task' => $data]);
    }

    public function new()
    {
        return view('Tasks/new');
    }

    public function create()
    {
        $model = new TaskModel;

        $result = $model->insert([
            'description' => $this->request->getPost("description"),
        ]);

        if ($result === false) {
            return redirect()->back()
                ->with('errors', $model->errors())
                ->with('warning', 'Invalid data');
        } else {
            return redirect()->to("/tasks/show/$result")
                ->with('info', 'Task created successfully');
        }
    }
}
