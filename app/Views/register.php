<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?php
$errors = session()->getFlashdata('errors') ?? [];
$old    = static fn (string $k): string => esc(old($k) ?? '');
$err    = static fn (string $k): string => isset($errors[$k]) ? '<span class="error">' . esc($errors[$k]) . '</span>' : '';
?>
<section class="form-wrap">
    <h1>Create your account</h1>
    <p class="sub">Customer registration. Employee accounts are created by HR or an administrator.</p>

    <?php if ($errors): ?>
        <div class="alert" role="alert">Please fix the highlighted fields and try again.</div>
    <?php endif; ?>

    <form action="<?= site_url('register') ?>" method="post" novalidate>
        <?= csrf_field() ?>

        <fieldset>
            <legend>Personal details</legend>
            <div class="row">
                <label>First name <input type="text" name="first_name" value="<?= $old('first_name') ?>" required><?= $err('first_name') ?></label>
                <label>Middle name <input type="text" name="middle_name" value="<?= $old('middle_name') ?>" required><?= $err('middle_name') ?></label>
                <label>Last name <input type="text" name="last_name" value="<?= $old('last_name') ?>" required><?= $err('last_name') ?></label>
            </div>
            <div class="row">
                <label>Birthdate <input type="date" name="birthdate" value="<?= $old('birthdate') ?>" max="<?= date('Y-m-d') ?>" required><?= $err('birthdate') ?></label>
                <label>Gender
                    <select name="gender" required>
                        <option value="">Select one</option>
                        <?php foreach ($genders as $g): ?>
                            <option value="<?= esc($g) ?>" <?= old('gender') === $g ? 'selected' : '' ?>><?= esc($g) ?></option>
                        <?php endforeach; ?>
                    </select><?= $err('gender') ?>
                </label>
            </div>
        </fieldset>

        <fieldset>
            <legend>Contact details</legend>
            <div class="row">
                <label>Email <input type="email" name="email" value="<?= $old('email') ?>" placeholder="johndoe@email.com" required><?= $err('email') ?></label>
                <label>Phone number <input type="tel" name="phone" value="<?= $old('phone') ?>" placeholder="09123456789" required><?= $err('phone') ?></label>
            </div>
            <label>Address <input type="text" name="address" value="<?= $old('address') ?>" placeholder="Street, City, ZIP" required><?= $err('address') ?></label>
        </fieldset>

        <fieldset>
            <legend>Account</legend>
            <label>Username <input type="text" name="username" value="<?= $old('username') ?>" autocomplete="username" required><?= $err('username') ?></label>
            <div class="row">
                <label>Password <input type="password" name="password" autocomplete="new-password" minlength="8" required><?= $err('password') ?></label>
                <label>Confirm password <input type="password" name="confirm" autocomplete="new-password" required><?= $err('confirm') ?></label>
            </div>
        </fieldset>

        <label class="check">
            <input type="checkbox" name="terms" value="1" <?= old('terms') ? 'checked' : '' ?>>
            I agree to the privacy notice. Sun Son Solar will use my details only to manage my account and services.
        </label>
        <?= $err('terms') ?>

        <button class="btn" type="submit">Register</button>
    </form>
</section>
<?= $this->endSection() ?>
