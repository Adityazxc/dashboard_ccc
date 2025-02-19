<div class="modal fade" id="addSellingModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Selling</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('selling/add_selling') ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <input id="idSellingProduct" name="idSellingProduct" type="hidden" class="form-control"
                                placeholder="Insert Barcode Product" autocomplete="off" required>
                            <div class="form-group form-group-default">
                                <label>Barcode Product</label>
                                <!-- Form input dengan ikon search -->
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


                    <div class="col-sm-12">
                        <div class="form-group form-group-default">
                            <label>Name Product</label>
                            <input id="nameSellingProduct" name="nameSellingProduct" type="text" class="form-control"
                                placeholder="Insert Name Product" autocomplete="off" required readonly>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group form-group-default">
                            <label>Amount</label>
                            <input id="amount" name="amount" type="number" class="form-control"
                                placeholder="Insert Size Printing" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group form-group-default">
                            <label>Price</label>
                            <input id="priceSellingProduct" name="priceSellingProduct" type="number" min="0"
                                class="form-control" placeholder="Price Product" autocomplete="off" required readonly>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group form-group-default">
                            <label>Sub Total</label>
                            <input id="subTotalSelling" name="subTotalSelling" type="number" min="0"
                                class="form-control" placeholder="Price Packing" autocomplete="off" required>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="form-group form-group-default">
                            <label>Source</label>
                            <select class="form-select" id="source" name="source" aria-label="Default select example"
                                required>
                                <option value="S">Shopee</option>
                                <option value="T">Tokopedia</option>
                                <option value="TK">Tiktok Shop</option>
                                <option value="O">Offline</option>
                            </select>
                        </div>
                    </div>

                    <input id="Profit" name="Profit" type="hidden" min="0" class="form-control" autocomplete="off"
                        required readonly>
                </div>
                <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>"
                    value="<?= $this->security->get_csrf_hash() ?>">

        </div>
        <div class="modal-footer border-0">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" id="submitButton" disabled>Add</button>
        </div>
        </form>

    </div>

</div>
</div>

<script>
    function toggleSubmitButton() {
        // Ambil nilai dari input subTotalSelling
        var subTotal = $('#Profit').val();

        // Periksa apakah subTotal null, kosong, atau 0
        if (subTotal === "" || subTotal === null || parseFloat(subTotal) <= 0) {
            // Disable tombol submit
            $('#submitButton').prop('disabled', true);
        } else {
            // Enable tombol submit
            $('#submitButton').prop('disabled', false);
        }
    }
    // Function to calculate the total
    function calculateTotalSelling() {
        var priceProduct = parseFloat($('#priceSellingProduct').val()) || 0;
        var amount = parseFloat($('#amount').val()) || 0;


        // Sum up the values
        var totalSelling = priceProduct * amount;

        toggleSubmitButton();
        // Set the total to the "Total" field
        $('#subTotalSelling').val(totalSelling.toFixed(0));  // Using toFixed(2) to display as a decimal
    }
    // Bind change event to recalculate total whenever any price input changes
    $(document).ready(function () {
        $('#amount').on('input', function () {
            calculateTotalSelling();  // Recalculate total on input change
        });

        // Initial calculation (in case values are pre-filled)
        calculateTotalSelling();
        toggleSubmitButton();


    });
    function autoSubmitScan() {
        var createDate = $('#createDate').val();
        var barcodeProduct = $('#barcodeSellingProduct').val();

        $.ajax({
            url: '<?= base_url("selling/get_product") ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                barcodeProduct: barcodeProduct
            },
            success: function (response) {
                $('#nameSellingProduct').val(response.nameProduct);
                $('#idSellingProduct').val(response.idProduct);
                $('#amount').val(response.amount);
                $('#priceSellingProduct').val(response.priceSelling);
                $('#subTotalSelling').val(response.priceSelling);
                $('#Profit').val(response.profit);
                toggleSubmitButton();


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
            // toggleSubmitButton();
        }

    });

    // Menambahkan event listener pada ikon search
    $('#searchIcon').on('click', function () {
        // Panggil fungsi yang sama saat menekan Enter
        autoSubmitScan();  // Panggil fungsi autoSubmitScan
    });

</script>