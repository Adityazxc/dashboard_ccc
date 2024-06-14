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

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800 totalstatus5"></div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-coin fa-2x text-gray-300"></i>
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
                <h2 class="card-title text-white mb-0 ">Corporate</h2>
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

<script>
    function get_csrf() {
        var xmlHttp = new XMLHttpRequest();
        xmlHttp.open("GET", '<?= base_url('ccc/get_csrf') ?>', false);
        xmlHttp.send(null);
        return xmlHttp.responseText;
    }
    var jumlah = () => {
        var formData = {
            status: $('#status').val(),
            dateFrom: $('[name="dateFrom"]').val(),
            dateThru: $('[name="dateThru"]').val(),
        };
        formData['<?= $this->security->get_csrf_token_name() ?>'] = get_csrf();

        // BOX 1 
        $('.totalstatus5').text('Rp 0');
        $.ajax({
            url: "<?= base_url('finance_corp/summary_customer') ?>",
            dataType: "JSON",
            type: "POST",
            data: formData,
            success: (r) => {
                // BOX 1                 
                var formattedTotal = formatRupiah(r.sum_totalharga);
                $('.totalstatus5').text(formattedTotal);
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
                "targets": [0, 2, 3, 4, 5, 6, 7, 8],
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


</script>