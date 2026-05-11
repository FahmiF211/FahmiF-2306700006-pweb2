<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Admin NexaGames') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --bg:#090a10; --surface:#151a2b; --soft:#95a3c6; --line:rgba(255,255,255,.1); --blue:#1e9dff; --purple:#8a4dff; }
        body { background:var(--bg); color:#ecf3ff; }
        .admin-shell { min-height:100vh; }
        .admin-sidebar { background:#0f1320; border-right:1px solid var(--line); }
        .admin-link { display:block; color:var(--soft); text-decoration:none; padding:.7rem .9rem; border-radius:.6rem; font-weight:600; }
        .admin-link:hover,.admin-link.active { color:#fff; background:linear-gradient(90deg, rgba(30,157,255,.25), rgba(138,77,255,.2)); }
        .panel { background:var(--surface); border:1px solid var(--line); border-radius:14px; }
        .text-soft { color:var(--soft); }
        .form-control,.form-select { background:#0f1320; border-color:var(--line); color:#ecf3ff; }
        .form-control:focus,.form-select:focus { background:#0f1320; color:#ecf3ff; border-color:var(--blue); box-shadow:0 0 0 .2rem rgba(30,157,255,.2); }
        .btn-primary { border:none; background:linear-gradient(90deg, var(--blue), var(--purple)); }
        .alert { border-radius:12px; border:1px solid var(--line); }
    </style>
</head>
<body>
<?php $path = trim(service('uri')->getPath(), '/'); ?>
<div class="container-fluid admin-shell">
    <div class="row">
        <aside class="col-12 col-lg-3 col-xl-2 p-3 admin-sidebar">
            <h4 class="mb-3">Admin NexaGames</h4>
            <nav class="d-grid gap-2">
                <a class="admin-link <?= $path === 'admin/dashboard' ? 'active' : '' ?>" href="<?= base_url('/admin/dashboard') ?>">Dasbor</a>
                <a class="admin-link <?= $path === 'admin/users' ? 'active' : '' ?>" href="<?= base_url('/admin/users') ?>">Pengguna</a>
                <a class="admin-link <?= $path === 'admin/reviews' ? 'active' : '' ?>" href="<?= base_url('/admin/reviews') ?>">Ulasan</a>
                <a class="admin-link <?= $path === 'admin/banners' ? 'active' : '' ?>" href="<?= base_url('/admin/banners') ?>">Banner</a>
                <a class="admin-link" href="<?= base_url('/') ?>">Kembali ke Situs</a>
            </nav>
        </aside>
        <main class="col-12 col-lg-9 col-xl-10 p-3 p-lg-4">
            <?php if (session()->getFlashdata('error')): ?><div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div><?php endif; ?>
            <?php if (session()->getFlashdata('success')): ?><div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div><?php endif; ?>
            <?= $this->renderSection('content') ?>
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
