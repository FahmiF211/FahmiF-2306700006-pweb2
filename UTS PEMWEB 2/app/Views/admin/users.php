<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<h1 class="mb-4">Manajemen Pengguna</h1>
<div class="panel p-3 table-responsive">
    <table class="table table-dark table-striped align-middle mb-0">
        <thead><tr><th>Nama</th><th>Email</th><th>Peran</th><th>Aksi</th></tr></thead>
        <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= esc($user['name']) ?></td>
                <td><?= esc($user['email']) ?></td>
                <td>
                    <form action="<?= base_url('/admin/users/role') ?>" method="post" class="d-flex gap-2">
                        <?= csrf_field() ?>
                        <input type="hidden" name="user_id" value="<?= esc($user['id']) ?>">
                        <select name="role" class="form-select form-select-sm">
                            <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>user</option>
                            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>admin</option>
                        </select>
                        <button class="btn btn-sm btn-primary" type="submit">Simpan</button>
                    </form>
                </td>
                <td>
                    <form action="<?= base_url('/admin/users/delete') ?>" method="post">
                        <?= csrf_field() ?>
                        <input type="hidden" name="user_id" value="<?= esc($user['id']) ?>">
                        <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
