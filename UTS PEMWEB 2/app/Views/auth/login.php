<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="hero-box mx-auto" style="max-width:560px;">
    <h1 class="mb-3">Login NexaGames</h1>
    <p class="text-soft">Masuk untuk menyimpan game favorit dan mengirim review.</p>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger border-0"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success border-0"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>

    <form method="post" action="<?= site_url('login') ?>" class="row g-3">
        <?= csrf_field() ?>
        <div class="col-12">
            <label class="form-label">Email</label>
            <input type="email" name="email" value="<?= old('email') ?>" class="form-control" required>
            <?php if (isset($validation) && $validation->hasError('email')): ?>
                <small class="text-danger"><?= esc($validation->getError('email')) ?></small>
            <?php endif; ?>
        </div>
        <div class="col-12">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
            <?php if (isset($validation) && $validation->hasError('password')): ?>
                <small class="text-danger"><?= esc($validation->getError('password')) ?></small>
            <?php endif; ?>
        </div>
        <div class="col-12 d-grid">
            <button type="submit" class="btn btn-electric">Login</button>
        </div>
    </form>

    <div class="my-3 text-center text-soft">atau</div>

    <div class="d-grid">
        <a href="<?= site_url('auth/google') ?>" class="btn btn-outline-light">Login dengan Google</a>
    </div>

    <p class="text-soft mt-3 mb-0">Belum punya akun? <a class="text-info" href="<?= site_url('register') ?>">Daftar sekarang</a></p>
</section>
<?= $this->endSection() ?>
