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
<?php $this->load->view('ccc_role/modal_add_data'); ?>
<!-- end Modal -->


<div class="card card-raised">
    <div class="card-header bg-primary text-white px-4">
        <div class="d-flex justify-content-between align-item-center">
            <div class="me-4">
                <h2 class="card-title text-white mb-0 ">Customers</h2>
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
        <form id="upload_data" action="<?php echo base_url('ccc/importData'); ?>" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-5 mb-2">
                    <button type="button" id="modal_csrf_token" class="btn btn-primary" data-toggle="modal"
                        data-target="#addCustomerModal">
                        <i class="bi bi-plus-lg"></i>
                        Add Customer
                    </button>
                </div>
                <input type="hidden"  id="Import_csrf" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
                <div class="col-md-3 mb-2">
                    <input type="file"  class="form-control" name="excel_file" id="excel_file" required>
                </div>
                <div class="col-md-4 mb-2">
                    <button type="submit" class="btn btn-primary" id="importDataBtn">
                        <i class="fas fa-file-import"></i>
                        Import Data
                    </button>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table id="voucher" class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Shipper Name</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Amount</th>
                        <th>AWB No</th>
                        <th>Service</th>
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
        var templateUrl = "<?= base_url('public/tamplate_excel/retail.xlsx') ?>";
        window.location.href = templateUrl;
    }
    var table;
    $(document).ready(function () {
        //datatables
        table = $('#voucher').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?= base_url('ccc/getdatatables_customer') ?>",
                "type": "POST",
                "data": function (data) {
                    data.status = $('[name="status"]').val();
                    data.dateFrom = $('[name="dateFrom"]').val();
                    data.dateThru = $('[name="dateThru"]').val();
                    data.<?= $this->security->get_csrf_token_name() ?> = get_csrf();                    

                }
            },
            "columnDefs": [{
                "targets": [0, 1, 2, 3, 4, 5, 6],
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
        $("#modal_csrf_token").click(function () {
            $.getJSON('<?= base_url('ccc/get_csrf_json') ?>', function (res) {
                if (res.status == "Success") {
                    $('[id="ModaladdCustomerModal_csrf"]').val(res.get_csrf_hash);
                }
            });
        });        
    });

    $(document).ready(function () {
        $("#upload_data").click(function () {
            $.getJSON('<?= base_url('ccc/get_csrf_json') ?>', function (res) {
                if (res.status == "Success") {
                    $('[id="Import_csrf"]').val(res.get_csrf_hash);
                }
            });
        });        
    });


   
</script>