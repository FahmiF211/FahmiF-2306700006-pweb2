<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="hero-box mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
        <div>
            <span class="badge badge-neon px-3 py-2 mb-2">Koleksi Pribadi</span>
            <h1 class="mb-0">Game Favorit Saya</h1>
        </div>
        <span class="text-soft"><?= esc(count($favorites)) ?> game tersimpan</span>
    </div>
</section>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success border-0"><?= esc(session()->getFlashdata('success')) ?></div>
<?php endif; ?>

<?php if (empty($favorites)): ?>
    <section class="hero-box text-center py-5">
        <h3 class="mb-2">Belum ada game favorit</h3>
        <p class="text-soft mb-3">Yuk tambahkan game favoritmu dari halaman detail game.</p>
        <a href="<?= base_url('/games') ?>" class="btn btn-electric px-4">Jelajahi Game</a>
    </section>
<?php else: ?>
    <section>
        <div class="row g-4">
            <?php foreach ($favorites as $favorite): ?>
                <div class="col-sm-6 col-xl-4">
                    <article class="card-game h-100">
                        <a href="<?= base_url('/games/detail/' . $favorite['game_id']) ?>" class="card-link">
                            <img src="<?= esc($favorite['game_thumbnail']) ?>" class="img-fluid" alt="<?= esc($favorite['game_title']) ?>">
                            <div class="p-3">
                                <h5 class="mb-2"><?= esc($favorite['game_title']) ?></h5>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge text-bg-dark border"><?= esc($favorite['game_genre']) ?></span>
                                    <small class="text-soft"><?= esc($favorite['game_platform']) ?></small>
                                </div>
                            </div>
                        </a>
                        <div class="px-3 pb-3">
                            <form action="<?= base_url('/games/favorite/remove') ?>" method="post">
                                <?= csrf_field() ?>
                                <input type="hidden" name="game_id" value="<?= esc($favorite['game_id']) ?>">
                                <button type="submit" class="btn btn-outline-danger w-100">Hapus dari Favorit</button>
                            </form>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>
<?= $this->endSection() ?>
