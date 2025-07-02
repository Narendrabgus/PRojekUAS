<?= $this->extend('layout_clear') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="card shadow-lg border-0 rounded-lg mt-5">
                <div class="card-header"><h3 class="text-center font-weight-light my-4">Login Bengkel Motor</h3></div>
                <div class="card-body">

                    <?php if (session()->getFlashdata('failed')): ?>
                        <div class="alert alert-danger" role="alert">
                            <?= session()->getFlashdata('failed') ?>
                        </div>
                    <?php endif; ?>

                    <?= form_open('login') ?>
                        <div class="form-floating mb-3">
                            <input class="form-control" id="inputUsername" type="text" name="username" placeholder="Username" required />
                            <label for="inputUsername">Username</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control" id="inputPassword" type="password" name="password" placeholder="Password" required />
                            <label for="inputPassword">Password</label>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </div>
                    <?= form_close() ?>
                    
                </div>
                <div class="card-footer text-center py-3">
                    <div class="small">Belum punya akun? Hubungi Admin!</div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>