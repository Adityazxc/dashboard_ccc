<?php $this->load->view('form_page/filter_form_lm') ?>
<div class="card card-raised">
    <div class="card-header text-white px-4">
        <div class="d-flex justify-content-between align-item-center">
            <div class="me-4">
                <h2 class="card-title text-primary mb-0 ">Customers Last Mile</h2>
            </div>
            <button type="button" id="export_data_last_mile" class="btn btn-success">
                <i class="bi bi-file-earmark-excel"></i> Download Data 
            </button>

        </div>
    </div>
   

        <!-- <div id="progress-container">
        <div id="progress-bar">0%</div>
    </div>
    <div id="upload-status"></div> -->
        <div class="card-body p-4">
        <?php $access_upload = in_array($role, ['Super User', 'PAO','CCC']); ?>
            <?php if ($access_upload): ?>
                <form action="<?= base_url('upload/import_data') ?>" method="POST" enctype="multipart/form-data">
                    <!-- Tambahkan ini di atas tabel -->
                    <div class="d-flex justify-content-end">

                        <input type="hidden" id="Import_csrf" name="<?= $this->security->get_csrf_token_name() ?>"
                            value="<?= $this->security->get_csrf_hash() ?>" />
                        <div class="p-2">
                            <input type="file" class="form-control" name="excel_file" id="excel_file" required>
                        </div>
                        <div class="p-2">
                            <button type="submit" class="btn btn-primary" id="importDataBtn">
                                <i class="fas fa-file-import"></i>
                                Import Data
                            </button>

                        </div>
                    </div>
                </form>
            <?php endif; ?>

            <div class="table-responsive">
                <table id="table_product" class="display table table-striped table-hover" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Customer Name</th>
                            <th>PIC</th>
                            <th>Tgl</th>
                            <th>Destination</th>
                            <th>Zone Code</th>
                            <th>Service</th>
                            <th>Shipment</th>
                            <th>Customer Industry</th>
                            <th>Pay Type</th>
                            <th>Zone Delivery</th>
                            <th>POD Code</th>
                            <th>Total Shipment</th>
                            <th>Total Amount</th>
                            <th>Total Weight</th>
                            <th>Delivered</th>
                            <th>On Proses</th>
                            <th>Return</th>
                            <th>Cust No</th>
                            <th>Un Inbound</th>
                            <th>Un Runsheet</th>
                            <th>Open POD</th>
                            <th>Undelivered</th>
                            <th>Customer Request</th>
                            <th>Un Receiving</th>
                            <th>Un Manifest</th>
                            <th>Auto Close Irreg</th>
                            <th>Auto Close System</th>
                            <th>Claim</th>
                            <th>Irregularity</th>
                            <th>Weight</th>
                            <th>First Attempt</th>

                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
</div>



<!-- Modal -->
<div class="modal fade" id="deleteValidasi" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="ModalLabel">Delete Validasi</h5>
                <!-- Tombol Close -->
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form action="<?= base_url('Admin/delete_validasi') ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <p id="message-warning"></p>
                    <input type="hidden" class="form-control" name="id_courrier_delete" id="id_courrier_delete"
                        autocomplete="off">
                    <input type="hidden" class="form-control" name="id_checker_delete" id="id_checker_delete"
                        autocomplete="off">
                    <input type="hidden" class="form-control" name="runsheet_date_delete" id="runsheet_date_delete"
                        autocomplete="off" required>
                    <input type="hidden" class="form-control" name="no_runsheet_delete" id="no_runsheet_delete"
                        autocomplete="off" required>
                </div>
                <div class="modal-footer">
                    <!-- Tombol Close (Footer) -->
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <!-- Tombol Reset -->
                    <button type="submit" class="btn btn-primary col-md-3">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="deleteValidasiBackup" tabindex="-1" role="dialog" aria-labelledby="ModalLabelBackup"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="ModalLabel">Delete Validasi</h5>
                <!-- Tombol Close -->
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form action="<?= base_url('Admin/delete_validasi_backup') ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <p id="message-warning_backup"></p>
                    <input type="hidden" class="form-control" name="id_courrier_delete_backup"
                        id="id_courrier_delete_backup" autocomplete="off">
                    <input type="hidden" class="form-control" name="id_checker_delete_backup"
                        id="id_checker_delete_backup" autocomplete="off">
                    <input type="hidden" class="form-control" name="runsheet_date_delete_backup"
                        id="runsheet_date_delete_backup" autocomplete="off" required>

                </div>
                <div class="modal-footer">
                    <!-- Tombol Close (Footer) -->
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <!-- Tombol Reset -->
                    <button type="submit" class="btn btn-primary col-md-3">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script type="text/javascript">

    function deleteValidasi(id_courier, id_checker, create_date, runsheet_date, no_runsheet, courier_name) {
        $('#message-warning').html('Apakah anda yakin akan hapus validasi <b> ' + courier_name + ',</b> pada tanggal upload <b>' + create_date + '</b>?');
        $('#id_checker_delete').val(id_checker);
        $('#id_courrier_delete').val(id_courier);
        $('#create_date_delete').val(create_date);
        $('#no_runsheet_delete').val(no_runsheet);
        $('#runsheet_date_delete').val(runsheet_date);
    }
    function deleteValidasiBackup(id_courier, id_checker, create_date, runsheet_date, courier_name) {
        $('#message-warning_backup').html('Apakah anda yakin akan hapus validasi <b> ' + courier_name + ',</b> pada tanggal upload <b>' + create_date + '</b>?');
        $('#id_checker_delete_backup').val(id_checker);
        $('#id_courrier_delete_backup').val(id_courier);
        $('#create_date_delete_backup').val(create_date);
        $('#runsheet_date_delete_backup').val(runsheet_date);
    }


    $(document).ready(function () {

        var role = "<?= $role ?>";

        var table = $('#table_product').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 10,
            lengthMenu: [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],

            ajax: {
                url: "<?= base_url('last_mile/getdatatables_last_mile') ?>",
                type: "POST",
                data: function (d) {

                    // ambil semua input dari form filter
                    var formData = $('#filterForm').serializeArray();

                    formData.forEach(function (item) {
                        d[item.name] = item.value;
                    });

                    // kalau mau kirim role juga
                    d.role = role;
                }
            },

            columnDefs: [
                {
                    targets: [6],
                    orderable: false,
                    className: 'text-center'
                }
            ],

            dom: 'Bfrtip',
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
        });

        // Reload hanya saat submit
        $('#filterForm').on('submit', function (e) {
            e.preventDefault();
            table.ajax.reload(null, false);
        });

    });
    $('#export_data_last_mile').click(function (e) {
    e.preventDefault();

    // ambil semua filter dari form
    const formData = $('#filterForm').serializeArray();

    // buat form dinamis
    const form = $('<form>', {
        method: 'POST',
        action: '<?= base_url('Last_mile/export_data_last_mile') ?>'
    });

    // loop semua filter
    formData.forEach(function (item) {
        form.append($('<input>', {
            type: 'hidden',
            name: item.name,
            value: item.value
        }));
    });

    $('body').append(form);
    form.submit();
});


</script>