<!DOCTYPE html>
<html lang="en">


<head>

    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title><?= $title ?></title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <link rel="icon" href="<?= base_url('public/img/camera.svg') ?>" type="image/svg+xml">


    <?php $this->load->view('template/header.php') ?>
</head>

<style>
    .full-width {
        margin-left: 0 !important;
        width: 100% !important;
    }
</style>

<body>
    <div class="wrapper">

        <?php
        // Ambil role dulu di awal agar tidak ambigu
        $role = $this->session->userdata('role');
        ?>

        <!-- Sidebar hanya jika role ADA -->
        <?php if (!empty($role)): ?>
            <?php $this->load->view('widgets/sidebar.php') ?>
        <?php endif; ?>


        <!-- Jika ROLE ADA → tampil dengan main-panel -->
        <?php if (!empty($role)): ?>

            <div class="main-panel">

                <?php $this->load->view('widgets/topbar.php') ?>

                <div class="container">
                    <div class="page-inner">
                        <?php
                        $page = $this->session->userdata('pages') . '/' . $page_name . '.php';
                        $this->load->view($page);
                        ?>
                    </div>
                </div>

            </div>

            <!-- Jika ROLE KOSONG → tampilkan content saja, tanpa main-panel & tanpa sidebar -->
        <?php else: ?>

            <!-- <div class="container" > -->
                <div class="page-inner m-o p-0">
                    <?php
                    $page = $this->session->userdata('pages') . '/' . $page_name . '.php';
                    $this->load->view($page);
                    ?>
                </div>
            <!-- </div> -->

        <?php endif; ?>

    </div>
</body>

<?php $this->load->view('template/bottom.php') ?>
</div>
<script>
    var userRole = '<?= $this->session->userdata("role") ?>'; // ambil role dari session PHP
    var idleTime = 0;
    var logoutTime = 1800; // 30 menit (dalam detik)

    // ✅ Jalankan auto logout hanya jika role tidak kosong
    if (userRole && userRole.trim() !== '') {

        function countdown() {
            idleTime++;
            if (idleTime === logoutTime) {
                alert("Anda telah logout otomatis karena tidak aktif.");
                window.location.href = '<?= base_url("auth/logout") ?>';
            }
        }

        function resetTimer() {
            idleTime = 0;
        }

        // Atur timer untuk menghitung mundur
        var countdownInterval = setInterval(countdown, 1000);

        document.addEventListener('mousemove', resetTimer);
        document.addEventListener('keydown', resetTimer);

    } else {
        console.log("Auto logout dinonaktifkan karena role kosong.");
    }
</script>

</html>