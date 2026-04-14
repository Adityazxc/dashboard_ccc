<div class="modal fade" id="add_customers" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Customers</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form action="<?= base_url('customers_lm/add_customers_lm') ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                           
                            <?php $users_ccc = json_decode($get_users_ccc, true); ?>

                            <div class="col-sm-12">
                                <div class="form-group form-group-default">
                                    <label>PIC BDO</label>
                                    <select class="form-select" id="pic" name="pic_bdo" required>
                                        <option value="">-- Pilih PIC BDO --</option>
                                        <?php foreach ($users_ccc as $pic): ?>
                                            <option value="<?= $pic['username']; ?>">
                                                <?= $pic['username']; ?> (
                                                <?= $pic['name']; ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>

                                </div>
                            </div>
                            <div class="form-group form-group-default">
                                <label>Account Number</label>
                                <input id="account_number" name="account_number" type="text" class="form-control"
                                    placeholder="Insert Account Number" autocomplete="off">
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Customer Branch</label>
                                <input id="cust_branch" name="cust_branch" type="text" class="form-control"
                                    placeholder="Insert Customer Branch" autocomplete="off">
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Customer Name</label>
                                <input id="cust_name" name="cust_name" type="text" class="form-control"
                                    placeholder="Insert Customer Name" autocomplete="off">
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Customer Name 2</label>
                                <input id="cust_name2" name="cust_name2" type="text" class="form-control"
                                    placeholder="Insert Customer Name 2" autocomplete="off">
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Payment Metode</label>
                                <select class="form-select" id="payment_metode" name="payment_metode"
                                    aria-label="Default select example" required>
                                    <option value="COD">COD</option>
                                    <option value="COD FEE">COD FEE</option>
                                    <option value="COD RETURN">COD RETURN</option>
                                    <option value="NON COD">NON COD</option>
                                    <option value="NON COD FEE">NON COD FEE</option>
                                    <option value="NON COD RETURN">NON COD RETURN</option>

                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Big Grouping Customer</label>
                                <input id="big_grouping_cust" name="big_grouping_cust" type="text" class="form-control"
                                    placeholder="Insert Big Grouping Customer" autocomplete="off">
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Customer Industry</label>
                                <input id="cust_industry" name="cust_industry" type="text" class="form-control"
                                    placeholder="Insert Customer Industry" autocomplete="off">
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Status Customer</label>
                                <select class="form-select" id="status_customer" name="status_customer"
                                    aria-label="Default select example" required>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                    <option value="0">0</option>
                                </select>
                            </div>
                        </div>


                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Cek</label>
                                <input id="cek" name="cek" type="text" class="form-control" placeholder="Insert Cek"
                                    autocomplete="off">
                            </div>
                        </div>



                        <script>
                            $('#add_customers').on('shown.bs.modal', function () {
                                $('#pic').select2({
                                    placeholder: "-- Pilih Origin --",
                                    allowClear: true,
                                    width: '100%',
                                    dropdownParent: $('#add_customers')
                                });
                            });


                        </script>
                        
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