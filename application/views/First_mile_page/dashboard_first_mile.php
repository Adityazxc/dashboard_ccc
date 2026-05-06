<?php $this->load->view('form_page/filter_form_fm') ?>
<style>
    .card:hover {
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
        transform: translateY(-3px);
    }
</style>
<div class="row row-cols-1 row-cols-sm-2 row-cols-md-5">
    <div class="col">
        <div class="card card-stats card-round">
            <div class="card-body">
                <div class="row">
                    <div class="col-3 me-0 pe-0">
                        <div class="icon-big text-center">
                            <i class="bi bi-safe"></i>
                        </div>
                    </div>
                    <div class="col-9 col-stats ms-0 ps-0">
                        <div class="numbers">
                            <p class="card-category">Cnote</p>
                            <div class="h5 mb-0 font-weight-bold text-gray-800 total_shipment"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Total Paid -->
    <div class="col">
        <div class="card card-stats card-round">
            <div class="card-body">
                <div class="row">
                    <div class="col-3 me-0 pe-0">
                        <div class="icon-big text-center">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                    </div>
                    <div class="col-9 col-stats ms-0 ps-0">
                        <div class="numbers">
                            <p class="card-category">Ammount</p>
                            <div class="h5 mb-0 font-weight-bold text-gray-800 total_amount"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Transfer -->

    <div class="col">
        <div class="card card-stats card-round">
            <div class="card-body">
                <div class="row">
                    <div class="col-3 me-0 pe-0">
                        <div class="icon-big text-center">
                            <i class="fas fa-credit-card"></i> <!-- Ikon check untuk sesuai -->
                        </div>
                    </div>
                    <div class="col-9 col-stats ms-0 ps-0">
                        <div class="numbers">
                            <p class="card-category">On Proses</p>
                            <div class="h5 mb-0 font-weight-bold text-gray-800 on_proses_count"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Cash -->
    <div class="col">
        <div class="card card-stats  card-round">
            <div class="card-body">
                <div class="row">
                    <div class="col-3 me-0 pe-0">
                        <div class="icon-big text-center">
                            <i class="fas fa-hand-holding-usd"></i> <!-- Ikon X untuk tidak sesuai -->
                        </div>
                    </div>
                    <div class="col-9 col-stats ms-0 ps-0">
                        <div class="numbers">
                            <p class="card-category">Delivered</p>
                            <div class="h5 mb-0 font-weight-bold text-gray-800 delivered_count"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Selisih Penyetoran -->

    <div class="col">
        <div class="card card-stats card-round">
            <div class="card-body">
                <div class="row">
                    <div class="col-3 me-0 pe-0">
                        <div class="icon-big text-center">
                            <i class="material-icons">account_balance_wallet</i></button>
                        </div>
                    </div>
                    <div class="col-9 col-stats ms-0 ps-0">
                        <div class="numbers">
                            <p class="card-category">Return</p>
                            <div class="h5 mb-0 font-weight-bold text-gray-800 return_count"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">



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

                    



                    $('.total_shipment').text(formatNum(r.total_shipment || 0));
                    $('.delivered_count').text(formatNum(r.delivered_count || 0));
                    $('.on_proses_count').text(formatNum(r.on_proses_count || 0));
                    $('.total_amount').text(formatNum(r.total_amount || 0));
                    $('.return_count').text(formatNum(r.return_count || 0));


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