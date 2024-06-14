<form id="filterForm">
    <div class="form-row">
        <div class="form-group col-md-4">
            <label for="dateFrom">From:</label>
            <input type="date" class="form-control" id="dateFrom" name="dateFrom" value="<?= date('Y-m-d') ?>">
        </div>
        <div class="form-group col-md-4">
            <label for="dateThru">Thru:</label>
            <input type="date" class="form-control" id="dateThru" name="dateThru" value="<?= date('Y-m-d') ?>">
        </div>
        <div class="form-group col-md-4">
            <label for="account_name">Filter Agen:</label>
            <input type="text" class="form-control" id="account_name" name="account_name" placeholder="Filter by Agen">
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
                            Digunakan</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800 totalstatus2"></div>
                    </div>
                    <button class="btn btn-default btn-icon" onclick="btnstatus1()">
                        <div class="col-auto">
                            <i class="bi bi-check2-all fa-2x"></i>
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
                    <button class="btn btn-default btn-icon" onclick="btnstatus2()">
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
                        <div class="h5 mb-0 font-weight-bold text-gray-800 totalstatus4"></div>
                    </div>
                    <button class="btn btn-default btn-icon" onclick="btnHangus()">
                        <div class="col-auto">
                            <i class="bi bi-x-circle-fill fa-2x"></i>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
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
        <div class="table-responsive">
            <input type="hidden" name="status" id="status" value="">

            <table id="voucher" class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Date</th>
                        <th>E-Voucher</th>
                        <th>AWB Claim</th>
                        <th>Nominal E-Voucher</th>
                        <th>Account ID</th>
                        <th>Account Name</th>
                        <th>Consignee</th>
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
            account_name : $('[name="account_name"]').val(),
        };
        formData['<?= $this->security->get_csrf_token_name() ?>'] = get_csrf();
        console.log(formData); 
        // BOX 1 
        $('.totalstatus1').text('Tunggu.');
        $('.totalstatus2').text('Tunggu.');
        $('.totalstatus3').text('Tunggu.');
        $('.totalstatus4').text('Tunggu.');
        $('.totalstatus5').text('Tunggu.');
        $.ajax({
            url: "<?= base_url('finance/summary_customer') ?>",
            dataType: "JSON",
            type: "POST",
            data: formData,
            success: (r) => {
                // BOX 1              
                $('.totalstatus1').text(r.sum_status1);
                $('.totalstatus2').text(r.sum_status2);
                $('.totalstatus3').text(r.sum_status3);
                $('.totalstatus4').text(r.sum_status4);
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
    $(document).ready(function () {
        //datatables
        table = $('#voucher').DataTable({
            "processing": true,
            "serverSide": true,
            "searching":false,
            "ajax": {
                "url": "<?= base_url('finance/getdatatables_customer') ?>",
                "type": "POST",
                "data": function (data) {
                    data.status = $('[name="status"]').val();
                    data.dateFrom = $('[name="dateFrom"]').val();
                    data.dateThru = $('[name="dateThru"]').val();
                    data.account_name = $('[name="account_name"]').val();
                    data.<?= $this->security->get_csrf_token_name() ?> = get_csrf();
                }
            },
            "columnDefs": [{
                "targets": [0, 2, 3, 4, 5, 6],
                "orderable": false
            },
            {
                "targets": [0, 1, 2, 3, 4, 5, 6],
                "className": 'text-center'
            }
            ],
            "buttons": [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
        new $.fn.dataTable.Buttons(table, {
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        }).container().appendTo($('.dataTables_length:eq(0)', table.table().container()));
        jumlah();
    });
    $('[name="account_name"]').on('keyup', function () {
        table.ajax.reload();
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

    function btnstatus1() {
        $('#status').val('status2');
        table.ajax.reload();
        jumlah();
    }

    function btnstatus2() {
        $('#status').val('status3');
        table.ajax.reload();
        jumlah();
    }

    function btnHangus() {
        $('#status').val('hangus');
        table.ajax.reload();
        jumlah();
    }

    function btnstatus4() {
        $('#status').val('status4');
        table.ajax.reload();
        jumlah();
    }
</script>