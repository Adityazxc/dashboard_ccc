<div class="modal fade" id="addUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">                    
                </button>
            </div>
            <form action="<?= base_url('users/add_users') ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <input id="idStockProduct" name="idStockProduct" type="hidden" class="form-control"
                                placeholder="Insert Barcode Product" autocomplete="off" required>
                            <div class="form-group form-group-default">
                                <label>Account Name</label>
                                <input id="accountName" name="accountName" type="text" class="form-control"
                                    placeholder="Insert Account Name" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Username</label>
                                <input id="username" name="username" type="text" class="form-control"
                                    placeholder="Insert Username" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>No Hp (diawali dengan 62)</label>
                                <input id="no_hp" name="no_hp" type="text" class="form-control"
                                    placeholder="Insert No hp" autocomplete="off" required>
                            </div>
                        </div>
                        <script>
                            document.getElementById('no_hp').addEventListener('input', function (e) {
                                // Hanya izinkan angka dan simbol "+" untuk kode negara
                                this.value = this.value.replace(/[^0-9+]/g, ''); // Menghapus semua karakter selain angka dan "+"
                            });
                        </script>
             

                        <div class="col-sm-12">

                            <div class="form-group form-group-default">

                                <label>Location</label>
                                <select class="form-select select2" name="location" id="location">
                                    <option value="">-- Pilih Origin --</option>
                                    <?php
                                    $get_origins = json_decode($get_origins, true); // Decode di view jika belum di controller
                                    if (isset($get_origins) && is_array($get_origins) && !empty($get_origins)): ?>
                                        <?php foreach ($get_origins as $get_origin): ?>
                                            <option value="<?= $get_origin['zone_code'] ?>">
                                                <?=$get_origin['origin_name']?> - <?= $get_origin['zone'] ?> (<?= $get_origin['zone_code'] ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="">Tidak ada origin</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>

                        <script>
                            $('#addUser').on('shown.bs.modal', function () {
                                $('#location').select2({
                                    placeholder: "-- Pilih Origin --",
                                    allowClear: true,
                                    width: '100%',
                                    dropdownParent: $('#addUser') // penting agar dropdown muncul di atas modal
                                });
                            });

                        </script>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Role</label>
                                <select class="form-select" id="role" name="role" aria-label="Default select example"
                                    required>
                                    <option value="Admin">Admin</option>
                                    <option value="BPS">BPS</option>
                                    <option value="BBP">BBP</option>
                                    <option value="CS">CS</option>
                                    <option value="CCC">CCC</option>
                                    <option value="HC">HC</option>
                                    <option value="Koordinator">Koordinator</option>
                                    <option value="Kepala Cabang">Kepala Cabang</option>
                                    <option value="Kepala Cabang BDO2">Kepala Cabang BDO2</option>
                                    <option value="PAO">PAO</option>
                                    <option value="Super User">Super User</option>
                                    <option value="POD">POD</option>
                                    <option value="Admin BDO2">Admin BDO2</option>
                                    <option value="Koordinator BDO2">Koordinator BDO2</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>

        </div>

    </div>
</div>

<script>

    function autoSubmitScan() {
        var createDate = $('#createDate').val();
        var barcodeProduct = $('#barcodeSellingProduct').val();

        $.ajax({
            url: '<?= base_url("stock/get_product") ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                barcodeProduct: barcodeProduct
            },
            success: function (response) {
                $('#nameSellingProduct').val(response.nameProduct);
                $('#idStockProduct').val(response.idProduct);
                $('#priceSellingProduct').val(response.priceSelling);
                $('#stock').val(response.stock);
                

            },
            error: function (xhr, status, error) {
                console.error("Ajax error:", status, error);
            }
        });
    }
    $('#barcodeSellingProduct').on('keypress', function (e) {
        if (e.which === 13) { // Key code 13 adalah Enter
            e.preventDefault(); // Mencegah form submit default
            autoSubmitScan(); // Panggil fungsi autoSubmitScan
        }
    });

</script>