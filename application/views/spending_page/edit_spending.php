<div class="modal fade" id="editSpending" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('spending/edit_spending') ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <p id="message-edit-user"></p>
                    <div class="row">
                        <input id="idSpendingEdit" name="idSpendingEdit" type="hidden" class="form-control"
                            placeholder="Insert Name Product" autocomplete="off" required>
                        <div class="col-sm-12">                            
                            <div class="form-group form-group-default">
                                <label>Spending Name</label>
                                <input id="nameSpendingEdit" name="nameSpendingEdit" type="text" class="form-control"
                                    placeholder="Insert Spending Name" autocomplete="off" required>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Description</label>
                                <input id="descriptionEdit" name="descriptionEdit" type="text" class="form-control"
                                    placeholder="Insert Description" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Nominal </label>
                                <input id="nominalEdit" name="nominalEdit" type="number" class="form-control"
                                    placeholder="Insert Description" autocomplete="off" required>
                            </div>
                        </div>
          
                    </div>
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>"
                        value="<?= $this->security->get_csrf_hash() ?>">

                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Edit Spending</button>
                </div>
            </form>

        </div>

    </div>
</div>