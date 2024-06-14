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


<div class="card card-raised">
    <div class="card-header bg-primary text-white px-4">
        <div class="d-flex justify-content-between align-item-center">
            <div class="me-4">
                <h2 class="card-title text-white mb-0 ">Customers</h2>
                <div class="card-subtitile">Details and history</div>
            </div>

        </div>
    </div>
    <div class="card-body p-4">
        <input type="hidden" name="status" id="status" value="">
        <!-- Tambahkan ini di atas tabel -->

        <form id="send_email_csrf" action="<?= base_url('marketing/test_checkbox') ?>" method="POST">
            <div class="mb-3">
            <input type="hidden" id="Import_csrf" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" />

                <button type="submit" class="btn btn-primary" id="getSelectedEmails" data-email="example@email.com">
                    <i class="bi bi-send"></i> Kirim Email</button>
            </div>
            <div class="table-responsive">
                <table id="voucher" class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="selectAll">
                                    <label class="form-check-label" for="selectAll"></label>
                                </div>
                            </th>
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

        </form>
    </div>
</div>

<script src="<?= base_url() ?>public/vendor/jquery/jquery.min.js"></script>

<script type="text/javascript">
    var table;

    $(document).ready(function () {
        //datatables
        table = $('#voucher').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?= base_url('marketing/getdatatables_send_email') ?>",
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
                "targets": [0, 1, 2, 3, 4, 5, 6, 7],
                "className": 'text-center'
            },
            {
                "targets": 3, // Targeting the Email column
                "render": function (data, type, row, meta) {
                    return '<small style="font-size:12px" class="email-cell">' + data + '</small>';
                }
            }
            ]
        });

        // checkbox
        $('#selectAll').change(function () {
            var checked = $(this).prop('checked');
            $('input:checkbox.data-check').prop('checked', checked);
        });

        // Tambahkan ini untuk menangani checkbox all
        $('#voucher tbody').on('change', 'input:checkbox.data-check', function () {
            $('#selectAll').prop('checked', false);
        });
        // end checkbox
        function updateCsrfToken() {
        $.getJSON('admin/get_csrf_json', function (res) {
            if (res.status == "Success") {
                $('[id="ModaladdCustomerModal_csrf"]').val(res.get_csrf_hash);
            }
        });
    }

        $(document).on('click', '#getSelectedEmails', function () {
            var selectedEmails = getSelectedEmails();

            if (selectedEmails.length > 0) {
                sendEmails(selectedEmails);
                updateCsrfToken();
                $("form#filterForm").submit();
            } else {
                alert('Tidak ada email yang dipilih.');
            }
        });

        // datefrom and date thru
        $('[name="dateFrom"]').on('change', (e) => {
            $('#status').val('status1');
            table.ajax.reload();
        });

        $('[name="dateThru"]').on('change', (e) => {
            $('#status').val('status1');
            table.ajax.reload();
        });

        // end datefrom and date thru


    });


    $(document).ready(function () {
        $("#send_email_csrf").click(function () {
            $.getJSON('<?= base_url('admin/get_csrf_json') ?>', function (res) {
                if (res.status == "Success") {
                    $('[id="Import_csrf"]').val(res.get_csrf_hash);
                }
            });
        });        
    });
</script>