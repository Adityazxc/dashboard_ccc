<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<!-- Bootstrap Datepicker CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"
    rel="stylesheet" />

<!-- Bootstrap Datepicker JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>


<form id="filterForm">
    <div class="row">
        <!-- DATE FILTER -->
        <div class="form-group col-md-3">
            <label for="dateFrom">From:</label>
            <input type="month" class="form-control" id="dateFrom" name="dateFrom"
                value="<?= $this->input->get('dateFrom') ?? date('Y-m') ?>">
        </div>

        <?php
        $full_access = in_array($role, [
            'Admin',
            'BPS',
            'Super User',
            'HC',
            'PAO',
            'CS',
            'CCC',
            'Kepala Cabang',
            "Admin BDO2",
            "Koordinator BDO2",
            "Koordinator"
        ]);
        $zone_only = in_array($role, ['Kepala Cabang BDO2', 'BBP']);
        $get_origins_array = json_decode($get_origins, true);

        // 🔥 Prioritaskan origin dari filter GET, kalau tidak ada fallback ke session origin
        $selected_origin = $this->input->get('origin') ?? null;
        $selected_zone = $this->input->get('zone') ?? $zone;
        ?>

        <!-- ORIGIN -->
        <div class="form-group col-md-3">
            <label for="origin" class="form-label required">Origin:</label>
            <select class="form-select select2" name="origin" id="origin" <?= (!$full_access) ? 'disabled' : '' ?>>
                <option value="">-- Pilih Origin --</option>
                <?php foreach ($get_origins_array as $get_origin): ?>
                    <option value="<?= $get_origin['origin_code'] ?>" <?= ($get_origin['origin_code'] == $selected_origin) ? 'selected' : '' ?>>
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
                <?php if (!empty($selected_zone)): ?>
                    <option value="<?= $selected_zone ?>" selected><?= $selected_zone ?></option>
                <?php endif; ?>
            </select>
        </div> -->

        <!-- Hidden Input jika tidak boleh ubah -->
        <?php if (!$full_access && !$zone_only): ?>
            <input type="hidden" name="origin" value="<?= $selected_origin ?>">
            <input type="hidden" name="zone" value="<?= $selected_zone ?>">
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
                const selectedZone = '<?= $selected_zone ?>';
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


<div class="container my-5">
    <div class="row justify-content-center" id="top3_container"></div>
</div>




<!-- Top Kurir -->
<div class="row">
    <div class="col-lg-12">
        <div class="card shadow border-0 rounded-4">
            <!-- Judul tabel -->
            <div class="card-header text-white fw-bold rounded-top-4"
                style="background: linear-gradient(90deg, #6a11cb, #2575fc); ">
                Top Courier Ranking
            </div>

            <div class="card-body p-4">
                <div class="table-responsive table-scroll">
                <table id="table_top_courier"
                class="table table-borderless table-hover align-middle text-center leaderboard-table">
                        <thead >
                        <tr class="custom-row">
                                <th>No</th>
                                <th>Photo Kurir</th>
                                <th>Kurir</th>
                                <th>Succes Rate</th>
                                <th>KPI</th>
                                <th>Photo Pod</th>
                                <th>HRS</th>
                                <th>Minus Poin</th>
                                <th>Total Poin</th>
                                <th>Zone</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data dari PHP / DB kamu otomatis masuk di sini -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="courierImageModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Foto Kurir</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
        <img id="courierImagePreview" src="" class="img-fluid rounded">
      </div>
    </div>
  </div>
</div>
<script>
function showCourierImage(src) {
    document.getElementById('courierImagePreview').src = src;
    var modal = new bootstrap.Modal(document.getElementById('courierImageModal'));
    modal.show();
}
</script>


<style>
    .courier-img {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    object-fit: cover;
    cursor: pointer;
    border: 2px solid #ddd;
    transition: 0.2s;
}

.courier-img:hover {
    transform: scale(1.1);
    border-color: #0d6efd;
}

</style>
<style>
   

    .custom-row td div {
        margin-bottom: 5px;
        border: 2px solid #FFBF00;
        border-radius: 8px;
        padding: 10px;
    }
</style>


<style>
    /* Base style semua card row */
    /* SIMPLE VERSION */
    #table_top_courier {
        border-collapse: separate;
        border-spacing: 0 10px;
    }

   

    #table_top_courier tbody tr {
        background: white;
        border-radius: 10px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    }

    #table_top_courier tbody td {
        border: none;
        padding: 15px;
        border-top: 1px solid #f0f0f0;
        border-bottom: 1px solid #f0f0f0;
    }

    #table_top_courier tbody tr td:first-child {
        border-left: 1px solid #f0f0f0;
        border-radius: 10px 0 0 10px;
    }

    #table_top_courier tbody tr td:last-child {
        border-right: 1px solid #f0f0f0;
        border-radius: 0 10px 10px 0;
    }

    /* Rank coloring */
    #table_top_courier tbody tr:nth-child(1) {
        border-left: 4px solid gold;
    }

    #table_top_courier tbody tr:nth-child(2) {
        border-left: 4px solid silver;
    }

    #table_top_courier tbody tr:nth-child(3) {
        border-left: 4px solid #cd7f32;
    }



    #table_top_courier tbody tr td {
        border: none !important;
    }

    #table_top_courier tbody tr {
        background: transparent !important;
    }


    
    
