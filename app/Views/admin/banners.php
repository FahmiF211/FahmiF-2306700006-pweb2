<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<h1 class="mb-4">Manajemen Banner</h1>

<div class="panel p-3 mb-4">
    <h5 class="mb-3">Tambah Banner</h5>
    <form action="<?= base_url('/admin/banners') ?>" method="post" enctype="multipart/form-data" class="row g-3">
        <?= csrf_field() ?>
        <div class="col-md-6"><input class="form-control" name="title" placeholder="Judul" required></div>
        <div class="col-md-6"><input class="form-control" name="subtitle" placeholder="Subjudul"></div>
        <div class="col-md-4"><input class="form-control" name="button_text" placeholder="Teks Tombol"></div>
        <div class="col-md-4"><input class="form-control" name="button_url" placeholder="URL Tombol"></div>
        <div class="col-md-4"><input type="file" class="form-control" name="image" accept="image/*"></div>
        <div class="col-md-4"><select name="is_active" class="form-select"><option value="1">Aktif</option><option value="0">Nonaktif</option></select></div>
        <div class="col-12"><button class="btn btn-primary" type="submit">Simpan Banner</button></div>
    </form>
</div>

<div class="panel p-3 table-responsive">
    <table class="table table-dark table-striped align-middle mb-0">
        <thead><tr><th>Banner</th><th>Konten</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
        <?php foreach ($banners as $banner): ?>
            <tr>
                <td style="width:180px;"><?php if ($banner['image']): ?><img src="<?= base_url($banner['image']) ?>" alt="banner" class="img-fluid rounded"><?php else: ?><span class="text-soft">Tanpa gambar</span><?php endif; ?></td>
                <td>
                    <strong><?= esc($banner['title']) ?></strong>
                    <div class="text-soft"><?= esc($banner['subtitle']) ?></div>
                    <div><small><?= esc($banner['button_text']) ?> - <?= esc($banner['button_url']) ?></small></div>
                </td>
                <td><?= $banner['is_active'] ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Nonaktif</span>' ?></td>
                <td>
                    <form action="<?= base_url('/admin/banners/update/' . $banner['id']) ?>" method="post" enctype="multipart/form-data" class="mb-2 d-grid gap-2">
                        <?= csrf_field() ?>
                        <input class="form-control form-control-sm" name="title" value="<?= esc($banner['title']) ?>" required>
                        <input class="form-control form-control-sm" name="subtitle" value="<?= esc($banner['subtitle']) ?>">
                        <input class="form-control form-control-sm" name="button_text" value="<?= esc($banner['button_text']) ?>">
                        <input class="form-control form-control-sm" name="button_url" value="<?= esc($banner['button_url']) ?>">
                        <input type="file" class="form-control form-control-sm" name="image" accept="image/*">
                        <select name="is_active" class="form-select form-select-sm"><option value="1" <?= (int) $banner['is_active'] === 1 ? 'selected' : '' ?>>Aktif</option><option value="0" <?= (int) $banner['is_active'] === 0 ? 'selected' : '' ?>>Nonaktif</option></select>
                        <button class="btn btn-sm btn-outline-info" type="submit">Perbarui</button>
                    </form>
                    <form action="<?= base_url('/admin/banners/delete') ?>" method="post">
                        <?= csrf_field() ?>
                        <input type="hidden" name="banner_id" value="<?= esc($banner['id']) ?>">
                        <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
