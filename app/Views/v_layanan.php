<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<h1 class="mt-4">Manajemen Layanan</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item active">Daftar Layanan</li>
</ol>

<?php if(session()->getFlashdata('success')): ?>
<div class="alert alert-success" role="alert">
    <?= session()->getFlashdata('success') ?>
</div>
<?php endif; ?>

<div class="card mb-4">
    <div class="card-header">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fas fa-plus"></i> Tambah Layanan
        </button>
    </div>
    <div class="card-body">
        <table id="datatablesSimple">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th>Estimasi (Menit)</th>
                    <th>Foto</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($layanan as $index => $item): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= $item['nama'] ?></td>
                    <td><?= number_to_currency($item['harga'], 'IDR') ?></td>
                    <td><?= $item['estimasi_menit'] ?></td>
                    <td><img src="<?= base_url('img/'.$item['foto']) ?>" alt="" width="100"></td>
                    <td>
                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal-<?= $item['id'] ?>">Ubah</button>
                        <a href="<?= base_url('layanan/delete/'.$item['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus layanan ini?')">Hapus</a>
                    </td>
                </tr>

                <div class="modal fade" id="editModal-<?= $item['id'] ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Data Layanan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <?= form_open_multipart(base_url('layanan/edit/' . $item['id'])) ?>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama Layanan</label>
                                    <input type="text" name="nama" class="form-control" value="<?= $item['nama'] ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="harga" class="form-label">Harga</label>
                                    <input type="number" name="harga" class="form-control" value="<?= $item['harga'] ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="estimasi_menit" class="form-label">Estimasi (Menit)</label>
                                    <input type="number" name="estimasi_menit" class="form-control" value="<?= $item['estimasi_menit'] ?>" required>
                                </div>
                                <img src="<?= base_url('img/'.$item['foto']) ?>" width="100">
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="check" value="1" id="check-<?= $item['id'] ?>">
                                    <label class="form-check-label" for="check-<?= $item['id'] ?>">Ceklis jika ingin ganti foto</label>
                                </div>
                                <div class="mb-3">
                                    <label for="foto" class="form-label">Ganti Foto</label>
                                    <input type="file" name="foto" class="form-control">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                            <?= form_close() ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Layanan Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open_multipart(base_url('layanan/create')) ?>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Layanan</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="harga" class="form-label">Harga</label>
                    <input type="number" name="harga" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="estimasi_menit" class="form-label">Estimasi (Menit)</label>
                    <input type="number" name="estimasi_menit" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="foto" class="form-label">Foto</label>
                    <input type="file" name="foto" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
            <?= form_close() ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
<script>
    window.addEventListener('DOMContentLoaded', event => {
        const datatablesSimple = document.getElementById('datatablesSimple');
        if (datatablesSimple) {
            new simpleDatatables.DataTable(datatablesSimple);
        }
    });
</script>
<?= $this->endSection() ?>