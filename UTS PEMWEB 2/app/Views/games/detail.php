<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="mb-4">
    <a href="<?= site_url('games') ?>" class="btn btn-outline-light btn-sm">&larr; Kembali ke Daftar Game</a>
</section>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger border-0"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success border-0"><?= esc(session()->getFlashdata('success')) ?></div>
<?php endif; ?>

<section class="hero-box">
    <div class="row g-4">
        <div class="col-lg-5">
            <img src="<?= esc($game['thumbnail']) ?>" alt="<?= esc($game['title']) ?>" class="img-fluid rounded-3 border border-secondary-subtle w-100">
        </div>
        <div class="col-lg-7">
            <span class="badge badge-neon px-3 py-2 mb-3">Detail Game</span>
            <h1 class="mb-3"><?= esc($game['title']) ?></h1>
            <p class="text-soft mb-2"><?= esc($game['short_description'] ?? '-') ?></p>
            <?php if (! empty($ratingSummary['total_review'])): ?>
                <p class="mb-4 text-soft">Rating Komunitas: <strong><?= number_format((float) $ratingSummary['avg_rating'], 1) ?>/5</strong> dari <?= esc($ratingSummary['total_review']) ?> ulasan</p>
            <?php else: ?>
                <p class="mb-4 text-soft">Belum ada rating komunitas untuk game ini.</p>
            <?php endif; ?>

            <div class="row g-3 mb-4">
                <div class="col-sm-6">
                    <div class="p-3 rounded-3 border border-secondary-subtle">
                        <small class="text-soft d-block">Genre</small>
                        <strong><?= esc($game['genre'] ?? '-') ?></strong>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="p-3 rounded-3 border border-secondary-subtle">
                        <small class="text-soft d-block">Platform</small>
                        <strong><?= esc($game['platform'] ?? '-') ?></strong>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="p-3 rounded-3 border border-secondary-subtle">
                        <small class="text-soft d-block">Publisher</small>
                        <strong><?= esc($game['publisher'] ?? '-') ?></strong>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="p-3 rounded-3 border border-secondary-subtle">
                        <small class="text-soft d-block">Developer</small>
                        <strong><?= esc($game['developer'] ?? '-') ?></strong>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="p-3 rounded-3 border border-secondary-subtle">
                        <small class="text-soft d-block">Tanggal Rilis</small>
                        <strong><?= esc($game['release_date'] ?? '-') ?></strong>
                    </div>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <a href="<?= esc($game['game_url']) ?>" target="_blank" rel="noopener" class="btn btn-electric px-4 py-2">Mainkan Sekarang</a>
                <?php if ($isLoggedIn): ?>
                    <?php if ($isFavorited): ?>
                        <form action="<?= site_url('games/favorite/remove') ?>" method="post">
                            <?= csrf_field() ?>
                            <input type="hidden" name="game_id" value="<?= esc($game['id']) ?>">
                            <button type="submit" class="btn btn-outline-danger px-4 py-2">Hapus dari Favorit</button>
                        </form>
                    <?php else: ?>
                        <form action="<?= site_url('games/favorite') ?>" method="post">
                            <?= csrf_field() ?>
                            <input type="hidden" name="game_id" value="<?= esc($game['id']) ?>">
                            <input type="hidden" name="game_title" value="<?= esc($game['title']) ?>">
                            <input type="hidden" name="game_thumbnail" value="<?= esc($game['thumbnail']) ?>">
                            <input type="hidden" name="game_genre" value="<?= esc($game['genre']) ?>">
                            <input type="hidden" name="game_platform" value="<?= esc($game['platform']) ?>">
                            <button type="submit" class="btn btn-outline-light px-4 py-2">Tambah ke Favorit</button>
                        </form>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="<?= site_url('login') ?>" class="btn btn-outline-light px-4 py-2">Login untuk Favorit</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<section class="hero-box mt-4">
    <h3 class="mb-3">Deskripsi Lengkap</h3>
    <p class="text-soft mb-0"><?= esc($game['description'] ?? '-') ?></p>
</section>

