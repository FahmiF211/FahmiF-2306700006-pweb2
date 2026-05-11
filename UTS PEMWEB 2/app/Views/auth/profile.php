<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="hero-box mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <h1 class="mb-2">Profil Pengguna</h1>
            <p class="text-soft mb-0">Kelola data akun, favorit, dan review kamu.</p>
        </div>
        <a href="<?= base_url('/logout') ?>" class="btn btn-outline-light">Logout</a>
    </div>
</section>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger border-0"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success border-0"><?= esc(session()->getFlashdata('success')) ?></div>
<?php endif; ?>

<section class="hero-box mb-4">
    <h3 class="mb-3">Ubah Profil</h3>
    <form action="<?= base_url('/profile/update') ?>" method="post" enctype="multipart/form-data" class="row g-3">
        <?= csrf_field() ?>
        <div class="col-md-6">
            <label class="form-label">Nama</label>
            <input type="text" class="form-control" name="name" value="<?= esc(old('name', $user['name'] ?? '')) ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" value="<?= esc(old('email', $user['email'] ?? '')) ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Password Baru</label>
            <input type="password" class="form-control" name="new_password" placeholder="Kosongkan jika tidak diubah">
        </div>
        <div class="col-md-6">
            <label class="form-label">Konfirmasi Password Baru</label>
            <input type="password" class="form-control" name="confirm_password" placeholder="Ulangi password baru">
        </div>
        <div class="col-md-6">
            <label class="form-label">Foto Profil</label>
            <input type="file" class="form-control" name="photo" accept="image/*">
        </div>
        <div class="col-md-6 d-flex align-items-end">
            <?php if (! empty($user['photo'])): ?>
                <img src="<?= base_url($user['photo']) ?>" alt="Foto profil" class="rounded-circle border border-secondary-subtle" style="width:72px;height:72px;object-fit:cover;">
            <?php else: ?>
                <span class="text-soft">Belum ada foto profil.</span>
            <?php endif; ?>
        </div>
        <div class="col-12 d-grid d-md-block">
            <button type="submit" class="btn btn-electric px-4">Simpan Perubahan</button>
        </div>
    </form>
</section>

<section class="hero-box mb-4">
    <h3 class="mb-3">Game Favorit Saya</h3>
    <?php if (empty($favorites)): ?>
        <p class="text-soft mb-0">Belum ada game favorit.</p>
    <?php else: ?>
        <div class="row g-3">
            <?php foreach ($favorites as $favorite): ?>
                <div class="col-md-6 col-xl-4">
                    <a href="<?= base_url('/games/detail/' . $favorite['game_id']) ?>" class="card-link">
                        <article class="card-game h-100">
                            <img src="<?= esc($favorite['game_thumbnail']) ?>" alt="<?= esc($favorite['game_title']) ?>" class="img-fluid">
                            <div class="p-3">
                                <h5 class="mb-1"><?= esc($favorite['game_title']) ?></h5>
                                <small class="text-soft d-block"><?= esc($favorite['game_genre']) ?> - <?= esc($favorite['game_platform']) ?></small>
                            </div>
                        </article>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<section class="hero-box">
    <h3 class="mb-3">Review Saya</h3>
    <?php if (empty($reviews)): ?>
        <p class="text-soft mb-0">Kamu belum membuat review.</p>
    <?php else: ?>
        <div class="d-grid gap-3">
            <?php foreach ($reviews as $review): ?>
                <article class="p-3 rounded-3 border border-secondary-subtle">
                    <div class="d-flex justify-content-between mb-2">
                        <strong><?= esc($review['game_title']) ?></strong>
                        <span class="badge badge-neon">Rating <?= esc($review['rating']) ?>/5</span>
                    </div>
                    <p class="mb-0 text-soft"><?= esc($review['comment'] ?: 'Tanpa komentar.') ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
<?= $this->endSection() ?>
