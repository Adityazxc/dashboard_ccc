<div class="card card-raised">
    <div class="card-header  text-white px-4">
        <div class="d-flex justify-content-between align-item-center">
            <div class="me-4">
                <h2 class="card-title text-primary mb-0 ">Data Kurir</h2>
            </div>

        </div>
    </div>
    <div class="card-body p-4">
        <div class="d-flex">
            <button class="btn btn-primary btn-round ms-auto mb-3" data-bs-toggle="modal" data-bs-target="#addCourier">
                <i class="fa fa-plus"></i>
                Add Kurir
            </button>
        </div>

        <div class="table-responsive">
            <table id="table_stock" class="display table table-striped table-hover" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <td>No</td>
                        <td>Id Kurir</td>
                        <td>Nama Kurir</td>
                        <td>NIK</td>
                        <td>Tipe Kurir</td>
                        <td>Lokasi</td>
                        <td>Area</td>
                        <td>Zone</td>
                        <td>No Tlp</td>
                        <td>Action</td>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $this->load->view('courier_page/modal_add_courier'); ?>
<?php $this->load->view('courier_page/edit_courier'); ?>
<script>

    $(document).ready(function () {
        $('#table_stock').DataTable({
            "processing": true,
            "serverSide": true,
            "pageLength": 10,  // Nilai default yang ditampilkan
            "lengthMenu": [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],  // Menentukan pilihan jumlah baris per halaman
            "ajax": {
                "url": "<?= base_url('courier/view_courier') ?>",
                "type": "POST",
                "data": function (data) {


                }
            },
            "columnDefs": [
                {
                    "targets": [9],
                    "orderable": false
                },
                {
                    "targets": [0, 9,8],
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
    function deletecourier(id, username) {
        $('#idCourierDelete').val(id);
        $('#message-delete-user').text('Apakah anda yakin akan akan menghapus user ' + username + ' ?');
    }
  
    function editCourier(id,id_courier, courier_name, nik, tipe_courier,location,area,zone,no_tlp) {
        $('#message-edit-user').text('Anda akan merubah data dari kurir ' + courier_name + ' ?');
        $('#idEdit').val(id);
        $('#idCourierEdit').val(id_courier);
        $('#courierNameEdit').val(courier_name);
        $('#nikEdit').val(nik);
        $("#type_courierEdit").val(tipe_courier).trigger("change");        
        $('#locationEdit').val(location);
        $('#areaEdit').val(area);
        $("#zoneEdit").val(zone).trigger("change");
        $('#no_tlpEdit').val(no_tlp);

    }

</script>

<!-- delete user -->
<div class="modal fade" id="deletecourier" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Delete Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">                    
                </button>
            </div>
            <form action="<?= base_url('courier/delete_courier') ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <p id="message-delete-user"></p>
                    <div class="row">
                        <input id="idCourierDelete" name="idCourierDelete" type="hidden" class="form-control"
                            placeholder="Insert Name Product" autocomplete="off" required>                      

                    </div>                   

                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Delete User</button>
                </div>
            </form>

        </div>

    </div>
</div>