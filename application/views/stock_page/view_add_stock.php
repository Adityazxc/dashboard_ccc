<div class="modal fade" id="addSellingModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Stock Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('stock/add_stock') ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <input id="idStockProduct" name="idStockProduct" type="hidden" class="form-control"
                                placeholder="Insert Barcode Product" autocomplete="off" required>
                            <div class="form-group form-group-default">
                                <label>Barcode Product</label>
                                <div class="form-group form-group-default">
                                    <div class="input-group">
                                        <input id="barcodeSellingProduct" name="barcodeSellingProduct" type="text"
                                            class="form-control" placeholder="Insert Barcode Product" autocomplete="off"
                                            required>
                                        <div class="input-group-append">
                                            <!-- Ikon Search -->
                                            <span class="input-group-text" id="searchIcon">
                                                <i class="fas fa-search"></i> <!-- Font Awesome Search Icon -->
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Name Product</label>
                                <input id="nameSellingProduct" name="nameSellingProduct" type="text"
                                    class="form-control" placeholder="Insert Name Product" autocomplete="off" required
                                    readonly>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Price</label>
                                <input id="priceSellingProduct" name="priceSellingProduct" type="number" min="0"
                                    class="form-control" placeholder="Price Product" autocomplete="off" required
                                    readonly>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Stock</label>
                                <input id="stock" name="stock" type="number" class="form-control"
                                    placeholder="Insert Stock" autocomplete="off" required readonly>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Add Stock</label>
                                <input id="addStock" name="addStock" type="number" class="form-control"
                                    placeholder="Insert Stock" autocomplete="off" required>
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

    $('#searchIcon').on('click', function () {
        // Panggil fungsi yang sama saat menekan Enter
        autoSubmitScan();  // Panggil fungsi autoSubmitScan
    });
</script>