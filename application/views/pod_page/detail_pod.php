<!-- Card Content -->
<?php

$no_runsheet = isset($get_cod_pod[0]->no_runsheet) ? $get_cod_pod[0]->no_runsheet : '';
$amount = isset($get_cod_pod[0]->amount) ? $get_cod_pod[0]->amount : '';
$courier_name = isset($get_cod_pod[0]->courier_name) ? $get_cod_pod[0]->courier_name : '';
$nik = isset($get_cod_pod[0]->nik) ? $get_cod_pod[0]->nik : '';
$tipe_courier = isset($get_cod_pod[0]->tipe_courier) ? $get_cod_pod[0]->tipe_courier : '';
$area = isset($get_cod_pod[0]->area) ? $get_cod_pod[0]->area : '';
$zone = isset($get_cod_pod[0]->zone) ? $get_cod_pod[0]->zone : '';
$no_tlp = isset($get_cod_pod[0]->no_tlp) ? $get_cod_pod[0]->no_tlp : '';
$hrs = isset($get_cod_pod[0]->hrs) ? $get_cod_pod[0]->hrs : 'contoh HRS';
$id_courier = isset($get_cod_pod[0]->id_courier) ? $get_cod_pod[0]->id_courier : '';

$total_awb = isset($get_cod_pod[0]->qty_awb) ? $get_cod_pod[0]->qty_awb : '';
$no_runsheet_courier = isset($get_cod_pod[0]->no_runsheet) ? $get_cod_pod[0]->no_runsheet : '';
$runsheet_date = isset($get_cod_pod[0]->runsheet_date) ? $get_cod_pod[0]->runsheet_date : '';
$cod_undelivered = isset($get_cod_pod[0]->cod_undelivered) ? $get_cod_pod[0]->cod_undelivered : '';
$minus_cod = isset($get_cod_pod[0]->minus_cod) ? $get_cod_pod[0]->minus_cod : '';
$persentase_cod = isset($get_cod_pod[0]->persentase_cod) ? $get_cod_pod[0]->persentase_cod : '';

$no_runsheet = isset($no_runsheet) ? $no_runsheet : '';
$readonly = ($mode == 'edit') ? 'readonly' : '';
$readonly_page_read = ($mode == 'read') ? 'readonly' : '';
$form_id_courier = ($mode == 'edit') ? 'disable' : '';
$form_date_from = ($mode == 'edit') ? 'readonly' : '';
$form_date_thru = ($mode == 'edit') ? 'readonly' : '';
$form_action = ($mode == 'edit') ? 'pod/edit_pod' : 'pod/create_pod';
$submit_button = ($mode == 'edit') ? 'btn btn-warning' : 'btn btn-primary';
$caption_button = ($mode == 'edit') ? 'Edit' : 'Save';

