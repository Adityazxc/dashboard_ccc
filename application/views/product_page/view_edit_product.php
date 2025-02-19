<div class="modal fade" id="ModalEditProduct" tabindex="-1" role="dialog" aria-labelledby="ModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="ModalLabel">Edit Product</h5>
                <!-- Tombol Close -->
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('product/edit_product') ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <p id="message-warning-edit"></p>
                    <input type="hidden" class="form-control" name="idProduct" id="idProduct" autocomplete="off">
                    <div class="row">
                        <input id="idProductEdit" name="idProductEdit" type="hidden" min="0" class="form-control"
                            placeholder="Price Press" autocomplete="off" readonly>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Name Product</label>
                                <input id="nameProductEdit" name="nameProductEdit" type="text" class="form-control"
                                    placeholder="Insert Name Product" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Barcode Product</label>
                                <input id="barcodeProductEdit" name="barcodeProductEdit" type="text"
                                    class="form-control" placeholder="Insert Barcode Product" autocomplete="off"
                                    required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Category</label>
                                <input id="categoryProductEdit" name="categoryProductEdit" type="text" class="form-control"
                                    placeholder="Insert Category Printing" autocomplete="off"
                                    style="text-transform: uppercase" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Size Printing</label>
                                <input id="sizePrintingEdit" name="sizePrintingEdit" type="text" class="form-control"
                                    placeholder="Insert Size Printing" autocomplete="off"
                                    style="text-transform: uppercase" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Price Product</label>
                                <input id="priceProductEdit" name="priceProductEdit" type="number" min="0"
                                    class="form-control" placeholder="Price Product" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Price Packing</label>
                                <input id="pricePackingEdit" name="pricePackingEdit" type="number" min="0"
                                    class="form-control" placeholder="Price Packing" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Price Printing</label>
                                <input id="pricePrintingEdit" name="pricePrintingEdit" type="number" min="0"
                                    class="form-control" placeholder="Price Printing" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Price Press</label>
                                <input id="pricePressEdit" name="pricePressEdit" type="number" min="0"
                                    class="form-control" placeholder="Price Press" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Price Marketing </label>
                                <input id="priceMarketingEdit" name="priceMarketingEdit" type="number" min="0"
                                    class="form-control" placeholder="Input Price Marketing" autocomplete="off"
                                    required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Price Production</label>
                                <input id="priceProductionEdit" name="priceProductionEdit" type="text" class="form-control"
                                    placeholder="Total" readonly autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Persentase Margin</label>
                                <input id="persentaseMarginEdit" name="persentaseMarginEdit" type="number" min="0"
                                    class="form-control" placeholder="input 10 for 10%" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Price Selling </label>
                                <input id="priceSellingEdit" name="priceSellingEdit" type="number" class="form-control"
                                    placeholder="Input Price Selling" readonly autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Persentase Admin</label>
                                <input id="persentaseAdminEdit" name="persentaseAdminEdit" type="number" min="0"
                                    class="form-control" placeholder="input 10 for 10%" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Price Admin </label>
                                <input id="priceAdminEdit" name="priceAdminEdit" type="text" class="form-control"
                                    placeholder="Input Price Admin" readonly autocomplete="off" required>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Profit</label>
                                <input id="profitEdit" name="profitEdit" type="text" class="form-control" placeholder="Total"
                                    readonly autocomplete="off" required>
                            </div>
                        </div>  

                    </div>

                </div>

                <div class="modal-footer">
                    <!-- Tombol Close (Footer) -->
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <!-- Tombol Reset -->
                    <button type="submit" class="btn btn-primary col-md-3">Edit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Function to calculate the total
    function calculateEditTotal() {
        var priceProductEdit = parseFloat($('#priceProductEdit').val()) || 0;
        var pricePackingEdit = parseFloat($('#pricePackingEdit').val()) || 0;
        var pricePrintingEdit = parseFloat($('#pricePrintingEdit').val()) || 0;
        var pricePressEdit = parseFloat($('#pricePressEdit').val()) || 0;
        var persentaseMarginEdit = parseFloat($('#persentaseMarginEdit').val()) || 0;
        var persentaseAdminEdit = parseFloat($('#persentaseAdminEdit').val()) || 0;
        var priceMarketingEdit = parseFloat($('#priceMarketingEdit').val()) || 0;

          // Sum up the values
        var price_productionEdit = priceProductEdit + pricePackingEdit + pricePrintingEdit + pricePressEdit + priceMarketingEdit;
        var priceSellingEdit = price_productionEdit * (1 + persentaseMarginEdit / 100);
        var priceAdminEdit = priceSellingEdit * (persentaseAdminEdit / 100);
        var profitEdit = priceSellingEdit - price_productionEdit - priceAdminEdit;

        $('#priceProductionEdit').val(price_productionEdit.toFixed(2));  // Using toFixed(2) to display as a decimal
        $('#priceSellingEdit').val(priceSellingEdit.toFixed(2));  // Using toFixed(2) to display as a decimal
        $('#priceAdminEdit').val(priceAdminEdit.toFixed(2));  // Using toFixed(2) to display as a decimal
        $('#profitEdit').val(profitEdit.toFixed(2));  // Using toFixed(2) to display as a decimal
        
    }

    // Bind change event to recalculate total whenever any price input changes
    $(document).ready(function () {
        $('#priceProductEdit, #pricePackingEdit, #pricePrintingEdit, #pricePressEdit,#persentaseMarginEdit,#persentaseAdminEdit,#priceMarketingEdit').on('input', function () {
            calculateEditTotal();  // Recalculate total on input change
        });

        // Initial calculation (in case values are pre-filled)
        calculateEditTotal();
    });
</script>