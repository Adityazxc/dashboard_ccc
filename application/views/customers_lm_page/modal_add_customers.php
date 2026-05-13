<div class="modal fade" id="add_customers" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Customers</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form action="<?= base_url('customers_fm/add_customers_fm') ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">

                            <?php $users_ccc = json_decode($get_users_ccc, true); ?>
                            <?php $type_cust = json_decode($get_type_cust, true); ?>


                            <div class="col-sm-12">
                                <div class="form-group form-group-default">
                                    <label>PIC BDO</label>
                                    <select class="form-select" id="pic" name="pic" required>
                                        <option value="">-- Pilih PIC --</option>
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
                                <label>Customer Name</label>
                                <input id="cust_name" name="cust_name" type="text" class="form-control"
                                    placeholder="Insert Customer Name" autocomplete="off">
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Grouping Customer</label>
                                <input id="grouping_cust" name="grouping_cust" type="text" class="form-control"
                                    placeholder="Insert Grouping Customer" autocomplete="off">
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>segmentasi</label>
                                <select class="form-select" id="segmentasi" name="segmentasi" required>


                                    <?php foreach ($type_cust as $cust): ?>
                                        <option value="<?= $cust['cust_industry'] ?>">
                                            <?= $cust['cust_industry'] ?>
                                        </option>
                                    <?php endforeach; ?>

                                </select>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Address</label>
                                <input id="address" name="address" type="text" class="form-control"
                                    placeholder="Insert Address" autocomplete="off">
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Source</label>
                                <input id="source" name="source" type="text" class="form-control"
                                    placeholder="Insert Address" autocomplete="off">
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
                                $('#segmentasi').select2({
                                    placeholder: "-- Pilih Segmentasi --",
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

    $(document).ready(function () {

    });

</script>