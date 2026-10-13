<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Sun Son Solar') ?></title>
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>
<header class="site-header">
    <a class="brand" href="<?= site_url('/') ?>">Sun Son Solar</a>
    <nav>
        <a href="<?= site_url('/') ?>#services">Services</a>
        <a href="<?= site_url('/') ?>#about">About Company</a>
        <a href="<?= site_url('/') ?>#support">Support</a>
        <a class="btn-nav" href="<?= site_url('register') ?>">Register</a>
    </nav>
</header>
<main>
    <?= $this->renderSection('content') ?>
</main>
<footer class="site-footer">
    <p>&copy; <?= date('Y') ?> Sun Son Solar &middot; Pasig City &middot; Team deVcore</p>
</footer>
</body>
</html>
