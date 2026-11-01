<?= $this->extend('layout') ?>

<?php
helper('form');

$errors = session('errors') ?? [];
?>

<?= $this->section('content') ?>
<header class="page-header">
    <p class="subtitle">Account</p>
    <h1>Log in</h1>
    <p class="summary">Log in to create, edit or delete tasks.</p>
</header>

<div class="card form-card">
    <form action="<?= site_url('login') ?>" method="post" novalidate>
        <?= csrf_field() ?>

        <div class="field">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" autocomplete="username" value="<?= esc((string) session('old_username'), 'attr') ?>">
            <?php if (isset($errors['username'])): ?><p class="field-error"><?= esc($errors['username']) ?></p><?php endif; ?>
        </div>

        <div class="field">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" autocomplete="current-password">
            <?php if (isset($errors['password'])): ?><p class="field-error"><?= esc($errors['password']) ?></p><?php endif; ?>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn">Log in</button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
