<?php

namespace App\Models;

class TaskModel extends \CodeIgniter\Model
{
    protected $table = 'task';
    protected $allowedFields = ['description'];

    protected $returnType    = \App\Entities\TaskEntity::class;
    protected $useTimestamps = true;

    protected $validationRules = [
        'description'     => 'required',
    ];

    protected $validationMessages = [
        'description' => [
            'required' => 'Please enter a description.',
        ],
    ];
}