$value_called = isset($get_cod_pod[0]->cod_paid) ? 'value="' . $get_cod_pod[0]->cod_paid . '"' : "";
$value_transfer = isset($get_cod_pod[0]->transfer) ? 'value="' . $get_cod_pod[0]->transfer . '"' : "";
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <a href="<?= base_url('pod') ?>">
                        <i class="bi bi-arrow-left fs-2 m-2"></i>
                    </a>
                    <h4 class="header-title m-2">Detail POD</h4>
                </div>
            </div>
            <!-- Input field -->
            <!-- <input type="text" id="no_runsheet" name="no_runsheet"
                                                    class="form-control border-left-0"
                                                    placeholder="Masukkan nomor runsheet (contoh: 123456)" value="<?= $id_courier ?>"
                                                    autocomplete="off" <?= $readonly ?> <?= $readonly_page_read ?> required> -->
            <div class="card-body m-3">
                <div class="col">
                    <div class="row">
                        <!-- Content Left -->
                        <div class="col-lg-6 border-end boder-2">
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="mb-3">
                                        <label for="select_courier" class="form-label fw-bold">ID Kurir</label>

                                        <select id="select_courier" name="select_courier"
                                            class="form-control selectpicker" title="Pilih Kurir"
                                            data-live-search="true" data-width="100%" <?= $form_id_courier?>>
                                            <option value="ALL" selected>Select Kurir</option>
                                            <?php
                                            $data_courier = json_decode($data_courier, true);
                                            if (!empty($data_courier) && is_array($data_courier)):
                                                foreach ($data_courier as $courier): ?>
                                                    <option value="<?= $courier['id_courier'] ?>">
                                                        <?= $courier['courier_name'] ?> (<?= $courier['id_courier'] ?>)
                                                    </option>
                                                <?php endforeach;
                                            else: ?>
                                                <option value="">Tidak ada data kurir</option>
                                            <?php endif; ?>
                                        </select>


                                    </div>


                                </div>




                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="dateFrom">From:</label>
                                        <input type="date" class="form-control" id="dateFrom" name="dateFrom"
                                            value="<?= date('Y-m-d') ?>" <?= $form_date_from?>>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="dateThru">Thru:</label>
                                        <input type="date" class="form-control" id="dateThru" name="dateThru"
                                            value="<?= date('Y-m-d') ?>"<?= $form_date_thru?> >
                                    </div>
                                </div>
                                <!-- <div class="row">
                                    <div class="col-lg-8">
                                        <div class="mb-3">
                                            <label for="courier" class="form-label fw-bold">No Runsheet</label>

                                            <div class="input-group shadow-sm">
                                                
                                                <select id="no_runsheet" name="no_runsheet"
                                                    class="form-control selectpicker" data-live-search="true"
                                                    title="Pilih Kurir">
                                                    <option value="ALL" selected>Select No Runsheet</option>

                                                </select>

                                            </div>
                                        </div>


                                    </div>
                                </div> -->
                                <div class="row">
                                    <div class="col-lg-9">
                                        <div class="mb-3">
                                            <label for="ticket_from_name" class="form-label required">Remarks</label>
                                            <input type="text" class="form-control" id="remarks" name="remarks"
                                                autocomplete="off" readonly required>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <script>

                                $(document).ready(function () {
                                    function loadRunsheetOptions() {

                                        let dateFrom = $("#dateFrom").val();
                                        let dateThru = $("#dateThru").val();
                                        let select_courier = $("#select_courier").val();


                                        $.ajax({
                                            url: "<?php echo base_url('Admin/select_runsheet'); ?>",
                                            type: "POST",
                                            data: {
                                                select_courier: select_courier,
                                                dateFrom: dateFrom,
                                                dateThru: dateThru
                                            },
                                            dataType: "json",
                                            success: function (response) {
                                                let options = '<option value="">Please select case</option>';
                                                $.each(response, function (index, caseType) {
                                                    let bgColorClass = caseType.status_pod_flag === 'N' ? 'bg-danger text-white px-2 py-1 rounded d-inline-block' : 'bg-success text-white px-2 py-1 rounded d-inline-block';
                                                    let labelText = caseType.status_pod_flag === 'N' ? 'Belum Approve' : 'Sudah Approve';
                                                    options += '<option value="' + caseType.no_runsheet + '" data-content="<span class=\'' + bgColorClass + '\'>' + caseType.no_runsheet + ' (' + labelText + ')</span>">selected' + caseType.no_runsheet + '</option>';
                                                });


                                                $('#no_runsheet').html(options).selectpicker('refresh');
                                            },
                                            error: function (xhr, status, error) {
                                                console.error("AJAX Error:", error);
                                                console.log(xhr.responseText);
                                            }
                                        });
                                    }

                                    // Event listener untuk semua perubahan
                                    $('#select_courier').change(loadRunsheetOptions);
                                    $('#dateFrom').change(loadRunsheetOptions);
                                    $('#dateThru').change(loadRunsheetOptions);


                                });

                            </script>


                            <form action="<?= base_url($form_action); ?>" method="post">
                                <!-- Detail Courier -->
                                <div class="row">
                                    <center>
                                        <h4>Detail Karyawan</h4>
                                    </center>
                                </div>
                                <input type="hidden" class="form-control" id="display_id_courier"
                                    name="display_id_courier" autocomplete="off" readonly required>
                                <input type="hidden" class="form-control" id="id_checker_notes" name="id_checker_notes"
                                    autocomplete="off"
                                    value="<?= isset($get_cod_pod[0]->id_checker_notes) ? $get_cod_pod[0]->id_checker_notes : '' ?>"
                                    readonly required>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="ticket_from_name" class="form-label required">Nama Kurir</label>
                                            <input type="text" class="form-control" id="courier_name"
                                                name="courier_name" autocomplete="off" value="<?= $courier_name ?>"
                                                readonly required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="ticket_from_name" class="form-label required">NIK</label>
                                            <input type="text" class="form-control" id="nik" name="nik"
                                                autocomplete="off" value="<?= $nik ?>" readonly required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="ticket_from_name" class="form-label required">Tipe Kurir</label>
                                            <input type="text" class="form-control" id="type_courier"
                                                name="type_courier" autocomplete="off" value="<?= $tipe_courier ?>"
                                                readonly required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="ticket_from_name" class="form-label required">Lokasi</label>
                                            <input type="text" class="form-control" id="location" name="location"
                                                autocomplete="off" value="<?= $courier_name ?>" readonly required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="ticket_from_name" class="form-label required">Area</label>
                                            <input type="text" class="form-control" id="area" name="area"
                                                autocomplete="off" value="<?= $area ?>" readonly required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="ticket_from_name" class="form-label required">Zone</label>
                                            <input type="text" class="form-control" id="zone" name="zone"
                                                autocomplete="off" value="<?= $zone ?>" readonly required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="ticket_from_name" class="form-label required">No Hp</label>
                                            <input type="text" class="form-control" id="no_tlp" name="no_tlp"
                                                autocomplete="off" value="<?= $no_tlp ?>" readonly required>
                                        </div>
                                    </div>
                                </div>


                        </div>

                        <!-- Content Right -->
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="mb-3">
                                                <label for="ticket_from_name" class="form-label required">HRS</label>
                                                <input type="text" class="form-control" id="hrs" name="hrs"
                                                    placeholder="Insert HRS" autocomplete="off"
                                                    value="<?= htmlspecialchars($hrs) ?>" <?php if (!empty($readonly))
                                                          echo 'readonly'; ?> required>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="mb-3">
                                                <label for="dri_display" class="form-label required">Total COD</label>
                                                <input type="text" class="form-control" id="cod_display"
                                                    autocomplete="off" placeholder="Total COD (Rp)"
                                                    value="<?= $amount ?>" readonly required>
                                                <input type="hidden" name="total_cod" id="total_cod"
                                                    value="<?= $amount ?>">
                                                <!-- Ini yang akan disimpan ke DB -->
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="row-lg-12">
                                        <div class="mb-3">
                                            <label for="dri" class="form-label required">DRI</label>
                                            <textarea class="form-control" id="dri" name="dri" rows="5"  readonly
                                                placeholder="No DRI"
                                                style="resize: vertical; max-height: 600px; overflow-y: auto;"><?= $no_runsheet_courier?></textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="ticket_from_name" class="form-label required">COD
                                            Cash</label>
                                        <input type="text" class="form-control" id="display_cod_called"
                                            name="display_cod_called" autocomplete="off" <?= $value_called ?>
                                            placeholder="Insert COD  Cash" <?= $readonly_page_read ?> required>
                                        <input type="hidden" name="cod_called" id="cod_called">
                                        <!-- Ini yang akan disimpan ke DB -->
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="ticket_from_name" class="form-label required">Transfer</label>
                                        <input type="text" class="form-control" id="display_transfer"
                                            name="display_transfer" autocomplete="off" <?= $value_transfer ?>
                                            placeholder="Insert transfer" <?= $readonly_page_read ?> required>
                                        <input type="hidden" name="transfer" id="transfer"
                                            value="<?= $get_cod_pod[0]->transfer ?? 0 ?>">
                                        <!-- Ini yang akan disimpan ke DB -->
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="ticket_from_name" class="form-label required">Undelivered</label>
                                        <input type="text" class="form-control" id="display_undelivered"
                                            name="display_undelivered" autocomplete="off"
                                            placeholder="Insert Undelivered" value="<?= $cod_undelivered ?>" readonly
                                            required>
                                        <input type="hidden" name="undelivered" id="undelivered"
                                            value="<?= $cod_undelivered ?>">
                                        <!-- Ini yang akan disimpan ke DB -->
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="ticket_from_name" class="form-label required">COD Plus /
                                            Minus</label>
                                        <input type="number" class="form-control" id="display_plus_minus"
                                            name="display_plus_minus" autocomplete="off" value="<?= $minus_cod ?>"
                                            readonly required>
                                        <input type="hidden" class="form-control" id="plus_minus" name="plus_minus"
                                            autocomplete="off" readonly required>
                                    </div>
                                </div>
                            </div>




                            <div class="row  ps-2 pe-2">
                                <center>
                                    <h4 class="border-bottom border-2">Shipment</h4>
                                </center>
                            </div>



                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="ticket_from_name" class="form-label required">Total AWB</label>
                                        <input type="text" class="form-control" id="total_awb" name="total_awb"
                                            autocomplete="off" value="<?= $total_awb ?>" readonly required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="ticket_from_name" class="form-label required">Target %</label>
                                        <input type="text" class="form-control" id="persentase_cod"
                                            name="persentase_cod" autocomplete="off" value="<?= $persentase_cod ?>"
                                            readonly required>

                                    </div>
                                </div>

                            </div>


                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="ticket_from_name" class="form-label required">DL</label>
                                        <input type="text" class="form-control" id="dl" name="dl" autocomplete="off"
                                            placeholder="-" value="" readonly required>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="ticket_from_name" class="form-label required">DL</label>
                                        <input type="text" class="form-control" id="dl" name="dl" autocomplete="off"
                                            placeholder="Insert DRI" value="" readonly required>
                                    </div>
                                </div>
                            </div> -->
                            <input type="hidden" id="runsheet_date" value="<?= $runsheet_date ?> " name="runsheet_date">

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="ticket_from_name" class="form-label required">Undel</label>
                                        <input type="text" class="form-control" id="awb_undel" name="awb_undel"
                                            autocomplete="off" placeholder="-" value="" readonly required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="ticket_from_name" class="form-label required">Other</label>
                                        <input type="text" class="form-control" id="other" name="other" autocomplete="off"
                                            placeholder="-" value="" readonly required>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" class="form-control" id="id_user" name="id_user" autocomplete="off"
                               value="<?= $id_user ?>" required>

                            <?php if ($mode != "read"): ?>
                                <div class="row">
                                    <div class="col d-flex justify-content-end">
                                        <button type="submit" class="<?= $submit_button ?>"><?= $caption_button ?></button>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        </form>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form'); // ambil form
        const driField = document.getElementById('dri'); // ambil textarea DRI

        form.addEventListener('submit', function (e) {
            const driValue = driField.value.trim();

            if (driValue === '') {
                e.preventDefault(); // cegah submit
                alert('Pastikan no runsheet yg akan di bayar tersedia!');
                driField.focus();
            }
        });
    });
