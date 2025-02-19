<div class="card card-raised">
    <div class="card-header  text-white px-4">
        <div class="d-flex justify-content-between align-item-center">
            <div class="me-4">
                <h2 class="card-title text-primary mb-0 ">Stock</h2>
            </div>

        </div>
    </div>
    <div class="card-body p-4">
        <div class="d-flex">
            <button class="btn btn-primary btn-round ms-auto mb-3" data-bs-toggle="modal"
                data-bs-target="#addSellingModal">
                <i class="fa fa-plus"></i>
                Add Stock
            </button>
        </div>

        <div class="table-responsive">
            <table id="table_stock" class="display table table-striped table-hover" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name Product</th>
                        <th>Barcode Product</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $this->load->view('stock_page/view_add_stock') ?>
<script>

    function editStock(id_product, stock) {
        $('#message-warning').val('Apakah anda yakin mengubah stok?');
        $('#stock_pro').val(stock);
        $('#id_product_add_stock').val(id_product);

        

    }
    $(document).ready(function () {
        $('#table_stock').DataTable({
            "processing": true,
            "serverSide": true,
            "pageLength": 10,  // Nilai default yang ditampilkan
            "lengthMenu": [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],  // Menentukan pilihan jumlah baris per halaman
            "ajax": {
                "url": "<?= base_url('stock/getdatatables_stock') ?>",
                "type": "POST",
                "data": function (data) {


                }
            },
            "columnDefs": [
                {
                    "targets": [0],
                    "orderable": false
                },
                {
                    "targets": [0],
                    "className": 'text-center'
                },
            ],
            "layout": {
                topStart: {
                    buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
                }
            }
        });
    });

</script>

<!-- modal edit stock -->
<div class="modal fade" id="ModalEditStock" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="ModalLabel">Edit Stock Product</h5>
                <!-- Tombol Close -->
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('stock/edit_stock') ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <p id="message-warning"></p>
                    <div class="col-sm-12">
                        <div class="form-group form-group-default">
                            <label>Stock</label>
                            <input id="stock_pro" name="stock_pro" type="number" class="form-control"
                                placeholder="Insert Stock" autocomplete="off" value="" style="text-transform: uppercase"
                                required>
                            <input id="id_product_add_stock" name="id_product_add_stock" type="hidden" 
                                 required>


                        </div>
                    </div>
                </div>
                <input type="hidden" id="ModalEditPassword_csrf" name="<?= $this->security->get_csrf_token_name() ?>"
                    value="<?= $this->security->get_csrf_hash() ?>" />
                <div class="modal-footer">
                    <!-- Tombol Close (Footer) -->
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <!-- Tombol Reset -->
                    <button type="submit" class="btn btn-primary col-md-3">Reset</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End modal edit stock -->