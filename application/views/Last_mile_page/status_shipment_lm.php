<?php $this->load->view('form_page/filter_form_lm')?>



<div class="card card-raised">
    <div class="card-header text-white px-4">
        <div class="d-flex justify-content-between align-item-center">
            <div class="me-4">
                <h2 class="card-title text-primary mb-0 ">Status Shipment Last Mile</h2>
            </div>

        </div>
    </div>
    
    <div class="card-body p-4">        
        <div class="table-responsive">
            <table id="table_coorporate" class="display table table-striped table-hover" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Account_number </th>
                        <th>Customer Name</th>                                                
                        <th>Delivered</th>
                        <th>Un Inbound</th>
                        <th>Un Runsheet</th>
                        <th>Open POD</th>
                        <th>Undel</th>
                        <th>Customer Request</th>
                        <th>Irregularity</th>
                        <th>Return</th>
                        <th>UN Receiving</th>
                        <th>UN Manifest</th>
                        <th>Auto Close IRREG</th>
                        <th>Auto Close System</th>
                        <th>Claim</th>
                        <th>Grand Total</th>

                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>




<script type="text/javascript">

   

    $(document).ready(function () {

        var role = "<?= $role ?>";

        var table = $('#table_coorporate').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 10,
            lengthMenu: [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],

            ajax: {
                url: "<?= base_url('last_mile/corp_stat_shp') ?>",
                type: "POST",
                data: function (d) {

                    // ambil semua input dari form filter
                    var formData = $('#filterForm').serializeArray();

                    formData.forEach(function (item) {
                        d[item.name] = item.value;
                    });

                    // kalau mau kirim role juga
                    d.role = role;
                }
            },

            columnDefs: [
                {
                    targets: [0],
                    orderable: false,
                    className: 'text-center'
                }
            ],

            dom: 'Bfrtip',
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
        });

        // Reload hanya saat submit
        $('#filterForm').on('submit', function (e) {
            e.preventDefault();
            table.ajax.reload(null, false);
        });

    });




</script>