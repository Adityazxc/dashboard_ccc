<div class="card card-raised">
    <div class="card-header  text-white px-4">
        <div class="d-flex justify-content-between align-item-center">
            <div class="me-4">
                <h2 class="card-title text-primary mb-0 ">Users</h2>
            </div>

        </div>
    </div>

    <div class="card-body p-4">
        <div class="d-flex">
            <button class="btn btn-primary btn-round ms-auto mb-3" data-bs-toggle="modal" data-bs-target="#addUser">
                <i class="fa fa-plus"></i>
                Add Users
            </button>
        </div>

        <div class="table-responsive">
            <table id="table_stock" class="display table table-striped table-hover" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Account Name</th>
                        <th>Username</th>
                        <th>Location</th>
                        <th>No Hp</th>
                        <th>Role</th>
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


<?php $this->load->view('user_page/modal_add_users') ?>
<?php $this->load->view('user_page/edit_user') ?>
<script>

    $(document).ready(function () {
        $('#table_stock').DataTable({
            "processing": true,
            "serverSide": true,
            "pageLength": 10,  // Nilai default yang ditampilkan
            "lengthMenu": [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],  // Menentukan pilihan jumlah baris per halaman
            "ajax": {
                "url": "<?= base_url('users/view_users') ?>",
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

    function deleteUsers(id, username) {
        $('#idUserDelete').val(id);
        $('#message-delete-user').text('Apakah anda yakin akan akan menghapus user ' + username + ' ?');
    }
    function resetPassword(id, username) {
        $('#message-warning').text('Apakah anda yakin akan reset password ' + username + ' ke default ?');
        $('#idUserReset').val(id);
        $('#newPasswordInput').val('123456');
    }
    function editUsers(id, username, location, no_hp, role, name) {
        $('#message-edit-user').text('Anda akan merubah data dari user ' + name + ' ?');
        $('#idUserEdit').val(id);
        $('#accountNameEdit').val(name);
        $('#usernameEdit').val(username);
        $('#noHpEdit').val(no_hp);
        $("#defaultRoleEdit").val(role).trigger("change");
        $("#defaultLocationEdit").val(location).trigger("change");

    }

</script>
<!-- reset password default -->
<div class="modal fade" id="resetPassword" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Reset Default Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">                    
                </button>
            </div>
            <form action="<?= base_url('users/default_password') ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <p id="message-warning"></p>
                    <div class="row">

                        <input id="idUserReset" name="idUserReset" type="hidden" class="form-control"
                            placeholder="Insert Name Product" autocomplete="off" required>
                        <input id="newPasswordInput" name="newPasswordInput" type="hidden" class="form-control"
                            placeholder="Insert Name Product" autocomplete="off" required>

                    </div>
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>"
                        value="<?= $this->security->get_csrf_hash() ?>">

                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Reset</button>
                </div>
            </form>

        </div>

    </div>
</div>
<!-- delete user -->
<div class="modal fade" id="deleteUsers" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Delete Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">                    
                </button>
            </div>
            <form action="<?= base_url('users/delete_users') ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <p id="message-delete-user"></p>
                    <div class="row">

                        <input id="idUserDelete" name="idUserDelete" type="hidden" class="form-control"
                            placeholder="Insert Name Product" autocomplete="off" required>
                        <input id="newPasswordInput" name="newPasswordInput" type="hidden" class="form-control"
                            placeholder="Insert Name Product" autocomplete="off" required>

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