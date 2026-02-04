<!-- Card Content -->
<?php

$no_runsheet = isset($get_cod_pod[0]->no_runsheet) ? $get_cod_pod[0]->no_runsheet : '';

$display_paid = isset($get_cod_pod[0]->display_paid) ? $get_cod_pod[0]->display_paid : '';

$no_runsheet = isset($no_runsheet) ? $no_runsheet : '';
$readonly = ($mode == 'edit') ? 'readonly' : '';
$readonly_page_read = ($mode == 'read') ? 'readonly' : '';
$form_id_courier = ($mode == 'edit') ? 'disable' : '';
$form_date_from = ($mode == 'edit') ? 'readonly' : '';
$form_date_thru = ($mode == 'edit') ? 'readonly' : '';

$submit_button = ($mode == 'edit') ? 'btn btn-warning' : 'btn btn-primary';
$caption_button = ($mode == 'edit') ? 'Edit' : 'Save';


$value_called = isset($get_cod_pod[0]->cod_paid) ? 'value="' . $get_cod_pod[0]->cod_paid . '"' : "";
$value_transfer = isset($get_cod_pod[0]->transfer) ? 'value="' . $get_cod_pod[0]->transfer . '"' : "";



//new

?>
<?php if ($this->session->flashdata('notify')): ?>
    <?php $notify = $this->session->flashdata('notify'); ?>
    <div class="alert alert-<?= $notify['type']; ?>">
        <?= $notify['message']; ?>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <a href="<?= base_url('pod') ?>">
                        <i class="bi bi-arrow-left fs-2 m-2"></i>
                    </a>
                    <h4 class="header-title m-2">Daftar Pernyerahan POD</h4>
                </div>
            </div>
            <!-- Input field -->
            <div class="card-body m-3">
                <div class="col">
                    <div class="row">
                        <!-- Content Left -->
                        <div class="col-lg-12 ">
                            <div class="row">

                                <form id="courierFilterForm">
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label for="dateFrom">From:</label>
                                            <input type="date" class="form-control" id="dateFrom" name="dateFrom"
                                                value="<?= htmlspecialchars($dateFrom) ?>" <?= $form_date_from ?>>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="dateThru">Thru:</label>
                                            <input type="date" class="form-control" id="dateThru" name="dateThru"
                                                value="<?= htmlspecialchars($dateThru) ?>" <?= $form_date_from ?>>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="select_courier" class="form-label fw-bold">ID Kurir</label>
                                            <select id="select_courier" name="select_courier"
                                                class="form-control selectpicker" data-live-search="true"
                                                data-width="100%" <?= ($mode === 'edit') ? 'disabled' : '' ?>>



                                                <option value="">-- Pilih Kurir --</option>

                                                <?php
                                                $data_courier = json_decode($data_courier, true);
                                                if (!empty($data_courier) && is_array($data_courier)):
                                                    foreach ($data_courier as $courier): ?>
                                                        <option value="<?= $courier['id_courier'] ?>"
                                                            <?= ($select_courier == $courier['id_courier']) ? 'selected' : '' ?>>
                                                            <?= $courier['courier_name'] ?> (<?= $courier['id_courier'] ?>)
                                                        </option>
                                                    <?php endforeach;
                                                endif; ?>
                                            </select>

                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-12 text-end">
                                            <? if($mode !== 'edit'):?>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bi bi-search"></i> Cari Data
                                            </button>
                                            <? endif;?>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- style avatar -->
                            <style>
                                .avatar-wrapper {
                                    width: 100px;
                                    height: 100px;
                                    min-width: 64px;
                                    min-height: 64px;

                                    border-radius: 50%;
                                    overflow: hidden;

                                    display: flex;
                                    align-items: center;
                                    justify-content: center;

                                    background: #f2f2f2;
                                }

                                .avatar-wrapper img {
                                    width: 100%;
                                    height: 100%;
                                    object-fit: cover;
                                    object-position: 50% 15%;

                                    display: block;
                                }
                            </style>

                            <!-- LOADING INDICATOR -->
                            <div id="loadingIndicator" class="text-center my-4" style="display: none;">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2">Memuat data...</p>
                            </div>


                            <div id="courierResultSection">
                                <!-- <div id="courierResultSection" style="display: none;"> -->
                                <div class="card border-0 shadow-sm rounded-3">
                                    <div class="card-body p-4">

                                        <div class="d-flex justify-content-between align-items-center">

                                            <!-- KIRI -->
                                            <div class="d-flex align-items-center mb-3 mb-md-0">
                                                <div class="avatar-wrapper me-3">
                                                    <img id="courierPhoto" src="" alt="Courier Photo"
                                                        style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover;">
                                                </div>
                                                <div>
                                                    <h3 class="fw-bold mb-1" id="courierName"></h3>
                                                    <a id="courierWhatsapp" href="" target="_blank"
                                                        class="text-success text-decoration-none">
                                                        <i class="bi bi-whatsapp"></i>
                                                        <span id="courierPhone"></span>
                                                    </a>
                                                </div>
                                            </div>

                                            <!-- KANAN -->
                                            <h4 class="fw-bold mb-1">
                                                <span class="fw-bold text-primary" id="runsheetReady">0</span>
                                                dari
                                                <span class="fw-bold text-primary" id="runsheetTotal">0</span>
                                                Runsheet Dapat Disetorkan
                                            </h4>

                                        </div>



                                    </div>
                                </div>
                                <form id="form_payment_cod" name="form_payment_cod" method="post">


                                    <div class="row align-items-start">

                                        <!-- KIRI : 6 kolom -->
                                        <div class="col-md-6">
                                            <h2 style="font-family:poppins; font-weight:bold; color:#515151">
                                                Nominal Yang Harus Disetorkan (Rp)
                                            </h2>
                                        </div>

                                        <!-- KANAN : 6 kolom -->
                                        <div class="col-md-6">

                                            <!-- ROW 1 -->
                                            <div class="row align-items-center mb-2">
                                                <div class="col-md-6 text-end">
                                                    <label for="nominal" class="form-label  mb-0">
                                                        Total Nominal
                                                    </label>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="hidden" class="form-control" id="nominal"
                                                        name="nominal" value="" disabled>
                                                    <input type="text" class="form-control" id="nominal_display"
                                                        name="nominal_display" value="" disabled>
                                                </div>
                                            </div>

                                            <!-- ROW 2 -->
                                            <div class="row align-items-center mb-2">
                                                <div class="col-md-6 text-end">
                                                    <label for="nominalUndel" class="form-label  mb-0">
                                                        Nominal Undelivered
                                                    </label>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="hidden" class="form-control" id="nominal_undel"
                                                        name="nominal_undel" value="" disabled>
                                                    <input type="text" class="form-control" id="nominal_undel_display"
                                                        name="nominal_undel_display" value="" disabled>
                                                </div>
                                            </div>

                                            <div class="row align-items-center mb-2">
                                                <div class="col-md-6 text-end">
                                                    <label for="nominalDelivered" class="form-label  mb-0">
                                                        Nominal Delivered
                                                    </label>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="hidden" value="" class="form-control"
                                                        id="nominal_delivered" name="nominal_delivered" disabled>
                                                    <input type="number" value="" class="form-control"
                                                        id="nominal_delivered_display" name="nominal_delivered_display"
                                                        disabled>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                    <div class="row align-items-start mt-4">

                                        <!-- KIRI : 6 kolom -->
                                        <div class="col-md-6">
                                            <h2 style="font-family:poppins; font-weight:bold; color:#515151">
                                                Nominal Yang Disetorkan (Rp)
                                            </h2>
                                        </div>

                                        <!-- KANAN : 6 kolom -->
                                        <div class="col-md-6">

                                            <!-- Switch Button -->
                                            <div class="row align-items-center mb-4">
                                                <div class="col-md-6 text-end">
                                                    <label class="form-label mb-0">Metode Pembayaran</label>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="btn-group" role="group" id="paymentMethodToggle">
                                                        <input type="radio" class="btn-check" name="payment_method"
                                                            id="codOption" value="cod" autocomplete="off" checked>
                                                        <label class="btn btn-outline-primary" for="codOption">
                                                            <i class="bi bi-cash"></i> COD
                                                        </label>

                                                        <input type="radio" class="btn-check" name="payment_method"
                                                            id="transferOption" value="transfer" autocomplete="off">
                                                        <label class="btn btn-outline-primary" for="transferOption">
                                                            <i class="bi bi-bank"></i> Transfer
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Form Group untuk COD -->
                                            <div id="codFormGroup">
                                                <!-- ROW 1 -->
                                                <div class="row align-items-center mb-2">
                                                    <div class="col-md-6 text-end">
                                                        <label for="cod" class="form-label mb-0">
                                                            COD
                                                        </label>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="number" class="form-control" id="cod" name="cod"
                                                            value="" placeholder="Masukkan Nominal" autocomplete="off" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Form Group untuk Transfer -->
                                            <div id="transferFormGroup" class="d-none">
                                                <!-- ROW 2 -->
                                                <div class="row align-items-center mb-2">
                                                    <div class="col-md-6 text-end">
                                                        <label for="transfer" class="form-label mb-0">
                                                            Transfer
                                                        </label>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="number" class="form-control" id="transfer"
                                                            name="transfer" value="" autocomplete="off" placeholder="Masukkan Nominal">
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="row align-items-center mb-2">
                                                    <div class="col-md-6 text-end">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <i style="color:red;">format yang di dukung (jpg,jpeg dan png)
                                                            maks 2 mb</i>
                                                    </div>
                                                </div>
                                                <div class="row align-items-center mb-2">
                                                    <div class="col-md-6 text-end">
                                                        <label for="pic_transfer" class="form-label mb-0">
                                                            Upload Bukti Transfer
                                                        </label>

                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="file" value="" class="form-control"
                                                            id="pic_transfer" name="pic_transfer" accept="image/*,.pdf">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- JavaScript untuk Toggle -->
                                            <script>
                                                document.addEventListener('DOMContentLoaded', function () {
                                                    // Ambil semua radio button
                                                    const codOption = document.getElementById('codOption');
                                                    const transferOption = document.getElementById('transferOption');
                                                    const codFormGroup = document.getElementById('codFormGroup');
                                                    const transferFormGroup = document.getElementById('transferFormGroup');

                                                    // Fungsi untuk toggle form
                                                    function toggleForms() {
                                                        if (codOption.checked) {
                                                            // Tampilkan COD form, sembunyikan Transfer form
                                                            codFormGroup.classList.remove('d-none');
                                                            transferFormGroup.classList.add('d-none');

                                                            // Non-aktifkan required pada transfer field
                                                            document.getElementById('transfer').removeAttribute('required');
                                                            document.getElementById('pic_transfer').removeAttribute('required');

                                                            // Aktifkan required pada COD field
                                                            document.getElementById('cod').setAttribute('required', 'required');

                                                            // Reset nilai transfer jika ada
                                                            document.getElementById('transfer').value = '';
                                                            document.getElementById('pic_transfer').value = '';

                                                        } else if (transferOption.checked) {
                                                            // Tampilkan Transfer form, sembunyikan COD form
                                                            transferFormGroup.classList.remove('d-none');
                                                            codFormGroup.classList.add('d-none');

                                                            // Non-aktifkan required pada COD field
                                                            document.getElementById('cod').removeAttribute('required');

                                                            // Aktifkan required pada transfer field
                                                            document.getElementById('transfer').setAttribute('required', 'required');
                                                            document.getElementById('pic_transfer').setAttribute('required', 'required');

                                                            // Reset nilai COD jika ada
                                                            document.getElementById('cod').value = '';
                                                        }
                                                    }

                                                    // Event listeners untuk radio buttons
                                                    codOption.addEventListener('change', toggleForms);
                                                    transferOption.addEventListener('change', toggleForms);

                                                    // Inisialisasi awal
                                                    toggleForms();

                                                    // Validasi form sebelum submit

                                                    if (codOption.checked && !document.getElementById('cod').value) {
                                                        e.preventDefault();
                                                        alert('Harap isi nominal COD');
                                                        document.getElementById('cod').focus();
                                                    } else if (transferOption.checked) {
                                                        const transferValue = document.getElementById('transfer').value;
                                                        const transferFile = document.getElementById('pic_transfer').files[0];

                                                        if (!transferValue) {
                                                            e.preventDefault();
                                                            alert('Harap isi nominal Transfer');
                                                            document.getElementById('transfer').focus();
                                                        } else if (!transferFile) {
                                                            e.preventDefault();
                                                            alert('Harap upload bukti transfer');
                                                            document.getElementById('pic_transfer').focus();
                                                        }
                                                    }
                                                });

                                            </script>

                                            <!-- Optional: Tambahkan CSS untuk styling -->
                                            <style>
                                                .btn-group .btn {
                                                    min-width: 100px;
                                                }

                                                .btn-check:checked+.btn-outline-primary {
                                                    background-color: #0d6efd;
                                                    color: white;
                                                    border-color: #0d6efd;
                                                }

                                                /* Animasi fade untuk toggle */
                                                #codFormGroup,
                                                #transferFormGroup {
                                                    transition: opacity 0.3s ease;
                                                }

                                                .d-none {
                                                    display: none !important;
                                                }
                                            </style>
                                            <div class="row align-items-center mb-2">
                                                <div class="col-md-6 text-end">
                                                    <b>
                                                        Selisi Setoran
                                                    </b>
                                                </div>
                                                <div class="col-md-6">
                                                    <text type="text" value="" id="minus_cod" name="minus_cod"
                                                        class="minus_cod"></text>
                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                    <input type="hidden" name="courier_id" id="courier_id_hidden">
                                    <input type="hidden" name="date_from" id="date_from_hidden">
                                    <input type="hidden" name="date_thru" id="date_thru_hidden">

                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary mt-3 me-5"
                                            style="border-radius:50px;">
                                            Submit
                                        </button>
                                    </div>


                                </form>



                                <div class="table-responsive">
                                    <table id="table_top_courier" class="display table table-striped table-hover"
                                        width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>No Runsheet</th>
                                                <th>Total COD</th>
                                                <th>Total Yang dibayar</th>
                                                <th>Status POD</th>
                                                <th>HRS</th>
                                                <th>Action</th>

                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>






    <!-- JAVASCRIPT -->
    <script>
        $(document).ready(function () {
            $('#select_courier').selectpicker('refresh');


            var table = null;

            // Initialize selectpicker
            $('.selectpicker').selectpicker();


            $('#form_payment_cod').on('submit', function (e) {
                e.preventDefault();

                const formData = new FormData(this);
                formData.append(
                    'payment_method',
                    $('input[name="payment_method"]:checked').val()
                );

                $.ajax({
                    url: '<?= base_url("pod/payment_cod") ?>',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function (res) {

                        $.notify({
                            message: res.message
                        }, {
                            type: res.type,
                            delay: 3000,
                            placement: {
                                from: "top",
                                align: "right"
                            },
                            offset: { x: 20, y: 70 }
                        });

                        if (res.type === 'success' && res.redirect) {
                            setTimeout(() => {
                                window.location.href = res.redirect;
                            }, 1000);
                        }

                        console.log(res);
                    }
                });
            });

            $('#courierFilterForm').on('submit', function (e) {
                e.preventDefault();

                const courierId = $('#select_courier').val();
                // const courierId = "BDO886"
                const dateFrom = $('#dateFrom').val();
                const dateThru = $('#dateThru').val();

                // Validasi
                if (!courierId) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: 'Silakan pilih kurir terlebih dahulu!'
                    });
                    return;
                }

                // Show loading
                $('#loadingIndicator').show();
                $('#courierResultSection').hide();

                // Get courier info dulu
                $.ajax({
                    url: '<?= base_url("pod/get_courier_info") ?>',
                    method: 'POST',
                    data: {
                        courier_id: courierId,
                        date_from: dateFrom,
                        date_thru: dateThru
                    },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            // Update courier card
                            updateCourierCard(response.courier);
                            // console.log(response.courier);
                            // Initialize atau reload DataTable                            
                            initDataTable(courierId, dateFrom, dateThru);

                            // isi ke form pembayaran
                            $('#courier_id_hidden').val(courierId);
                            $('#date_from_hidden').val(dateFrom);
                            $('#date_thru_hidden').val(dateThru);


                            // Show result
                            $('#loadingIndicator').hide();
                            $('#courierResultSection').fadeIn();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message || 'Terjadi kesalahan'
                            });
                            $('#loadingIndicator').hide();
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Gagal memuat data. Silakan coba lagi.'
                        });
                        $('#loadingIndicator').hide();
                    }
                });
            });

            // Function to initialize DataTable
            function initDataTable(courierId, dateFrom, dateThru) {
                // Destroy existing table
                if (table !== null) {
                    table.destroy();
                }

                // Initialize new DataTable
                table = $('#table_top_courier').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "pageLength": 10,
                    "lengthMenu": [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
                    "ajax": {
                        "url": "<?= base_url('hrs/getdatatables_hrs') ?>",
                        "type": "POST",
                        "data": function (d) {
                            d.courier_id = courierId;
                            d.date_from = dateFrom;
                            d.date_thru = dateThru;
                        }
                    },
                    "columnDefs": [
                        {
                            "targets": [0],
                            "orderable": false
                        },
                        {
                            "targets": [0],
                            "className": 'text-center'
                        },


                    ],
                    "layout": {
                        topStart: {
                            buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
                        }
                    }
                });
            }

            // Update courier card
            function updateCourierCard(courier) {
                const photoUrl = courier.photo
                    ? '<?= base_url("uploads/image_courier/") ?>' + courier.photo
                    : '<?= base_url("uploads/image_courier/courier.png") ?>';

                // Paksa angka
                const nominal = Number(courier.nominal) || 0;
                const nominalUndel = Number(courier.nominalUndel) || 0;
                const nominalDelivered = nominal - nominalUndel;

                $('#courierPhoto').attr('src', photoUrl);
                $('#courierName').text(courier.courier_name + ' - ' + courier.id_courier);
                $('#courierPhone').text(courier.no_tlp || 'N/A');

                // Value asli (untuk submit)
                $('#nominal_delivered').val(nominalDelivered);
                $('#nominal_undel').val(nominalUndel);
                $('#nominal').val(nominal);

                // Value display (rupiah)
                $('#nominal_delivered_display').val(formatRupiah(nominalDelivered));
                $('#nominal_undel_display').val(formatRupiah(nominalUndel));
                $('#nominal_display').val(formatRupiah(nominal));

                if (courier.no_tlp) {
                    const cleanPhone = courier.no_tlp.replace(/[^0-9]/g, '');
                    const waPhone = cleanPhone.startsWith('0')
                        ? '62' + cleanPhone.substring(1)
                        : cleanPhone;
                    $('#courierWhatsapp').attr('href', 'https://wa.me/' + waPhone);
                } else {
                    $('#courierWhatsapp').removeAttr('href').css('cursor', 'default');
                }

                $('#runsheetReady').text(courier.runsheet_ready || 0);
                $('#runsheetTotal').text(courier.runsheet_total || 0);
            }

        });



    </script>

    <!-- script format Rupiah dan sum otomatis -->
    <script>
        function formatRupiah(angka) {
            if (!angka) return '0';
            return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }


        const nominal_undel = document.getElementById('nominal_undel');
        const nominal = document.getElementById('nominal');


        function hitungSelisih() {
            const nominalDelivered = Number($('#nominal_delivered').val()) || 0;
            const transfer = Number($('#transfer').val()) || 0;
            const cod = Number($('#cod').val()) || 0;

            let dibayar = 0;

            // Ambil salah satu
            if (transfer > 0) {
                dibayar = transfer;
            } else if (cod > 0) {
                dibayar = cod;
            }

            const selisih = nominalDelivered - dibayar;

            // ===== DISPLAY =====
            let display = '0';

            if (selisih < 0) {
                // Uang lebih
                display = '+Rp ' + formatRupiah(Math.abs(selisih));
            } else if (selisih > 0) {
                // Uang kurang
                display = 'Rp ' + formatRupiah(selisih);
            }

            $('.minus_cod').text(display);

            // ===== VALUE ASLI (UNTUK BACKEND) =====
            $('#selisih_real').val(selisih);

            // ===== WARNA =====
            if (selisih < 0) {
                $('.minus_cod')
                    .addClass('text-success')
                    .removeClass('text-danger');
            } else if (selisih > 0) {
                $('.minus_cod')
                    .addClass('text-danger')
                    .removeClass('text-success');
            } else {
                $('.minus_cod').removeClass('text-danger text-success');
            }
        }



        // Trigger realtime
        $('#total_nominal, #transfer, #cod').on('input', hitungSelisih);


        $(document).ready(function () {

            $('#select_courier').selectpicker('refresh');

            const dateFrom = $('#dateFrom').val();
            const courierId = $('#select_courier').val();

            // cek kalau ada filter dari flashdata
            if (dateFrom !== '' && courierId !== '') {
                // trigger submit form otomatis
                $('#courierFilterForm').trigger('submit');
            }
        });


    </script>