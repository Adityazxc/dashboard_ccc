<style>
    .card:hover {
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
        transform: translateY(-3px);
    }
</style>
<?php
$success = isset($get_summmary_awb->total_success) ? $get_summmary_awb->total_success : "0";
$inprogress = isset($get_summmary_awb->total_inprogress) ? $get_summmary_awb->total_inprogress : "0";
$failed = isset($get_summmary_awb->total_failed) ? $get_summmary_awb->total_failed : "0";
$total_awb = isset($data_awb[0]->sum_qty_awb) ? $data_awb[0]->sum_qty_awb : "0";
$percent_success = ($total_awb > 0) ? round(($success / $total_awb) * 100, 2) : 0;
$percent_failed = ($total_awb > 0) ? round(($failed / $total_awb) * 100, 2) : 0;
$percent_inprogress = ($total_awb > 0) ? round(($inprogress / $total_awb) * 100, 2) : 0;

?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <a href="<?= base_url('leaderboard') ?>">
                        <i class="bi bi-arrow-left fs-2 m-2"></i>
                    </a>
                    <h4 class="header-title m-2">Statistik Kurir</h4>
                </div>
            </div>


            <style>
                .flip-card {
                    background: transparent;
                    width: 90vw;
                    max-width: 700px;
                    aspect-ratio: 16 / 9;
                    height: auto;
                    perspective: 1000px;
                    margin: 0 auto;
                }

                .flip-card-inner {
                    position: relative;
                    width: 100%;
                    height: 100%;
                    transition: transform 0.8s;
                    transform-style: preserve-3d;
                }

                .flip-card.flipped .flip-card-inner {
                    transform: rotateY(180deg);
                }

                .flip-card-front,
                .flip-card-back {
                    position: absolute;
                    width: 100%;
                    height: 100%;
                    -webkit-backface-visibility: hidden;
                    backface-visibility: hidden;
                    border-radius: 1rem;
                    box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15);
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    padding: 1rem;
                    box-sizing: border-box;
                }

                .flip-card-front {
                    background: #E0589E;
                    /* pink */
                    color: white;
                }

                .flip-card-back {
                    background: #fff;
                    color: #333;
                    transform: rotateY(180deg);
                    box-shadow: 0 0 10px rgba(236, 72, 153, 0.8);
                    /* shadow pink */
                    border: 1px solid #E0589E40;
                }

                @media (min-width: 768px) {
                    .flip-card {
                        max-height: 300px;
                    }
                }

                
            </style>

            <row class="d-flex justify-content-center">
                <div class="flip-card " onclick="this.classList.toggle('flipped')" style="cursor: pointer,">
                    <div class="flip-card-inner">
                        <!-- FRONT -->

                        <div class="flip-card-front d-flex flex-column" style="height: 100%;">
                            <div class="row">
                                <div class="col  d-flex justify-content-start">
                                    <ul style="list-style:none" class="m-1 p-1">
                                        <li>
                                            <h1 style="color:white; font-size: 2rem;">
                                                <?= $data_courier[0]->courier_name ?>
                                            </h1>
                                        </li>
                                        <li>
                                            <h1 style="color:white;font-size: 1rem;">
                                                <?= $data_courier[0]->id_courier ?>
                                            </h1>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col d-flex justify-content-end">
                                    <ul style="list-style:none" class="mt-2 pt-2 me-1 pe-1">
                                        <li class="d-flex justify-content-center">
                                            <h1 style="color:white; font-size: 2rem;" id="sum-awb">
                                                <?= isset($data_awb[0]->sum_qty_awb) ? $data_awb[0]->sum_qty_awb : "0" ?>
                                            </h1>
                                        </li>
                                        <li>
                                            <h1 style="color:white;font-size: 1rem;">
                                                Total Awb</h1>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <!-- Tetap di bawah, tanpa absolute -->
                            <div class="mt-auto m-2">
                                <div class="d-flex align-items-end">
                                    <h1 style="color: white; font-size: 2.5rem; margin: 0;">75</h1>
                                    <p style="color: white; font-size: 1rem; margin: 0 0 4px 4px;">Point</p>
                                </div>
                            </div>

                            <div class="wave-decoration"></div>
                        </div>

                        <!-- BACK -->
                        <div class="flip-card-back">
                            <div class="row d-flex justify-content-start">
                                <!-- Left -->

                                <div class="col-lg-7">
                                    <div class="row">
                                        <h3 style="color:#575757;">Detail Performa</h3>
                                        <div class="d-flex justify-content-center">
                                            <center>
                                                <div class="row m-2">
                                                    <h3 style="color:#575757;">Total</h3>
                                                    <h5 style="color:#575757;"><?= $total_awb ?></h5>
                                                    <div class="col-lg-4">
                                                        <div class="row">
                                                            <ul style="list-style: none; padding-left: 0; margin: 0;">
                                                                <li>
                                                                    <h2
                                                                        style="color:#59F278; margin: 0;font-size:2rem;">
                                                                        <?= $percent_success ?>%
                                                                    </h2>
                                                                </li>
                                                                <li>
                                                                    <p style="color:#575757; margin: 0;font-size:1rem;">
                                                                        sukses</p>
                                                                </li>
                                                                <li>
                                                                    <p style="color:#575757; margin: 0;font-size:1rem;">
                                                                        (<?= $success ?> dari<?= $total_awb ?>)
                                                                    </p>
                                                                </li>
                                                            </ul>

                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="row">
                                                            <ul style="list-style: none; padding-left: 0; margin: 0;">
                                                                <li>
                                                                    <h2
                                                                        style="color:#FFA500; margin: 0; font-size:2rem;">
                                                                        <?= $percent_inprogress ?>%
                                                                    </h2>
                                                                </li>
                                                                <li>
                                                                    <p style="color:#575757; margin: 0;font-size:1rem;">
                                                                        Proses</p>
                                                                </li>
                                                                <li>
                                                                    <p style="color:#575757; margin: 0;font-size:1rem;">
                                                                        (<?= $inprogress ?> dari
                                                                        <?= $total_awb ?> )
                                                                    </p>
                                                                </li>
                                                            </ul>

                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="row">
                                                            <ul style="list-style: none; padding-left: 0; margin: 0;">
                                                                <li>
                                                                    <h2
                                                                        style="color:#F25961; margin: 0;font-size:2rem;">
                                                                        <?= $percent_failed ?>%
                                                                    </h2>
                                                                </li>
                                                                <li>
                                                                    <p style="color:#575757; margin: 0;font-size:1rem;">
                                                                        Gagal</p>
                                                                </li>
                                                                <li>
                                                                    <p style="color:#575757; margin: 0;font-size:1rem;">
                                                                        (<?= $failed ?> dari
                                                                        <?= $total_awb ?> )
                                                                    </p>
                                                                </li>
                                                            </ul>

                                                        </div>
                                                    </div>

                                                </div>
                                        </div>
                                        </center>
                                    </div>
                                </div>


                                <!-- Right -->

                                <div class="col-lg-5">
                                    <div class="row">
                                        <h3 style="color:#575757;">Detail Foto POD</h3>
                                        <div id="chartContainer">
                                            <canvas id="sourceFotoPodChart" width="250" height="250"></canvas>
                                            <div id="chartCenterText"
                                                style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); font-size:14px; text-align:center;">
                                            </div>
                                        </div>
                                        <div id="noDataAlert" class="alert alert-warning text-center d-none mt-2"
                                            role="alert">
                                            Data tidak tersedia
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </row>


            <div class="row mt-4 m-2">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <center>
                                <div class="card-title">SEBARAN KIRIMAN STATUS SUKSES</div>
                            </center>
                        </div>
                        <div class="card-body">
                            <div class="chart-container" style="position: relative;">
                                <canvas id="sourceSuccessCod"></canvas>
                            </div>
                        </div>


                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <center>
                                <div class="card-title">SEBARAN KIRIMAN STATUS GAGAL</div>
                            </center>
                        </div>
                        <div class="card-body">
                            <div class="chart-container" style="position: relative;">
                                <canvas id="sourceFailedCod"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>




