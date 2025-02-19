<form id="filterForm">
    <div class="row">
        <div class="form-group col-md-6">
            <label for="dateFrom">From:</label>
            <input type="date" class="form-control" id="dateFrom" name="dateFrom" value="<?= date('Y-m-d') ?>">
        </div>
        <div class="form-group col-md-6">
            <label for="dateThru">Thru:</label>
            <input type="date" class="form-control" id="dateThru" name="dateThru" value="<?= date('Y-m-d') ?>">
        </div>
    </div>
</form>
<div class="card card-raised">
    <div class="card-header  text-white px-4">
        <div class="d-flex justify-content-between align-item-center">
            <div class="me-4">
                <h2 class="card-title text-primary mb-0 ">Spending</h2>
            </div>

        </div>
    </div>
    <div class="card-body p-4">
        <div class="d-flex">
            <button class="btn btn-primary btn-round ms-auto mb-3" data-bs-toggle="modal" data-bs-target="#addSpending">
                <i class="fa fa-plus"></i>
                Add Spending
            </button>
        </div>

        <div class="table-responsive">
            <table id="table_stock" class="display table table-striped table-hover" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name Spending</th>
                        <th>Create Date</th>
                        <th>Description</th>
                        <th>Nominal Spending</th>
                        <th>Action</th>

                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $this->load->view('spending_page/modal_add_spending') ?>
<?php $this->load->view('spending_page/edit_spending') ?>
<script>

    $(document).ready(function () {
        var table=$('#table_stock').DataTable({
            "processing": true,
            "serverSide": true,
            "pageLength": 10,  // Nilai default yang ditampilkan
            "lengthMenu": [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],  // Menentukan pilihan jumlah baris per halaman
            "ajax": {
                "url": "<?= base_url('spending/view_spending') ?>",
                "type": "POST",
                "data": function (data) {
                    data.dateFrom = $('[name="dateFrom"]').val();
                    data.dateThru = $('[name="dateThru"]').val();

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
        // Event listener untuk filter tanggal
        $('[name="dateFrom"], [name="dateThru"]').on('change', function () {
            table.ajax.reload(null, false); // Reload data tanpa reset paging
        });
    });
    function detailUsers(id, username) {
        $('#message-warning').text('Apakah anda yakin akan reset password ' + username + ' ?');
        $('#idUser').val(id);
        $('#newPasswordInput').val('123456');

        $('#idDeletUser').val(id);

    }
    function deleteSpending(id, username) {
        $('#idSpendingDelete').val(id);
        console.log(id);
        $('#message-delete-user').text('Apakah anda yakin akan akan menghapus spending ' + username + ' ?');
    }

    function editSpending(id, name_spending, description, nominal_spending) {
        $('#message-edit-user').text('Anda akan merubah data dari user ' + name_spending + ' ?');
        $('#idSpendingEdit').val(id);
        $('#nameSpendingEdit').val(name_spending);
        $('#descriptionEdit').val(description);
        $('#nominalEdit').val(nominal_spending);

    }

</script>

<!-- delete user -->
<div class="modal fade" id="deleteSpending" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Delete Spending</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('spending/delete_spending') ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <p id="message-delete-user"></p>
                    <div class="row">

                        <input id="idSpendingDelete" name="idSpendingDelete" type="hidden" class="form-control"
                            autocomplete="off" >                            

                    </div>
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>"
                        value="<?= $this->security->get_csrf_hash() ?>">

                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Delete User</button>
                </div>
            </form>

        </div>

    </div>
</div>