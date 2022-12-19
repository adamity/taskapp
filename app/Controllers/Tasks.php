<?php

namespace App\Controllers;

use \App\Models\TaskModel;
use \App\Entities\TaskEntity;

class Tasks extends BaseController
{
    private $model;

    public function __construct()
    {
        $this->model = new TaskModel;
    }

    public function index()
    {
        $task = $this->model->findAll();
        return view('Tasks/index', ['tasks' => $task]);
    }

    public function show($id)
    {
        $task = $this->getTaskOr404($id);
        return view('Tasks/show', ['task' => $task]);
    }

    public function edit($id)
    {
        $task = $this->getTaskOr404($id);
        return view('Tasks/edit', ['task' => $task]);
    }

    public function new()
    {
        return view('Tasks/new', ['task' => new TaskEntity]);
    }

    public function update($id)
    {
        $task = $this->getTaskOr404($id);
        $task->fill($this->request->getPost());

        if (!$task->hasChanged()) {
            return redirect()->back()
                ->with('errors', $this->model->errors())
                ->with('warning', 'Nothing to update')
                ->withInput();
        }

        if ($this->model->save($task)) {
            return redirect()->to("/tasks/show/$id")
                ->with('info', 'Task updated successfully');
        } else {
            return redirect()->back()
                ->with('errors', $this->model->errors())
                ->with('warning', 'Invalid data')
                ->withInput();
        }
    }

    public function create()
    {
        $task = new TaskEntity($this->request->getPost());

        if ($this->model->insert($task)) {
            return redirect()->to('/tasks/show/' . $this->model->insertID)
                ->with('info', 'Task created successfully');
        } else {
            return redirect()->back()
                ->with('errors', $this->model->errors())
                ->with('warning', 'Invalid data')
                ->withInput();
        }
    }

    public function delete($id)
    {
        $task = $this->getTaskOr404($id);

        if ($this->request->getMethod() === 'post') {
            $this->model->delete($id);

            return redirect()->to("/tasks")
                ->with('info', 'Task deleted');
        }

        return view('Tasks/delete', ['task' => $task]);
    }

    public function getTaskOr404($id)
    {
        $task = $this->model->find($id);

        if (!$task) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Task with id $id not found");
        }

        return $task;
    }
}
