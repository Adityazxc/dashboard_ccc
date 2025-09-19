<form id="filterForm">
    <div class="row">
        <div class="form-group col-md-3">
            <label for="dateFrom">From:</label>
            <input type="date" class="form-control" id="dateFrom" name="dateFrom" value="<?= date('Y-m-d') ?>">
        </div>
        <div class="form-group col-md-3">
            <label for="dateThru">Thru:</label>
            <input type="date" class="form-control" id="dateThru" name="dateThru" value="<?= date('Y-m-d') ?>">
        </div>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
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

    </div>

</form>

<style>
    .card:hover {
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
        transform: translateY(-3px);
    }
</style>
<!-- Summary qty -->
<div class="row">
    <!-- qty selling -->
    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
            <div class="card-body">
                <div class="row">
                    <div class="col-5">
                        <div class="icon-big text-center">
                            <i class="fas fa-box"></i> <!-- Ikon kotak untuk total barang -->
                        </div>
                    </div>
                    <div class="col-7 col-stats">
                        <div class="numbers">
                            <p class="card-category">Total</p>
                            <div class="h5 mb-0 font-weight-bold text-gray-800 totalAwb"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Gross Profit -->

    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
            <div class="card-body">
                <div class="row">
                    <div class="col-5">
                        <div class="icon-big text-center">
                            <i class="far fa-check-circle"></i> <!-- Ikon check untuk sesuai -->
                        </div>
                    </div>
                    <div class="col-7 col-stats">
                        <div class="numbers">
                            <p class="card-category">Sesuai</p>
                            <div class="h5 mb-0 font-weight-bold text-gray-800 approve"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php 
    $tidak_sesuai = "qty_tidak_sesuai";
    $encoded = base64_encode($tidak_sesuai); // Hasil: "VGlkYWsgU2VzdWFp"
    $text_revision = "qty_revisi";
    $revision = base64_encode($text_revision); // Hasil: "VGlkYWsgU2VzdWFp"
    
    ?>
    
    <!-- Profit -->
    <div class="col-sm-6 col-md-3">
        <div class="card card-stats  card-round"
       onclick="window.location.href='dashboard/not_approve/data?filter=<?= $encoded ?>';"
        style="cursor: pointer;border-bottom: 4px solid red;">
            <div class="card-body">
                <div class="row">
                    <div class="col-5">
                        <div class="icon-big text-center">
                            <i class="far fa-times-circle"></i> <!-- Ikon X untuk tidak sesuai -->
                        </div>
                    </div>
                    <div class="col-7 col-stats">
                        <div class="numbers">
                            <p class="card-category">Tidak Sesuai</p>
                            <div class="h5 mb-0 font-weight-bold text-gray-800 notApprove"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Revisi -->
    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round"onclick="window.location.href='dashboard/not_approve/data?filter=<?= $revision ?>';"
        style="cursor: pointer;border-bottom: 4px solid ; ">
            <div class="card-body">
                <div class="row">
                    <div class="col-5">
                        <div class="icon-big text-center">
                            <i class="far fa-edit"></i> <!-- Ikon X untuk tidak sesuai -->
                        </div>
                    </div>
                    <div class="col-7 col-stats">
                        <div class="numbers">
                            <p class="card-category">Revisi</p>
                            <div class="h5 mb-0 font-weight-bold text-gray-800 revision"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Pie Chart -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Pie Chart</div>
            </div>
            <div class="card-body">
                <div class="chart-container" style="position: relative;">
                    <canvas id="sourcePieChart"></canvas>

                </div>

            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <div class="row">

                    <div class="col">
                        <div class="card-title">Multiple Line Chart</div>
                    </div>
                    <div class="col">
                        <select id="yearFilter" class="form-control">
                            <?php
                            $currentYear = date("Y");
                            for ($i = $currentYear; $i >= 2025; $i--) {
                                echo "<option value='{$i}'>{$i}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="multipleLineChart" style="width: 100%; height: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

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
    var jumlah = () => {
        var formData = {
            dateFrom: $('[name="dateFrom"]').val(),
            dateThru: $('[name="dateThru"]').val(),
            origin: $('[name="origin"]').val().trim(),
            zone: $('[name="zone"]').val().trim(),
        };

        // BOX 1 

        $.ajax({
            url: "admin/summary_dashboard",
            dataType: "JSON",
            type: "POST",
            data: formData,
            success: (r) => {
                console.log(r.totalAwb);
                console.log("hehe");

                $('.totalAwb').text(r.totalAwb || 0);
                $('.approve').text((r.approve || 0));
                $('.notApprove').text((r.notApprove || 0));
                $('.revision').text((r.revision || 0));

            },
            error: (xhr, status, error) => {
                console.error("AJAX ERROR:", xhr.responseText);
            }
        });
    }

    $(document).ready(function () {
        jumlah();
        $('[name="dateFrom"], [name="dateThru"],[name="origin"], [name="zone"]').on('change', () => {
            jumlah();
        });
    });

</script>

<!-- Multiple Chart -->
<script>


    function lineMultipleChart() {
        var selectedYear = $('#yearFilter').val() || new Date().getFullYear();

        var formData = {
            year: selectedYear,
            origin: $('[name="origin"]').val().trim(),
            zone: $('[name="zone"]').val().trim(),
        };

        $.ajax({
            url: "<?= base_url('admin/getSourceDataMultiple') ?>",
            type: "POST",
            data: formData,
            dataType: "JSON",
            success: function (response) {
                if (response.success) {
                    var dataBySource = response.dataBySource || {};
                    var months = response.months || [];

                    var datasets = [];
                    var colors = ["#42B549", "#EE4D2D", "#000000", "#9E9E9E", "#1428A0", "#FFC107"];
                    var sources = Object.keys(dataBySource);

                    sources.forEach(function (source, index) {
                        datasets.push({
                            label: source,
                            data: Object.values(dataBySource[source]),
                            borderColor: colors[index % colors.length],
                            backgroundColor: 'transparent',
                            pointBorderWidth: 2,
                            pointHoverRadius: 4,
                            pointHoverBorderWidth: 1,
                            pointRadius: 4,
                            borderWidth: 2,
                            fill: false,
                            tension: 0.3
                        });
                    });

                    if (window.multipleLineChart && typeof window.multipleLineChart.destroy === 'function') {
                        window.multipleLineChart.destroy();
                    }

                    var ctx = document.getElementById('multipleLineChart').getContext('2d');
                    window.multipleLineChart = new Chart(ctx, {
                        type: 'line',
                        data: { labels: months, datasets: datasets },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        fontColor: 'rgb(154, 154, 154)',
                                        fontSize: 11,
                                        usePointStyle: true,
                                        padding: 20
                                    }
                                },
                                tooltip: {
                                    mode: "index",
                                    intersect: false
                                }
                            },
                            scales: {
                                x: {
                                    title: { display: true, text: "Bulan" },
                                    grid: { drawOnChartArea: false }
                                },
                                y: {
                                    title: { display: true, text: "Jumlah Penjualan" },
                                    beginAtZero: true
                                }
                            },
                            layout: {
                                padding: { left: 20, right: 20, top: 20, bottom: 20 }
                            }
                        }
                    });
                } else {
                    alert('Gagal memuat data chart.');
                }
            },
            error: function () {
                alert('Terjadi kesalahan saat memuat data.');
            }
        });
    }



    function loadSourcePieChart() {
        var formData = {
            dateFrom: $('[name="dateFrom"]').val(),
            dateThru: $('[name="dateThru"]').val(),
            origin: $('[name="origin"]').val().trim(),
            zone: $('[name="zone"]').val().trim(),
        };

        $.ajax({
            url: "<?= base_url('admin/getSourceData') ?>",
            type: "POST",
            data: formData,
            dataType: "JSON",
            success: function (response) {
                var canvas = document.getElementById('sourcePieChart');
                var ctx = canvas.getContext('2d');

                // Hapus chart sebelumnya jika ada
                if (window.myPieChart) {
                    window.myPieChart.destroy();
                }

                if (response.success) {
                    var sourceLabels = response.sourceLabels || [];
                    var sourceCounts = response.sourceCounts || [];

                    var total = sourceCounts.reduce((a, b) => a + b, 0);

                    if (total === 0) {
                        // Tampilkan pesan di tengah canvas
                        ctx.clearRect(0, 0, canvas.width, canvas.height);
                        ctx.font = "16px Arial";
                        ctx.textAlign = "center";
                        ctx.textBaseline = "middle";
                        ctx.fillStyle = "#888";
                        ctx.fillText("Data tidak tersedia", canvas.width / 2, canvas.height / 2);
                        return;
                    }

                    // Gambar pie chart jika ada data
                    window.myPieChart = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            datasets: [{
                                data: sourceCounts,
                                backgroundColor: ["#1d7af3", "#f0582c", "#5fbc55", "#211f20"],
                                borderWidth: 0
                            }],
                            labels: sourceLabels,
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        fontColor: 'rgb(154, 154, 154)',
                                        fontSize: 11,
                                        usePointStyle: true,
                                        padding: 20
                                    }
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function (tooltipItem) {
                                            var value = sourceCounts[tooltipItem.dataIndex];
                                            var percentage = ((value / total) * 100).toFixed(2);
                                            return `${sourceLabels[tooltipItem.dataIndex]}: ${value} (${percentage}%)`;
                                        }
                                    }
                                }
                            },
                            layout: {
                                padding: { left: 20, right: 20, top: 20, bottom: 20 }
                            }
                        }
                    });
                } else {
                    alert('Gagal memuat data chart.');
                }
            },
            error: function () {
                alert('Terjadi kesalahan saat memuat data.');
            }
        });
    }


    // Event listener untuk memuat ulang chart saat tanggal berubah
    $(document).ready(function () {
        loadSourcePieChart(); // Muat pertama kali
        lineMultipleChart();
        $('#dateFrom, #dateThru,#origin,#zone').on('change', function () {
            loadSourcePieChart(); // Muat ulang saat filter berubah
            lineMultipleChart();
        });

        $('#yearFilter,#origin,#zone').on('change', function () {
            lineMultipleChart();
        });
    });
</script>