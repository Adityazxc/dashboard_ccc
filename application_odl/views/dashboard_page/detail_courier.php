<div class="card">
    <div class="card-body">


        <div class="row">
            <div class="col">
                <h1>Summary Validasi POD</h1>
            </div>
            <div class="col d-flex justify-content-end">
                <?php if ($status_pod != "T"): ?>
                    <span class="badge rounded-pill bg-success"> Approve </span>
                <?php else: ?>
                    <span class="badge rounded-pill bg-warning"> In Progress </span>
                <?php endif; ?>
            </div>
        </div>



        <div class="container">
            <div class="row">
                <div class="col-sm">
                    <?php if (!empty($courier->courier_name)): ?>
                        <p><?= $courier->courier_name ?> - <?= $courier->id_courier ?> </p>
                    <?php else: ?>
                        <center>
                            <img src="<?= base_url('public/img/no-results.png') ?>" width="300px" height="300px">
                            <p>Mohon maaf, data yang Anda masukkan tidak valid karena data kurir dengan Kurir ID
                                <b><?= $id_courier ?></b> tersebut tidak terdaftar di database!<br> Silakan lengkapi format
                                di bawah
                                dan hubungi Tim IT.
                            </p>


                            <button type="button" class="btn btn-success" onclick="downloadTemplate()"><i
                                    class="bi bi-download"></i> Download Template</button>

                        </center>
                    <?php endif; ?>


                </div>
            </div>
            <div class="col-sm">
                <?php if (!empty($courier->courier_name)): ?>
                    <a href="https://wa.me/<?= $courier->no_tlp ?>">

                        <i class="bi bi-whatsapp"> <?= $courier->no_tlp ?></i>
                    </a>
                <?php else: ?>

                <?php endif; ?>

                <?php
                $persentase_progres = ($progress->success_pod / ($progress->success_pod + $progress->in_progress_pod)) * 100;
                $persentase_photo_pod = (($progress->qty_revisi + $progress->qty_sesuai) / $progress->qty_awb) * 100;
                ?>
                <div class="row">
                    <div class="col-md-6">
                        <br>
                        <h3 class="d-flex justify-content-start">Progress Delivery</h3>
                        <br>
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped" style="width:<?= $persentase_progres ?>%">
                                <?= number_format($persentase_progres, 1) ?>%
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <br>
                        <h3 class="d-flex justify-content-start">Success Rate Photo POD</h3>
                        <br>
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped" style="width:<?= $persentase_photo_pod ?>%">
                                <?= number_format($persentase_photo_pod, 1) ?>%
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col d-flex justify-content-end">
                    

                        <div class="d-flex justify-content-end">

                            <form id="formStatusPod" enctype="multipart/form-data">
                                <input type="hidden" id="runsheet_date_status_pod" name="runsheet_date_status_pod"
                                    value="<?= $runsheet_date ?>">
                                <input type="hidden" id="id_courier_status_pod" name="id_courier_status_pod"
                                    value="<?= $id_courier ?>">
                                <button type="submit" class="btn btn-primary mt-3">Submit</button>
                            </form>

                        </div>
                    
                </div>
                <script>
                    $(document).ready(function () {
                        $('#formStatusPod').on('submit', function (e) {
                            e.preventDefault(); // Stop form from submitting the traditional way

                            let formData = new FormData(this);

                            $.ajax({
                                url: '<?= base_url("admin/change_status_awb") ?>',
                                type: 'POST',
                                data: formData,
                                processData: false, // wajib untuk FormData
                                contentType: false, // wajib untuk FormData
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
                                            window.location.href = res.redirect; // Redirect ke halaman validasi/admin
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
                                            window.location.href = res.redirect; // Redirect ke halaman validasi/admin
                                        }, 1000);

                                    }

                                },
                                error: function (xhr, status, error) {
                                    // ❌ Gagal
                                    console.error('Error:', error);
                                    alert('Gagal submit!');
                                }
                            });
                        });
                    });
                </script>


            </div>
        </div>
    </div>
