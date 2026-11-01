<?php

namespace App\Models;

use CodeIgniter\Model;

class TaskModel extends Model
{
    protected $table         = 'tasks';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = ['title', 'status', 'task_date', 'is_archived', 'created_at'];

    // Fill created_at automatically on insert. The table has no updated_at column.
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    // Tasks scheduled for today only (archived tasks are left out).
    public function getToday(): array
    {
        return $this->where('task_date', date('Y-m-d'))
                    ->where('is_archived', 0)
                    ->orderBy('id')
                    ->findAll();
    }

    // Every task, earliest date first (archived tasks are left out).
    public function getAllByDate(): array
    {
        return $this->where('is_archived', 0)
                    ->orderBy('task_date')
                    ->orderBy('id')
                    ->findAll();
    }

    // Soft delete: mark the task as archived instead of removing the row.
    public function archive(int $id): bool
    {
        return $this->update($id, ['is_archived' => 1]);
    }
}
