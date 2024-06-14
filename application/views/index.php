<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>
        E Voucher - Login
    </title>
    <link href="<?= base_url() ?>public/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="<?= base_url() ?>public/css/sb-admin-2.min.css" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link rel="icon" href="<?= base_url('public/img/voucher.png') ?>" type="image/png">
        

</head>
<?php
// Tampilkan pesan sukses jika ada
if ($this->session->flashdata('success_message')) {
    echo '<div class="alert alert-success">' . $this->session->flashdata('success_message') . '</div>';
}

// Tampilkan pesan kesalahan jika ada
if ($this->session->flashdata('error_message')) {
    echo '<div class="alert alert-danger">' . $this->session->flashdata('error_message') . '</div>';
}
?>

<body class="bg-gradient-light">

    <div class="container">


        <div class="row justify-content-center">
            <div class="col-xl-10 ">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">

                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block">
                                <img src="<?= base_url('public/img/qr_jne.png') ?>" alt="QR Code"
                                    style="max-width: 100%; max-height: 100%;">
                            </div>


                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">E-Voucher JNE</h1>
                                    </div>
                                    <form class="user" action="<?php echo base_url('auth/login'); ?>" method="post">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" id="username"
                                                name="username" aria-describedby="emailHelp"
                                                placeholder="Enter Username" autocomplete="off" required>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="password" class="form-control form-control-user"
                                                    id="password" name="password" placeholder="Password" autocomplete="off" required>
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary" type="button"
                                                        id="togglePassword">
                                                        <i class="fa fa-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />

                                        <hr>
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Login
                                        </button>


                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <script>
        $(document).ready(function () {
            // Mengatur fungsi toggle untuk tombol mata
            $("#togglePassword").click(function () {
                // Memperoleh jenis input (password atau text)
                var type = $("#password").attr("type");

                // Mengganti jenis input sesuai dengan kondisi saat ini
                if (type === "password") {
                    $("#password").attr("type", "text");
                } else {
                    $("#password").attr("type", "password");
                }
            });
        });
    </script>

    <!-- Bootstrap core JavaScript-->

</body>

</html>