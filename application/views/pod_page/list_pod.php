<form id="filterForm">
    <div class="row">
        <div class="form-group col-md-2">
            <label for="dateFrom">From:</label>
            <input type="date" class="form-control" id="dateFrom" name="dateFrom" value="<?= date('Y-m-d') ?>">
        </div>
        <div class="form-group col-md-2">
            <label for="dateThru">Thru:</label>
            <input type="date" class="form-control" id="dateThru" name="dateThru" value="<?= date('Y-m-d') ?>">
        </div>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
        <?php
        $full_access = in_array($role, ['BPS', 'Super User', 'HC', 'PAO', 'CS', 'CCC', 'Kepala Cabang']);        
        $zone_only = in_array($role, ['Kepala Cabang BDO2', 'BBP', 'Admin','Admin BDO2','POD']);
        $get_origins_array = json_decode($get_origins, true);

        // Ambil origin code dari object jika zone_only
        $selected_origin = $zone_only ? $origin : $origin;
        ?>

        <!-- ORIGIN -->
        <div class="form-group col-md-3">
            <label for="origin" class="form-label required">Origin:</label>
            <select class="form-select select2" name="origin" id="origin" <?= (!$full_access) ? 'disabled' : '' ?>>
                <option value="">-- Pilih Origin --</option>
                <?php foreach ($get_origins_array as $get_origin): ?>
                    <option value="<?= $get_origin['origin_code'] ?>" <?= (!$full_access && $get_origin['origin_code'] == $origin) ? 'selected' : '' ?>>
                        <?= $get_origin['origin_name'] ?> (<?= $get_origin['origin_code'] ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>



        <!-- ZONE -->
        <div class="form-group col-md-3">
            <label for="zone" class="form-label required">Zone:</label>
            <select class="form-select select2" name="zone" id="zone" <?= (!$full_access && !$zone_only) ? 'disabled' : '' ?>>
                <option value="">-- Pilih Zone --</option>
                <?php if (!$full_access && !$zone_only && !empty($zone)): ?>
                    <option value="<?= $zone ?>" selected><?= $zone ?></option>
                <?php endif; ?>
            </select>
        </div>

        <!-- Hidden Input jika tidak boleh ubah -->
        <?php if (!$full_access && !$zone_only): ?>
            <input type="hidden" name="origin" value="<?= $selected_origin ?>">
            <input type="hidden" name="zone" value="<?= $zone ?>">
        <?php endif; ?>
        <div class="form-group d-flex align-items-center col-md-2">
            <button type="submit" class="btn btn-primary">Filter</button>
        </div>
    </div>
</form>
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




<style>
    #progress-container {
        width: 100%;
        background-color: #ddd;
        margin-top: 10px;
        border-radius: 5px;
    }

    #progress-bar {
        width: 0%;
        height: 30px;
        background-color: #4caf50;
        text-align: center;
        line-height: 30px;
        color: white;
        font-weight: bold;
        border-radius: 5px;
    }
</style>



<!-- Top Kurir -->
<div class="card card-raised">
    <div class="card-body p-4">
        <div class="card-header text-white px-4">
            <div class="d-flex justify-content-between align-item-center">
                <div class="me-4">
                    <h2 class="card-title text-primary mb-0 ">Status Penyetoran COD</h2>
                </div>
                <a href="<?=base_url('pod/detail_pod')?>" class="btn btn-primary"  >
                    <i class="fas fa-add"></i>
                    Add COD
                </a>
            </div>
        </div>
       
        <div class="table-responsive">
            <table id="table_top_courier" class="display table table-striped table-hover" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Courier ID</th>
                        <th>No Runsheet</th>
                        <th>Runsheet Date</th>                      
                        <th>POD Create Date</th>                      
                        <th>POD Create By</th>                      
                        <th>POD Closing By</th>                      
                        <th>Progres Setor</th>
                        <th>Status Setoran</th>
                        <th>Selisih Setoran</th>
                        <th>Action</th>
                 
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {        
        var role = "<?= $role ?>";
        var table = $('#table_top_courier').DataTable({
            "processing": true,
            "serverSide": true,
            "pageLength": 5,
            "lengthMenu": [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
            "ajax": {
                "url": "<?= base_url('pod/getdatatables_cod_pod') ?>",
                "type": "POST",
                "data": function (d) {
                    d.dateFrom = $('[name="dateFrom"]').val();
                    d.dateThru = $('[name="dateThru"]').val();
                    d.origin = $('[name="origin"]').val().trim();
                    d.zone = $('[name="zone"]').val().trim();


                }
            },
            "columnDefs": [
                {
                    "targets": [0],
                    "orderable": false,
                    "className": 'text-center'
                }
            ],
            "dom": 'Bfrtip',
            "buttons": ['copy', 'csv', 'excel', 'pdf', 'print']
        });
       $('#filterForm').on('submit', function (e) {
            e.preventDefault(); // Cegah reload form
            table.ajax.reload(null, false);
        });
    });


    // function list_data(){
    //     var formData = {
    //         year: selectedYear,
    //         dateFrom: $('[name="dateFrom"]').val(),
    //         dateThru: $('[name="dateThru"]').val(),
    //         origin: $('[name="origin"]').val().trim(),
    //         zone: $('[name="zone"]').val().trim(),
    //     };
    // }
    
    
</script>