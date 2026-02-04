<!-- Card Content -->
<?php

$page_name = ($mode == 'add') ? 'Add HRS' : 'Edit HRS';
$name_button = ($mode == 'add') ? 'Save' : 'Edit';
$color_button = ($mode == 'add') ? 'primary' : 'warning';
$link_submit = ($mode == 'add') ? 'hrs/save_hrs' : 'hrs/edit_hrs';
?>



<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <!-- <a href="<?= base_url('pod') ?>">
                        <i class="bi bi-arrow-left fs-2 m-2"></i>
                    </a> -->
                    <h4 class="header-title m-2"><?= $page_name ?></h4>
                </div>
            </div>
            <!-- Input field -->
            <div class="card-body m-3">
                <div class="col">
                    <div class="row">
                        <!-- Content Left -->
                        <div class="col-lg-12 ">
                            <div class="row">


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



                            <div id="courierResultSection">

                                <div class="row justify-content-center mb-5 text-center">

                                    <div class="col-md-2 mb-3">
                                        <div class="card shadow-sm border-0">
                                            <div class="card-body">
                                                <small class="text-muted">Achievement</small>
                                                <h3 class="fw-bold text-success">82,4%</h3>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2 mb-3">
                                        <div class="card shadow-sm border-0">
                                            <div class="card-body">
                                                <small class="text-muted">Total AWB</small>
                                                <h3 class="fw-bold">
                                                    <?= $get_detail_cod->total_awb ?>
                                                </h3>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2 mb-3">
                                        <div class="card shadow-sm border-0">
                                            <div class="card-body">
                                                <small class="text-muted">Delivered</small>
                                                <h3 class="fw-bold text-primary">
                                                    <?= $get_detail_cod->delivered ?>
                                                </h3>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2 mb-3">
                                        <div class="card shadow-sm border-0">
                                            <div class="card-body">
                                                <small class="text-muted">Undelivered</small>
                                                <h3 class="fw-bold text-danger">
                                                    <?= $get_detail_cod->undeliverd ?>
                                                </h3>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2 mb-3">
                                        <div class="card shadow-sm border-0">
                                            <div class="card-body">
                                                <small class="text-muted">Antar Ulang</small>
                                                <h3 class="fw-bold text-warning">
                                                    <?= $get_detail_cod->antar_ulang ?? 0 ?>
                                                </h3>
                                            </div>
                                        </div>
                                    </div>

                                </div>


                                <form id="form_add_hrs" name="form_add_hrs" method="post">
                                    <input type="hidden" class="form-control" name="dri" id="dri"
                                        value="<?= $no_runsheet ?>">
                                    <input type="hidden" name="dateFrom" value="<?= $date_from ?>">
                                    <input type="hidden" name="dateThru" value="<?= $date_thru ?>">
                                    <input type="hidden" name="select_courier" value="<?= $id_courier ?>">

                                    <div class="border-top border-bottom border-secondary pt-4 ms-4 ps-4">
                                        <div class="row align-items-start">
                                            <!-- KIRI : 6 kolom -->
                                            <div class="col-md-6 d-flex ">
                                                <h2 class="fw-bold mb-0" style="font-family:poppins; color:#515151">
                                                    Nomor HRS
                                                </h2>
                                            </div>


                                            <!-- KANAN : 6 kolom -->
                                            <div class="col-md-6 mb-4">
                                                <div id="hrs-container">
                                                    <?php if ($mode == 'add'): ?>
                                                        <div
                                                            class="row justify-content-end align-items-center mb-2 hrs-row">
                                                            <div class="col-md-6">
                                                                <input type="text" class="form-control" name="hrs[]"
                                                                    placeholder="Masukkan No HRS">
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="d-flex gap-2 justify-content-start">
                                                                    <button type="button"
                                                                        class="btn btn-success btn-sm btn-custom add-row">
                                                                        <i class="bi bi-plus-lg text-white fs-4"></i>
                                                                    </button>
                                                                    <button type="button"
                                                                        class="btn btn-danger btn-sm btn-custom remove-row">
                                                                        <i class="bi bi-dash-lg text-white fs-4"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php else:
                                                        foreach ($list_hrs as $hrs) {
                                                            ?>
                                                            <div class="row justify-content-end align-items-center mb-2 hrs-row"
                                                                data-type="edit" data-id="<?= $hrs->id_hrs ?>">
                                                                <div class="col-md-6">
                                                                    <input type="hidden" name="id_hrs_edit[]"
                                                                        value="<?= $hrs->id_hrs ?>">
                                                                    <input type="text" class="form-control" name="hrs_edit[]"
                                                                        value="<?= $hrs->hrs ?>">
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <button type="button"
                                                                        class="btn btn-danger btn-sm btn-custom remove-row">
                                                                        <i class="bi bi-dash-lg text-white fs-4"></i>
                                                                    </button>
                                                                </div>
                                                            </div>



                                                            <?php
                                                        } ?>

                                                        <div class="hrs-container">
                                                            <div
                                                                class="row justify-content-end align-items-center mb-2 hrs-row" data-type="add">
                                                                <div class="col-md-6">
                                                                    <input type="text" class="form-control" name="add_hrs[]"
                                                                        placeholder="Masukkan No HRS">
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <div class="d-flex gap-2 justify-content-start">
                                                                        <button type="button"
                                                                            class="btn btn-success btn-sm btn-custom add-row">
                                                                            <i class="bi bi-plus-lg text-white fs-4"></i>
                                                                        </button>
                                                                        <button type="button"
                                                                            class="btn btn-danger btn-sm btn-custom remove-row">
                                                                            <i class="bi bi-dash-lg text-white fs-4"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>




                                                </div>
                                            </div>



                                        </div>

                                        <!-- SUBMIT -->
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-<?= $color_button ?> mt-3 me-5"
                                            style="border-radius:50px;">
                                            <?= $name_button ?>
                                        </button>
                                    </div>

                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>


