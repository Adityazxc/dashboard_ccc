<?php
$full_access = in_array($role, ['BPS', 'Super User', 'HC', 'PAO', 'CS', 'CCC', 'Kepala Cabang']);
$zone_only = in_array($role, ['Kepala Cabang BDO2', 'BBP', 'Admin', 'Admin BDO2', 'POD']);
$get_origins_array = json_decode($get_origins, true);
$users_ccc = json_decode($get_users_ccc, true);
$status_pod = json_decode($get_status_pod, true);
$dateFrom = $this->input->get('dateFrom') ?: date('Y-m-d');
$dateThru = $this->input->get('dateThru') ?: date('Y-m-d');
// Ambil origin code dari object jika zone_only
$selected_origin = $zone_only ? $origin : $origin;
$regionals = json_decode($get_regional, true);
$services = json_decode($get_service, true);
$type_cust = json_decode($get_type_cust, true);
$grouping_customers = json_decode($get_grouping_customer, true);
?>
<form id="filterForm">
    <div class="row">
        <div class="form-group col-md-2">
            <label for="dateFrom">From:</label>
            <input type="date" class="form-control" id="dateFrom" name="dateFrom" value="<?= $dateFrom ?>">

        </div>
        <div class="form-group col-md-2">
            <label for="dateThru">Thru:</label>
            <input type="date" class="form-control" id="dateThru" name="dateThru" value="<?= $dateThru ?>">

        </div>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
        <!-- <div class="form-group col-md-2">
            <label>Destination</label>
            <select class="form-select" id="regional" name="regional" aria-label="Default select example" required>
                <?php foreach ($regionals as $regional): ?>
                    <option value="<?= $regional['code'] ?>" ?>
                        <?= $regional['code'] ?> (<?= $regional['regional'] ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div> -->

        <!-- ORIGIN -->
        <div class="form-group col-md-2">
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
        <div class="form-group col-md-2">
            <label for="zone" class="form-label required">Zone:</label>
            <select class="form-select select2" name="zone" id="zone" <?= (!$full_access && !$zone_only) ? 'disabled' : '' ?>>
                <option value="">-- Pilih Zone --</option>
                <?php if (!$full_access && !$zone_only && !empty($zone)): ?>
                    <option value="<?= $zone ?>" selected><?= $zone ?></option>
                <?php endif; ?>
            </select>
        </div>

        <div class="form-group col-md-2">
            <label>PIC BDO</label>
            <select class="form-select" id="pic" name="pic">
                <option value="">-- Pilih PIC BDO --</option>
                <?php foreach ($users_ccc as $pic): ?>
                    <option value="<?= $pic['username']; ?>">
                        <?= $pic['username']; ?> (
                        <?= $pic['name']; ?>)
                    </option>
                <?php endforeach; ?>
            </select>

        </div>

        <div class="form-group col-md-2">
            <label>Category Shipment</label>
            <select class="form-select" id="category_shipment" name="category_shipment"
                aria-label="Default select example">
                <option value="" selected disabled>-- Pilih Category Shipment --</option>
                <option value="DOMESTIC">DOMESTIC</option>
                <option value="INTERCITY">INTERCITY</option>
                <option value="INTRACITY">INTRACITY</option>
                <option value="INTRAREGIONAL">INTRAREGIONAL</option>
            </select>
        </div>

    </div>
    <div class="row">

        <div class="form-group col-md-2">
            <label>Service</label>
            <select class="form-select" id="service" name="service" aria-label="Default select example">
                <option value="">-- Pilih Service --</option>
                <?php foreach ($services as $service): ?>
                    <option value="<?= $service['service_code'] ?>" ?>
                        <?= $service['service_code'] ?> (<?= $service['service_name'] ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group col-md-2">
            <label>Cod Flag</label>
            <select class="form-select" id="cod_flag" name="cod_flag" aria-label="Default select example">
                <option value="" selected disabled>-- Pilih Cod Flag --</option>
                <option value="COD">COD</option>
                <option value="NON COD">NON COD</option>
            </select>
        </div>
        <div class="form-group col-md-2">
            <label>Zona Delivery</label>
            <select class="form-select" id="zone_delivery" name="zone_delivery" aria-label="Default select example">
                <option value="" selected disabled>-- Pilih Zona Delivery --</option>
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="C">C</option>
            </select>
        </div>

        <div class="form-group col-md-3">
            <label>Status POD</label>
            <select class="form-select" id="status_pod" name="status_pod[]" multiple>

                <option value="">-- Pilih Status POD --</option>
                <?php foreach ($status_pod as $status): ?>
                    <option value="<?= $status['pod_code'] ?>">
                        <?= $status['pod_code'] ?> (<?= $status['pod_status'] ?>)
                    </option>
                <?php endforeach; ?>

            </select>
        </div>
        <div class="form-group col-md-3">
            <label>Customer LM</label>
            <select class="form-select" id="customer_lm" name="customer_lm">
                <option value="">-- Pilih Customer --</option>

                <?php foreach ($grouping_customers as $grouping_customer): ?>
                    <option value="<?= $grouping_customer['account_number'] ?>">
                        <?= $grouping_customer['big_grouping_cust'] ?>
                    </option>
                <?php endforeach; ?>

            </select>
        </div>
        <div class="form-group col-md-2">
            <label>Type Cust</label>
            <select class="form-select" id="type_cust" name="type_cust[]" multiple>


                <?php foreach ($type_cust as $cust): ?>
                    <option value="<?= $cust['cust_industry'] ?>">
                        <?= $cust['cust_industry'] ?>
                    </option>
                <?php endforeach; ?>

            </select>
        </div>


        <!-- Hidden Input jika tidak boleh ubah -->
        <?php if (!$full_access && !$zone_only): ?>
            <input type="hidden" name="origin" value="<?= $selected_origin ?>">
            <input type="hidden" name="zone" value="<?= $zone ?>">
        <?php endif; ?>
        <?php
        $seg1 = $this->uri->segment(1);
        $seg2 = $this->uri->segment(2);

        if (
            ($seg1 == 'last_mile' && $seg2 == 'import') ||
            in_array($seg2, ['upload_lm', 'performance_shipment_lm', 'status_shipment_lm'])
        ):
            ?>

            <input type="hidden" name="origin" value="<?= $selected_origin ?>">

            <div class="form-group col-auto d-flex align-items-end">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fas fa-filter me-2"></i> Filter Data
                </button>
            </div>

        <?php endif; ?>
    </div>
</form>
<script>
    $(document).ready(function () {
        $('#customer_lm').select2({
            placeholder: "-- Pilih Customer --",
            allowClear: true,
            width: '100%'
        });
        $('#origin').select2({
            placeholder: "-- Pilih Origin --",
            allowClear: true,
            width: '100%'
        });
        $('#status_pod').select2({
            placeholder: "-- Pilih Status Pod --",
            allowClear: true,
            width: '100%'
        });
        $('#regional').select2({
            placeholder: "-- Pilih Regional --",
            allowClear: true,
            width: '100%'
        });
        $('#pic').select2({
            placeholder: "-- Pilih PIC --",
            allowClear: true,
            width: '100%'
        });
        $('#service').select2({
            placeholder: "-- Pilih Service --",
            allowClear: true,
            width: '100%'
        });

        $('#zone').select2({
            placeholder: "-- Pilih Zone --",
            allowClear: true,
            width: '100%'
        });

        $('#type_cust').select2({
            placeholder: "-- Pilih Type Customer --",
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