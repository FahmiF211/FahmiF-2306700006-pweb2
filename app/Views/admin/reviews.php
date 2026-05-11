<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<h1 class="mb-4">Moderasi Review</h1>
<div class="panel p-3 table-responsive">
    <table class="table table-dark table-striped align-middle mb-0">
        <thead><tr><th>Pengguna</th><th>Game</th><th>Rating</th><th>Komentar</th><th>Aksi</th></tr></thead>
        <tbody>
        <?php foreach ($reviews as $review): ?>
            <tr>
                <td><?= esc($review['user_name'] ?? '-') ?><br><small class="text-soft"><?= esc($review['user_email'] ?? '-') ?></small></td>
                <td><?= esc($review['game_title']) ?></td>
                <td><?= esc($review['rating']) ?>/5</td>
                <td><?= esc($review['comment'] ?: '-') ?></td>
                <td>
                    <form action="<?= base_url('/admin/reviews/delete') ?>" method="post">
                        <?= csrf_field() ?>
                        <input type="hidden" name="review_id" value="<?= esc($review['id']) ?>">
                        <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
