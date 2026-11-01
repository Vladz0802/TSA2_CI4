<?php
helper(['url', 'form']);

$page     = trim(uri_string(), '/');
$loggedIn = (bool) session()->get('isLoggedIn');
$links    = [
    ''        => 'Today',
    'tasks'   => 'All Tasks',
    'profile' => 'Profile',
    'about'   => 'About',
];

// The New Task link only shows once logged in.
if ($loggedIn) {
    $links['tasks/new'] = 'New Task';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title) ?> | DayBoard</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/forms.css') ?>">
</head>
<body>
<div class="app">

    <aside class="sidebar">
        <div class="logo">Day<span>Board</span></div>
        <nav>
            <?php foreach ($links as $path => $label): ?>
                <a href="<?= base_url($path) ?>" class="<?= $page === $path ? 'active' : '' ?>"><?= $label ?></a>
            <?php endforeach; ?>
        </nav>

        <div class="auth-box">
            <?php if ($loggedIn): ?>
                <p class="who">Signed in as<br><strong><?= esc(session('fullName')) ?></strong></p>
                <form action="<?= site_url('logout') ?>" method="post">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-outline">Log out</button>
                </form>
            <?php else: ?>
                <a href="<?= site_url('login') ?>" class="btn">Log in</a>
            <?php endif; ?>
        </div>
    </aside>

    <main class="content">
        <?php if (session('success')): ?>
            <div class="alert success"><?= esc(session('success')) ?></div>
        <?php endif; ?>
        <?php if (session('error')): ?>
            <div class="alert error"><?= esc(session('error')) ?></div>
        <?php endif; ?>

        <?= $this->renderSection('content') ?>
    </main>

</div>
</body>
</html>
