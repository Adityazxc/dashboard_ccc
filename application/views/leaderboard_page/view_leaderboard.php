<form id="filterForm">
    <div class="row">
        <div class="form-group col-md-3">
            <label for="dateFrom">From:</label>
            <input type="month" class="form-control" id="dateFrom" name="dateFrom" value="<?= date('Y-m') ?>">
            
        </div>      
        <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
        <!-- Bootstrap Datepicker CSS -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet"/>

<!-- Bootstrap Datepicker JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

        <?php
        $full_access = in_array($role, ['Admin', 'BPS', 'Super User', 'HC', 'PAO', 'CS', 'CCC', 'Kepala Cabang']);
        $zone_only = in_array($role, ['Kepala Cabang BDO2', 'BBP']);
        $get_origins_array = json_decode($get_origins, true);

        // Ambil origin code dari object jika zone_only
        $selected_origin = $zone_only ? $origin : $origin;
        ?>

        <!-- ORIGIN -->
        <div class="form-group col-md-3">
            <label for="origin" class="form-label required">Origin:</label>
            <select class="form-select select2" name="origin" id="origin" >
                <option value="">-- Pilih Origin --</option>
                <?php foreach ($get_origins_array as $get_origin): ?>
                            <option value="<?= $get_origin['origin_code'] ?>" <?= (!$full_access && $get_origin['origin_code'] == $origin) ? 'selected' : '' ?>>
                                <?= $get_origin['origin_name'] ?> (<?= $get_origin['origin_code'] ?>)
                            </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- ZONE -->
        <!-- <div class="form-group col-md-3">
            <label for="zone" class="form-label required">Zone:</label>
            <select class="form-select select2" name="zone" id="zone" <?= (!$full_access && !$zone_only) ? 'disabled' : '' ?>>
                <option value="">-- Pilih Zone --</option>
                <?php if (!$full_access && !$zone_only && !empty($zone)): ?>
                    <option value="<?= $zone ?>" selected><?= $zone ?></option>
                <?php endif; ?>
            </select>
        </div> -->

        <!-- Hidden Input jika tidak boleh ubah -->
        <?php if (!$full_access && !$zone_only): ?>
                    <input type="hidden" name="origin" value="<?= $selected_origin ?>">
                    <input type="hidden" name="zone" value="<?= $zone ?>">
        <?php endif; ?>

        <script>
            $(document).ready(function () {
                $('#origin').select2({
                    placeholder: "-- Pilih Origin --",
                    allowClear: true,
                    width: '100%'
                });

                $('#zone').select2({
                    placeholder: "-- Pilih Zone --",
                    allowClear: true,
                    width: '100%'
                });
                
                $('#dateFrom').datepicker({
                format: "yyyy-mm",
                startView: 1,
                minViewMode: 1,
                autoclose: true,
                todayHighlight: true
            });


                function loadZones(originCode, selectedZone = '') {
                    $.ajax({
                        url: '<?= base_url('admin/get_zone'); ?>',
                        method: 'POST',
                        data: { origin: originCode },
                        dataType: 'json',
                        success: function (response) {
                            let options = '<option value="">-- Pilih Zone --</option>';
                            $.each(response, function (index, z) {
                                const selected = (z.zone_code === selectedZone) ? 'selected' : '';
                                options += `<option value="${z.zone_code}" ${selected}>${z.zone}</option>`;
                            });
                            $('#zone').html(options).trigger('change');
                        },
                        error: function () {
                            alert('Gagal mengambil data zone.');
                        }
                    });
                }

                // Jika zone_only, otomatis load zones saat halaman dibuka
                const isZoneOnly = <?= $zone_only ? 'true' : 'false' ?>;
                const selectedOrigin = '<?= $selected_origin ?>';
                const selectedZone = '<?= $zone ?>';
                if (isZoneOnly && selectedOrigin !== '') {
                    loadZones(selectedOrigin, selectedZone);
                }

                // Jika full_access, boleh pilih origin → trigger zone ajax
                $('#origin').on('change', function () {
                    const origin = $(this).val();
                    if (origin) {
                        loadZones(origin);
                    } else {
                        $('#zone').html('<option value="">-- Pilih Zone --</option>').trigger('change');
                    }
                });
            });
        </script>

    </div>

</form>

