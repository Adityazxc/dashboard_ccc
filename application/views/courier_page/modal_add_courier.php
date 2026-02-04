<div class="modal fade" id="addCourier" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Courier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>

            <form action="<?= base_url('courier/add_courier') ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <input id="idStockProduct" name="idStockProduct" type="hidden" class="form-control"
                                placeholder="Insert Barcode Product" autocomplete="off" required>
                            <div class="form-group form-group-default">
                                <label>Nama Kurir</label>
                                <input id="courierName" name="courierName" type="text" class="form-control"
                                    placeholder="Masukkan Nama Kurir" autocomplete="off" value="testingkurir" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>ID Courier</label>
                                <input id="idCourier" name="idCourier" type="text" class="form-control"
                                    placeholder="Masukkan Id Courier" autocomplete="off" value="BDO12345" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>NIK</label>
                                <input id="nik" name="nik" type="text" class="form-control" placeholder="Masukkan NIK"
                                    autocomplete="off" value="Nik12345" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Tipe Kurir</label>
                                <select class="form-select" id="type_courier" name="type_courier"
                                    aria-label="Default select example" required>
                                    <option value="RIDER">RIDER</option>
                                    <option value="DRIVER">DRIVER</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Lokasi</label>
                                <input id="location" name="location" type="text" class="form-control"
                                    placeholder="Masukkan Location" autocomplete="off" value="BDO12345" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Area</label>
                                <input id="area" name="area" type="text" class="form-control"
                                    placeholder="Masukkan Area" autocomplete="off" value="bage" required>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Zone</label>
                                <select class="form-select" id="zone" name="zone" aria-label="Default select example"
                                    required>
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
                                <select class="form-select select2" name="work_zone" id="work_zone">
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
                            $('#addCourier').on('shown.bs.modal', function () {
                                $('#work_zone').select2({
                                    placeholder: "-- Pilih Origin --",
                                    allowClear: true,
                                    width: '100%',
                                    dropdownParent: $('#addCourier') // penting agar dropdown muncul di atas modal
                                });
                            });

                        </script>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>No Hp (diawali dengan 62)</label>
                                <input id="no_tlp" name="no_tlp" type="number" class="form-control"
                                    placeholder="Masukkan no hp" autocomplete="off" value="0882" required>
                            </div>
                        </div>



                        <!-- UPLOAD FOTO -->
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Foto Avatar</label>
                                <input type="file" id="imgAdd" name="avatar" class="form-control" accept="image/*"
                                    required>
                            </div>
                        </div>

                        <!-- PREVIEW -->
                        <div class="col-sm-12 text-center mt-3">
                            <img id="image" class="avatar-preview" style="display:none;">
                        </div>

                    </div>

                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </div>
            </form>

        </div>
    </div>

</div>

<style>
    .avatar-preview {
        width: 300px;
        height: 300px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #ddd;
    }
</style>
<script>
    let cropper;
    const inputFile = document.getElementById('imgAdd');
    const image = document.getElementById('image');
    const form = document.querySelector('form');

    inputFile.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (e) {
            image.src = e.target.result;
            image.style.display = 'block';

            if (cropper) cropper.destroy();

            cropper = new Cropper(image, {
                aspectRatio: 1,
                viewMode: 1,
                autoCropArea: 1
            });
        };
        reader.readAsDataURL(file);
    });

    form.addEventListener('submit', function (e) {

        if (!cropper) {
            alert('Pilih gambar dulu');
            e.preventDefault();
            return;
        }

        e.preventDefault(); // tahan submit sebentar

        const canvas = cropper.getCroppedCanvas({
            width: 400,
            height: 400
        });

        canvas.toBlob(function (blob) {

            // 🔥 GANTI FILE INPUT ASLI
            const fileInput = document.getElementById('imgAdd');
            const dataTransfer = new DataTransfer();
            const file = new File([blob], 'avatar.jpg', { type: 'image/jpeg' });
            dataTransfer.items.add(file);
            fileInput.files = dataTransfer.files;

            // 🚀 LANJUTKAN SUBMIT NORMAL
            form.submit();

        }, 'image/jpeg');
    });
</script>






<script>
    document.getElementById('idCourier').addEventListener('input', function () {
        this.value = this.value.toUpperCase();
    });
    document.getElementById('courierName').addEventListener('input', function () {
        this.value = this.value.toUpperCase();
    });
    document.getElementById('nik').addEventListener('input', function () {
        this.value = this.value.toUpperCase();
    });
    document.getElementById('location').addEventListener('input', function () {
        this.value = this.value.toUpperCase();
    });
    document.getElementById('area').addEventListener('input', function () {
        this.value = this.value.toUpperCase();
    });
    document.getElementById('no_tlp').addEventListener('input', function () {
        this.value = this.value.toUpperCase();
    });   
</script>