<script>
    var id_courier = "<?= $id_courier ?>";
    function load_statistik_foto_pod() {
        var formData = {

            id_courier: id_courier,
        };

        $.ajax({
            url: "<?= base_url('leaderboard/statistik_photo_pod') ?>",
            type: "POST",
            data: formData,
            dataType: "JSON",
            success: function (response) {
                var canvas = document.getElementById('sourceFotoPodChart');
                var ctx = canvas.getContext('2d');

                // Hapus chart sebelumnya jika ada
                if (window.loadStatistikPod) {
                    window.loadStatistikPod.destroy();
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
                    window.loadStatistikPod = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            datasets: [{
                                data: sourceCounts,
                                backgroundColor: ["#CA498C", "#FDE3DF", "#E6BFCE", "#CF9BBD"],
                                borderWidth: 0
                            }],
                            labels: sourceLabels,
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'right',
                                    align: 'center',
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


</script>





<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script>
    function animateCount(id, targetValue, duration = 1000) {
        const element = document.getElementById(id);
        if (!element) return;

        const startTime = performance.now();

        function update(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            const current = Math.floor(progress * targetValue); // Tanpa desimal

            element.textContent = current;

            if (progress < 1) {
                requestAnimationFrame(update);
            }
        }

        requestAnimationFrame(update);
    }


    // Contoh: panggil saat halaman load
    document.addEventListener("DOMContentLoaded", () => {
        animateCount("sum-awb", <?= $total_awb ?>);
    });


    function load_status_succes() {
        var formData = {

            id_courier: id_courier,
        };

        $.ajax({
            url: "<?= base_url('leaderboard/get_succes_cod') ?>",
            type: "POST",
            data: formData,
            dataType: "JSON",
            success: function (response) {
                var canvas = document.getElementById('sourceSuccessCod');
                var ctx = canvas.getContext('2d');

                // Hapus chart sebelumnya jika ada
                if (window.successChart) {
                    window.successChart.destroy();
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
                    window.successChart = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            datasets: [{
                                data: sourceCounts,
                                backgroundColor: ["#CA498C", "#FDE3DF", "#E6BFCE", "#CF9BBD"],
                                borderWidth: 0
                            }],
                            labels: sourceLabels,
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'right',
                                    align: 'center',
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

    function load_status_failed() {
        var formData = {

            id_courier: id_courier,
        };

        $.ajax({
            url: "<?= base_url('leaderboard/get_failed_cod') ?>",
            type: "POST",
            data: formData,
            dataType: "JSON",
            success: function (response) {
                var canvas = document.getElementById('sourceFailedCod');
                var ctx = canvas.getContext('2d');

                // Hapus chart sebelumnya jika ada
                if (window.failedChart) {
                    window.failedChart.destroy();
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
                    window.failedChart = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            datasets: [{
                                data: sourceCounts,
                                backgroundColor: ["#CA498C", "#FDE3DF", "#E6BFCE", "#CF9BBD"],
                                borderWidth: 0
                            }],
                            labels: sourceLabels,
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'right',
                                    align: 'center',
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
        load_status_succes();
        load_statistik_foto_pod();
        load_status_failed()
        console.log(Chart.version);

        $('#dateFrom, #dateThru,#origin,#zone').on('change', function () {
            load_status_succes(); // Muat ulang saat filter berubah                
            load_status_failed()// Muat ulang saat filter berubah     
            load_statistik_foto_pod();
        });


    });
</script>