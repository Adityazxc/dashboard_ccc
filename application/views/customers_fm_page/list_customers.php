




<div class="card card-raised">
    <div class="card-header  text-white px-4">
        <div class="d-flex justify-content-between align-item-center">
            <div class="me-4">
                <h2 class="card-title text-primary mb-0 ">Customers First Mile</h2>
            </div>

        </div>
    </div>

    <div class="card-body p-4">
        <div class="d-flex">
            <button class="btn btn-primary btn-round ms-auto mb-3" data-bs-toggle="modal"
                data-bs-target="#add_customers">
                <i class="fa fa-plus"></i>
                Add Customers
            </button>
        </div>

        <div class="table-responsive">
            <table id="table_stock" class="display table table-striped table-hover" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>CUST ID</th>
                        <th>CUST NAME</th>
                        <th>GROUPING CUST</th>
                        <th>SEGMENTASI</th>
                        <th>PIC</th>
                        <th>Status</th>
                        <th>Address</th>
                        <th>Source</th>
                        <th>Action</th>

                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

<?php $this->load->view('customers_fm_page/modal_add_customers') ?>
<?php $this->load->view('customers_fm_page/edit_customers') ?>

<script>

    $(document).ready(function () {
        $('#table_stock').DataTable({
            "processing": true,
            "serverSide": true,
            "pageLength": 10,  // Nilai default yang ditampilkan
            "lengthMenu": [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],  // Menentukan pilihan jumlah baris per halaman
            "ajax": {
                "url": "<?= base_url('customers_fm/view_customers') ?>",
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
                    "targets": [0, 4],
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






    function editCustomers(
        id,
       cust_id,
       cust_name,
       grouping_cust,
       segmentasi,
       pic,
       status,
       address,
         source

    ) {
        $('#id_cus_fm_edit').val(id);
        $('#cust_id_edit').val(cust_id);        
        $('#cust_name_edit').val(cust_name);
        $('#grouping_cust_edit').val(grouping_cust);
        $('#segmentasi_edit').val(segmentasi);
        $('#address_edit').val(address);
        $('#source_edit').val(source);
        $('#segmentasi_edit').val(segmentasi).trigger('change');
        $('#pic_bdo_edit').val(pic).trigger('change');
        $('#status_edit').val(status).trigger('change');        
      
    }

    function deactiveCustomer(id, username) {
        $('#message-locked-warning').text('Apakah anda yakin akan Deactive Customer ' + username + '?');
        $('#id_customer_deactive').val(id);
    }
</script>

<!-- Locked user -->
<div class="modal fade" id="deactiveCustomer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Deactive Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form action="<?= base_url('customers_fm/deactive') ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <p id="message-locked-warning"></p>
                    <div class="row">

                        <input id="id_customer_deactive" name="id_customer_deactive" type="hidden" class="form-control"
                            autocomplete="off" >

                    </div>

                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Deactive Customer</button>
                </div>
            </form>

        </div>

    </div>
</div>