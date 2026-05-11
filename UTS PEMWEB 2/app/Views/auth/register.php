<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="hero-box mx-auto" style="max-width:680px;">
    <h1 class="mb-3">Register NexaGames</h1>
    <p class="text-soft">Buat akun untuk menikmati fitur komunitas NexaGames.</p>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger border-0"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <form method="post" action="<?= base_url('/register') ?>" class="row g-3">
        <?= csrf_field() ?>
        <div class="col-md-6">
            <label class="form-label">Nama</label>
            <input type="text" name="name" value="<?= old('name') ?>" class="form-control" required>
            <?php if (isset($validation) && $validation->hasError('name')): ?>
                <small class="text-danger"><?= esc($validation->getError('name')) ?></small>
            <?php endif; ?>
        </div>
        <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" name="email" value="<?= old('email') ?>" class="form-control" required>
            <?php if (isset($validation) && $validation->hasError('email')): ?>
                <small class="text-danger"><?= esc($validation->getError('email')) ?></small>
            <?php endif; ?>
        </div>
        <div class="col-md-6">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
            <?php if (isset($validation) && $validation->hasError('password')): ?>
                <small class="text-danger"><?= esc($validation->getError('password')) ?></small>
            <?php endif; ?>
        </div>
        <div class="col-md-6">
            <label class="form-label">Konfirmasi Password</label>
            <input type="password" name="password_confirm" class="form-control" required>
            <?php if (isset($validation) && $validation->hasError('password_confirm')): ?>
                <small class="text-danger"><?= esc($validation->getError('password_confirm')) ?></small>
            <?php endif; ?>
        </div>
        <div class="col-12 d-grid">
            <button type="submit" class="btn btn-electric">Daftar</button>
        </div>
    </form>
    <p class="text-soft mt-3 mb-0">Sudah punya akun? <a class="text-info" href="<?= base_url('/login') ?>">Login di sini</a></p>
</section>
<?= $this->endSection() ?>