<?php if (! empty($game['minimum_system_requirements']) && is_array($game['minimum_system_requirements'])): ?>
    <section class="hero-box mt-4">
        <h3 class="mb-3">Minimum System Requirements</h3>
        <div class="row g-3">
            <?php foreach ($game['minimum_system_requirements'] as $label => $value): ?>
                <div class="col-md-6">
                    <div class="p-3 rounded-3 border border-secondary-subtle h-100">
                        <small class="text-soft d-block"><?= esc(ucwords(str_replace('_', ' ', $label))) ?></small>
                        <strong><?= esc($value ?: '-') ?></strong>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>

<?php if (! empty($game['screenshots']) && is_array($game['screenshots'])): ?>
    <section class="hero-box mt-4">
        <h3 class="mb-3">Screenshots</h3>
        <div class="row g-3">
            <?php foreach ($game['screenshots'] as $shot): ?>
                <div class="col-md-6">
                    <img src="<?= esc($shot['image'] ?? '') ?>" alt="Screenshot <?= esc($game['title']) ?>" class="img-fluid rounded-3 border border-secondary-subtle w-100">
                </div>
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>

<section class="hero-box mt-4">
    <h3 class="mb-3">Review Komunitas</h3>

    <?php if ($isLoggedIn): ?>
        <form action="<?= site_url($userReview ? 'games/review/update' : 'games/review') ?>" method="post" class="mb-4">
            <?= csrf_field() ?>
            <input type="hidden" name="game_id" value="<?= esc($game['id']) ?>">
            <input type="hidden" name="game_title" value="<?= esc($game['title']) ?>">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Rating</label>
                    <select name="rating" class="form-select" required>
                        <option value="">Pilih Rating</option>
                        <option value="5" <?= (string) ($userReview['rating'] ?? '') === '5' ? 'selected' : '' ?>>5 - Sangat Bagus</option>
                        <option value="4" <?= (string) ($userReview['rating'] ?? '') === '4' ? 'selected' : '' ?>>4 - Bagus</option>
                        <option value="3" <?= (string) ($userReview['rating'] ?? '') === '3' ? 'selected' : '' ?>>3 - Cukup</option>
                        <option value="2" <?= (string) ($userReview['rating'] ?? '') === '2' ? 'selected' : '' ?>>2 - Kurang</option>
                        <option value="1" <?= (string) ($userReview['rating'] ?? '') === '1' ? 'selected' : '' ?>>1 - Buruk</option>
                    </select>
                </div>
                <div class="col-md-9">
                    <label class="form-label">Komentar</label>
                    <textarea name="comment" rows="3" class="form-control" placeholder="Bagikan pengalamanmu memainkan game ini..."><?= esc($userReview['comment'] ?? '') ?></textarea>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-electric px-4"><?= $userReview ? 'Perbarui Review' : 'Kirim Review' ?></button>
                </div>
            </div>
        </form>
        <?php if ($userReview): ?>
            <form action="<?= site_url('games/review/delete') ?>" method="post" class="mb-4">
                <?= csrf_field() ?>
                <input type="hidden" name="game_id" value="<?= esc($game['id']) ?>">
                <button type="submit" class="btn btn-outline-danger px-4">Hapus Review Saya</button>
            </form>
        <?php endif; ?>
    <?php else: ?>
        <div class="alert alert-info border-0">Login terlebih dahulu untuk menulis review dan memberi rating.</div>
    <?php endif; ?>

    <?php if (empty($reviews)): ?>
        <p class="text-soft mb-0">Belum ada review untuk game ini.</p>
    <?php else: ?>
        <div class="d-grid gap-3">
            <?php foreach ($reviews as $review): ?>
                <article class="p-3 rounded-3 border border-secondary-subtle">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <strong><?= esc($review['user_name'] ?? 'Pengguna') ?></strong>
                        <span class="badge badge-neon">Rating <?= esc($review['rating']) ?>/5</span>
                    </div>
                    <p class="mb-0 text-soft"><?= esc($review['comment'] ?: 'Tidak ada komentar.') ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
<?= $this->endSection() ?>
