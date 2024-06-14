<form id="filterForm">
    <div class="form-row">
        <div class="form-group col-md-5">
            <label for="dateFrom">From:</label>
            <input type="date" class="form-control" id="dateFrom" name="dateFrom" value="<?= date('Y-m-d') ?>">
        </div>
        <div class="form-group col-md-5">
            <label for="dateThru">Thru:</label>
            <input type="date" class="form-control" id="dateThru" name="dateThru" value="<?= date('Y-m-d') ?>">
        </div>
    </div>
</form>



<!-- Modal -->
<?php $this->load->view('ccc_role_corp/modal_add_data'); ?>
<!-- end Modal -->

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
<div class="card card-raised">
    <div class="card-header bg-primary text-white px-4">
        <div class="d-flex justify-content-between align-item-center">
            <div class="me-4">
                <h2 class="card-title text-white mb-0 ">Corporate</h2>
                <div class="card-subtitile">Details and history</div>
            </div>
            <div class="me-4">
                <button type="button" class="btn btn-success" onclick="downloadTemplate()"><i
                        class="bi bi-download"></i> Download Template</button>
            </div>

        </div>
    </div>
    <div class="card-body p-4">
        <input type="hidden" name="status" id="status" value="">
        <!-- Tambahkan ini di atas tabel -->
        <form id="upload_data" action="<?php echo base_url('ccc_corp/importData'); ?>" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-5 mb-2">
                    
                </div>
                <div class="col-md-3 mb-2">
                    <input type="file" class="form-control" name="excel_file" id="excel_file" required>
                </div>
                <input type="hidden"  id="Import_csrf" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
                <div class="col-md-4 mb-2">                    
                    <button type="submit" id="importDataBtn" class="btn btn-primary"><i class="fas fa-file-import"> </i> Import
                        Data</button>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table id="voucher" class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Date</th>
                        <th>AWB No</th>
                        <th>Id Customer</th>
                        <th>Customer Name</th>
                        <th>Consignee</th>
                        <th>Qty</th>
                        <th>Weight</th>
                        <th>Amount</th>
                        <th>Create Date</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="<?= base_url() ?>public/vendor/jquery/jquery.min.js"></script>

<script type="text/javascript">
    function downloadTemplate() {
        // Ganti URL dengan lokasi file template Anda
        var templateUrl = "<?= base_url('public/tamplate_excel/corp.xlsx') ?>";
        window.location.href = templateUrl;
    }
    var table;
    $(document).ready(function () {
        //datatables
        table = $('#voucher').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?= base_url('finance_corp/getdatatables_customer') ?>",
                "type": "POST",
                "data": function (data) {
                    data.status = $('[name="status"]').val();
                    data.dateFrom = $('[name="dateFrom"]').val();
                    data.dateThru = $('[name="dateThru"]').val();
                    data.<?= $this->security->get_csrf_token_name() ?> = get_csrf();

                }
            },
            "columnDefs": [{
                "targets": [0, 1, 2, 3, 4, 5, 6, 7, 8],
                "orderable": false
            },
            {
                "targets": [0, 1, 2, 3, 4, 5, 6],
                "className": 'text-center'
            }
            ]
        });




    });

    $('[name="dateFrom"]').on('change', (e) => {
        $('#status').val('status1');
        table.ajax.reload();

    });
    $('[name="dateThru"]').on('change', (e) => {
        $('#status').val('status1');
        table.ajax.reload();

    });


    $(document).ready(function () {
        $("#upload_data").click(function () {
            $.getJSON('<?= base_url('admin/get_csrf_json') ?>', function (res) {
                if (res.status == "Success") {
                    $('[id="Import_csrf"]').val(res.get_csrf_hash);
                }
            });
        });        
    });

</script>