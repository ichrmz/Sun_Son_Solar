<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<section class="form-wrap center">
    <h1>Welcome, <?= esc($name) ?>.</h1>
    <p class="sub">Your account is set up. A member of our crew will reach out within one business day.</p>
    <a class="btn" href="<?= site_url('/') ?>">Back to home</a>
</section>
<?= $this->endSection() ?>
