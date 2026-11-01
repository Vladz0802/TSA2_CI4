<?= $this->extend('layout') ?>

<?php helper('form'); $loggedIn = (bool) session()->get('isLoggedIn'); ?>

<?= $this->section('content') ?>
<header class="page-header header-row">
    <div>
        <p class="subtitle">Sorted by date</p>
        <h1>All Tasks</h1>
        <p class="summary"><?= count($tasks) ?> tasks in total</p>
    </div>
    <?php /* Visible to everyone: logged-out visitors get sent to the login page. */ ?>
    <a href="<?= site_url('tasks/new') ?>" class="btn">+ New Task</a>
</header>

<div class="card">
    <?php if (empty($tasks)): ?>
        <p class="empty">No tasks yet.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Task</th>
                    <th>Status</th>
                    <?php if ($loggedIn): ?><th>Actions</th><?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tasks as $task): ?>
                    <tr class="<?= $task['task_date'] === $today ? 'is-today' : '' ?>">
                        <td class="date"><?= date('M j, Y', strtotime($task['task_date'])) ?></td>
                        <td><?= esc($task['title']) ?></td>
                        <td><span class="badge <?= esc($task['status'], 'attr') ?>"><?= esc(ucwords(str_replace('_', ' ', $task['status']))) ?></span></td>
                        <?php if ($loggedIn): ?>
                            <td class="actions">
                                <a href="<?= site_url('tasks/' . $task['id'] . '/edit') ?>" class="btn btn-small btn-outline">Edit</a>
                                <form action="<?= site_url('tasks/' . $task['id'] . '/delete') ?>" method="post" class="inline" onsubmit="return confirm('Delete this task?');">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-small btn-danger">Delete</button>
                                </form>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
