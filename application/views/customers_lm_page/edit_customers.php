<div class="modal fade" id="ModalEditCustomer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Customers</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form action="<?= base_url('customers_lm/edit_customer_lm') ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <p id="message-edit-user"></p>
                    <div class="row">

                        <input id="id_cus_lm_edit" name="id_cus_lm_edit" type="hidden" class="form-control">
                        <?php $users_ccc = json_decode($get_users_ccc, true); ?>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>PIC BDO</label>
                                <select class="form-select" id="pic_bdo_edit" name="pic_bdo_edit" required>
                                    <option value="">-- Pilih PIC BDO --</option>
                                    <?php foreach ($users_ccc as $pic): ?>
                                        <option value="<?= $pic['username']; ?>">
                                            <?= $pic['username']; ?> (<?= $pic['name']; ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <script>
                            $('#ModalEditCustomer').on('shown.bs.modal', function () {
                                $('#pic_bdo_edit').select2({
                                    placeholder: "-- Pilih Origin --",
                                    allowClear: true,
                                    width: '100%',
                                    dropdownParent: $('#ModalEditCustomer')
                                });
                            });


                        </script>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Account Number</label>
                                <input id="account_number_edit" name="account_number_edit" type="text"
                                    class="form-control" autocomplete="off">
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Customer Branch</label>
                                <input id="cust_branch_edit" name="cust_branch_edit" type="text" class="form-control"
                                    autocomplete="off">
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Customer Name</label>
                                <input id="cust_name_edit" name="cust_name_edit" type="text" class="form-control"
                                    autocomplete="off">
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Customer Name 2</label>
                                <input id="cust_name2_edit" name="cust_name2_edit" type="text" class="form-control"
                                    autocomplete="off">
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Payment Metode</label>
                                <select class="form-select" id="payment_metode_edit" name="payment_metode_edit"
                                    required>
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
                                <input id="big_grouping_cust_edit" name="big_grouping_cust_edit" type="text"
                                    class="form-control" autocomplete="off">
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Customer Industry</label>
                                <input id="cust_industry_edit" name="cust_industry_edit" type="text"
                                    class="form-control" autocomplete="off">
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Status Customer</label>
                                <select class="form-select" id="status_customer_edit" name="status_customer_edit"
                                    required>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>                                    
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Cek</label>
                                <input id="cek_edit" name="cek_edit" type="text" class="form-control"
                                    autocomplete="off">
                            </div>
                        </div>

                    </div>

                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>"
                        value="<?= $this->security->get_csrf_hash() ?>">

                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Edit User</button>
                    </div>
                </div>
            </form>

        </div>

    </div>
</div>