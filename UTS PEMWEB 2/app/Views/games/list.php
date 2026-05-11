<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="hero-box mb-4">
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3">
        <div>
            <span class="badge badge-neon px-3 py-2 mb-3">Daftar Game NexaGames</span>
            <h1 class="mb-2">Temukan Game Favoritmu</h1>
            <p class="text-soft mb-0">Gunakan pencarian, filter, dan urutan untuk menemukan game yang paling cocok.</p>
        </div>
        <div class="text-soft">Menampilkan <?= esc(count($games)) ?> dari <?= esc($totalFilteredGames ?? count($games)) ?> game</div>
    </div>
</section>

<section class="hero-box mb-4">
    <form method="get" action="<?= site_url('games') ?>" class="row g-3">
        <div class="col-12 col-lg-4">
            <label class="form-label">Cari Nama Game</label>
            <input type="text" name="search" class="form-control" value="<?= esc($filters['search']) ?>" placeholder="Contoh: Warframe">
        </div>
        <div class="col-6 col-lg-2">
            <label class="form-label">Genre</label>
            <select name="genre" class="form-select">
                <option value="">Semua Genre</option>
                <?php foreach ($genres as $genre): ?>
                    <option value="<?= esc($genre) ?>" <?= $filters['genre'] === $genre ? 'selected' : '' ?>><?= esc(ucwords(str_replace('-', ' ', $genre))) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-6 col-lg-2">
            <label class="form-label">Platform</label>
            <select name="platform" class="form-select">
                <?php foreach ($platforms as $platform): ?>
                    <option value="<?= esc($platform) ?>" <?= $filters['platform'] === $platform ? 'selected' : '' ?>><?= esc(strtoupper($platform)) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-6 col-lg-2">
            <label class="form-label">Sort</label>
            <select name="sort" class="form-select">
                <?php foreach ($sorts as $sort): ?>
                    <option value="<?= esc($sort) ?>" <?= $filters['sort'] === $sort ? 'selected' : '' ?>><?= esc(ucwords(str_replace('-', ' ', $sort))) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-6 col-lg-2 d-grid align-self-end">
            <button type="submit" class="btn btn-electric">Terapkan Filter</button>
        </div>
    </form>
</section>

<?php if ($errorApi): ?>
    <div class="alert alert-danger" role="alert">
        <strong>Gagal memuat data game.</strong> <?= esc($apiMessage) ?>
    </div>
<?php endif; ?>

<?php if (empty($games)): ?>
    <section class="hero-box text-center py-5">
        <h3 class="mb-2">Data game tidak ditemukan</h3>
        <p class="text-soft mb-0">Coba ubah kata kunci pencarian atau kombinasi filter yang kamu pilih.</p>
    </section>
<?php else: ?>
    <section class="fade-in-up">
        <div class="row g-4">
            <?php foreach ($games as $game): ?>
                <div class="col-sm-6 col-xl-4">
                    <a href="<?= site_url('games/detail/' . $game['id']) ?>" class="card-link">
                        <?php $rating = number_format(3.8 + (($game['id'] ?? 1) % 12) / 10, 1); ?>
                        <article class="card-game h-100">
                            <img src="<?= esc($game['thumbnail']) ?>" class="card-thumb" alt="<?= esc($game['title']) ?>">
                            <div class="p-3 p-lg-4 card-body-modern">
                                <h5 class="mb-2"><?= esc($game['title']) ?></h5>
                                <p class="text-soft mb-3 card-desc"><?= esc($game['short_description']) ?></p>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge text-bg-dark border rounded-pill px-3 py-2"><?= esc($game['genre']) ?></span>
                                    <span class="platform-pill"><?= esc(str_replace('PC (Windows)', 'PC', $game['platform'])) ?></span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <small class="text-soft">Rating <?= esc($rating) ?>/5</small>
                                    <div class="rating-track"><div class="rating-fill" style="width:<?= esc(((float) $rating / 5) * 100) ?>%"></div></div>
                                </div>
                                <span class="btn btn-electric w-100 mt-auto">Lihat Detail Game</span>
                            </div>
                        </article>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="row g-4 mt-1 d-none" id="gameSkeleton">
            <?php for ($i = 0; $i < 3; $i++): ?>
                <div class="col-sm-6 col-xl-4">
                    <div class="skeleton-card">
                        <div class="skeleton skeleton-thumb"></div>
                        <div class="skeleton skeleton-line"></div>
                        <div class="skeleton skeleton-line short"></div>
                    </div>
                </div>
            <?php endfor; ?>
        </div>

        <?php if (($pagination['totalPages'] ?? 1) > 1): ?>
            <?php
            $currentPage = (int) ($pagination['page'] ?? 1);
            $totalPages = (int) ($pagination['totalPages'] ?? 1);
            $query = $filters;
            ?>
            <nav class="mt-4" aria-label="Pagination game">
                <ul class="pagination justify-content-center">
                    <?php
                    $prevPage = max(1, $currentPage - 1);
                    $query['page'] = $prevPage;
                    ?>
                    <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= $currentPage <= 1 ? '#' : site_url('games') . '?' . http_build_query($query) ?>">Sebelumnya</a>
                    </li>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <?php $query['page'] = $i; ?>
                        <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                            <a class="page-link" href="<?= site_url('games') . '?' . http_build_query($query) ?>"><?= esc($i) ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php
                    $nextPage = min($totalPages, $currentPage + 1);
                    $query['page'] = $nextPage;
                    ?>
                    <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= $currentPage >= $totalPages ? '#' : site_url('games') . '?' . http_build_query($query) ?>">Berikutnya</a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    </section>
<?php endif; ?>

<script>
document.addEventListener('submit', function (event) {
  if (!event.target || event.target.getAttribute('method') !== 'get') return;
  var skeleton = document.getElementById('gameSkeleton');
  if (skeleton) skeleton.classList.remove('d-none');
});
</script>
<?= $this->endSection() ?>