<style>
    .btn-custom {
        padding: 0.25rem 0.5rem;
        border-radius: 5px;
        margin: 0;
        font-size: 1rem;
    }
</style>
<script>
$(document).ready(function () {

    // ================================
    // REMOVE ROW (EDIT vs ADD)
    // ================================
    $('#hrs-container').on('click', '.remove-row', function () {

        let row = $(this).closest('.hrs-row');
        let type = row.data('type'); // edit / add
        let id_hrs = row.data('id'); // hanya ada kalau edit

        // ======================
        // EDIT MODE ➜ AJAX DELETE
        // ======================
        if (type === 'edit') {

            if (!id_hrs) {
                alert('ID HRS tidak ditemukan');
                return;
            }

            if (!confirm('Yakin ingin menghapus HRS ini?')) return;

            $.ajax({
                url: '<?= base_url("hrs/delete_hrs") ?>',
                type: 'POST',
                dataType: 'json',
                data: { id_hrs: id_hrs },
                success: function (res) {
                    if (res.status === 'success') {
                        row.fadeOut(200, function () {
                            $(this).remove();
                        });
                    } else {
                        alert(res.message ?? 'Gagal menghapus data');
                    }
                },
                error: function () {
                    alert('Terjadi kesalahan server');
                }
            });

            return; // STOP
        }

        // ======================
        // ADD MODE ➜ UI ONLY
        // ======================
        let totalRow = $('#hrs-container .hrs-row').length;

        if (totalRow <= 1) {
            alert('Minimal harus ada 1 HRS');
            return;
        }

        row.fadeOut(150, function () {
            $(this).remove();
        });
    });

});
</script>

<script>
    $(document).ready(function () {
        // Tambah row
        $('#hrs-container').on('click', '.add-row', function () {
            let row = $(this).closest('.hrs-row').clone();
            row.find('input').val(''); // kosongkan input
            $('#hrs-container').append(row);
        });

        // Hapus row
      

        $('#form_add_hrs').on('submit', function (e) {
            e.preventDefault();
            let formData = new FormData(this);

            $.ajax({

                url: '<?= base_url($link_submit) ?>',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    var res = JSON.parse(response);
                    if (res.status === 'success') {
                        $.notify({
                            message: res.message
                        }, {
                            type: 'success',
                            delay: 3000,
                            placement: {
                                from: "top",
                                align: "right"
                            },
                            offset: { x: 20, y: 70 }
                        });

                        setTimeout(function () {
                            window.location.href = res.redirect;
                        }, 1000);
                    } else if (res.status === 'danger') {
                        $.notify({
                            message: res.message
                        }, {
                            type: 'danger',
                            delay: 3000,
                            placement: {
                                from: "top",
                                align: "right"
                            },
                            offset: { x: 20, y: 70 }
                        });

                        setTimeout(function () {
                            window.location.href = res.redirect;
                        }, 1000);

                    }


                },
                error: function (xhr, status, error) {

                    console.error('Error:', error);
                    alert('Gagal submit!');
                }



            })

        })
    });

</script>