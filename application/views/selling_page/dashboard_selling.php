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
    <div class="card-header text-white px-4">
        <div class="d-flex justify-content-between align-item-center">
            <div class="me-4">
                <h2 class="card-title text-primary mb-0 ">Selling</h2>
            </div>

        </div>
    </div>
    <div class="card-body p-4">
        <div class="d-flex">
            <button class="btn btn-primary btn-round ms-auto mb-3" data-bs-toggle="modal"
                data-bs-target="#addSellingModal">
                <i class="fa fa-plus"></i>
                Add Selling
            </button>
        </div>

        <div class="table-responsive">
            <table id="table_selling" class="display table table-striped table-hover" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Date Selling</th>
                        <th>Name Product</th>
                        <th>Barcode Product</th>
                        <th>Amount</th>
                        <th>Price</th>
                        <th>Sub Total</th>
                        <th>Source</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $this->load->view('selling_page/view_add_selling') ?>
<script>
   $(document).ready(function () {
    // Inisialisasi DataTable
    var table = $('#table_selling').DataTable({
        "processing": true,
        "serverSide": true,
        "pageLength": 10, // Nilai default yang ditampilkan
        "lengthMenu": [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]], // Pilihan jumlah baris per halaman
        "ajax": {
            "url": "<?= base_url('selling/getdatatables_selling') ?>",
            "type": "POST",
            "data": function (data) {
                data.dateFrom = $('[name="dateFrom"]').val();
                data.dateThru = $('[name="dateThru"]').val();
            }
        },
        "columnDefs": [
            {
                "targets": [0],
                "orderable": false, // Non-aktifkan pengurutan pada kolom pertama
                "className": 'text-center' // Tambahkan class untuk align text
            }
        ],
        "dom": 'Bfrtip', // Menambahkan opsi button
        "buttons": ['copy', 'csv', 'excel', 'pdf', 'print']
    });

    // Event listener untuk filter tanggal
    $('[name="dateFrom"], [name="dateThru"]').on('change', function () {
        table.ajax.reload(null, false); // Reload data tanpa reset paging
    });
});


</script>