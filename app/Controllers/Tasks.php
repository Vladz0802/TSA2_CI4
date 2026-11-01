<?php

namespace App\Controllers;

use App\Models\TaskModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;

class Tasks extends BaseController
{
    // Task List page (/tasks): public. Archived tasks are left out by TaskModel.
    public function index(): string
    {
        return view('tasks', [
            'title' => 'All Tasks',
            'tasks' => (new TaskModel())->getAllByDate(),
            'today' => date('Y-m-d'),
        ]);
    }

    // Empty New Task form (/tasks/new): login required (see Routes.php).
    public function new(): string
    {
        return view('task_form', [
            'title'   => 'New Task',
            'heading' => 'New Task',
            'button'  => 'Create Task',
            'action'  => site_url('tasks'),
            'task'    => null,
        ]);
    }

    // Validates the New Task form, then saves the task.
    public function create(): RedirectResponse
    {
        if (! $this->validate($this->rules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        (new TaskModel())->insert([
            'title'       => trim((string) $this->request->getPost('title')),
            'status'      => $this->request->getPost('status') ?: 'pending',
            'task_date'   => $this->request->getPost('task_date'),
            'is_archived' => 0,
        ]);

        return redirect()->to('/tasks')->with('success', 'Task created.');
    }

    // Edit form (/tasks/5/edit), filled in with the saved task.
    public function edit(int $id): string
    {
        return view('task_form', [
            'title'   => 'Edit Task',
            'heading' => 'Edit Task',
            'button'  => 'Save Changes',
            'action'  => site_url('tasks/' . $id . '/update'),
            'task'    => $this->findActiveTask($id),
        ]);
    }

    // Validates the edit form, then updates the task.
    public function update(int $id): RedirectResponse
    {
        $this->findActiveTask($id); // 404 if it does not exist or is archived

        if (! $this->validate($this->rules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        (new TaskModel())->update($id, [
            'title'     => trim((string) $this->request->getPost('title')),
            'status'    => $this->request->getPost('status') ?: 'pending',
            'task_date' => $this->request->getPost('task_date'),
        ]);

        return redirect()->to('/tasks')->with('success', 'Task updated.');
    }

    // "Delete" = soft delete: the row stays in the table, only is_archived becomes 1.
    public function delete(int $id): RedirectResponse
    {
        $this->findActiveTask($id);

        (new TaskModel())->archive($id);

        return redirect()->to('/tasks')->with('success', 'Task deleted.');
    }

    // Validation rules shared by the create and update forms.
    private function rules(): array
    {
        return [
            'title'     => 'required|max_length[150]',
            'task_date' => 'required|valid_date[Y-m-d]',
            'status'    => 'permit_empty|in_list[pending,in_progress,completed]',
        ];
    }

    // Finds a task that is not archived, or shows a 404 page.
    private function findActiveTask(int $id): array
    {
        $task = (new TaskModel())->find($id);

        if ($task === null || (int) $task['is_archived'] === 1) {
            throw PageNotFoundException::forPageNotFound();
        }

        return $task;
    }
}
