<?php

?>
<div class="card border-0 shadow-sm rounded-3 mt-4">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center">
            <!-- KIRI -->
            <div class="d-flex align-items-center">
                <div class="avatar-wrapper me-3">
                    <?php 
                    // Ambil foto dari database, default jika tidak ada
                    $photo_url = !empty($courier_data['photo_url']) ? 
                        base_url($courier_data['photo_url']) : 
                        base_url('public/img/Image-not-found.png');
                    ?>
                    <img src="<?= $photo_url ?>" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover;">
                </div>
                <div>
                    <h3 class="fw-bold mb-1">
                        <?= html_escape($courier_data['courier_name']) ?> - <?= $courier_data['id_courier'] ?>
                    </h3>
                    <a href="https://wa.me/<?= $courier_data['no_tlp'] ?>" class="text-success text-decoration-none" target="_blank">
                        <i class="bi bi-whatsapp"></i>
                        <?= $courier_data['no_tlp'] ?>
                    </a>
                </div>
            </div>
            
            <!-- KANAN: Statistik Runsheet -->
            <h4 class="fw-bold mb-1">
                <span class="fw-bold text-primary"><?= $runsheet_stats['depositable'] ?></span>
                dari
                <span class="fw-bold text-primary"><?= $runsheet_stats['total'] ?></span>
                Runsheet Dapat Disetorkan.
            </h4>
        </div>
    </div>
</div>