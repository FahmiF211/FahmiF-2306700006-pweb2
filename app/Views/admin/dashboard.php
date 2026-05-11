<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<h1 class="mb-4">Dashboard Admin</h1>
<div class="row g-3 mb-4">
    <div class="col-md-6 col-xl-3"><div class="panel p-3"><small class="text-soft">Total Pengguna</small><h3 class="mb-0"><?= esc($totalUsers) ?></h3></div></div>
    <div class="col-md-6 col-xl-3"><div class="panel p-3"><small class="text-soft">Total Favorit</small><h3 class="mb-0"><?= esc($totalFavorites) ?></h3></div></div>
    <div class="col-md-6 col-xl-3"><div class="panel p-3"><small class="text-soft">Total Review</small><h3 class="mb-0"><?= esc($totalReviews) ?></h3></div></div>
    <div class="col-md-6 col-xl-3"><div class="panel p-3"><small class="text-soft">Rata-rata Rating</small><h3 class="mb-0"><?= number_format($avgRating, 1) ?>/5</h3></div></div>
</div>
<div class="row g-3">
    <div class="col-lg-6">
        <div class="panel p-3">
            <h5>Review Terbaru</h5>
            <?php if (empty($latestReviews)): ?><p class="text-soft mb-0">Belum ada review.</p><?php else: foreach ($latestReviews as $review): ?>
                <div class="border-bottom border-secondary-subtle py-2">
                    <strong><?= esc($review['user_name'] ?? 'Pengguna') ?></strong> - <?= esc($review['game_title']) ?>
                    <div class="text-soft">Rating <?= esc($review['rating']) ?>/5</div>
                </div>
            <?php endforeach; endif; ?>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="panel p-3">
            <h5>Pengguna Terbaru</h5>
            <?php if (empty($latestUsers)): ?><p class="text-soft mb-0">Belum ada pengguna.</p><?php else: foreach ($latestUsers as $user): ?>
                <div class="border-bottom border-secondary-subtle py-2">
                    <strong><?= esc($user['name']) ?></strong>
                    <div class="text-soft"><?= esc($user['email']) ?></div>
                </div>
            <?php endforeach; endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
