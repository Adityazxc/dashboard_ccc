<div class="modal fade" id="addUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
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
                                <label>Source</label>
                                <select class="form-select" id="role" name="role" aria-label="Default select example"
                                    required>
                                    <option value="Upper">Upper</option>
                                    <option value="Admin">Admin</option>
                                    <option value="Finance">Finance</option>
                                    <option value="Production">Production</option>
                                    <option value="Marketing">Marketing</option>
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