<!-- Top Kurir -->
<div class="row">
    <div class="col-lg-12">
        <div class="card card-raised">
            <div class="card-body p-4">
                <div class="card-header text-white px-4">
                    <div class="d-flex justify-content-between align-item-center">
                        <div class="me-4">
                            <h2 class="card-title text-primary mb-0 ">Top Kurir</h2>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="table_top_courier" class="display table table-striped table-hover" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kurir</th>
                                <th>Succes_rate</th>
                                <th>KPI</th>
                                <th>Photo Pod</th>
                                <th>HRS</th>
                                <th>Minus Poin</th>
                                <th>Total Poin</th>
                                <th>Zone</th>
                                <?php if (empty($role)): ?>
                                    <th>Action</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
        
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="col-lg-6">
        <div class="card card-raised">
            <div class="card-body p-4">
                <div class="card-header text-white px-4">
                    <div class="d-flex justify-content-between align-item-center">
                        <div class="me-4">
                            <h2 class="card-title text-primary mb-0 ">Botttom Kurir</h2>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="table_bottom_courier" class="display table table-striped table-hover" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kurir</th>
                                <th>Succes_rate</th>
                                <th>KPI</th>
                                <th>Photo Pod</th>
                                <th>HRS</th>
                                <th>Minus Poin</th>
                                <th>Total Poin</th>
                                <th>Zone</th>
                                <?php if (empty($role)): ?>
                                    <th>Action</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
        
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        </div>
    </div> -->
<script>
    $(document).ready(function () {
        // var role= "<?= $role ?>";        
       // TOP 10 Courier
var tableTop = $('#table_top_courier').DataTable({
    processing: true,
    serverSide: true,
    pageLength: 10,
    lengthMenu: [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
    ajax: {
        url: "<?= base_url('leaderboard/getdatatables_courier') ?>", // satu fungsi
        type: "POST",
        data: function (d) {
            d.dateFrom = $('[name="dateFrom"]').val();                    
            d.origin = $('[name="origin"]').val().trim();                    
            d.type = 'top'; // 👈 penting
        }
    },
    columnDefs: [
        { targets: [4, 9], orderable: false, className: 'text-center' }
    ],
    dom: 'Bfrtip',
    buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
});


// BOTTOM 10 Courier
// var tableBottom = $('#table_bottom_courier').DataTable({
//     processing: true,
//     serverSide: true,
//     pageLength: 10,
//     lengthMenu: [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
//     ajax: {
//         url: "<?= base_url('leaderboard/getdatatables_courier') ?>", // masih satu fungsi juga
//         type: "POST",
//         data: function (d) {
//             d.dateFrom = $('[name="dateFrom"]').val();                    
//             d.origin = $('[name="origin"]').val().trim();                    
//             d.type = 'bottom'; // 👈 beda ini saja
//         }
//     },
//     columnDefs: [
//         { targets: [4, 9], orderable: false, className: 'text-center' }
//     ],
//     dom: 'Bfrtip',
//     buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
// });

// Reload kalau filter berubah
$('[name="dateFrom"], [name="origin"], [name="zone"]').on('change', function () {
    tableTop.ajax.reload(null, false);
    tableBottom.ajax.reload(null, false);
});

    });
</script>

<script>
    // $(document).ready(function () {
    //     var role= "<?= $role ?>";        
    //     var table = $('#table_worst_courier').DataTable({
    //         "processing": true,
    //         "serverSide": true,
    //         "pageLength": 3,
    //         "lengthMenu": [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
    //         "ajax": {
    //             "url": "<?= base_url('leaderboard/getdatatables_worst_courier') ?>",
    //             "type": "POST",
    //             "data": function (d) {
    //                 d.dateFrom = $('[name="dateFrom"]').val();
    //                 d.dateThru = $('[name="dateThru"]').val();
    //                 d.origin = $('[name="origin"]').val().trim();
    //                 d.zone = $('[name="zone"]').val().trim();
    //                 d.role = role;
                    
    //             }
    //         },
    //         "columnDefs": [
    //             {
    //                 "targets": [],
    //                 "orderable": false,
    //                 "className": 'text-center'
    //             }
    //         ],
    //         "dom": 'Bfrtip',
    //         "buttons": ['copy', 'csv', 'excel', 'pdf', 'print']
    //     });          
    //     $('[name="dateFrom"], [name="dateThru"], [name="origin"], [name="zone"]').on('change', function () {
    //     table.ajax.reload(null, false); // reload without resetting the paging
    //     jumlah();
    // });   
    // });
</script>