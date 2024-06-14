<!-- Bootstrap core JavaScript-->

<!-- <script src="https://css.jne.co.id/mysales/assets/js/core/jquery.3.2.1.min.js"></script> -->
<script src="<?= base_url() ?>public/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="<?= base_url() ?>public/vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="<?= base_url() ?>public/js/sb-admin-2.min.js"></script>

<!-- Page level plugins -->
<script src="<?= base_url() ?>public/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>public/vendor/datatables/dataTables.bootstrap4.min.js"></script>

<!-- Page level custom scripts -->
<script src="<?= base_url() ?>public/js/demo/datatables-demo.js"></script>
<script src="https://css.jne.co.id/mysales/assets/js/plugin/datatables/datatables.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>

<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!-- Include DataTables core script -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

<!-- Include DataTables Buttons script -->
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>

<!-- Include JSZip -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<!-- Include PDFMake and VFS Fonts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

<!-- Include Buttons HTML5 and Print scripts -->
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<!-- notify  -->
<script src="https://css.jne.co.id/mysales/assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>



<script>
    function get_csrf() {
        var xmlHttp = new XMLHttpRequest();
        xmlHttp.open("GET", '<?= base_url('ccc/get_csrf') ?>', false);
        xmlHttp.send(null);
        return xmlHttp.responseText;
    }

    
    <?php if ($this->session->flashdata('success')) { ?>
        var content = {};
        content.message = '<?php echo $this->session->flashdata("success") ?>';
        content.icon = 'la la-check-circle';

        $.notify(content, {
            type: 'success',
            placement: {
                from: 'top',
                align: 'right'
            },
            time: 1000,
        }); <?php } ?>
</script>