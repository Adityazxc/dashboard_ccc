
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>




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
                        <td>Photo</td>
                        <td>Id Kurir</td>
                        <td>Nama Kurir</td>
                        <td>NIK</td>
                        <td>Tipe Kurir</td>
                        <td>Lokasi</td>
                        <td>Area</td>
                        <td>Zone</td>
                        <td>No Tlp</td>
                        <td>Zona Kerja</td>
                        <td>Action</td>
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

<div class="modal fade" id="courierImageModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Foto Kurir</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
        <img id="courierImagePreview" src="" class="img-fluid rounded">
      </div>
    </div>
  </div>
</div>
<script>
function showCourierImage(src) {
    const img = document.getElementById('courierImagePreview');
    const defaultImg = "<?= base_url('uploads/image_courier/courier.png') ?>";

    img.onerror = function () {
        this.onerror = null; // cegah infinite loop
        this.src = defaultImg;
    };

    img.src = src;

    const modal = new bootstrap.Modal(document.getElementById('courierImageModal'));
    modal.show();
}
</script>


<style>
    .courier-img {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    object-fit: cover;
    cursor: pointer;
    border: 2px solid #ddd;
    transition: 0.2s;
}

.courier-img:hover {
    transform: scale(1.1);
    border-color: #0d6efd;
}

</style>
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
    function deletecourier(id, username,courier_id) {
        $('#idCourierDelete').val(id);
        $('#courier_id_delete').val(courier_id);        
        $('#message-delete-user').text('Apakah anda yakin akan akan menghapus user ' + username + ' ?');
    }
  
    function editCourier(id,id_courier,work_zone, courier_name, nik, tipe_courier,location,area,zone,no_tlp) {
        $('#message-edit-user').text('Anda akan merubah data dari kurir ' + courier_name + ' ?');
        $('#idEdit').val(id);
        
        $("#work_zone_edit").val(work_zone).trigger("change");        
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
                        <input id="courier_id_delete" name="courier_id_delete" type="hidden" class="form-control"
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

