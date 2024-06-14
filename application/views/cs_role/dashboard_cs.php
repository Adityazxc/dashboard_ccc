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
<div class="row">
    <!-- Filter Form -->
    <!-- Earnings (Monthly) Card Example -->
    <div class="col mb-4 hover-effect">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Dikirim</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800 totalEmailDikirim"></div>
                    </div>
                    <button class="btn btn-default btn-icon" onclick="btnEmailDikirim()">
                        <div class="col-auto">
                            <i class="bi bi-send fa-2x "></i>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Earnings (Monthly) Card Example -->
    <div class="col mb-4 hover-effect">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Belum Dikirim</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800 totalBelumDikirim"></div>
                    </div>
                    <button class="btn btn-default btn-icon" onclick="btnBelumDikirim()">
                        <div class="col-auto">
                            <i class="bi bi-send-dash fa-2x"></i>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Earnings (Monthly) Card Example -->
    <div class="col mb-4 hover-effect">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Belum digunakan
                        </div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800 totalstatus3"></div>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-default btn-icon" onclick="btnstatus3()">
                        <div class="col-auto">
                            <i class="bi bi-hourglass-split fa-2x"></i>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Requests Card Example -->
    <div class="col mb-4 hover-effect">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Hangus</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800 totalHangus"></div>
                    </div>
                    <button class="btn btn-default btn-icon" onclick="btnstatus4()">
                        <div class="col-auto">
                            <i class="bi bi-x-circle-fill fa-2x"></i>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="col mb-4 ">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800 totalPengeluaran"></div>
                    </div>

                    <div class="col-auto">
                        <i class="bi bi-coin fa-2x"></i>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header bg-primary text-white px-4">
        <div class="d-flex justify-content-between align-item-center">
            <div class="me-4">
                <h2 class="card-title text-white mb-0 ">Customers</h2>
                <div class="card-subtitile">Details and history</div>
            </div>

        </div>
    </div>
    <div class="card-body">
        <input type="hidden" name="status" id="status" value="">
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
                        <th>Status</th>
                        <th>Service</th>
                        <th>E-Voucher</th>
                        <th>Status Email</th>                        
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="<?= base_url() ?>public/vendor/jquery/jquery.min.js"></script>

<script>
    var jumlah = () => {
        var formData = {
            status: $('#status').val(),
            dateFrom: $('[name="dateFrom"]').val(),
            dateThru: $('[name="dateThru"]').val(),
        };
        formData['<?= $this->security->get_csrf_token_name() ?>'] = get_csrf();
        // BOX 1 
        $('.totalEmailDikirim').text('Tunggu.');
        $('.totalBelumDikirim').text('Tunggu.');
        $('.totalstatus3').text('Tunggu.');
        $('.totalHangus').text('Tunggu.');
        $('.totalstatus5').text('Tunggu.');
        $.ajax({
            url: "<?= base_url('marketing/summary_customer') ?>",
            dataType: "JSON",
            type: "POST",
            data: formData,
            success: (r) => {
                // BOX 1              
                $('.totalEmailDikirim').text(r.sum_email_dikirim);
                $('.totalBelumDikirim').text(r.sum_belum_dikirim);
                $('.totalstatus3').text(r.sum_status3);
                $('.totalHangus').text(r.sum_hangus);
                $('.totalPengeluaran').text(formatRupiah(r.sum_status5));



            }
        });
    }
    function formatRupiah(angka) {
        var number_string = angka.toString();
        var sisa = number_string.length % 3;
        var rupiah = number_string.substr(0, sisa);
        var ribuan = number_string.substr(sisa).match(/\d{3}/g);

        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        return 'Rp ' + rupiah;
    }
</script>



<script type="text/javascript">
    var table;
    var table;

    $(document).ready(function () {
        // datatables
        table = $('#voucher').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?= base_url('marketing/getdatatables_customer') ?>",
                "type": "POST",
                "data": function (data) {
                    data.status = $('[name="status"]').val();
                    data.dateFrom = $('[name="dateFrom"]').val();
                    data.dateThru = $('[name="dateThru"]').val();
                    data.<?= $this->security->get_csrf_token_name() ?> = get_csrf();
                }
            },
            "columnDefs": [{
                "targets": [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
                "orderable": false
            },
            {
                "targets": [0, 1, 2, 3, 4, 5, 6],
                "className": 'text-center'
            }],
            "buttons": [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });

        // Initialize the DataTables Buttons extension
        new $.fn.dataTable.Buttons(table, {
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        }).container().appendTo($('.dataTables_length:eq(0)', table.table().container()));

        jumlah();
    });


    $('[name="dateFrom"]').on('change', (e) => {
        $('#status').val('status1');
        table.ajax.reload();
        jumlah();

    });
    $('[name="dateThru"]').on('change', (e) => {
        $('#status').val('status1');
        table.ajax.reload();
        jumlah();
    });

    function btnEmailDikirim() {
        $('#status').val('emailDikirim');
        table.ajax.reload();
        jumlah();
    }

    function btnBelumDikirim() {
        $('#status').val('belumDikirim');
        table.ajax.reload();
        jumlah();
    }

    function btnstatus3() {
        $('#status').val('belumDigunakan');
        table.ajax.reload();
        jumlah();
    }

    function btnstatus4() {
        $('#status').val('hangus');
        table.ajax.reload();
        jumlah();
    }
</script>