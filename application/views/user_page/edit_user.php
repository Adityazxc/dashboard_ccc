<div class="modal fade" id="editusers" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('users/edit_users') ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <p id="message-edit-user"></p>
                    <div class="row">
                        <input id="idUserEdit" name="idUserEdit" type="hidden" class="form-control"
                            placeholder="Insert Name Product" autocomplete="off" required>
                        <div class="col-sm-12">
                            <input id="idStockProduct" name="idStockProduct" type="hidden" class="form-control"
                                placeholder="Insert Barcode Product" autocomplete="off" required>
                            <div class="form-group form-group-default">
                                <label>Account Name</label>
                                <input id="accountNameEdit" name="accountNameEdit" type="text" class="form-control"
                                    placeholder="Insert Barcode Product" autocomplete="off" required>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Username</label>
                                <input id="usernameEdit" name="usernameEdit" type="text" class="form-control"
                                    placeholder="Insert Name Product" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <label>Role</label>
                            <select class="form-control" id="defaultRoleEdit" name="defaultRoleEdit" required>
                                <option value="Upper">Upper</option>
                                <option value="Admin">Admin</option>
                                <option value="Finance">Finance</option>
                                <option value="Production">Production</option>
                                <option value="Marketing">Marketing</option>
                            </select>

                        </div>
                    </div>
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>"
                        value="<?= $this->security->get_csrf_hash() ?>">

                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Edit User</button>
                </div>
            </form>

        </div>

    </div>
</div>