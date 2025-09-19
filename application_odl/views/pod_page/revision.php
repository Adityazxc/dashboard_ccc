<div class="tab-content mt-3" id="checkerTabsContent">
    <!-- Tab Data Tidak Sesuai -->
    <div class="tab-pane fade" id="revision" role="tabpanel" aria-labelledby="revision-tab">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5>Data Revision</h5>
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <div class="mb-3">
                        <input type="text" id="searchAwbRevision" class="form-control"
                            placeholder="Cari berdasarkan AWB...">
                    </div>

                </div>
            </div>

            <!--  Card Body sekarang mencakup gambar -->
            <div class="card-body">
                <div class="row">
                    <?php if (!empty($get_data_revision)): ?>
                        <?php foreach ($get_data_revision as $data_revision): ?>
                            <div class="col-md-2 mb-3 card-item card-item-revision"
                                data-awb-revision="<?= strtolower($data_revision->awb) ?>">
                                <div class="card mb-0 pb-0">
                                    <a data-bs-toggle="modal" data-bs-target="#ModalDetailImage" onclick="modalRevision(
                                             '<?= $data_revision->url_photo ?>',
                                             '<?= base_url($data_revision->url_revision) ?>',
                                             '<?= $data_revision->awb ?>',
                                             '<?= $data_revision->receiver_address ?>',                
                                             '<?= $data_revision->receiver_name ?>',
                                             '<?= $data_revision->link_maps ?>',
                                             '<?= $data_revision->no_tlp ?>',                                             
                                             '<?= $data_revision->reason_revision ?>',                                             

                                               
                                        )">
                                         <?php
                                        $isEmpty = empty($data_revision->url_photo);
                                        $isLocalPath = strpos($data_revision->url_photo, 'http') !== 0;
                                    ?>

                                    <?php if ($isEmpty): ?>
                                        <img src="<?= base_url('public/img/Image-not-found.png') ?>" class="card-img-top" style="width: 100%; object-fit: cover;">
                                    <?php elseif ($isLocalPath): ?>
                                        <img src="<?= base_url($data_revision->url_photo) ?>" class="card-img-top" style="width: 100%; object-fit: cover;">
                                    <?php else: ?>
                                        <img src="<?= $data_revision->url_photo ?>" class="card-img-top" style="width: 100%; object-fit: cover;">
                                    <?php endif; ?>
                                    </a>
                                    <p class="card-text small text-start ms-3 me-3 mb-1"><?= $data_revision->awb ?></p>

                                    <p class="card-text small text-start ms-3 me-3 mb-1 text-truncate" data-bs-toggle="tooltip"
                                        data-bs-placement="top"
                                        title="<?= htmlspecialchars($data_revision->remarks, ENT_QUOTES) ?>">
                                        <?= $data_revision->remarks ?>
                                    </p>


                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">Belum ada Revisi.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div> <!-- ⬅ Penutup card -->
    </div>
</div>
<script>
    document.getElementById('searchAwbRevision').addEventListener('input', function () {
        const keyword_revision = this.value.toLowerCase();
        const cards_revision = document.querySelectorAll('.card-item-revision');

        cards_revision.forEach(card => {
            const awb_revision = card.getAttribute('data-awb-revision');
            if (awb_revision.includes(keyword_revision)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });
</script>