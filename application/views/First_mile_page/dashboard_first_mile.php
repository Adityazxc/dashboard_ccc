<?php $this->load->view('form_page/filter_form_fm') ?>

<style>
    .card:hover {
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
        transform: translateY(-3px);
    }
    .stats-value small{
    font-size: 13px;
    margin-top: 4px;
}
    .stats-card {
        border: none;
        border-radius: 18px;
        overflow: hidden;
        transition: all .25s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, .06);
    }

    .stats-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 24px rgba(0, 0, 0, .12);
    }

    .stats-icon {
        width: 58px;
        height: 58px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        color: #fff;
    }

    .bg-purple {
        background: linear-gradient(135deg, #7b61ff, #5b42f3);
    }

    .bg-green {
        background: linear-gradient(135deg, #1cc88a, #0fa968);
    }

    .bg-orange {
        background: linear-gradient(135deg, #ffb547, #ff8c00);
    }

    .bg-blue {
        background: linear-gradient(135deg, #36b9cc, #258391);
    }

    .bg-red {
        background: linear-gradient(135deg, #ff6b6b, #e63946);
    }

    .stats-title {
        font-size: 13px;
        color: #8c8c8c;
        margin-bottom: 4px;
    }

    .stats-value {
        font-size: 28px;
        font-weight: 700;
        color: #2d3436;
        line-height: 1;
    }
</style>

<div class="row g-4">

    <!-- Total Shipment -->
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card stats-card h-100">
            <div class="card-body d-flex align-items-center">

                <div class="stats-icon bg-purple me-3">
                    <i class="bi bi-box-seam"></i>
                </div>

                <div>
                    <div class="stats-title">Total Shipment</div>
                    <div class="stats-value total_shipment">0</div>
                </div>

            </div>
        </div>
    </div>

    <!-- Amount -->
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card stats-card h-100">
            <div class="card-body d-flex align-items-center">

                <div class="stats-icon bg-green me-3">
                    <i class="bi bi-cash-stack"></i>
                </div>

                <div>
                    <div class="stats-title">Total Amount</div>
                    <div class="stats-value total_amount">0</div>
                </div>

            </div>
        </div>
    </div>

    <!-- On Proses -->
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card stats-card h-100">
            <div class="card-body d-flex align-items-center">

                <div class="stats-icon bg-orange me-3">
                    <i class="bi bi-arrow-repeat"></i>
                </div>

                <div>
                    <div class="stats-title">On Process</div>
                    <div class="stats-value on_proses_count">0</div>
                </div>

            </div>
        </div>
    </div>

    <!-- Delivered -->
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card stats-card h-100">
            <div class="card-body d-flex align-items-center">

                <div class="stats-icon bg-blue me-3">
                    <i class="bi bi-check-circle-fill"></i>
                </div>

                <div>
                    <div class="stats-title">Delivered</div>
                    <div class="stats-value delivered_count">0</div>
                </div>

            </div>
        </div>
    </div>

    <!-- Return -->
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card stats-card h-100">
            <div class="card-body d-flex align-items-center">

                <div class="stats-icon bg-red me-3">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </div>

                <div>
                    <div class="stats-title">Return</div>
                    <div class="stats-value return_count">0</div>
                </div>

            </div>
        </div>
    </div>

</div>

<div class="row mt-3">



    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Pay Type</div>
            </div>
            <div class="card-body">
                <div class="chart-container" style="position: relative; height:350px;">
                    <canvas id="PayTypeChart"></canvas>

                    <!-- Empty State -->
                    <div id="PayTypeEmptyState" style="display:none; 
                position:absolute; 
                top:50%; 
                left:50%; 
                transform:translate(-50%, -50%);
                text-align:center;
                color:#9aa0ac;">
                        <i class="fas fa-chart-pie" style="font-size:48px; opacity:0.3;"></i>
                        <div style="margin-top:10px; font-size:14px;">
                            Data tidak tersedia
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Service</div>
            </div>
            <div class="card-body">
                <div class="chart-container" style="position: relative; height:350px;">
                    <canvas id="serviceChart"></canvas>

                    <!-- Empty State -->
                    <div id="serviceEmptyState" style="display:none; 
                position:absolute; 
                top:50%; 
                left:50%; 
                transform:translate(-50%, -50%);
                text-align:center;
                color:#9aa0ac;">
                        <i class="fas fa-chart-pie" style="font-size:48px; opacity:0.3;"></i>
                        <div style="margin-top:10px; font-size:14px;">
                            Data tidak tersedia
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


</div>
<!-- 
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Top Customer</div>
            </div>
            <div class="card-body">
                <div class="chart-container" style="position: relative; height:350px;">
                    <canvas id="topCustomer"></canvas>

                    <div id="customerEmptyState" style="display:none;
                position:absolute;
                top:50%;
                left:50%;
                transform:translate(-50%, -50%);
                text-align:center;
                color:#9aa0ac;">
                        <i class="fas fa-chart-bar" style="font-size:48px; opacity:0.3;"></i>
                        <div style="margin-top:10px; font-size:14px;">
                            Data tidak tersedia
                        </div>
                    </div>

                </div>
            </div>
        </div>


    </div> -->

<script>
    function formatNum(x) {
        return new Intl.NumberFormat('id-ID').format(x);
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
    var jumlah = () => {
        var formData = $('#filterForm').serialize();

        $.ajax({
            url: "<?= base_url('first_mile/summary_dashboard') ?>",
            dataType: "JSON",
            type: "POST",
            data: formData,

            success: (r) => {

                let totalShipment = parseInt(r.total_shipment || 0);
                let delivered = parseInt(r.delivered_count || 0);
                let onProses = parseInt(r.on_proses_count || 0);
                let returned = parseInt(r.return_count || 0);

                // HITUNG PERSENTASE
                let deliveredPercent = totalShipment > 0
                    ? ((delivered / totalShipment) * 100).toFixed(1)
                    : 0;

                let onProsesPercent = totalShipment > 0
                    ? ((onProses / totalShipment) * 100).toFixed(1)
                    : 0;

                let returnPercent = totalShipment > 0
                    ? ((returned / totalShipment) * 100).toFixed(1)
                    : 0;

                // TOTAL
                $('.total_shipment').text(formatNum(totalShipment));

                // DELIVERED
                $('.delivered_count').html(`
            ${formatNum(delivered)}
            <small class="d-block text-success fw-semibold">
                ${deliveredPercent}%
            </small>
        `);

                // ON PROSES
                $('.on_proses_count').html(`
            ${formatNum(onProses)}
            <small class="d-block text-warning fw-semibold">
                ${onProsesPercent}%
            </small>
        `);

                // RETURN
                $('.return_count').html(`
            ${formatNum(returned)}
            <small class="d-block text-danger fw-semibold">
                ${returnPercent}%
            </small>
        `);

                // AMOUNT
                $('.total_amount').text(formatNum(r.total_amount || 0));

            },

            error: (xhr, status, error) => {
                console.error("AJAX ERROR:", xhr.responseText);
            }
        });
    }



</script>

<!-- Multiple Chart -->
<script>





    function loadServiceChart() {
        var formData = $('#filterForm').serialize();

        $.ajax({
            url: "<?= base_url('first_mile/getServiceChart') ?>",
            type: "POST",
            data: formData,
            dataType: "JSON",
            success: function (response) {
                var canvas = document.getElementById('serviceChart');
                var ctx = canvas.getContext('2d');

                if (window.myPieChart) {
                    window.myPieChart.destroy();
                }

                if (response.success) {

                    var serviceLabels = response.serviceLabels || [];
                    var serviceCounts = response.serviceCounts || [];

                    var total = serviceCounts.reduce((a, b) => a + b, 0);

                    if (total === 0) {
                        $('#serviceEmptyState').show();
                        return;
                    } else {
                        $('#serviceEmptyState').hide();
                    }

                    window.myPieChart = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            datasets: [{
                                data: serviceCounts,
                                backgroundColor: [
                                    "#1d7af3",
                                    "#f0582c",
                                    "#5fbc55",
                                    "#211f20",
                                    "#9b59b6",
                                    "#f1c40f",
                                    "#e74c3c",
                                    "#1abc9c"
                                ],
                                borderWidth: 0
                            }],
                            labels: serviceLabels,
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
                                            var value = serviceCounts[tooltipItem.dataIndex];
                                            var percentage = ((value / total) * 100).toFixed(2);
                                            return `${serviceLabels[tooltipItem.dataIndex]}: ${value} (${percentage}%)`;
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
    function loadPayTypeChart() {
        var formData = $('#filterForm').serialize();

        $.ajax({
            url: "<?= base_url('first_mile/getPayTypeChart') ?>",
            type: "POST",
            data: formData,
            dataType: "JSON",
            success: function (response) {
                var canvas = document.getElementById('PayTypeChart');
                var ctx = canvas.getContext('2d');

                if (window.PayTypeChartInstance) {
                    window.PayTypeChartInstance.destroy();
                }

                if (response.success) {

                    var PayTypeLabels = response.PayTypeLabels || [];
                    var PayTypeCounts = response.PayTypeCounts || [];

                    var total = PayTypeCounts.reduce((a, b) => a + b, 0);

                    if (total === 0) {
                        $('#PayTypeEmptyState').show();
                        return;
                    } else {
                        $('#PayTypeEmptyState').hide();
                    }

                    window.PayTypeChartInstance = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            datasets: [{
                                data: PayTypeCounts,
                                backgroundColor: [
                                    "#1d7af3",
                                    "#f0582c",
                                    "#5fbc55",
                                    "#211f20",
                                    "#9b59b6",
                                    "#f1c40f",
                                    "#e74c3c",
                                    "#1abc9c"
                                ],
                                borderWidth: 0
                            }],
                            labels: PayTypeLabels,
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
                                            var value = PayTypeCounts[tooltipItem.dataIndex];
                                            var percentage = ((value / total) * 100).toFixed(2);
                                            return `${PayTypeLabels[tooltipItem.dataIndex]}: ${value} (${percentage}%)`;
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
    let topCustomerChart = null;
    // function loadTopCustomers() {

    //     var formData = $('#filterForm').serialize();

    //     $.ajax({
    //         url: "<?= base_url('first_mile/getTopCustomers') ?>",
    //         type: "POST",
    //         data: formData,
    //         dataType: "JSON",
    //         success: function (response) {

    //             if (response.labels.length === 0) {
    //                 $('#customerEmptyState').show();
    //                 return;
    //             }

    //             $('#customerEmptyState').hide();

    //             // Gabungkan data untuk sorting
    //             let dataGabung = response.labels.map(function (label, i) {
    //                 return {
    //                     label: label,
    //                     value: response.total_shipment[i]
    //                 }
    //             });

    //             // Urutkan dari terbesar
    //             dataGabung.sort((a, b) => b.value - a.value);

    //             // Pecah lagi setelah sorting
    //             let labels = dataGabung.map(d => d.label);
    //             let values = dataGabung.map(d => d.value);

    //             const ctx = document.getElementById('topCustomer').getContext('2d');

    //             if (topCustomerChart) {
    //                 topCustomerChart.destroy();
    //             }

    //             topCustomerChart = new Chart(ctx, {
    //                 type: 'bar',
    //                 data: {
    //                     labels: labels,
    //                     datasets: [{
    //                         data: values,
    //                         backgroundColor: '#4e73df',
    //                         borderRadius: 6,
    //                         barThickness: 18
    //                     }]
    //                 },
    //                 options: {
    //                     indexAxis: 'y', // ini bikin horizontal
    //                     responsive: true,
    //                     maintainAspectRatio: false,
    //                     plugins: {
    //                         legend: {
    //                             display: false
    //                         }
    //                     },
    //                     scales: {
    //                         x: {
    //                             beginAtZero: true
    //                         },
    //                         y: {
    //                             ticks: {
    //                                 autoSkip: false
    //                             }
    //                         }
    //                     }
    //                 }
    //             });

    //         },
    //         error: function () {
    //             alert('Terjadi kesalahan saat memuat data.');
    //         }
    //     });
    // }


    $(document).ready(function () {
        jumlah();
        loadServiceChart(); // Muat pertama kali
        loadPayTypeChart();
        // loadTopCustomers();

        $('#serviceEmptyState').hide();
        $('#cetegoryEmptyState').hide();
        $('#filterForm').on('change', function () {
            jumlah();

            loadPayTypeChart();
            loadServiceChart(); // Muat ulang saat filter berubah
            // loadTopCustomers();

        });


    });
</script>