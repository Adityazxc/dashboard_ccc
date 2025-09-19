<div class="modal fade" id="ModalEditCourier" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Courier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">                    
                </button>
            </div>
            <form action="<?= base_url('courier/edit_courier') ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <p id="message-edit-user"></p>
                    <div class="row">
                        <div class="col-sm-12">
                            <input id="idEdit" name="idEdit" type="hidden" class="form-control"
                                placeholder="Insert Barcode Product" autocomplete="off" required>
                            <div class="form-group form-group-default">
                                <label>Nama Kurir</label>
                                <input id="courierNameEdit" name="courierNameEdit" type="text" class="form-control"
                                    placeholder="Masukkan Nama Kurir" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>ID Courier</label>
                                <input id="idCourierEdit" name="idCourierEdit" type="text" class="form-control"
                                    placeholder="Masukkan Id Courier" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>NIK</label>
                                <input id="nikEdit" name="nikEdit" type="text" class="form-control" placeholder="Masukkan NIK"
                                    autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Tipe Kurir</label>
                                <select class="form-select" id="type_courierEdit" name="type_courierEdit"
                                    aria-label="Default select example" required>
                                    <option value="RIDER">RIDER</option>
                                    <option value="DRIVER">DRIVER</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Lokasi</label>
                                <input id="locationEdit" name="locationEdit" type="text" class="form-control"
                                    placeholder="Masukkan Location" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Area</label>
                                <input id="areaEdit" name="areaEdit" type="text" class="form-control"
                                    placeholder="Masukkan Area" autocomplete="off" required>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Zone</label>
                                <select class="form-select" id="zoneEdit" name="zoneEdit" aria-label="Default select example"
                                    required>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="C">C</option>
                                    <option value="C1">C1</option>
                                    <option value="C2">C2</option>
                                    <option value="C3">C3</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>No Hp (diawali dengan 62)</label>
                                <input id="no_tlpEdit" name="no_tlpEdit" type="number" class="form-control"
                                    placeholder="Masukkan no hp" autocomplete="off" required>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>"
                        value="<?= $this->security->get_csrf_hash() ?>">

                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Edit Kurir</button>
                </div>
            </form>

        </div>

    </div>
</div>


<script>
    document.getElementById('idCourierEdit').addEventListener('input', function () {
        this.value = this.value.toUpperCase();
    });
    document.getElementById('courierNameEdit').addEventListener('input', function () {
        this.value = this.value.toUpperCase();
    });
    document.getElementById('nikEdit').addEventListener('input', function () {
        this.value = this.value.toUpperCase();
    });
    document.getElementById('locationEdit').addEventListener('input', function () {
        this.value = this.value.toUpperCase();
    });
    document.getElementById('areaEdit').addEventListener('input', function () {
        this.value = this.value.toUpperCase();
    });
    document.getElementById('no_tlpEdit').addEventListener('input', function () {
        this.value = this.value.toUpperCase();
    });   
</script>