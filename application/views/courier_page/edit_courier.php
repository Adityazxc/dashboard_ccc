<div class="modal fade" id="ModalEditCourier" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Courier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form action="<?= base_url('courier/edit_courier') ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <p id="message-edit-user"></p>
                    <div class="row">
                        <div class="col-sm-12">
                            <input id="idEdit" name="idEdit" type="hidden" class="form-control"
                                placeholder="Insert Barcode Product" autocomplete="off" required>
                            <div class="form-group form-group-default">
                                <label>Nama Kurir</label>
                                <input id="courierNameEdit" name="courierNameEdit" type="text" class="form-control"
                                    placeholder="Masukkan Nama Kurir" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>ID Courier</label>
                                <input id="idCourierEdit" name="idCourierEdit" type="text" class="form-control"
                                    placeholder="Masukkan Id Courier" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>NIK</label>
                                <input id="nikEdit" name="nikEdit" type="text" class="form-control"
                                    placeholder="Masukkan NIK" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Tipe Kurir</label>
                                <select class="form-select" id="type_courierEdit" name="type_courierEdit"
                                    aria-label="Default select example" required>
                                    <option value="RIDER">RIDER</option>
                                    <option value="DRIVER">DRIVER</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Lokasi</label>
                                <input id="locationEdit" name="locationEdit" type="text" class="form-control"
                                    placeholder="Masukkan Location" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Area</label>
                                <input id="areaEdit" name="areaEdit" type="text" class="form-control"
                                    placeholder="Masukkan Area" autocomplete="off" required>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Zone</label>
                                <select class="form-select" id="zoneEdit" name="zoneEdit"
                                    aria-label="Default select example" required>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="C">C</option>
                                    <option value="C1">C1</option>
                                    <option value="C2">C2</option>
                                    <option value="C3">C3</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-12">

                            <div class="form-group form-group-default">

                                <label>Location</label>
                                <select class="form-select select2" name="work_zone_edit" id="work_zone_edit" required>
                                    <option value="">-- Pilih Origin --</option>
                                    <?php
                                    $get_origins = json_decode($get_origins, true); // Decode di view jika belum di controller
                                    if (isset($get_origins) && is_array($get_origins) && !empty($get_origins)): ?>
                                        <?php foreach ($get_origins as $get_origin): ?>
                                            <option value="<?= $get_origin['zone_code'] ?>">
                                                <?= $get_origin['origin_name'] ?> - <?= $get_origin['zone'] ?>
                                                (<?= $get_origin['zone_code'] ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="">Tidak ada origin</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>

                        <script>
                            $('#ModalEditCourier').on('shown.bs.modal', function () {
                                $('#work_zone_edit').select2({
                                    placeholder: "-- Pilih Origin --",
                                    allowClear: true,
                                    width: '100%',
                                    dropdownParent: $('#ModalEditCourier') // penting agar dropdown muncul di atas modal
                                });
                            });

                        </script>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>No Hp (diawali dengan 62)</label>
                                <input id="no_tlpEdit" name="no_tlpEdit" type="number" class="form-control"
                                    placeholder="Masukkan no hp" autocomplete="off" required>
                            </div>
                        </div>


                        <!-- UPLOAD FOTO -->
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Foto Avatar</label>
                                <input type="file" id="imgEdit" name="avatarEdit" class="form-control" accept="image/*">

                            </div>
                        </div>

                        <!-- PREVIEW -->
                        <div class="col-sm-12 text-center mt-3">
                            <img id="imageEdit" class="avatar-preview-edit" style="display:none;">
                        </div>
                    </div>
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>"
                        value="<?= $this->security->get_csrf_hash() ?>">

                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Edit Kurir</button>
                </div>
            </form>

        </div>

    </div>
</div>

<style>
    .avatar-preview-edit {
        width: 300px;
        height: 300px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #ddd;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function () {

        let cropperEdit = null;

        const modalEdit = document.getElementById('ModalEditCourier'); // pastikan ID ini benar
        const formEdit = modalEdit.querySelector('form');
        const inputEdit = modalEdit.querySelector('#imgEdit');
        const imageEdit = modalEdit.querySelector('#imageEdit');

        /* =========================
           RESET SAAT MODAL DITUTUP
        ========================== */
        modalEdit.addEventListener('hidden.bs.modal', function () {
            if (cropperEdit) {
                cropperEdit.destroy();
                cropperEdit = null;
            }
            imageEdit.src = '';
            imageEdit.style.display = 'none';
            inputEdit.value = '';
        });

        /* =========================
           PILIH FOTO
        ========================== */
        inputEdit.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function (e) {
                imageEdit.src = e.target.result;
                imageEdit.style.display = 'block';

                if (cropperEdit) cropperEdit.destroy();

                cropperEdit = new Cropper(imageEdit, {
                    aspectRatio: 1,
                    viewMode: 1,
                    autoCropArea: 1
                });
            };
            reader.readAsDataURL(file);
        });

        /* =========================
           SUBMIT FORM EDIT
        ========================== */
        formEdit.addEventListener('submit', function (e) {

            // ✅ KALAU TIDAK GANTI FOTO → SUBMIT NORMAL
            if (!inputEdit.files.length) {
                return;
            }

            // ❌ kalau ada foto → crop dulu
            e.preventDefault();

            if (!cropperEdit) {
                alert('Pilih foto dulu');
                return;
            }

            const canvas = cropperEdit.getCroppedCanvas({
                width: 400,
                height: 400
            });

            canvas.toBlob(function (blob) {
                const file = new File([blob], 'avatar.jpg', { type: 'image/jpeg' });

                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                inputEdit.files = dataTransfer.files;

                formEdit.submit();
            }, 'image/jpeg');
        });

    });
</script>


<script>
    document.getElementById('idCourierEdit').addEventListener('input', function () {
        this.value = this.value.toUpperCase();
    });
    document.getElementById('courierNameEdit').addEventListener('input', function () {
        this.value = this.value.toUpperCase();
    });
    document.getElementById('nikEdit').addEventListener('input', function () {
        this.value = this.value.toUpperCase();
    });
    document.getElementById('locationEdit').addEventListener('input', function () {
        this.value = this.value.toUpperCase();
    });
    document.getElementById('areaEdit').addEventListener('input', function () {
        this.value = this.value.toUpperCase();
    });
    document.getElementById('no_tlpEdit').addEventListener('input', function () {
        this.value = this.value.toUpperCase();
    });   
</script>