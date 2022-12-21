<?php

namespace App\Controllers;

use \App\Models\TaskModel;
use \App\Entities\TaskEntity;

class Tasks extends BaseController
{
    private $model;
    private $current_user;

    public function __construct()
    {
        $this->model = new TaskModel;
        $this->current_user = service('auth')->getCurrentUser();
    }

    public function index()
    {
        $task = $this->model->paginateTasksByUserId($this->current_user->id);
        return view('Tasks/index', [
            'tasks' => $task,
            'pager' => $this->model->pager,
        ]);
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

        $post = $this->request->getPost();
        unset($post['user_id']);

        $task->fill($post);

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
        $task->user_id = $this->current_user->id;

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
        $task = $this->model->getTaskByUserId($id, $this->current_user->id);

        if (!$task) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Task with id $id not found");
        }

        return $task;
    }
}
