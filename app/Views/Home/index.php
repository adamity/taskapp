<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>Home<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <h1>Welcome</h1>

    <a href="<?= site_url('/signup') ?>">Sign up</a>

    <?php if (current_user()): ?>
        <a href="<?= site_url('/logout') ?>">Log out</a>
        <p>User is logged in</p>
        <p>Hello <?= esc(current_user()->name) ?></p>
    <?php else: ?>
        <a href="<?= site_url('/login') ?>">Log in</a>
        <p>User is not logged in</p>
    <?php endif ?>
<?= $this->endSection() ?>