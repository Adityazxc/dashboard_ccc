
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forbidden Directory</title>

    <script src="<?= base_url('public/js/plugin/webfont/webfont.min.js') ?>"></script>
    <script>
        WebFont.load({
            google: { "families": ["Public Sans:300,400,500,600,700"] },
            custom: { "families": ["Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"], urls: ['public/css/fonts.min.css'] },
            active: function () {
                sessionStorage.fonts = true;
            }
        });
    </script>

    <!-- CSS -->
    <link rel="stylesheet" href="<?= base_url('public/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('public/css/plugins.min.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('public/css/kaiadmin.min.css'); ?>">
    
    <!-- Fonts -->

<!-- DataTables JS -->
<script src="<?= base_url('public/js/core/jquery-3.7.1.min.js') ?>"></script>

<!-- chart -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>


<!-- icon -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">