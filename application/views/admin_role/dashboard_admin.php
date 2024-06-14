<!-- Modal -->
<?php $this->load->view('admin_role/modal_add_data'); ?>
<!-- end Modal -->

<div class="card shadow mb-4">
    <div class="card-header bg-primary text-white px-4">
        <div class="d-flex justify-content-between align-item-center">
            <div class="me-4">
                <h2 class="card-title text-white mb-0 ">User Status</h2>
                <div class="card-subtitile">Details and history</div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-5 mb-2">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addCustomerModal"
                    id="modal_csrf_token">
                    <i class="bi bi-plus-lg"></i> Add User
                </button>
            </div>
        </div>
        <div class="table-responsive">
            <table id="voucher" class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Area</th>
                        <th>role</th>
                        <th>Account ID</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>


<script src="<?= base_url() ?>public/vendor/jquery/jquery.min.js"></script>




<script type="text/javascript">
    function get_csrf() {
        var xmlHttp = new XMLHttpRequest();
        xmlHttp.open("GET", 'admin/get_csrf', false);
        xmlHttp.send(null);
        return xmlHttp.responseText;
    }
    var table;
    $(document).ready(function () {
        //datatables
        table = $('#voucher').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?= base_url('admin/view_users') ?>",
                "type": "POST",
                "data": function (data) {
                    data.<?= $this->security->get_csrf_token_name() ?> = get_csrf;
                }
            }, "columnDefs": [{
                "targets": [0, 2, 3, 4, 5],
                "orderable": false
            },
            {
                "targets": [0, 1, 2, 3, 4, 5],
                "className": 'text-center'
            }
            ],
        });

        setInterval(function () {
            $('#voucher').DataTable().ajax.reload();

        }, 180000);
    });


    $(document).ready(function () {
        $("#modal_csrf_token").click(function () {
            $.getJSON('admin/get_csrf_json',
                function (res) {
                    if (res.status == "Success") {
                        $('[id="ModaladdCustomerModal_csrf"]').val(res.get_csrf_hash);
                    }
                })
        });
    });


</script>

<script>
    function detailUsers(id, username) {
        $('#message-warning').text('Apakah anda yakin akan reset password ' + username + ' ?');
        $('#customerId').val(id);
        $('#newPasswordInput').val('123456');
        $('[id="ModalEditPassword_csrf"]').val(get_csrf);

    }


</script>

<!-- Modal -->
<div class="modal fade" id="ModalEditPassword" tabindex="-1" role="dialog" aria-labelledby="Modal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">Reset password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('admin/reset_password') ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <p id="message-warning"></p>
                    <input type="hidden" class="form-control" name="customerId" id="customerId" autocomplete="off">
                    <input type="hidden" name="new_password" id="newPasswordInput" style="display: none;"
                        autocomplete="off" required>
                </div>


                <input type="hidden" id="ModalEditPassword_csrf" name="<?= $this->security->get_csrf_token_name() ?>"
                    value="<?= $this->security->get_csrf_hash() ?>" />
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary col-md-3">Reset</button>
                </div>
            </form>
        </div>
    </div>
</div>