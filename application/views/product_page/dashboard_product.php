<div class="card card-raised">
    <div class="card-header text-white px-4">
        <div class="d-flex justify-content-between align-item-center">
            <div class="me-4">
                <h2 class="card-title text-primary mb-0 ">Product</h2>
            </div>

        </div>
    </div>
    <div class="card-body p-4">
        <div class="d-flex">
            <button class="btn btn-primary btn-round ms-auto mb-3" data-bs-toggle="modal" data-bs-target="#addRowModal">
                <i class="fa fa-plus"></i>
                Add Product
            </button>
        </div>
<?php echo $role;?>
        <div class="table-responsive">
            <table id="table_product" class="display table table-striped table-hover" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Img</th>
                        <th>Name Product</th>
                        <th>Barcode Product</th>
                        <th>Size Printing</th>
                        <th>Profit</th>
                        <th>Price Admin</th>
                        <th>Category</th>
                        <th>Price Selling</th>
                        <th>Price Product</th>
                        <th>Price Packing</th>
                        <th>Price Printing</th>
                        <th>Price Press</th>
                        <th>Price Marketing</th>
                        <th>Total Production</th>
                        <?php if($role == "Admin" || $role = "Upper") { ?>
                            <th>Action</th>
                        <?php }; ?>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Preview Gambar -->
<div class="modal fade" id="productImageModal" tabindex="-1" aria-labelledby="productImageModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-body p-0 text-center position-relative">
                <!-- Tombol Close di Atas Gambar -->
                <button type="button" class="btn-close position-absolute top-0 end-0 m-3 bg-light p-2 rounded-circle"
                    data-bs-dismiss="modal" aria-label="Close" style="z-index: 1055;"></button>

                <!-- Carousel -->
                <div id="carouselProductImages" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner" id="carouselInnerImages"></div>

                    <!-- Tombol Navigasi -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselProductImages"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon bg-dark p-3 rounded-circle" aria-hidden="true"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselProductImages"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon bg-dark p-3 rounded-circle" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Modal Preview Gambar -->

<script>
    function showProductImages(id_product) {
        $.ajax({
            url: "<?= base_url('product/get_product_images'); ?>",
            type: "POST",
            data: { id_product: id_product },
            dataType: "json",
            success: function (response) {
                if (response.length > 0) {
                    let carouselInner = document.getElementById("carouselInnerImages");
                    let prevBtn = document.querySelector(".carousel-control-prev");
                    let nextBtn = document.querySelector(".carousel-control-next");

                    carouselInner.innerHTML = ""; // Hapus gambar sebelumnya

                    response.forEach((image, index) => {
                        let activeClass = index === 0 ? "active" : "";
                        carouselInner.innerHTML += `
                        <div class="carousel-item ${activeClass}">
                            <img src="<?= base_url('uploads/products/'); ?>${image.foto}" class="d-block w-100" style="max-height: 90vh; object-fit: contain;">
                        </div>`;
                    });

                    // Tampilkan tombol navigasi hanya jika ada lebih dari 1 gambar
                    if (response.length > 1) {
                        prevBtn.style.display = "block";
                        nextBtn.style.display = "block";
                    } else {
                        prevBtn.style.display = "none";
                        nextBtn.style.display = "none";
                    }

                    // Tampilkan modal
                    $("#productImageModal").modal("show");
                } else {
                    alert("No images found!");
                }
            }
        });
    }
</script>

<!-- Modal -->
<div class="modal fade" id="ModalRemoveProduct" tabindex="-1" role="dialog" aria-labelledby="ModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="ModalLabel">Hapus Product</h5>
                <!-- Tombol Close -->
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('product/delete_product') ?>" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <p id="message-warning"></p>
                    <input type="hidden" class="form-control" name="idProductDelete" id="idProductDelete"
                        autocomplete="off">
                    <input type="hidden" name="new_password" id="newPasswordInput" style="display: none;"
                        autocomplete="off" required>
                </div>
                <input type="hidden" id="ModalEditPassword_csrf" name="<?= $this->security->get_csrf_token_name() ?>"
                    value="<?= $this->security->get_csrf_hash() ?>" />
                <div class="modal-footer">
                    <!-- Tombol Close (Footer) -->
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <!-- Tombol Reset -->
                    <button type="submit" class="btn btn-primary col-md-3">Reset</button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php $this->load->view('product_page/view_edit_product') ?>


<script type="text/javascript">
    function editProduct(id_product, name_product, barcode_product, size_printing, profit, admin, selling_price, category, price_shirt, price_packing, price_printing, price_press, persentase_Admin, persentase_margin, price_production, price_marketing) {
        $('#message-warning-edit').text('Apakah anda yakin akan ,mengubah product ' + name_product + ' ?');
        $('#idProductEdit').val(id_product);
        $('#nameProductEdit').val(name_product);
        $('#barcodeProductEdit').val(barcode_product);
        $('#sizePrintingEdit').val(size_printing);
        $('#priceProductEdit').val(price_shirt);
        $('#pricePackingEdit').val(price_packing);
        $('#pricePrintingEdit').val(price_printing);
        $('#pricePressEdit').val(price_press);

        $('#profitEdit').val(profit);
        $('#priceAdminEdit').val(admin);
        $('#priceSellingEdit').val(selling_price);
        $('#categoryProductEdit').val(category);
        $('#priceProductionEdit').val(price_production);
        $('#priceMarketingEdit').val(price_marketing);
        $('#persentaseAdminEdit').val(persentase_Admin);
        $('#persentaseMarginEdit').val(persentase_margin);


    }
    function removeProduct(id_product, name_product) {
        $('#message-warning').text('Apakah anda yakin akan hapus product ' + name_product + ' ?');
        $('[id="ModalRemoveProduct"]').val(get_csrf());
        $('#idProductDelete').val(id_product);
    }
    function get_csrf() {
        var xmlHttp = new XMLHttpRequest();
        xmlHttp.open("GET", '<?= base_url('product/get_csrf') ?>', false);
        xmlHttp.send(null);
        return xmlHttp.responseText;
    }


    $(document).ready(function () {
        $('#table_product').DataTable({
            "processing": true,
            "serverSide": true,
            "pageLength": 10,  // Nilai default yang ditampilkan
            "lengthMenu": [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],  // Menentukan pilihan jumlah baris per halaman
            "ajax": {
                "url": "<?= base_url('product/getdatatables_product') ?>",
                "type": "POST",
                "data": function (data) {
                    // Mengirimkan CSRF token
                    data.<?= $this->security->get_csrf_token_name() ?> = get_csrf();
                }
            },
            "columnDefs": [
                {
                    "targets": [0, 9],
                    "orderable": false
                },
                {
                    "targets": [0, 9],
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



</script>


<?php $this->load->view('product_page/view_add_data.php') ?>
</div>