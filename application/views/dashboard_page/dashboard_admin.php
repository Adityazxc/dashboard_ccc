<form id="filterForm">
    <div class="row">
        <div class="form-group col-md-6">
            <label for="dateFrom">From:</label>
            <input type="date" class="form-control" id="dateFrom" name="dateFrom" value="<?= date('Y-m-d') ?>">
        </div>
        <div class="form-group col-md-6">
            <label for="dateThru">Thru:</label>
            <input type="date" class="form-control" id="dateThru" name="dateThru" value="<?= date('Y-m-d') ?>">
        </div>
    </div>
</form>
<style>
    .card:hover {
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
        transform: translateY(-3px);
    }
</style>
<div class="row">
    <!-- qty selling -->
    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
            <div class="card-body">
                <div class="row">
                    <div class="col-5">
                        <div class="icon-big text-center">
                            <i class="fa fa-shopping-bag"></i>
                        </div>
                    </div>
                    <div class="col-7 col-stats">
                        <div class="numbers">
                            <p class="card-category">Selling</p>
                            <div class="h5 mb-0 font-weight-bold text-gray-800 totalSelling"></div>
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
                            <i class="far fa-check-circle"></i>
                        </div>
                    </div>
                    <div class="col-7 col-stats">
                        <div class="numbers">
                            <p class="card-category">Gross Profit</p>
                            <div class="h5 mb-0 font-weight-bold text-gray-800 grossProfit"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Profit -->
    <div class="col-sm-6 col-md-3">
        <div class="card card-stats  card-round">
            <div class="card-body">
                <div class="row">
                    <div class="col-5">
                        <div class="icon-big text-center">
                            <i class="bi bi-coin"></i>
                        </div>
                    </div>
                    <div class="col-7 col-stats">
                        <div class="numbers">
                            <p class="card-category">Profit</p>
                            <div class="h5 mb-0 font-weight-bold text-gray-800 totalProfit"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Offline -->
    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
            <div class="card-body">
                <div class="row">
                    <div class="col-5">
                        <div class="icon-big text-center">
                            <i class="bi bi-shop"></i>
                        </div>
                    </div>
                    <div class="col-7 col-stats">
                        <div class="numbers">
                            <p class="card-category">Offline</p>
                            <div class="h5 mb-0 font-weight-bold text-gray-800 profitOffline"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Tiktok -->
    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
            <div class="card-body">
                <div class="row">
                    <div class="col-5">
                        <div class="icon-big text-center">
                            <img src="public/img/tiktok_shop.png" height="50">
                        </div>
                    </div>
                    <div class="col-7 col-stats">
                        <div class="numbers">
                            <p class="card-category">Tiktok</p>
                            <div class="h5 mb-0 font-weight-bold text-gray-800 profiTiktok"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Tokopedia -->
    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
            <div class="card-body">
                <div class="row">
                    <div class="col-5">
                        <div class="icon-big text-center">
                            <img src="public/img/tokopedia.png" height="50">
                        </div>
                    </div>
                    <div class="col-7 col-stats">
                        <div class="numbers">
                            <p class="card-category">Tokopedia</p>
                            <div class="h5 mb-0 font-weight-bold text-gray-800 profiTokopedia"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Shopee -->
    <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
            <div class="card-body">
                <div class="row">
                    <div class="col-5">
                        <div class="icon-big text-center">
                            <img src="public/img/shopee.png" height="50">
                        </div>
                    </div>
                    <div class="col-7 col-stats">
                        <div class="numbers">
                            <p class="card-category">Shopee</p>
                            <div class="h5 mb-0 font-weight-bold text-gray-800 profiShopee"></div>
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
                <div class="card-title">Pie Chart</div>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="sourcePieChart" style="width: 100%; height: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Multiple Line Chart</div>
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
        };

        // BOX 1 
        $('.totalSelling').text('Tunggu.');
        $('.totalProfit').text('Tunggu.');
        $.ajax({
            url: "admin/summary_customer",
            dataType: "JSON",
            type: "POST",
            data: formData,
            success: (r) => {
                $('.totalSelling').text(r.sum_selling);
                $('.totalProfit').text(formatRupiah(r.sum_profit));
                $('.profiTokopedia').text(formatRupiah(r.sum_tokopedia));
                $('.profiShopee').text(formatRupiah(r.sum_shopee));
                $('.profiTiktok').text(formatRupiah(r.sum_Tiktok));
                $('.profitOffline').text(formatRupiah(r.sum_offline));
                $('.grossProfit').text(formatRupiah(r.gross_profit));
            }
        });
    }

    $(document).ready(function () {
        jumlah();
        $('[name="dateFrom"], [name="dateThru"]').on('change', () => {
            jumlah();
        });
    });

