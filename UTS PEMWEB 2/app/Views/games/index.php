<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php if (! empty($banners)): ?>
    <section class="hero-box mb-4">
        <div id="homeBanner" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner rounded-3">
                <?php foreach ($banners as $index => $banner): ?>
                    <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                        <?php if (! empty($banner['image'])): ?>
                            <img src="<?= base_url($banner['image']) ?>" class="d-block w-100" alt="<?= esc($banner['title']) ?>" style="max-height:340px;object-fit:cover;">
                        <?php endif; ?>
                        <div class="carousel-caption text-start" style="background:rgba(0,0,0,.45);border-radius:10px;padding:1rem;">
                            <h3><?= esc($banner['title']) ?></h3>
                            <p><?= esc($banner['subtitle']) ?></p>
                            <?php if (! empty($banner['button_url'])): ?>
                                <a href="<?= esc($banner['button_url']) ?>" class="btn btn-electric"><?= esc($banner['button_text'] ?: 'Lihat Sekarang') ?></a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#homeBanner" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
            <button class="carousel-control-next" type="button" data-bs-target="#homeBanner" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
        </div>
    </section>
<?php endif; ?>

<section class="hero-box mb-4 mb-lg-5 fade-in-up" style="background:radial-gradient(circle at 15% 20%, rgba(36,169,255,.24), transparent 46%),radial-gradient(circle at 82% 4%, rgba(134,88,255,.27), transparent 50%),linear-gradient(145deg, rgba(17,28,58,.96), rgba(11,18,40,.95));">
    <div class="row align-items-center g-4">
        <div class="col-lg-7">
            <span class="badge badge-neon px-3 py-2 mb-3">NexaGames | Katalog Game Premium</span>
            <h1 class="mb-3">Jelajahi Dunia Game Gratis Paling Seru</h1>
            <p class="text-soft mb-4">Temukan data game multiplayer, shooter, dan MMORPG dalam satu portal modern yang cepat dan interaktif.</p>
            <div class="d-flex flex-wrap gap-2 mb-4">
                <span class="platform-pill">409+ Game Gratis</span>
                <span class="platform-pill">Multiplayer</span>
                <span class="platform-pill">MMORPG</span>
                <span class="platform-pill">Shooter</span>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="#game-populer" class="btn btn-electric">Jelajahi Game</a>
                <a href="#trending-games" class="btn btn-ghost-neon">Game Populer</a>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="glass-box p-3">
                <img src="<?= esc($games[0]['thumbnail'] ?? 'https://www.freetogame.com/g/540/thumbnail.jpg') ?>" alt="Showcase game" class="img-fluid rounded-4" style="width:100%;height:240px;object-fit:cover;">
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <strong><?= esc($games[0]['title'] ?? 'Showcase Game') ?></strong>
                    <span class="platform-pill">Live</span>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>

<?php if ($errorApi): ?>
    <div class="alert alert-warning"><strong>Gagal memuat data game.</strong> <?= esc($apiMessage ?? 'Silakan coba lagi beberapa saat lagi.') ?></div>
<?php endif; ?>

<?php
$genreCounts = [];
foreach ($games as $item) {
    $genre = $item['genre'] ?? 'Lainnya';
    $genreCounts[$genre] = ($genreCounts[$genre] ?? 0) + 1;
}
arsort($genreCounts);
$topGenres = array_slice($genreCounts, 0, 6, true);

$latestGames = $games;
usort($latestGames, static fn($a, $b) => strcmp($b['release_date'] ?? '', $a['release_date'] ?? ''));
$latestGames = array_slice($latestGames, 0, 3);
$trendingGames = array_slice($games, 0, 4);
?>

