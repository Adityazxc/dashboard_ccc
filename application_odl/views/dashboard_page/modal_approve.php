<script>

    function detailImage(
        urlPod, runsheetDate, noRunsheet, awb, statusCod, linkMaps,
        idCourier, courierName, noTlp, destinationCode, zone,
        receivedDate, receiverName, receiverAddress, paymentType,
        amount, statusVia, podStatus, bigGroupingCust,
    ) {
        var text = "Mohon untuk memperbaiki foto validasi POD untuk paket dengan nomor AWB *" + awb + "* atas nama *" + receiverName + "* di alamat *" + receiverAddress + "* segera.";
        const BASE_URL = "<?= base_url() ?>"; // Inject dari PHP
        const DEFAULT_IMAGE = BASE_URL + "public/img/Image-not-found.png";

        if (!urlPod || urlPod.trim() === "") {
            finalUrlPod = DEFAULT_IMAGE;
        } else if (urlPod.startsWith("http://") || urlPod.startsWith("https://")) {
            finalUrlPod = urlPod; // <- Gunakan langsung
        } else {
            finalUrlPod = BASE_URL + urlPod; // <- Tambahkan base_url hanya untuk path lokal
        }

        let content = `
        <div class="modal-body">
        <div class="container mt-4">
        <div class="row">
            <div class="col-md-6 text-center">            
               <img src="${finalUrlPod}" class="img-thumbnail image-box" 
     onerror="this.onerror=null; this.src='${DEFAULT_IMAGE}'">
            </div>


            <div class="col-md-6">
                <div class="row">
                    <!-- AWB Baris 1 -->
                    <div class="col-lg-12 border-bottom">
                            <p class="sub-header mb-0 mt-2"><strong>AWB</strong></p>
                            <p class="text-left">${awb}</p>
                            <p class="text-left">${urlPod}</p>
                        </div>

                        <!-- AWB Baris 2 -->
                        <div class="col-lg-12 border-bottom">
                            <p class="sub-header mb-0 mt-2"><strong>No Runsheet</strong></p>
                            <p class="text-left no_runsheet">${noRunsheet}</p>
                        </div>

                        <!-- AWB Baris 3 -->
                        <div class="col-lg-12 border-bottom">
                            <p class="sub-header mb-0 mt-2"><strong>Status Code</strong></p>
                            <p class="text-left status_cod">${statusCod}</p>
                        </div>

                        <div class="col-lg-12 border-bottom">
                            <p class="sub-header mb-0 mt-2"><strong>Lokasi</strong></p>
                            <a href="${linkMaps}" id="mapLink" target="_blank">
                                <i class="bi bi-geo-alt">
                                    Buka di google maps
                                </i>
                            </a>

                    </div>
                </div>                 
            </div>
        </div>

        <p class="h3 text-center mt-4">Detail Runsheet</p>
            <div class="row task-dates mb-0 mt-2">
                <div class="col-lg-6 border-bottom">
                    <p class="sub-header mb-0 mt-2"><strong>Tanggal Runsheet</strong></p>
                    <p class="text-left ">${noRunsheet}</p>
                </div>
                <div class="col-lg-6 border-bottom">
                    <p class="sub-header mb-0 mt-2"><strong>ID Kurir</strong></p>
                    <p class="text-left">${idCourier}</p>
                </div>
                <div class="col-lg-6 border-bottom">
                    <p class="sub-header mb-0 mt-2"><strong>Nama Kurir</strong></p>
                    <p class="text-left ">${courierName}</p>
                </div>
                <div class="col-lg-6 border-bottom">
                    <p class="sub-header mb-0 mt-2"><strong>Kontak Kurir</strong></p>
                    <div class="col-sm">
                        <a href="https://wa.me/${noTlp}?text=${encodeURIComponent(text)}" id="no_wa" target="_blank">

                            <i class="bi bi-whatsapp">
                                <text class="text-left ">${noTlp}</text>
                            </i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-6 border-bottom">
                    <p class="sub-header mb-0 mt-2"><strong>Destinasi</strong></p>
                    <p class="text-left ">${destinationCode}</p>
                </div>
                <div class="col-lg-6 border-bottom">
                    <p class="sub-header mb-0 mt-2"><strong>Zona</strong></p>
                    <p class="text-left ">${zone}</p>
                </div>
                <div class="col-lg-6 border-bottom">
                    <p class="sub-header mb-0 mt-2"><strong>Alamat Penerima</strong></p>
                    <p class="text-left ">${receiverAddress}</p>
                </div>
                <div class="col-lg-6 border-bottom">
                    <p class="sub-header mb-0 mt-2"><strong>Status Via</strong></p>
                    <p class="text-left ">${statusVia}</p>
                </div>
                <div class="col-lg-6 border-bottom">
                    <p class="sub-header mb-0 mt-2"><strong>Tanggal Diterima</strong></p>
                    <p class="text-left ">${receivedDate}</p>
                </div>


                <div class="col-lg-6 border-bottom">
                    <p class="sub-header mb-0 mt-2"><strong>Nama Penerima</strong></p>
                    <p class="text-left ">${receiverName}</p>
                </div>

                <div class="col-lg-6 border-bottom">
                    <p class="sub-header mb-0 mt-2"><strong>Type Payment</strong></p>
                    <p class="text-left ">${paymentType}</p>
                </div>

                <div class="col-lg-6 border-bottom">
                    <p class="sub-header mb-0 mt-2"><strong>Amount</strong></p>
                    <p class="text-left ">${amount}</p>
                </div>
                <div class="col-lg-6 border-bottom">
                    <p class="sub-header mb-0 mt-2"><strong>Big Grouping Customer</strong></p>
                    <p class="text-left ">${bigGroupingCust}</p>
                </div>
            </div>
        </div>    
        <div class="modal-footer">
                    
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
       
    </div>
            `;
        document.getElementById('modalContentContainer').innerHTML = content;
    }

    function detailImage_non_approves(urlPod, awb, receiverAddress, receiverName, linkMaps, noTlp,runsheet_date,id_courier,no_runsheet) {
        const BASE_URL = "<?= base_url() ?>"; // Inject dari PHP
        const DEFAULT_IMAGE = BASE_URL + "public/img/Image-not-found.png";

        if (!urlPod || urlPod.trim() === "") {
            finalUrlPod = DEFAULT_IMAGE;
        } else if (urlPod.startsWith("http://") || urlPod.startsWith("https://")) {
            finalUrlPod = urlPod; // <- Gunakan langsung
        } else {
            finalUrlPod = BASE_URL + urlPod; // <- Tambahkan base_url hanya untuk path lokal
        }


        var text = "Mohon untuk memperbaiki foto validasi POD untuk paket dengan nomor AWB *" + awb + "* atas nama *" + receiverName + "* di alamat *" + receiverAddress + "* segera.";
        let content = `
    <form action="<?= base_url('upload/revision') ?>" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="awb" id="awb" value=${awb} >
     <input type="hidden" name="runsheet_date_revision" id="runsheet_date_revision" value=${runsheet_date} >
    <input type="hidden" name="id_courier_revision" id="id_courier_revision" value=${id_courier} >
    <input type="hidden" name="no_runsheet_revision" id="no_runsheet_revision" value=${no_runsheet} >

    <div class="modal-body">
        <div class="container mt-4">
        <div class="row">
            <div class="col-md-6">            
            <div class="col-lg-12 border-bottom"><p class="sub-header mb-0 mt-2"><strong>AWB</strong></p><p>${awb}</p></div>
            <div class="col-lg-12 border-bottom"><p class="sub-header mb-0 mt-2"><strong>Nama Penerima</strong></p><p>${receiverName}</p></div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-lg-12 border-bottom"><p class="sub-header mb-0 mt-2"><strong>Alamat Penerima</strong></p><p>${receiverAddress}</p></div>
                    <div class="col-lg-12"><p class="sub-header mb-0 mt-2"><strong>Alasan Revisi</strong></p>                
                        <select class="form-select" id="reason" name="reason" aria-label="Default select example" required>                            
                            <option value="Error SCA">Error SCA</option>
                            <option value="Kelalaian Kurir">Kelalaian Kurir</option>                        
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="col-lg-12 border-bottom"><p class="sub-header mb-0 mt-2"><strong>Lokasi</strong></p>
                <a href="${linkMaps}" id="mapLink" target="_blank">
                                <i class="bi bi-geo-alt">
                                    Buka di google maps
                                </i>
                            </a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="col-lg-12 border-bottom"><p class="sub-header mb-0 mt-2"><strong>No Telp</strong></p>
                 <a href="https://wa.me/${noTlp}?text=${encodeURIComponent(text)}" id="no_wa" target="_blank">
                  <i class="bi bi-whatsapp">
                                <text class="text-left ">${noTlp}</text>
                            </i>
                            </a>
                </div>
            </div>
        </div>

        <div class="row mt-4">
        
            <div class="col-md-6">
                <div class="col-lg-12 "><p class="sub-header mb-0 mt-2"><strong>Foto POD Invalid</strong></p>
                    <img src="${finalUrlPod}" class="img-thumbnail image-box" 
     onerror="this.onerror=null; this.src='${DEFAULT_IMAGE}'">

                </div>
            </div>

            <div class="col-md-6">
             <div class="col-lg-12">
        <p class="sub-header mb-0 mt-2"><strong>Unggahan Foto Perbaikan</strong></p>
        <i style="color:red;">format yang di dukung (jpg,jpeg dan png)</i>
        <div id="drop-area" class="upload-area">
            <p>Drag & Drop gambar di sini atau klik tombol di bawah</p>            
            <input type="file" name="revision_img" id="fileElem" accept=".jpg,.jpeg,.png" hidden required>
            <button class="btn btn-primary mt-2" type="button" onclick="document.getElementById('fileElem').click()">Pilih Gambar</button>
        </div>
        <div id="preview" class="mt-4 d-flex flex-wrap"></div>
    </div>
        </div>
    </div>
    <div class="modal-footer">
                    
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Submit</button>
    </div>
    </form>
    `;

        document.getElementById('modalContentContainer').innerHTML = content;

        const dropArea = document.getElementById('drop-area');
        const fileInput = document.getElementById('fileElem');
        const preview = document.getElementById('preview');

        // Hanya 1 gambar yang boleh ditampilkan
        fileInput.addEventListener('change', (e) => {
            handleSingleFile(e.target.files[0]);
        });

        dropArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropArea.classList.add('dragover');
        });

        dropArea.addEventListener('dragleave', () => {
            dropArea.classList.remove('dragover');
        });

        dropArea.addEventListener('drop', (e) => {
            e.preventDefault();
            dropArea.classList.remove('dragover');
            if (e.dataTransfer.files && e.dataTransfer.files[0]) {
                handleSingleFile(e.dataTransfer.files[0]);
            }
        });

        function handleSingleFile(file) {
            if (!file || !file.type.startsWith('image/')) return;

            const reader = new FileReader();
            reader.onload = (e) => {
                // Clear preview sebelum menambahkan gambar baru
                preview.innerHTML = '';

                const img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('me-2');
                img.style.maxWidth = '150px';
                img.style.borderRadius = '5px';

                preview.appendChild(img);
            };
            reader.readAsDataURL(file);
        }
    }

    function modalRevision(urlPod, urlRevision, awb, receiverAddress, receiverName, linkMaps, noTlp, reasonRevision) {
        const BASE_URL = "<?= base_url() ?>"; // Inject dari PHP
        const DEFAULT_IMAGE = BASE_URL + "public/img/Image-not-found.png";

        if (!urlPod || urlPod.trim() === "") {
            finalUrlPod = DEFAULT_IMAGE;
        } else if (urlPod.startsWith("http://") || urlPod.startsWith("https://")) {
            finalUrlPod = urlPod; // <- Gunakan langsung
        } else {
            finalUrlPod = BASE_URL + urlPod; // <- Tambahkan base_url hanya untuk path lokal
        }

        var text = "Mohon untuk memperbaiki foto validasi POD untuk paket dengan nomor AWB *" + awb + "* atas nama *" + receiverName + "* di alamat *" + receiverAddress + "* segera.";
        let content = `
    <form action="<?= base_url('upload/revision') ?>" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="awb" id="awb" value=${awb} >
   

    <div class="modal-body">
        <div class="container mt-4">
        <div class="row">
            <div class="col-md-6">            
            <div class="col-lg-12 border-bottom"><p class="sub-header mb-0 mt-2"><strong>AWB</strong></p><p>${awb}</p></div>
            <div class="col-lg-12 border-bottom"><p class="sub-header mb-0 mt-2"><strong>Nama Penerima</strong></p><p>${receiverName}</p></div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-lg-12 border-bottom"><p class="sub-header mb-0 mt-2"><strong>Alamat Penerima</strong></p><p>${receiverAddress}</p></div>
                    <div class="col-lg-12 border-bottom"><p class="sub-header mb-0 mt-2"><strong>Alasan Revisi</strong></p><p>${reasonRevision}</p></div>
                    
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="col-lg-12 border-bottom"><p class="sub-header mb-0 mt-2"><strong>Lokasi</strong></p>
               <a href="${linkMaps}" id="mapLink" target="_blank">
                                <i class="bi bi-geo-alt">
                                    Buka di google maps
                                </i>
                            </a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="col-lg-12 border-bottom"><p class="sub-header mb-0 mt-2"><strong>No Telp</strong></p>
                 <a href="https://wa.me/${noTlp}?text=${encodeURIComponent(text)}" id="no_wa" target="_blank">
                  <i class="bi bi-whatsapp">
                                <text class="text-left ">${noTlp}</text>
                            </i>
                            </a>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
              
                <div class="col-lg-12 "><p class="sub-header mb-0 mt-2"><strong>Foto POD Invalid</strong></p>
                    <img src="${finalUrlPod}" class="img-thumbnail image-box" width="350"
     onerror="this.onerror=null; this.src='${DEFAULT_IMAGE}'">

                </div>
            
            </div>
            <div class="col-md-6">
                <div class="col-lg-12 "><p class="sub-header mb-0 mt-2"><strong>Foto POD Revisi</strong></p>
                    <img src="${urlRevision}" class="img-thumbnail image-box" width="350">
                </div>
            </div>
        </div>
      
    <div class="modal-footer">
                    
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
        
    </div>
    </form>
    `;
        document.getElementById('modalContentContainer').innerHTML = content;
    }

</script>
<style>
    .upload-area {
        border: 2px dashed #ccc;
        border-radius: 5px;
        padding: 60px 30px;
        text-align: center;
        color: #aaa;
        transition: 0.3s;
    }

    .upload-area.dragover {
        border-color: #0d6efd;
        background-color: #e7f1ff;
        color: #0d6efd;
    }

    #preview img {
        max-width: 450px;
        margin: 10px;
        border-radius: 5px;
    }
</style>

<!-- Modal -->
<div class="modal fade" id="ModalDetailImage" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="ModalLabel">Detail Image</h5>
                <!-- Tombol Close -->
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div id="modalContentContainer">


            </div>
        </div>
    </div>
</div>