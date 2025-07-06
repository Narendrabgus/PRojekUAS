<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<h1 class="mt-4">Checkout</h1>
<ol class="breadcrumb mb-4">
    <li class="breadcrumb-item"><a href="<?= base_url('riwayat') ?>">Riwayat</a></li>
    <li class="breadcrumb-item active">Checkout</li>
</ol>

<?= form_open('checkout/buy') // Perbaikan: Mengarahkan ke rute 'buy' di dalam grup checkout ?>
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Isi Data Diri & Alamat Jemput Motor</div>
            <div class="card-body">
                <input type="hidden" name="total_harga" id="total_harga" value="<?= $total ?>">
                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat Lengkap Penjemputan</label>
                    <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="kota_tujuan" class="form-label">Pilih Kota/Kabupaten</label>
                    <select class="form-control" id="kota_tujuan" name="kota_tujuan" required></select>
                </div>
                <div class="mb-3">
                    <label for="layanan_antar_jemput" class="form-label">Pilih Layanan Antar Jemput</label>
                    <select class="form-control" id="layanan_antar_jemput" name="layanan_antar_jemput" disabled required></select>
                </div>
                <div class="mb-3">
                    <label for="ongkir" class="form-label">Biaya Antar Jemput</label>
                    <input type="text" class="form-control" id="ongkir" name="ongkir" readonly>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">Ringkasan Pesanan</div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <?php foreach($items as $item): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?= $item['name'] ?>
                        <span><?= number_to_currency($item['price'], 'IDR') ?></span>
                    </li>
                    <?php endforeach; ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center fw-bold">
                        Subtotal Layanan
                        <span><?= number_to_currency($total, 'IDR') ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center fw-bold">
                        Biaya Jemput
                        <span id="ongkir_summary">Rp 0</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center h4 fw-bold">
                        Total Bayar
                        <span id="total_bayar"><?= number_to_currency($total, 'IDR') ?></span>
                    </li>
                </ul>
                <button type="submit" class="btn btn-success w-100 mt-3">Buat Pesanan</button>
            </div>
        </div>
    </div>
</div>
<?= form_close() ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        let ongkir = 0;
        const subtotal = <?= $total ?>;
        
        $('#kota_tujuan').select2({
            placeholder: 'Ketik nama kota/kabupaten...',
            ajax: {
                url: "<?= site_url('checkout/get-location') ?>", // PERBAIKAN DI SINI
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return { search: params.term };
                },
                processResults: function(data) {
                    return {
                        results: data.map(function(item) {
                            return { id: item.city_id, text: item.type + " " + item.city_name };
                        })
                    };
                },
                cache: true
            }
        });

        $('#kota_tujuan').on('change', function() {
            const id_kota = $(this).val();
            if (id_kota) {
                $.ajax({
                    url: "<?= site_url('checkout/get-cost') ?>", // PERBAIKAN DI SINI
                    type: 'GET',
                    data: { 'destination': id_kota },
                    dataType: 'json',
                    success: function(data) {
                        $('#layanan_antar_jemput').empty().prop('disabled', false);
                        $('#layanan_antar_jemput').append('<option value="">Pilih Layanan...</option>');
                        data.forEach(function(layanan) {
                            const text = layanan.service + ' (' + layanan.description + ') - ' + 'Rp ' + layanan.cost[0].value.toLocaleString('id-ID');
                            $('#layanan_antar_jemput').append(new Option(text, layanan.cost[0].value));
                        });
                    }
                });
            } else {
                $('#layanan_antar_jemput').empty().prop('disabled', true);
            }
        });
        
        $('#layanan_antar_jemput').on('change', function() {
            ongkir = parseInt($(this).val()) || 0;
            hitungTotal();
        });

        function hitungTotal() {
            const total_bayar = subtotal + ongkir;
            $('#ongkir').val(ongkir);
            $('#ongkir_summary').html("Rp " + ongkir.toLocaleString('id-ID'));
            $('#total_bayar').html("Rp " + total_bayar.toLocaleString('id-ID'));
            $('#total_harga').val(total_bayar);
        }
    });
</script>
<?= $this->endSection() ?>