</div>

<script>
    function downloadTemplate() {
        // Ganti URL dengan lokasi file template Anda
        var templateUrl = "<?= base_url('public/tamplate_excel/data_courier.xlsx') ?>";
        window.location.href = templateUrl;
    }
</script>
<?php if (!empty($courier->courier_name)): ?>
    <div class="container mt-4">
        <!-- Nav Tabs -->
        <ul class="nav nav-tabs" id="checkerTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="sesuai-tab" data-bs-toggle="tab" data-bs-target="#sesuai" type="button"
                    role="tab" aria-controls="sesuai" aria-selected="true">
                    Data Sesuai (<?= $summary_checkers_approve ?>)
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tidak-sesuai-tab" data-bs-toggle="tab" data-bs-target="#tidak-sesuai"
                    type="button" role="tab" aria-controls="tidak-sesuai" aria-selected="false">
                    Data Tidak Sesuai (<?= $summary_checkers_not_approve ?>)
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="revision-tab" data-bs-toggle="tab" data-bs-target="#revision" type="button"
                    role="tab" aria-controls="revision" aria-selected="false">
                    Data Revisi (<?= $summary_revision ?>)
                </button>
            </li>
        </ul>
        <!-- Tab Content -->
        <div class="tab-content mt-3" id="checkerTabsContent">
            <!-- Tab Data Sesuai -->
            <div class="tab-pane fade show active" id="sesuai" role="tabpanel" aria-labelledby="sesuai-tab">
                <div class="card">

                    <div class="card-header d-flex justify-content-between">
                        <h5>Data Sesuai</h5>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <div class="mb-3">
                                <input type="text" id="searchAwb" class="form-control"
                                    placeholder="Cari berdasarkan AWB...">
                            </div>

                            <?php if ($status_pod == "T"): ?>
                                <div class="form-check">
                                    <input type="checkbox" id="select-all-sesuai"></input>
                                    <label class="form-check-label"> Select All</label>
                                </div>
                                <button id="update-status-sesuai" type="button" class="btn btn-danger">Tidak sesuai</button>
                            <?php endif; ?>
                        </div>
                    </div>
                    <!-- script post tidak status -->
                    <script>
                        $(document).ready(function () {
                            // Pilih semua checkbox
                            var id_courier = "<?= $id_courier ?>";
                            var runsheet_date = "<?= $runsheet_date ?>";
                            var no_runsheet = "<?= $no_runsheet ?>";


                            $("#select-all-sesuai").click(function () {
                                $(".checker-checkbox-sesuai").prop("checked", this.checked);
                            });

                            // Update status berdasarkan checkbox yang dipilih
                            $("#update-status-sesuai").click(function () {
                                let selectedIds = [];
                                $(".checker-checkbox-sesuai:checked").each(function () {
                                    selectedIds.push($(this).val());
                                });

                                if (selectedIds.length === 0) {
                                    alert("Pilih minimal satu data untuk diubah statusnya.");
                                    return;
                                }

                                // Kirim data via AJAX ke server
                                $.ajax({
                                    url: "<?= base_url('admin/change_status') ?>", // Sesuaikan dengan route di controller
                                    type: "POST",
                                    data: {
                                        ids: selectedIds,
                                        id_courier: id_courier,
                                        runsheet_date: runsheet_date,
                                        no_runsheet: no_runsheet,
                                    },
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
                                                location.reload(); // Reload halaman agar data terbaru muncul
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
                                                location.reload(); // Reload halaman agar data terbaru muncul
                                            }, 1000);

                                        }

                                    },
                                    error: function () {
                                        alert("Gagal memperbarui status. Silakan coba lagi.");
                                    }
                                });
                            });
                        });

                    </script>
                    <div class="card-body">
                        <div class="row">
                            <?php if (!empty($data_checkers_approve)): ?>
                                <?php foreach ($data_checkers_approve as $data_checker): ?>
                                    <div class="col-md-2 mb-3 card-item card-item-approve"
                                        data-awb="<?= strtolower($data_checker->awb) ?>">

                                        <?php if ($data_checker->is_banking == 1): ?>
                                            <div class="card mb-0 pb-0 " style="border-bottom: 4px solid #0d6efd;">
                                            <?php else: ?>
                                                <div class="card mb-0 pb-0 ">
                                                <?php endif; ?>
                                                <a data-bs-toggle="modal" data-bs-target="#ModalDetailImage" onclick="detailImage(
                                        '<?= $data_checker->url_pod ?>',
                                            '<?= $data_checker->runsheet_date ?>',
                                            '<?= $data_checker->no_runsheet ?>',
                                            '<?= $data_checker->awb ?>',
                                            '<?= $data_checker->status_cod ?>',
                                            '<?= $data_checker->link_maps ?>',
                                            '<?= $data_checker->id_courier ?>',
                                            '<?= $data_checker->courier_name ?>',
                                            '<?= $data_checker->no_tlp ?>',
                                            '<?= $data_checker->destination_code ?>',
                                            '<?= $data_checker->zone ?>',
                                            '<?= $data_checker->received_date ?>',
                                            '<?= $data_checker->receiver_name ?>',
                                            '<?= $data_checker->receiver_address ?>',                
                                            '<?= $data_checker->paymeny_type ?>',
                                            '<?= $data_checker->amount ?>',                
                                            '<?= $data_checker->status_via ?>',                
                                            '<?= $data_checker->pod_status ?>',                
                                            '<?= $data_checker->big_grouping_cust ?>'     
                                    )">
                                                    <?php
                                                    $isEmpty = empty($data_checker->url_photo);
                                                    $isLocalPath = strpos($data_checker->url_photo, 'http') !== 0;
                                                    ?>

                                                    <?php if ($isEmpty): ?>
                                                        <img src="<?= base_url('public/img/Image-not-found.png') ?>"
                                                            class="card-img-top" style="width: 100%; object-fit: cover;">
                                                    <?php elseif ($isLocalPath): ?>
                                                        <img src="<?= base_url($data_checker->url_photo) ?>" class="card-img-top"
                                                            style="width: 100%; object-fit: cover;">
                                                    <?php else: ?>
                                                        <img src="<?= $data_checker->url_photo ?>" class="card-img-top"
                                                            style="width: 100%; object-fit: cover;">
                                                    <?php endif; ?>


                                                </a>


                                                <p class="card-text small text-start ms-3 me-3 mb-1"><?= $data_checker->awb ?></p>
                                                <!-- <p class="card-text fw-bold text-start ms-3 me-3 mb-1"><?= $data_checker->big_grouping_cust ?></p> -->

                                                <p class="card-text small text-start ms-3 me-3 mb-1 text-truncate"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="<?= htmlspecialchars($data_checker->remarks, ENT_QUOTES) ?>">
                                                    <?= $data_checker->remarks ?>
                                                </p>


                                                <div class="position-absolute top-0 end-0 m-1">
                                                    <input type="checkbox" class="checker-checkbox-sesuai"
                                                        value="<?= $data_checker->id_checker ?>">
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="text-muted">Belum ada data yang sesuai.</p>
                                <?php endif; ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <script>
                document.getElementById('searchAwb').addEventListener('input', function () {
                    const keyword = this.value.toLowerCase();
                    const cards = document.querySelectorAll('.card-item');

                    cards.forEach(card => {
                        const awb = card.getAttribute('data-awb');
                        if (awb.includes(keyword)) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            </script>

            <!-- Tab Data Tidak Sesuai -->
            <div class="tab-content mt-3" id="checkerTabsContent">
                <div class="tab-pane fade" id="tidak-sesuai" role="tabpanel" aria-labelledby="tidak-sesuai-tab">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h5>Data Tidak Sesuai</h5>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <div class="mb-3">
                                    <input type="text" id="searchAwbTidakSesuai" class="form-control"
                                        placeholder="Cari berdasarkan AWB...">
                                </div>
                                <?php if ($status_pod == "T"): ?>
                                    <div class="form-check">
                                        <input type="checkbox" id="select-all-tidak-sesuai">
                                        <label class="form-check-label"> Select All</label>
                                    </div>
                                    <button id="update-status-tidak-sesuai" type="button" class="btn btn-primary btn-sm">Data
                                        sesuai</button>

                                <?php endif; ?>
                            </div>
                        </div>

                        <!--  Card Body sekarang mencakup gambar -->
                        <div class="card-body">
                            <div class="row">
                                <?php if (!empty($data_checkers_not_approve)): ?>
                                    <?php foreach ($data_checkers_not_approve as $data_checker): ?>
                                        <div class="col-md-2 mb-3 card-item card-item-not-approve"
                                            data-awb-not-approve="<?= strtolower($data_checker->awb) ?>">
                                            <?php if ($data_checker->is_banking == 1): ?>
                                                <div class="card mb-0 pb-0 " style="border-bottom: 4px solid #0d6efd;">
                                                <?php else: ?>
                                                    <div class="card mb-0 pb-0 ">
                                                    <?php endif; ?>
                                                    <a data-bs-toggle="modal" data-bs-target="#ModalDetailImage" onclick="detailImage_non_approves(
                                             '<?= $data_checker->url_photo ?>',
                                             '<?= $data_checker->awb ?>',
                                             '<?= $data_checker->receiver_address ?>',                
                                             '<?= $data_checker->receiver_name ?>',
                                             '<?= $data_checker->link_maps ?>',
                                             '<?= $data_checker->no_tlp ?>',  
                                               '<?= $runsheet_date ?>',                                             
                                             '<?= $id_courier ?>',                                             
                                             '<?= $no_runsheet ?>',                                            
                                               
                                        )">
                                                        <?php
                                                        $isEmpty = empty($data_checker->url_photo);
                                                        $isLocalPath = strpos($data_checker->url_photo, 'http') !== 0;
                                                        ?>

                                                        <?php if ($isEmpty): ?>
                                                            <img src="<?= base_url('public/img/Image-not-found.png') ?>"
                                                                class="card-img-top" style="width: 100%; object-fit: cover;">
                                                        <?php elseif ($isLocalPath): ?>
                                                            <img src="<?= base_url($data_checker->url_photo) ?>" class="card-img-top"
                                                                style="width: 100%; object-fit: cover;">
                                                        <?php else: ?>
                                                            <img src="<?= $data_checker->url_photo ?>" class="card-img-top"
                                                                style="width: 100%; object-fit: cover;">
                                                        <?php endif; ?>
                                                    </a>

                                                    <p class="card-text small text-start ms-3 me-3 mb-1"><?= $data_checker->awb ?>
                                                    </p>
                                                    <!-- <p class="card-text fw-bold text-start ms-3 me-3 mb-1"><?= $data_checker->big_grouping_cust ?></p> -->

                                                    <p class="card-text small text-start ms-3 me-3 mb-1 text-truncate"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="<?= htmlspecialchars($data_checker->remarks, ENT_QUOTES) ?>">
                                                        <?= $data_checker->remarks ?>
                                                    </p>


                                                    <input type="checkbox" class="checker-checkbox-tidak-sesuai position-absolute"
                                                        style="top: 5px; left: 5px;" value="<?= $data_checker->id_checker ?>">
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <p class="text-muted">Belum ada data yang tidak sesuai.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div> <!-- ⬅ Penutup card -->
                    </div>
                </div>

                <?php $this->load->view('dashboard_page/revision') ?>
            </div>
            <script>
                document.getElementById('searchAwbTidakSesuai').addEventListener('input', function () {
                    const keyword_not_approve = this.value.toLowerCase();
                    const cards_not_approve = document.querySelectorAll('.card-item-not-approve');

                    cards_not_approve.forEach(card => {
                        const awb_not_approve = card.getAttribute('data-awb-not-approve');
                        if (awb_not_approve.includes(keyword_not_approve)) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            </script>

        </div>
    </div>
    <!-- Script select all -->
    <script>
        document.getElementById('select-all-sesuai').addEventListener('click', function () {
            let checkboxes = document.querySelectorAll('.checker-checkbox-sesuai');
            let allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);
            checkboxes.forEach(checkbox => checkbox.checked = !allChecked);
        });

        document.getElementById('select-all-tidak-sesuai').addEventListener('click', function () {
            let checkboxes = document.querySelectorAll('.checker-checkbox-tidak-sesuai');
            let allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);
            checkboxes.forEach(checkbox => checkbox.checked = !allChecked);
        });
    </script>
    <!-- End Script select all -->

    <!-- scirpt kirim data tidak sesuai -->
    <script>

        $(document).ready(function () {
            var runsheet_date_approve = "<?= $runsheet_date ?>";
            var id_courier_approve = "<?= $id_courier ?>";
            var no_runsheet_approve = "<?= $no_runsheet ?>";

            console.log(runsheet_date_approve, no_runsheet_approve);

            // Pilih semua checkbox                    
            $("#select-all-tidak-sesuai").click(function () {
                $(".checker-checkbox-tidak-sesuai").prop("checked", this.checked);
            });

            // Update status berdasarkan checkbox yang dipilih
            $("#update-status-tidak-sesuai").click(function () {
                let selectedIds = [];
                $(".checker-checkbox-tidak-sesuai:checked").each(function () {
                    selectedIds.push($(this).val());
                });

                if (selectedIds.length === 0) {
                    alert("Pilih minimal satu data untuk diubah statusnya.");
                    return;
                }

                // Kirim data via AJAX ke server
                $.ajax({
                    url: "<?= base_url('admin/change_status_approve') ?>", // Sesuaikan dengan route di controller
                    type: "POST",
                    data: {
                        ids_tidak_sesuai: selectedIds,
                        runsheet_date_approve: runsheet_date_approve,
                        no_runsheet_approve: no_runsheet_approve,
                        id_courier_approve: id_courier_approve,
                    },
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
                                location.reload(); // Reload halaman agar data terbaru muncul
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
                                location.reload(); // Reload halaman agar data terbaru muncul
                            }, 1000);

                        }

                    },
                    error: function () {
                        alert("Gagal memperbarui status. Silakan coba lagi.");
                    }
                });
            });
        });


    </script>
    <!-- end scirpt kirim data tidak sesuai -->

    <?php $this->load->view('dashboard_page/modal_approve') ?>




    <!-- modal detail non approve -->



    <style>
        .checker-checkbox-sesuai,
        .checker-checkbox-tidak-sesuai {
            width: 24px;
            height: 24px;
            top: 10px;
            left: 10px;
            cursor: pointer;
            /* Efek kursor tangan */
        }

        /* Opsional: Efek saat checked */
        .checker-checkbox-sesuai:checked,
        .checker-checkbox-tidak-sesuai:checked {
            background-color: #007bff;
            border-color: #007bff;
        }

        /* Opsional: Efek hover */
        .checker-checkbox-sesuai:hover,
        .checker-checkbox-tidak-sesuai:hover {
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
            /* Efek glow saat hover */
        }
    </style>

<?php else: ?>

<?php endif; ?>
<script>
    //   document.addEventListener('DOMContentLoaded', function () {
    //     let modal = new bootstrap.Modal(document.getElementById('ModalDetailImageNonApprove'));
    //     modal.show();
    //   });
</script>