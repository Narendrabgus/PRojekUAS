<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Core</div>
                <a class="nav-link" href="index.html">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Home
                </a>
                
                <div class="sb-sidenav-menu-heading">Menu</div>

                <?php if (session()->get('role') == 'admin') : ?>
                <a class="nav-link <?= (uri_string() == 'layanan') ? 'active' : '' ?>" href="<?= base_url('layanan') ?>">
                    <div class="sb-nav-link-icon"><i class="fas fa-wrench"></i></div>
                    Manajemen Layanan
                </a>
                <?php endif; ?>

                <a class="nav-link <?= (uri_string() == 'riwayat') ? 'active' : '' ?>" href="<?= base_url('riwayat') ?>">
                    <div class="sb-nav-link-icon"><i class="fas fa-history"></i></div>
                    Riwayat Pilihan
                </a>

            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            <?= session()->get('role') ?>
        </div>
    </nav>
</div>