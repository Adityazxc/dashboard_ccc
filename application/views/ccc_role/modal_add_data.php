<div class="modal fade" id="addCustomerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Customer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-horizontal" id='addCustomer' action="<?php echo base_url('ccc/add_data'); ?>" method="post"
                enctype="multipart/form-data" role="form">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="customerName">Nama Pengirim:</label>
                        <input type="text" class="form-control" id="customerName"
                            oninput="this.value = this.value.toUpperCase()" name="customerName" autocomplete="off" required>
                    </div>
                    <div class="form-group">
                        <label for="awb_no">No Awb:</label>
                        <input type="text" maxlength="16" class="form-control" pattern="[A-Za-z0-9-]{16}" id="awb_no"
                            title="Please enter a 16-character alphanumeric code" name="awb_no" autocomplete="off" required>

                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" autocomplete="off" required>
                    </div>
                    <div class="form-group">
                        <label for="no_tlp">No Telepon</label>
                        <input type="number" class="form-control" id="no_tlp" name="no_tlp" autocomplete="off" required>
                    </div>
                    <div class="form-group">
                        <label for="ongkir">Harga Ongkir</label>
                        <input type="number" class="form-control" id="ongkir" name="ongkir" autocomplete="off" required>
                    </div>
                    <div class="form-group">
                        <label for="service">Service</label>
                        <select class="form-control" id="service" name="service" autocomplete="off" required>
                            <option value="CTC">CTC</option>
                            <option value="CTC YES">CTC YES</option>
                        </select>
                    </div>
                    
                    <input type="hidden" id="ModaladdCustomerModal_csrf"
                        name="<?= $this->security->get_csrf_token_name() ?>"
                        value="<?= $this->security->get_csrf_hash() ?>" />

                    <!-- Add more form fields as needed -->
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i>
                        Add Customer
                    </button>
            </form>
        </div>
    </div>
</div>
</div>


