


<div class="card card-raised">
    <div class="card-header  text-white px-4">
        <div class="d-flex justify-content-between align-item-center">
            <div class="me-4">
                <h2 class="card-title text-primary mb-0 ">Customers Last Mile</h2>
            </div>

        </div>
    </div>

    <div class="card-body p-4">
        <div class="d-flex">
            <button class="btn btn-primary btn-round ms-auto mb-3" data-bs-toggle="modal" data-bs-target="#add_customers">
                <i class="fa fa-plus"></i>
                Add Customers
            </button>
        </div>

        <div class="table-responsive">
            <table id="table_stock" class="display table table-striped table-hover" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ACCOUNT NUMBER</th>
                        <th>CUST_BRANCH</th>
                        <th>CUST_NAME</th>
                        <th>CUST_NAME2</th>
                        <th>PAYMENT METODE</th>
                        <th>BIG_GROUPING_CUST</th>
                        <th>Cust Industry</th>
                        <th>Status Customer</th>
                        <th>Cek</th>
                        <th>PIC BDO</th>
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

<?php $this->load->view('customers_lm_page/modal_add_customers')?>
<?php $this->load->view('customers_lm_page/edit_customers')?>

<script>

    $(document).ready(function () {
        $('#table_stock').DataTable({
            "processing": true,
            "serverSide": true,
            "pageLength": 10,  // Nilai default yang ditampilkan
            "lengthMenu": [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],  // Menentukan pilihan jumlah baris per halaman
            "ajax": {
                "url": "<?= base_url('customers_lm/view_customers') ?>",
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
                    "targets": [0, 4, 6],
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
        id_cus_lm,
        account_number,
        cust_branch,
        cust_name,
        cust_name2,
        payment_metode,
        big_grouping_cust,
        cust_industry,
        status_customer,
        cek,
        pic_bdo
    ) {
        $('#id_cus_lm_edit').val(id_cus_lm);
        $('#account_number_edit').val(account_number);
        $('#cust_branch_edit').val(cust_branch);
        $('#cust_name_edit').val(cust_name);
        $('#cust_name2_edit').val(cust_name2);
        $('#payment_metode_edit').val(payment_metode).trigger('change');
        $('#big_grouping_cust_edit').val(big_grouping_cust);
        $('#cust_industry_edit').val(cust_industry);
        $('#status_customer_edit').val(status_customer).trigger('change');
        $('#cek_edit').val(cek);
        $('#pic_bdo_edit').val(pic_bdo).trigger('change');
    }

    function deactiveCustomer(id, username) {
        $('#message-locked-warning').text('Apakah anda yakin akan deactive customer ' + username + '?');
        $('#id_customer_deactive').val(id);
    }
    function activeCustomer(id, username) {
        $('#message-active-warning').text('Apakah anda yakin akan active customer ' + username + '?');
        $('#id_customer_active').val(id);
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
            <form action="<?= base_url('customers_lm/deactive') ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <p id="message-locked-warning"></p>
                    <div class="row">

                        <input id="id_customer_deactive" name="id_customer_deactive" type="hidden" class="form-control"
                            autocomplete="off" required>

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
<!-- Locked user -->
<div class="modal fade" id="activeCustomer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Active Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form action="<?= base_url('customers_lm/active') ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <p id="message-active-warning"></p>
                    <div class="row">

                        <input id="id_customer_active" name="id_customer_active" type="hidden" class="form-control"
                            autocomplete="off" required>

                    </div>


                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Active Customer</button>
                </div>
            </form>

        </div>

    </div>
</div>

