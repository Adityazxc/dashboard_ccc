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
                                    placeholder="Masukkan Nama Kurir" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>ID Courier</label>
                                <input id="idCourier" name="idCourier" type="text" class="form-control"
                                    placeholder="Masukkan Id Courier" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>NIK</label>
                                <input id="nik" name="nik" type="text" class="form-control" placeholder="Masukkan NIK"
                                    autocomplete="off" required>
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
                                    placeholder="Masukkan Location" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Area</label>
                                <input id="area" name="area" type="text" class="form-control"
                                    placeholder="Masukkan Area" autocomplete="off" required>
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
                                <label>No Hp (diawali dengan 62)</label>
                                <input id="no_tlp" name="no_tlp" type="number" class="form-control"
                                    placeholder="Masukkan no hp" autocomplete="off" required>
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