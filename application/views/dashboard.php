<!DOCTYPE html>
<html lang="en">

<head>
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title><?= $title ?></title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <link rel="icon" href="<?= base_url('public/img/Suupaaneko_Logogram.png') ?>" type="image/png">


    <?php include 'template/header.php' ?>
</head>


<body>
    <div class="wrapper">
        <!-- Sidebar --> 
        <?php include 'widgets/sidebar.php' ?>
        <!-- End Sidebar -->

        <div class="main-panel">
            

                <?php include 'widgets/topbar.php'?>
                
                <div class="container">
                    <div class="page-inner">
                        <?php include $this->session->userdata('pages') . '/' . $page_name . '.php' ?>
                    </div>
                </div>
                
            
            
        </div>
        
    </body>
    <?php include 'template/bottom.php' ?>
</div>
<script>
    var idleTime = 0;
    var logoutTime = 1800;

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
</script>
</html>