</style>
<script>
    $(document).ready(function () {
        // var role= "<?= $role ?>";        
        var table = $('#table_top_courier').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 10,
            searching: true,
            ajax: {
                url: "<?= base_url('leaderboard/getdatatables_courier') ?>",
                type: "POST",
                data: function (d) {
                    d.dateFrom = $('[name="dateFrom"]').val();
                    d.origin = $('[name="origin"]').val().trim();
                    d.type = 'top';
                }
            },

            rowCallback: function (row, data, index) {
                // DataTables serverSide: data[0] = nomor urut
                var rank = parseInt(data[0]);

                // Reset default class rows
                $(row).removeClass();

                // Tambah custom class row-card + rank
                $(row).addClass("leaderboard-row rank-" + rank);
            }
        });
        $('[name="dateFrom"], [name="dateThru"], [name="origin"], [name="zone"]').on('change', function () {
            table.ajax.reload(null, false); // reload without resetting the paging        
            loadTop3();
        });

        function loadTop3() {
            $.ajax({
                url: "<?= base_url('leaderboard/get_top3_ajax') ?>",
                type: "POST",
                dataType: "json",
                data: {
                    dateFrom: $('[name="dateFrom"]').val(),
                    origin: $('[name="origin"]').val(),
                    zone: $('[name="zone"]').val()
                },
                success: function (res) {
                    let html = '';
                    const positions = [1, 2, 3]; // urutan podium

                    positions.forEach((rank, idx) => {
                        if (res[idx]) {
                            const courier = res[idx];
                            const tidakSesuai = courier.total_qty_awb - courier.total_qty_sesuai - courier.total_qty_revisi;
                            const type_courier = courier.type_courier;
                            let icon_courier = (type_courier === "DRIVER") ? "driver" : "raider";
                            html += `
                       <div class="col-md-3 ${rank === 1 ? 'position-first' : ''}">
                            <div class="card text-center shadow-lg border-0" style="border-radius: 15px;">
                                <div class="card-header text-white position-relative rank-${rank}"
                                    style="height:12rem; border-radius: 15px 15px 0 0;                                    
                                        background: url('<?= base_url("public/img/") ?>rank${rank}.png') no-repeat center center;
                                        background-size: cover;">
                                    <span class="position-absolute top-0 end-0 m-2" 
                                        style="font-family: 'Montserrat', sans-serif; font-size: 3.5rem; font-style:italic;font-weight: bold; ">
                                        ${rank}<sup>${rank === 1 ? 'st' : rank === 2 ? 'nd' : 'rd'}</sup>
                                    </span>

                                    <!-- Gambar Profil -->

                                  <div class="position-absolute" 
                                style="bottom: -40px; left: 20px; z-index: 2;">
                                <div class="hexagon-container">
                                    <div class="hexagon">
                                        <img src="<?= base_url('uploads/image_courier/courier.png') ?>" 
                                            alt="${courier.courier_name}">
                                    </div>
                                </div>
                            </div>
                                </div>

                                
                                <!-- Tambahkan margin-top pada card-body agar tidak tertutup gambar -->
                                <div class="card-body">
                                    <div class="col d-flex justify-content-end align-self-end mb-2">
                                        <div class="btn custom-primary-btn" 
                                            style="background-color: #383486; color: #fff; border: none; border-radius: 10px; font-weight: 800; font-size: larger;">
                                            ${courier.total_poin} Pts
                                        </div>
                                    </div>
                                    <h6 class="mb-1 text-start fw-bold text-truncate" 
                                        style="font-family: 'Montserrat', sans-serif; font-size: 1rem; font-weight: 800;"
                                        data-bs-toggle="tooltip" 
                                        data-bs-placement="top"
                                        title="${courier.courier_name}">
                                        ${courier.courier_name}
                                        <img class="ms-2" src="<?= base_url('public/img/') ?>${icon_courier}.png" alt=""> 
                                    </h6>
                                  <p class="text-start fw-medium" 
                                    style="
                                            background: linear-gradient(90deg, 
                                                ${rank === 1 ? '#BF36CE, #332BC2' : 
                                                rank === 2 ? '#45A4D6, #A54B4C' : 
                                                rank === 3 ? '#E26E55, #6399A6' : 
                                                '#383486, #6A67CE'});
                                            -webkit-background-clip: text;
                                            background-clip: text;
                                            -webkit-text-fill-color: transparent;
                                            font-weight: 600;
                                    ">
                                        ${courier.id_courier} - ${courier.zone}
                                    </p>
                                    <div class="d-flex justify-content-around text-center mt-3">
                                        <div>
                                            <span style="color:#5C5C5C; font-size: 2rem; font-weight:bold;">
                                                ${courier.total_qty_sesuai}
                                            </span><br>
                                            <small style="font-weight:bold;">Sesuai</small>
                                        </div>
                                        <div class="vr"></div>
                                        <div>
                                            <span style="color:#5C5C5C; font-size: 2rem; font-weight:bold;">
                                                ${tidakSesuai}
                                            </span><br>
                                            <small style="font-weight:bold;">Tidak Sesuai</small>
                                        </div>
                                        <div class="vr"></div>
                                        <div>
                                            <span style="color:#5C5C5C; font-size: 2rem; font-weight:bold;">
                                                ${courier.total_qty_revisi}
                                            </span><br>
                                            <small style="font-weight:bold;">Revisi</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                        }
                    });

                    $("#top3_container").html(html || "<p class='text-center'>Tidak ada data</p>");
                }
            });
        }

        $(document).ready(function () {
            loadTop3();
            $('[name="dateFrom"], [name="origin"], [name="zone"]').on('change', loadTop3);
        });


        loadTop3();
    });

</script>