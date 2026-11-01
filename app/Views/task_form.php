<?= $this->extend('layout') ?>

<?php
// Used for both New Task and Edit Task.
// $task is null for a new task, or the saved row when editing.
helper('form');

$errors = session('errors') ?? [];

// old('field') = what the user typed before validation failed (already escaped).
// Otherwise fall back to the saved value, or a sensible default.
$value = static fn (string $field, string $default = '') => old($field) ?? esc($default);

$currentStatus = old('status') ?? ($task['status'] ?? 'pending');
$statuses      = ['pending' => 'Pending', 'in_progress' => 'In Progress', 'completed' => 'Completed'];
?>

<?= $this->section('content') ?>
<header class="page-header">
    <p class="subtitle"><?= $task === null ? 'Create' : 'Update' ?></p>
    <h1><?= esc($heading) ?></h1>
</header>

<div class="card form-card">
    <form action="<?= esc($action, 'attr') ?>" method="post" novalidate>
        <?= csrf_field() ?>

        <div class="field">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" maxlength="150" value="<?= $value('title', $task['title'] ?? '') ?>">
            <?php if (isset($errors['title'])): ?><p class="field-error"><?= esc($errors['title']) ?></p><?php endif; ?>
        </div>

        <div class="field">
            <label for="task_date">Date</label>
            <input type="date" id="task_date" name="task_date" value="<?= $value('task_date', $task['task_date'] ?? date('Y-m-d')) ?>">
            <?php if (isset($errors['task_date'])): ?><p class="field-error"><?= esc($errors['task_date']) ?></p><?php endif; ?>
        </div>

        <div class="field">
            <label for="status">Status</label>
            <select id="status" name="status">
                <?php foreach ($statuses as $key => $label): ?>
                    <option value="<?= $key ?>" <?= $currentStatus === $key ? 'selected' : '' ?>><?= $label ?></option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($errors['status'])): ?><p class="field-error"><?= esc($errors['status']) ?></p><?php endif; ?>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn"><?= esc($button) ?></button>
            <a href="<?= site_url('tasks') ?>" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
