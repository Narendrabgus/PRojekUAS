<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<h1 class="mt-4">Riwayat Pilihan Layanan</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Home</a></li>
    <li class="breadcrumb-item active">Riwayat</li>
</ol>

<div class="card mb-4">
    <div class="card-header">
        <i class="fas fa-table me-1"></i>
        Daftar Layanan yang Dipilih
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama Layanan</th>
                        <th>Harga Satuan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($items)) : foreach ($items as $item) : ?>
                    <tr>
                        <td><?= $item['name'] ?></td>
                        <td><?= number_to_currency($item['price'], 'IDR') ?></td>
                        <td>
                            <a href="<?= base_url('riwayat/delete/' . $item['rowid']) ?>" class="btn btn-danger btn-sm">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr>
                        <td colspan="3" class="text-center">Belum ada layanan yang dipilih.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2" class="text-right">Total Biaya</th>
                        <th><?= number_to_currency($total, 'IDR') ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="mt-3 text-end">
            <a href="<?= base_url('riwayat/clear') ?>" class="btn btn-warning">Kosongkan Riwayat</a>
            <?php if (!empty($items)) : ?>
                <a href="<?= base_url('checkout') ?>" class="btn btn-success">Lanjutkan Checkout</a>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>