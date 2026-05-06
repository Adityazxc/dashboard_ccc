<div class="modal fade" id="ModalEditCustomer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Customers</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form action="<?= base_url('customers_fm/edit_customer_fm') ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <p id="message-edit-user"></p>
                    <div class="row">

                        <input id="id_cus_fm_edit" name="id_cus_fm_edit" type="hidden" class="form-control">
                        <?php $users_ccc = json_decode($get_users_ccc, true); ?>
                        <?php $type_cust = json_decode($get_type_cust, true); ?>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>PIC</label>
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
                                $('#segmentasi_edit').select2({
                                    placeholder: "-- Pilih Origin --",
                                    allowClear: true,
                                    width: '100%',
                                    dropdownParent: $('#ModalEditCustomer')
                                });
                            });


                        </script>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Customer ID</label>
                                <input id="cust_id_edit" name="cust_id_edit" type="text" class="form-control"
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
                                <label>Grouping Customers </label>
                                <input id="grouping_cust_edit" name="grouping_cust_edit" type="text"
                                    class="form-control" autocomplete="off">
                            </div>
                        </div>

                      
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>segmentasi</label>
                                <select class="form-select" id="segmentasi_edit" name="segmentasi_edit" required>


                                    <?php foreach ($type_cust as $cust): ?>
                                        <option value="<?= $cust['segmentasi'] ?>">
                                            <?= $cust['segmentasi'] ?>
                                        </option>
                                    <?php endforeach; ?>

                                </select>
                            </div>
                        </div>


                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Status</label>
                                <select class="form-select" id="status_edit" name="status_edit" required>
                                    <option value="Active">Active</option>
                                    <option value="Deactive">Deactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Address</label>
                                <input id="address_edit" name="address_edit" type="text" class="form-control"
                                    autocomplete="off">
                            </div>
                        </div>
                      
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Source</label>
                                <input id="source_edit" name="source_edit" type="text" class="form-control"
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