</script>


<script>
    // HTML: Pastikan elemen ini sudah ada di halaman Anda
    // <canvas id="multipleLineChart"></canvas>

    function lineMultipleChart() {
        var formData = {
            dateFrom: $('[name="dateFrom"]').val(),
            dateThru: $('[name="dateThru"]').val()
        };

        $.ajax({
            url: "<?= base_url('admin/getSourceDataMultiple') ?>", // URL endpoint
            type: "POST",
            data: formData,
            dataType: "JSON",
            success: function (response) {
                if (response.success) {
                    var labels = response.Labels || [];
                    var counts = response.Counts || [];
                    var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                    var datasets = [];
                    var colors = ["#1d7af3", "#f0582c", "#5fbc55", "#211f20"];


                    labels.forEach(function (label, index) {
                        var monthlyData = Array(12).fill(0);
                        if (counts[index]) {
                            monthlyData[index % 12] = counts[index];
                        }

                        datasets.push({
                            label: label,
                            data: monthlyData,
                            borderColor: colors[index % colors.length],
                            backgroundColor: 'transparent',
                            pointBorderWidth: 2,
                            pointHoverRadius: 4,
                            pointHoverBorderWidth: 1,
                            pointRadius: 4,
                            borderWidth: 2,
                            fill: true,
                            tension: 0.3
                        });
                    });

                    // Hapus chart lama jika ada dan valid
                    if (window.multipleLineChart && typeof window.multipleLineChart.destroy === 'function') {
                        window.multipleLineChart.destroy();
                    }

                    // Buat chart baru
                    var ctx = document.getElementById('multipleLineChart').getContext('2d');
                    window.multipleLineChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: months,
                            datasets: datasets
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
                                    mode: "index",
                                    intersect: false
                                }
                            },
                            scales: {
                                x: {
                                    title: {
                                        display: true,
                                        text: "Bulan"
                                    },
                                    grid: {
                                        drawOnChartArea: false
                                    }
                                },
                                y: {
                                    title: {
                                        display: true,
                                        text: "Jumlah Penjualan"
                                    },
                                    beginAtZero: true
                                }
                            },
                            layout: {
                                padding: {
                                    left: 20,
                                    right: 20,
                                    top: 20,
                                    bottom: 20
                                }
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
        // Mendapatkan data awal dari PHP jika ada


        var formData = {
            dateFrom: $('[name="dateFrom"]').val(),
            dateThru: $('[name="dateThru"]').val()
        };

        $.ajax({
            url: "<?= base_url('admin/getSourceData') ?>", // Sesuaikan URL-nya
            type: "POST",
            data: formData,
            dataType: "JSON",
            success: function (response) {
                if (response.success) {
                    // Data dari server
                    var sourceLabels = response.sourceLabels || [];
                    var sourceCounts = response.sourceCounts || [];

                    // Total data
                    var total = sourceCounts.reduce((a, b) => a + b, 0);

                    // Menghapus chart sebelumnya jika ada
                    if (window.myPieChart) {
                        window.myPieChart.destroy();
                    }

                    // Membuat pie chart baru
                    var ctx = document.getElementById('sourcePieChart').getContext('2d');
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
                                padding: {
                                    left: 20,
                                    right: 20,
                                    top: 20,
                                    bottom: 20
                                }
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
        $('#dateFrom, #dateThru').on('change', function () {
            loadSourcePieChart(); // Muat ulang saat filter berubah
            lineMultipleChart();
        });
    });
</script>