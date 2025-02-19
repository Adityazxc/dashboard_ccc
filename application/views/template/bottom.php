<script>
    $(document).ready(function () {
        // Ambil pesan notifikasi dari PHP
        var notifyMessage = "<?= $this->session->flashdata('notify')['message'] ?? '' ?>";
        var notifyType = "<?= $this->session->flashdata('notify')['type'] ?? '' ?>";

        // Jika ada pesan, tampilkan notifikasi
        if (notifyMessage) {
            $.notify({
                message: notifyMessage
            }, {
                type: notifyType,
                delay: 3000, // Durasi notifikasi dalam milidetik
                placement: {
                    from: "top",
                    align: "right" // Posisi notifikasi
                },
                offset: {
                    x: 20,
                    y: 70
                }
            });
        }
    });
</script>
<script src="<?= base_url('public/js/core/popper.min.js') ?>"></script>
<script src="<?= base_url('public/js/core/bootstrap.min.js') ?>"></script>


<!-- jQuery Scrollbar -->
<script src="<?= base_url('public/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') ?>"></script>

<!-- Chart JS -->
<script src="<?= base_url('public/js/plugin/chart.js/chart.min.js') ?>"></script>

<!-- jQuery Sparkline -->
<script src="<?= base_url('public/js/plugin/jquery.sparkline/jquery.sparkline.min.js') ?>"></script>

<!-- Chart Circle -->
<script src="<?= base_url('public/js/plugin/chart-circle/circles.min.js') ?>"></script>

<!-- Datatables -->
<script src="<?= base_url('public/js/plugin/datatables/datatables.min.js') ?>"></script>

<!-- Bootstrap Notify -->
<script src="<?= base_url('public/js/plugin/bootstrap-notify/bootstrap-notify.min.js') ?>"></script>

<!-- jQuery Vector Maps -->
<script src="<?= base_url('public/js/plugin/jsvectormap/jsvectormap.min.js') ?>"></script>
<script src="<?= base_url('public/js/plugin/jsvectormap/world.js') ?>"></script>
<!-- Google Maps Plugin -->
<script src="<?= base_url('public/js/plugin/gmaps/gmaps.js') ?>"></script>

<!-- Sweet Alert -->
<script src="<?= base_url('public/js/plugin/sweetalert/sweetalert.min.js') ?>"></script>

<!-- Kaiadmin JS -->
<script src="<?= base_url('public/js/kaiadmin.min.js') ?>"></script>

