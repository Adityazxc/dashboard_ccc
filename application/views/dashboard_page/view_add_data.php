<div class="modal fade" id="addRowModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered"  role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('product/add_product') ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Name Product</label>
                                <input id="nameProduct" name="nameProduct" type="text" class="form-control"
                                    placeholder="Insert Name Product" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Barcode Product</label>
                                <input id="barcodeProduct" name="barcodeProduct" type="text" class="form-control"
                                    placeholder="Insert Barcode Product" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Size Printing</label>
                                <input id="sizePrinting" name="sizePrinting" type="text" class="form-control"
                                    placeholder="Insert Size Printing" autocomplete="off" style="text-transform: uppercase" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Price Product</label>
                                <input id="priceProduct" name="priceProduct" type="number" min="0" class="form-control"
                                    placeholder="Price Product" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Price Packing</label>
                                <input id="pricePacking" name="pricePacking" type="number" min="0" class="form-control"
                                    placeholder="Price Packing" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Price Printing</label>
                                <input id="pricePrinting" name="pricePrinting" type="number" min="0"
                                    class="form-control" placeholder="Price Printing" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Price Press</label>
                                <input id="pricePress" name="pricePress" type="number" min="0" class="form-control"
                                    placeholder="Price Press" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Adds</label>
                                <input id="pricePress" name="pricePress" type="number" min="0" class="form-control"
                                    placeholder="Price Press" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Total</label>
                                <input id="total" name="total" type="text" class="form-control" placeholder="Total"
                                    readonly autocomplete="off">
                            </div>
                        </div>

                    </div>
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>"
                        value="<?= $this->security->get_csrf_hash() ?>">

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
    // Function to calculate the total
    function calculateTotal() {
        var priceProduct = parseFloat($('#priceProduct').val()) || 0;
        var pricePacking = parseFloat($('#pricePacking').val()) || 0;
        var pricePrinting = parseFloat($('#pricePrinting').val()) || 0;
        var pricePress = parseFloat($('#pricePress').val()) || 0;

        // Sum up the values
        var total = priceProduct + pricePacking + pricePrinting + pricePress;

        // Set the total to the "Total" field
        $('#total').val(total.toFixed(2));  // Using toFixed(2) to display as a decimal
    }

    // Bind change event to recalculate total whenever any price input changes
    $(document).ready(function () {
        $('#priceProduct, #pricePacking, #pricePrinting, #pricePress').on('input', function () {
            calculateTotal();  // Recalculate total on input change
        });

        // Initial calculation (in case values are pre-filled)
        calculateTotal();
    });
</script>