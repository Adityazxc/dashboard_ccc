<div class="modal fade" id="addRowModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Product</h5>
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
                                <label>Img Product</label>
                                <input id="imgProduct" name="imgProduct[]" type="file" multiple accept="image/*"
                                    class="form-control" placeholder="Insert image product" autocomplete="off" required
                                    onchange="previewImages()">
                            </div>
                        </div>
                        <div id="imagePreview" class="d-flex flex-wrap gap-2 mt-2"></div>
                        <script>
                            function previewImages() {
                                var preview = document.getElementById('imagePreview');
                                preview.innerHTML = ''; // Clear previous previews
                                var files = document.getElementById('imgProduct').files;

                                if (files.length < 3) {
                                    alert("Minimal harus mengunggah 3 gambar!");
                                    return;
                                }
                                
                                if (files) {
                                    Array.from(files).forEach(file => {
                                        var reader = new FileReader();
                                        reader.onload = function (event) {
                                            var imgElement = document.createElement("img");
                                            imgElement.src = event.target.result;
                                            imgElement.style.width = "100px";
                                            imgElement.style.height = "100px";
                                            imgElement.style.objectFit = "cover";
                                            imgElement.style.borderRadius = "8px";
                                            imgElement.style.marginRight = "10px";
                                            preview.appendChild(imgElement);
                                        };
                                        reader.readAsDataURL(file);
                                    });
                                }
                            }
                        </script>

                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Barcode Product</label>
                                <input id="barcodeProduct" name="barcodeProduct" type="text" class="form-control"
                                    placeholder="Insert Barcode Product" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Category</label>
                                <input id="categoryProduct" name="categoryProduct" type="text" class="form-control"
                                    placeholder="Insert Category Printing" autocomplete="off"
                                    style="text-transform: uppercase" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Size Printing</label>
                                <input id="sizePrinting" name="sizePrinting" type="text" class="form-control"
                                    placeholder="Insert Size Printing" autocomplete="off"
                                    style="text-transform: uppercase" required>
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
                                <label>Stock</label>
                                <input id="stockProduct" name="stockProduct" type="number" min="0" class="form-control"
                                    placeholder="Insert First Stock Product" autocomplete="off" required>
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
                                <label>Price Marketing </label>
                                <input id="priceMarketing" name="priceMarketing" type="number" min="0"
                                    class="form-control" placeholder="Input Price Marketing" autocomplete="off"
                                    required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Price Production</label>
                                <input id="priceProduction" name="priceProduction" type="text" class="form-control"
                                    placeholder="Total" readonly autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Persentase Margin</label>
                                <input id="persentaseMargin" name="persentaseMargin" type="number" min="0"
                                    class="form-control" placeholder="input 10 for 10%" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Price Selling </label>
                                <input id="priceSelling" name="priceSelling" type="number" class="form-control"
                                    placeholder="Input Price Selling" readonly autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Persentase Admin</label>
                                <input id="persentaseAdmin" name="persentaseAdmin" type="number" min="0"
                                    class="form-control" placeholder="input 10 for 10%" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Price Admin </label>
                                <input id="priceAdmin" name="priceAdmin" type="text" class="form-control"
                                    placeholder="Input Price Admin" readonly autocomplete="off" required>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Profit</label>
                                <input id="profit" name="profit" type="text" class="form-control" placeholder="Total"
                                    readonly autocomplete="off" required>
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
        var persentaseMargin = parseFloat($('#persentaseMargin').val()) || 0;
        var persentaseAdmin = parseFloat($('#persentaseAdmin').val()) || 0;
        var priceMarketing = parseFloat($('#priceMarketing').val()) || 0;


        // Sum up the values
        var price_production = priceProduct + pricePacking + pricePrinting + pricePress + priceMarketing;
        var priceSelling = price_production * (1 + persentaseMargin / 100);
        var priceAdmin = priceSelling * (persentaseAdmin / 100);
        var profit = priceSelling - price_production - priceAdmin;




        // Set the total to the "Total" field
        $('#priceProduction').val(price_production.toFixed(2));  // Using toFixed(2) to display as a decimal
        $('#priceSelling').val(priceSelling.toFixed(2));  // Using toFixed(2) to display as a decimal
        $('#priceAdmin').val(priceAdmin.toFixed(2));  // Using toFixed(2) to display as a decimal
        $('#profit').val(profit.toFixed(2));  // Using toFixed(2) to display as a decimal

    }

    // Bind change event to recalculate total whenever any price input changes
    $(document).ready(function () {
        $('#priceProduct, #pricePacking, #pricePrinting, #pricePress,#persentaseMargin,#persentaseAdmin,#priceMarketing').on('input', function () {
            calculateTotal();  // Recalculate total on input change
        });

        // Initial calculation (in case values are pre-filled)
        calculateTotal();
    });
</script>