</script>


<!-- script format Rupiah dan sum otomatis -->
<script>
    function formatRupiah(angka) {
        if (!angka) return '0';
        return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    const displayCod = document.getElementById('cod_display');
    const hiddenCod = document.getElementById('total_cod');
    const display_cod_called = document.getElementById('display_cod_called');
    const cod_called = document.getElementById('cod_called');
    const display_undelivered = document.getElementById('display_undelivered');
    const undelivered = document.getElementById('undelivered');
    const display_transfer = document.getElementById('display_transfer');
    const transfer = document.getElementById('transfer');
    const display_plus_minus = document.getElementById('display_plus_minus');
    const plus_minus = document.getElementById('plus_minus');
    const persentase_input = document.getElementById('persentase_cod'); // Pakai satu input aja, bisa ditampilin

    // === Fungsi Utama Update ===
    function updateDRI() {
        const total = parseInt(hiddenCod.value) || 0;
        const called = parseInt(cod_called.value) || 0;
        const undel = parseInt(undelivered.value) || 0;
        const transfer_cod = parseInt(transfer.value) || 0;

        // Hitung Plus-Minus
        const result = total - called - undel - transfer_cod;
        display_plus_minus.value = formatRupiah(result);
        plus_minus.value = result;

        // Hitung Persentase
        const totalCalled_cod = total - undel;
        let percentage = 0;
        if (totalCalled_cod > 0) {
            percentage = ((called + transfer_cod) / totalCalled_cod) * 100;
        }
        // Di akhir fungsi updateDRI()
        persentase_input.value = percentage.toFixed(2); // Tanpa simbol '%'
    }

    // === Fungsi untuk Format Input dan Set Nilai Hidden ===
    function handleNumberInput(displayInput, hiddenInput) {
        displayInput.addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, '');
            e.target.value = formatRupiah(value);
            hiddenInput.value = value;
            updateDRI();
        });
    }

    // === Event Binding ===
    handleNumberInput(displayCod, hiddenCod);
    handleNumberInput(display_cod_called, cod_called);
    handleNumberInput(display_undelivered, undelivered);
    handleNumberInput(display_transfer, transfer);

    // Inisialisasi pertama
    updateDRI();
    //  script auto submit 

    $(document).ready(function () {

        let ajaxRequest; // simpan global

        function autoSubmitScan() {
            var id_courier = $('#select_courier').val();
            let dateFrom = $("#dateFrom").val();
            let dateThru = $("#dateThru").val();

            if (ajaxRequest) {
                ajaxRequest.abort(); // stop request lama
            }

            ajaxRequest = $.ajax({
                url: '<?= base_url("Pod/search_courier") ?>',
                type: 'POST',
                dataType: 'json',
                cache: false, // jangan cache
                data: {
                    id_courier: id_courier,
                    dateFrom: dateFrom,
                    dateThru: dateThru,
                },
                success: function (response) {
                    $('#courier_name').val(response.courier_name || "");
                    $('#type_courier').val(response.type_courier || "");
                    $('#location').val(response.location || "");
                    $('#zone').val(response.zone || "");
                    $('#area').val(response.area || "");
                    $('#no_tlp').val(response.no_tlp || "");
                    $('#nik').val(response.nik || "");
                    $('#display_id_courier').val(response.id_courier || "");

                    let runsheetList = (response.dri || []).map(item => item.no_runsheet).join('\n');
                    $('#dri').val(runsheetList);

                    $('#total_awb').val(response.total_awb || 0);

                    const codDisplay = response.cod_display || 0;
                    const angka = response.display_undelivered || 0;

                    $('#cod_display').val(formatRupiah(codDisplay));
                    $('#display_undelivered').val(formatRupiah(angka));

                    $('#total_cod').val(codDisplay);
                    $('#undelivered').val(response.undelivered || 0);
                    $('#runsheet_date').val(response.runsheet_date || "");
                    $('#remarks').val(response.remarks || "");
                    $('#dl').val(response.dl || "");
                    $('#awb_undel').val(response.undel || "");
                    $('#other').val(response.other || "");
                },
                error: function () {
                    $('#remarks').val("Data Kurir tidak ditemukan");
                }
            });
        }

        // Panggil fungsi saat select no_runsheet berubah
        $('#select_courier,#dateFrom, #dateThru').on('change', function () {
            autoSubmitScan();
        });
    });


    // function autoSubmitScan() {
    //     var no_runsheet = $('#no_runsheet').val();

    //     $.ajax({
    //         url: '<?= base_url("Pod/search_courier") ?>',
    //         type: 'POST',
    //         dataType: 'json',
    //         data: {
    //             no_runsheet: no_runsheet
    //         },
    //         success: function (response) {
    //             $('#courier_name').val(response.courier_name);
    //             $('#type_courier').val(response.type_courier);
    //             $('#location').val(response.location);
    //             $('#zone').val(response.zone);
    //             $('#area').val(response.area);
    //             $('#no_tlp').val(response.no_tlp);
    //             $('#nik').val(response.nik);
    //             $('#display_id_courier').val(response.id_courier);
    //             $('#dri').val(response.dri);
    //             $('#total_awb').val(response.total_awb);
    //             // Format ke rupiah untuk tampilan
    //             const codDisplay = response.cod_display || 0;
    //             const angka = response.display_undelivered;

    //             $('#cod_display').val(formatRupiah(codDisplay));
    //             $('#display_undelivered').val(formatRupiah(angka));


    //             // Simpan nilai asli ke hidden input
    //             $('#total_cod').val(codDisplay);
    //             $('#undelivered').val(response.undelivered || 0);
    //             $('#runsheet_date').val(response.runsheet_date);
    //             $('#remarks').val(response.remarks);
    //             $('#dl').val(response.dl);
    //             $('#awb_undel').val(response.undel);

    //             console.log(response);
    //         },
    //         error: function (xhr, status, error) {
    //             $('#remarks').val("Data Kurir tidak ditemukan");
    //         }
    //     });
    // }
    // $('#no_runsheet').on('keypress', function (e) {
    //     if (e.which === 13) { // Key code 13 adalah Enter
    //         e.preventDefault(); // Mencegah form submit default
    //         autoSubmitScan(); // Panggil fungsi autoSubmitScan
    //     }
    // });
    // // Panggil fungsi saat select no_runsheet berubah
    // $('#no_runsheet').on('change', function () {
    //     autoSubmitScan();
    // });
</script>