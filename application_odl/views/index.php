<!DOCTYPE html>
<html lang="en">

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Validasi
</title>
<link rel="icon" href="<?= base_url('public/img/camera.svg') ?>" type="image/svg+xml">



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
<!-- <link rel="icon" href="<?= base_url('public/img/Suupaaneko_Logogram.png') ?>" type="image/png"> -->

<!-- icon -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">

<head>

    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
        <link rel="icon" href="assets/img/kaiadmin/favicon.ico" type="image/x-icon" />

        <title>
            Validasi Login
        </title>
        <!-- <link href="<?= base_url() ?>public/vendor/fontawesome-free/css/all.min.css" rel="stylesheet"> -->

        <link
            href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
            rel="stylesheet">
        <link rel="icon" href="<?= base_url('public/img/scanning.png') ?>" type="image/png">


       
    </head>



</head>
<?php
// Tampilkan pesan sukses jika ada
if ($this->session->flashdata('success_message')) {
    echo '<div class="-success">' . $this->session->flashdata('success_message') . '</div>';
}

// Tampilkan pesan kesalahan jika ada
if ($this->session->flashdata('error_message')) {
    echo '<div class="alert alert-danger">' . $this->session->flashdata('error_message') . '</div>';
}
?>

<body class="login bg-primary">
    <div class="wrapper wrapper-login">
        <div class="container container-login animated fadeIn">
            <!-- <img src="public/img/supaneko.png" style="height:auto; max-width:100%; display:block;"> -->
            <img src="public/img/logistics.svg" style="height:auto; max-width:100%; display:block;">
            <h3 class="text-center">Sign In</h3>
            <div class="login-form">
                <form class="user" action="<?php echo base_url('auth/login'); ?>" method="post">
                    <div class="form-group">
                        <input type="text" class="form-control form-control-user" id="username" name="username"
                            aria-describedby="emailHelp" placeholder="Enter Username" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <input type="password" class="form-control form-control-user" id="password" name="password"
                                placeholder="Password" autocomplete="off">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword"
                                    onclick="showPassword()">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>"
                        value="<?= $this->security->get_csrf_hash() ?>" />

                    <hr>
                    <button type="submit" class="btn btn-primary w-100 btn-login">
                        Login
                    </button>

                    <center style="margin-top :10px;">

                        <button type="button" class="btn btn-link" data-bs-toggle="modal"
                            data-bs-target="#logUpdate">
                            Log Update
                        </button>

                    </center>

                </form>
            </div>
        </div>
    </div>
</body>

<script>
    function showPassword() {
        var x = document.getElementById("password");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }
</script>


<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>




<!-- The Modal -->
<div class="modal" id="logUpdate">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Log Update</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="update-section">
                    <strong>Header Update</strong>
                    <ul>
                        <li>Text</li>
                        <li>Text</li>
                        <li>Text</li>
                        <li>Text</li>                      
                    </ul>
                </div>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>





</html>