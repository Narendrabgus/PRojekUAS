<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<h1 class="mt-4">Pilih Layanan</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active">Home</li>
</ol>

<?php if (session()->getFlashData('success')) : ?>
    <div class="alert alert-success" role="alert">
        <?= session()->getFlashData('success') ?>
    </div>
<?php endif; ?>

<div class="row">
    <?php foreach ($layanan as $item) : ?>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <h5 class="card-title"><?= $item['nama'] ?></h5>
                    <p><?= number_to_currency($item['harga'], 'IDR') ?></p>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                     <?= form_open('riwayat/add', ['class' => 'd-inline']) ?>
                        <?= form_hidden('id', $item['id']); ?>
                        <?= form_hidden('nama', $item['nama']); ?>
                        <?= form_hidden('harga', $item['harga']); ?>
                        <button type="submit" class="btn btn-sm text-white stretched-link">Pilih Layanan Ini</button>
                    <?= form_close() ?>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
    <?php endforeach ?>
</div>

<?= $this->endSection() ?>