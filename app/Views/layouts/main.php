<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'NexaGames') ?></title>
    <meta name="description" content="Portal katalog game gratis modern dengan data game real-time.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&family=Rajdhani:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --bg-main:#060b1a; --bg-surface:#101933; --text-main:#ecf3ff; --text-soft:#9da9c9; --electric-blue:#24a9ff; --neon-purple:#8658ff; --cyber-cyan:#27e6ff; --line:rgba(255,255,255,.11); }
        body { background:radial-gradient(circle at 8% 5%, rgba(36,169,255,.28), transparent 38%),radial-gradient(circle at 89% 10%, rgba(134,88,255,.28), transparent 42%),linear-gradient(180deg,#060b1a,#070d20 40%,#080f24); color:var(--text-main); font-family:"Rajdhani",sans-serif; min-height:100vh; }
        h1,h2,h3,h4,h5 { font-family:"Orbitron",sans-serif; letter-spacing:.6px; }
        .navbar { background:linear-gradient(145deg, rgba(12,20,44,.72), rgba(8,13,30,.72)); backdrop-filter:blur(16px); border-bottom:1px solid rgba(255,255,255,.14); transition:background .35s ease, border-color .35s ease; }
        .navbar.navbar-scrolled { background:linear-gradient(145deg, rgba(12,20,44,.56), rgba(8,13,30,.56)); border-color:rgba(39,230,255,.2); }
        .brand-neon { color:var(--text-main); text-decoration:none; font-family:"Orbitron",sans-serif; font-size:1.3rem; }
        .brand-neon span { color:var(--electric-blue); }
        .nav-pill { color:var(--text-soft); text-decoration:none; border:1px solid rgba(255,255,255,.14); border-radius:999px; padding:.4rem .95rem; font-weight:600; transition:all .25s ease; }
        .nav-pill:hover,.nav-pill.active { color:#fff; border-color:rgba(39,230,255,.46); background:linear-gradient(90deg, rgba(36,169,255,.22), rgba(134,88,255,.2)); box-shadow:0 0 20px rgba(39,230,255,.18); }
        .hero-box,.card-game,.glass-box { border:1px solid var(--line); background:linear-gradient(155deg, rgba(18,28,56,.9), rgba(10,16,35,.92)); box-shadow:0 14px 35px rgba(0,0,0,.35); }
        .hero-box,.glass-box { border-radius:22px; padding:2rem; }
        .card-game { border-radius:18px; overflow:hidden; transition:transform .32s ease,border-color .32s ease,box-shadow .32s ease; position:relative; }
        .card-game::before { content:""; position:absolute; inset:-1px; border-radius:18px; background:linear-gradient(120deg, rgba(39,230,255,.35), rgba(134,88,255,.2), transparent 55%); opacity:0; transition:opacity .35s ease; pointer-events:none; }
        .card-game:hover { transform:translateY(-8px); border-color:rgba(36,169,255,.7); box-shadow:0 0 0 1px rgba(36,169,255,.26), 0 18px 40px rgba(0,0,0,.46), 0 0 42px rgba(134,88,255,.24); }
        .card-game:hover::before { opacity:1; }
        .card-game .card-thumb { width:100%; height:200px; object-fit:cover; transform:scale(1); transition:transform .4s ease; }
        .card-game:hover .card-thumb { transform:scale(1.08); }
        .card-body-modern { display:flex; flex-direction:column; height:calc(100% - 200px); }
        .card-desc { min-height:72px; }
        .rating-track { width:88px; height:8px; border-radius:999px; background:rgba(255,255,255,.12); overflow:hidden; }
        .rating-fill { height:100%; border-radius:inherit; background:linear-gradient(90deg,#24a9ff,#27e6ff); }
        .platform-pill { border:1px solid rgba(39,230,255,.35); border-radius:999px; padding:.2rem .55rem; font-size:.8rem; color:#cff8ff; background:rgba(39,230,255,.1); }
        .card-link { color:inherit; text-decoration:none; display:block; height:100%; }
        .badge-neon,.btn-electric { color:#fff; background:linear-gradient(90deg, var(--electric-blue), var(--neon-purple)); }
        .badge-neon { border-radius:999px; font-weight:700; letter-spacing:.4px; }
        .btn-electric { border:none; font-weight:700; border-radius:12px; padding:.65rem 1.1rem; transition:transform .22s ease, box-shadow .22s ease, filter .22s ease; }
        .btn-electric:hover { color:#fff; transform:translateY(-2px); box-shadow:0 10px 25px rgba(36,169,255,.32); filter:brightness(1.07); }
        .btn-ghost-neon { border:1px solid rgba(39,230,255,.42); color:#dffaff; background:rgba(8,13,30,.5); border-radius:12px; padding:.65rem 1.1rem; font-weight:700; }
        .btn-ghost-neon:hover { color:#fff; box-shadow:0 0 25px rgba(39,230,255,.2); background:rgba(39,230,255,.14); }
        .text-soft { color:var(--text-soft); }
        .form-control,.form-select { background-color:rgba(12,15,24,.9); border-color:var(--line); color:var(--text-main); }
        .form-control:focus,.form-select:focus { background-color:rgba(12,15,24,1); color:var(--text-main); border-color:rgba(30,157,255,.6); box-shadow:0 0 0 .2rem rgba(30,157,255,.2); }
        .form-label { color:var(--text-soft); font-weight:600; }
        .alert { border:1px solid var(--line); border-radius:12px; }
        .alert-danger { background:rgba(220,53,69,.15); color:#ffd6db; }
        .alert-success { background:rgba(25,135,84,.16); color:#d4f7e6; }
        .alert-warning { background:rgba(255,193,7,.14); color:#ffeeb9; }
        .alert-info { background:rgba(13,202,240,.13); color:#cff7ff; }
        .footer-main { margin-top:2.5rem; border-top:1px solid var(--line); background:linear-gradient(180deg, rgba(9,15,34,.8), rgba(6,11,26,.95)); }
        .fade-in-up { opacity:0; transform:translateY(16px); animation:fadeInUp .7s ease forwards; }
        .fade-in-up.delay-1 { animation-delay:.14s; }
        .fade-in-up.delay-2 { animation-delay:.28s; }
        .skeleton { background:linear-gradient(100deg, rgba(255,255,255,.06) 35%, rgba(255,255,255,.16) 50%, rgba(255,255,255,.06) 65%) rgba(255,255,255,.06); background-size:300% 100%; animation:shimmer 1.25s infinite; border-radius:10px; }
        .skeleton-card { border:1px solid var(--line); border-radius:18px; padding:1rem; background:rgba(15,24,48,.5); }
        .skeleton-thumb { height:160px; }
        .skeleton-line { height:12px; margin-top:.7rem; }
        .skeleton-line.short { width:55%; }
        #app-loading { position:fixed; inset:0; z-index:9999; display:flex; align-items:center; justify-content:center; background:rgba(9,10,16,.95); }
        .loader-ring { width:54px; height:54px; border:4px solid rgba(255,255,255,.12); border-top-color:var(--electric-blue); border-radius:50%; animation:spin 1s linear infinite; }
        @keyframes spin { to { transform:rotate(360deg); } }
        @keyframes shimmer { to { background-position:-200% 0; } }
        @keyframes fadeInUp { to { opacity:1; transform:translateY(0); } }
        @media (max-width: 991.98px) { .hero-box,.glass-box { padding:1.25rem; } .card-game .card-thumb { height:180px; } }
    </style>
</head>
<body>
<?php $path = trim(service('uri')->getPath(), '/'); ?>
<div id="app-loading"><div class="text-center"><div class="loader-ring mx-auto mb-2"></div><small class="text-soft">Memuat data...</small></div></div>

<nav class="navbar navbar-expand-lg sticky-top" id="mainNavbar">
    <div class="container py-2 d-flex justify-content-between align-items-center gap-3 flex-wrap">
        <a class="brand-neon" href="<?= site_url('/') ?>">Nexa<span>Games</span></a>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <a class="nav-pill <?= $path === '' ? 'active' : '' ?>" href="<?= site_url('/') ?>">Beranda</a>
            <a class="nav-pill <?= str_starts_with($path, 'games') ? 'active' : '' ?>" href="<?= site_url('games') ?>">Daftar Game</a>
            <?php if (session()->get('user_id')): ?>
                <a class="nav-pill <?= $path === 'favorites' ? 'active' : '' ?>" href="<?= site_url('favorites') ?>">Favorit</a>
                <a class="nav-pill <?= $path === 'profile' ? 'active' : '' ?>" href="<?= site_url('profile') ?>">Profil</a>
                <?php if ((string) session()->get('user_role') === 'admin'): ?>
                    <a class="nav-pill <?= str_starts_with($path, 'admin') ? 'active' : '' ?>" href="<?= site_url('admin/dashboard') ?>">Admin</a>
                <?php endif; ?>
            <?php else: ?>
                <a class="nav-pill <?= $path === 'login' ? 'active' : '' ?>" href="<?= site_url('login') ?>">Login</a>
                <a class="nav-pill <?= $path === 'register' ? 'active' : '' ?>" href="<?= site_url('register') ?>">Daftar</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<main class="container py-4 py-lg-5">
    <?= $this->renderSection('content') ?>
</main>

<footer class="footer-main py-4">
    <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
        <p class="mb-0 text-soft">&copy; <?= date('Y') ?> NexaGames - Portal katalog game gratis.</p>
        <small class="text-soft">Didukung server game dan katalog game real-time.</small>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
window.addEventListener('load', function () {
  var loading = document.getElementById('app-loading');
  if (loading) loading.style.display = 'none';
});
document.addEventListener('submit', function () {
  var loading = document.getElementById('app-loading');
  if (loading) loading.style.display = 'flex';
});
window.addEventListener('scroll', function () {
  var navbar = document.getElementById('mainNavbar');
  if (!navbar) return;
  if (window.scrollY > 16) {
    navbar.classList.add('navbar-scrolled');
  } else {
    navbar.classList.remove('navbar-scrolled');
  }
});
</script>
</body>
</html>