<section class="mb-4 fade-in-up delay-1" id="trending-games">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Trending Games</h2>
        <span class="text-soft">Paling ramai dimainkan minggu ini</span>
    </div>
    <div class="row g-3">
        <?php foreach ($trendingGames as $trend): ?>
            <div class="col-6 col-lg-3">
                <a href="<?= site_url('games/detail/' . $trend['id']) ?>" class="card-link">
                    <div class="glass-box p-2 h-100">
                        <img src="<?= esc($trend['thumbnail']) ?>" alt="<?= esc($trend['title']) ?>" class="img-fluid rounded-3" style="height:140px;width:100%;object-fit:cover;">
                        <div class="pt-2 px-1">
                            <h6 class="mb-1"><?= esc($trend['title']) ?></h6>
                            <small class="text-soft"><?= esc($trend['genre']) ?></small>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="glass-box mb-4 fade-in-up delay-2">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Genre Populer</h2>
        <span class="text-soft">Berdasarkan katalog game aktif</span>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <?php foreach ($topGenres as $genreName => $totalGenre): ?>
            <span class="platform-pill"><?= esc($genreName) ?> (<?= esc($totalGenre) ?>)</span>
        <?php endforeach; ?>
    </div>
</section>

<section id="game-populer" class="fade-in-up">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Game Populer</h2>
        <span class="text-soft">Menampilkan <?= esc($displayedGame ?? count($games)) ?> dari <?= esc($totalGame) ?> game</span>
    </div>
    <?php if (empty($games)): ?>
        <div class="hero-box text-center py-5">
            <h3 class="mb-2">Belum ada data game</h3>
            <p class="text-soft mb-0">Data populer tidak tersedia saat ini. Coba muat ulang beberapa saat lagi.</p>
        </div>
    <?php else: ?>
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

        <?php if (($totalPages ?? 1) > 1): ?>
            <nav class="mt-4" aria-label="Navigasi halaman game">
                <ul class="pagination justify-content-center flex-wrap gap-1">
                    <?php $prevPage = max(1, (int) $currentPage - 1); ?>
                    <li class="page-item <?= (int) $currentPage <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= (int) $currentPage > 1 ? site_url('/') . '?page=' . $prevPage : '#' ?>">Sebelumnya</a>
                    </li>

                    <?php for ($page = 1; $page <= (int) $totalPages; $page++): ?>
                        <li class="page-item <?= (int) $currentPage === $page ? 'active' : '' ?>">
                            <a class="page-link" href="<?= site_url('/') . '?page=' . $page ?>"><?= $page ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php $nextPage = min((int) $totalPages, (int) $currentPage + 1); ?>
                    <li class="page-item <?= (int) $currentPage >= (int) $totalPages ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= (int) $currentPage < (int) $totalPages ? site_url('/') . '?page=' . $nextPage : '#' ?>">Berikutnya</a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</section>

<section class="row g-4 mt-1 mb-4">
    <div class="col-lg-7">
        <div class="glass-box h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="mb-0">Game Terbaru</h2>
                <span class="text-soft">Rilis paling baru</span>
            </div>
            <?php foreach ($latestGames as $latest): ?>
                <a href="<?= site_url('games/detail/' . $latest['id']) ?>" class="card-link d-flex justify-content-between align-items-center py-2 border-bottom border-secondary-subtle">
                    <div>
                        <strong><?= esc($latest['title']) ?></strong>
                        <div class="text-soft small"><?= esc($latest['genre']) ?></div>
                    </div>
                    <span class="platform-pill"><?= esc($latest['release_date'] ?? 'TBA') ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="glass-box h-100">
            <h2 class="mb-3">Statistik Website</h2>
            <div class="row g-3 text-center">
                <div class="col-6"><div class="p-3 rounded-4" style="background:rgba(36,169,255,.1)"><h4 class="mb-1"><?= esc($totalGame) ?>+</h4><small class="text-soft">Total Game</small></div></div>
                <div class="col-6"><div class="p-3 rounded-4" style="background:rgba(134,88,255,.12)"><h4 class="mb-1"><?= esc(count($topGenres)) ?></h4><small class="text-soft">Genre Aktif</small></div></div>
                <div class="col-6"><div class="p-3 rounded-4" style="background:rgba(39,230,255,.1)"><h4 class="mb-1">24/7</h4><small class="text-soft">Update Data</small></div></div>
                <div class="col-6"><div class="p-3 rounded-4" style="background:rgba(255,255,255,.08)"><h4 class="mb-1">Fast</h4><small class="text-soft">Akses Katalog</small></div></div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
