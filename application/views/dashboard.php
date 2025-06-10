<!DOCTYPE html>
<html lang="en">

<head>
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title><?= $title ?></title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <link rel="icon" href="<?= base_url('public/img/camera.svg') ?>" type="image/svg+xml">


    <?php $this->load->view('template/header.php') ?>
</head>


<body>
    <div class="wrapper">
        <!-- Sidebar --> 
        <?php $this->load->view('widgets/sidebar.php') ?>
        <!-- End Sidebar -->

        <div class="main-panel">
            

                <?php $this->load->view('widgets/topbar.php')?>
                
                <div class="container">
                    <div class="page-inner">
                        <?php $this->load->view( $this->session->userdata('pages') . '/' . $page_name . '.php') ?>
                    </div>
                </div>
                
            
            
        </div>
        
    </body>
    <?php $this->load->view( 'template/bottom.php' )?>
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