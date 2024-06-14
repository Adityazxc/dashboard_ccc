<form id="filterForm">
    <div class="form-row">
        <div class="form-group col-md-5">
            <label for="dateFrom">From:</label>
            <input type="date" class="form-control" id="dateFrom" name="dateFrom" value="<?= date('Y-m-d') ?>">
        </div>
        <div class="form-group col-md-5">
            <label for="dateThru">Thru:</label>
            <input type="date" class="form-control" id="dateThru" name="dateThru" value="<?= date('Y-m-d') ?>">
        </div>
    </div>
</form>

<div class="card shadow mb-4">
    <div class="card-header bg-primary text-white px-4">
        <div class="d-flex justify-content-between align-item-center">
            <div class="me-4">
                <h2 class="card-title text-white mb-0 ">User Logs</h2>
                <div class="card-subtitile">Details and history</div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="voucher" class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Role</th>
                        <th>IP Address</th>
                        <th>OS</th>
                        <th>Browser</th>
                        <th>login Time</th>
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
        xmlHttp.open("GET", '<?= base_url('admin/get_csrf') ?>', false);
        xmlHttp.send(null);
        return xmlHttp.responseText;
    }
    var table;
    $(document).ready(function () {
        table = $('#voucher').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?= base_url('admin/view_users_logs') ?>",
                "type": "POST",
                "data": function (d) {
                    d.dateFrom = $('#dateFrom').val();
                    d.dateThru = $('#dateThru').val();
                    d.<?= $this->security->get_csrf_token_name() ?> = get_csrf();
                },
            }, "columnDefs": [{
                "targets": [0, 2, 3, 4, 5, 6],
                "orderable": false
            },
            {
                "targets": [1, 2, 3, 4, 5, 6],
                "className": 'text-center'
            }
            ],
        });

    });

    $('#dateFrom, #dateThru').change(function () {
        table.draw();
    });

</script>