<?php
defined('BASEPATH') or exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\IOFactory;

class Product extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Customer_model');
        $this->load->model('Product_model');
        $this->load->library('session');
        $this->load->helper(['url', 'form']);
        $this->session->set_userdata('pages', 'product_page');
    }
    public function index()
    {
        $user_role = $this->session->userdata('role');
        $password = $this->session->userdata('password');
        if ($password == "e10adc3949ba59abbe56e057f20f883e") {
            redirect('reset_password/input_password');
        }else if($this->session->userdata('logged_in') && ($user_role == 'Upper' || $user_role == 'Finance' || $user_role == 'Admin'|| $user_role == 'Marketing')) {        
        $data['title'] = 'Product';
        $data['page_name'] = 'dashboard_product';
        $data['role'] = $user_role;
        $this->load->view('dashboard', $data);
        } else {
            redirect('auth');
        }
        
    }
    public function get_product_images()
    {
        $id_product = $this->input->post('id_product');
        $this->db->where('id_product', $id_product);
        $query = $this->db->get('photo_product');

        echo json_encode($query->result());
    }

    public function getdatatables_product()
    {
        $list = $this->Product_model->getdatatables_product();
        $data = array();
        $no = $this->input->post('start', true);
        $user_role = $this->session->userdata('role');
        foreach ($list as $item) {
            $no++;

            $row = array();
            $row[] = '<small style="font-size:12px">' . $no . '</small>';
            // Menampilkan Gambar Produk
            $image_path = base_url('uploads/products/' . $item->foto);
            $image_tag = $item->foto ? '<img src="' . $image_path . '" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover; cursor: pointer;" onclick="showProductImages(' . $item->id_product . ')">' : '<small>No Image</small>';
            $row[] = $image_tag;


            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->name_product) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->barcode_product) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->size_printing) . '</small>';
            $row[] = '<small style="font-size:12px">' . $this->rupiah($item->profit) . '</small>';
            $row[] = '<small style="font-size:12px">' . $this->rupiah($item->admin) . '</small>';
            $row[] = '<small style="font-size:12px">' . $item->category . '</small>';
            $row[] = '<small style="font-size:12px">' . $this->rupiah($item->selling_price) . '</small>';
            $row[] = '<small style="font-size:12px">' . $this->rupiah($item->price_shirt) . '</small>';
            $row[] = '<small style="font-size:12px">' . $this->rupiah($item->price_packing) . '</small>';
            $row[] = '<small style="font-size:12px">' . $this->rupiah($item->price_printing) . '</small>';
            $row[] = '<small style="font-size:12px">' . $this->rupiah($item->price_press) . '</small>';
            $row[] = '<small style="font-size:12px">' . $this->rupiah($item->price_marketing) . '</small>';
            $row[] = '<small style="font-size:12px">' . $this->rupiah($item->price_production) . '</small>';
            if ($user_role == "Admin" || $user_role == 'Upper') {

                $row[] = '<div class="form-button-action"> 
                <button class="btn btn-link btn-simple-primary btn-lg" onclick="editProduct(
                    ' . htmlspecialchars($item->id_product) . ', \'' . htmlspecialchars($item->name_product) . '\', \'' .
                    htmlspecialchars($item->barcode_product) . '\', \'' . htmlspecialchars($item->size_printing) . '\', \'' .
                    htmlspecialchars($item->profit) . '\', \'' . htmlspecialchars($item->admin) . '\', \'' .
                    htmlspecialchars($item->selling_price) . '\', \'' . htmlspecialchars($item->category) . '\', \'' .
                    htmlspecialchars($item->price_shirt) . '\', \'' . htmlspecialchars($item->price_packing) . '\', \'' .
                    htmlspecialchars($item->price_printing) . '\', \'' . htmlspecialchars($item->price_press) . '\', \'' .
                    htmlspecialchars($item->persentase_admin) . '\', \'' . htmlspecialchars($item->persentase_margin) . '\', \'' .
                    htmlspecialchars($item->price_production) . '\', \'' . htmlspecialchars($item->price_marketing) . '\')"
                    data-bs-toggle="modal" data-bs-target="#ModalEditProduct">
                    <i class="fa fa-edit"></i>
                    </button>
                    
                    <button class="btn btn-link btn-danger btn-lg" onclick="removeProduct(
                        ' . htmlspecialchars($item->id_product) . ', \'' . htmlspecialchars($item->name_product) . '\')"
                        data-bs-toggle="modal" data-bs-target="#ModalRemoveProduct">
                        <i class="fa fa-times"></i>
                        </button>
                        </div>';
            }else{

            }

            $data[] = $row;
        }


        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->Product_model->count_all_product(),
            "recordsFiltered" => $this->Product_model->count_filtered_product(),
            "data" => $data,
        );
        // output to json format
        echo json_encode($output);
    }
    function rupiah($angka)
    {

        $hasil_rupiah = "Rp " . number_format($angka, 0, ',', '.');
        return $hasil_rupiah;

    }
    public function delete_product()
    {
        $id_product = $this->input->post("idProductDelete");
        if ($this->Product_model->_delete_product($id_product)) {
            // Jika berhasil, set flashdata untuk notifikasi sukses
            $this->session->set_flashdata('notify', [
                'message' => 'Produk berhasil dihapus!',
                'type' => 'success'
            ]);
        } else {
            // Jika gagal, set flashdata untuk notifikasi error
            $this->session->set_flashdata('notify', [
                'message' => 'Gagal menghapus produk!',
                'type' => 'danger'
            ]);
        }

        // var_dump($id_product);
        redirect("product");
    }
    public function add_product()
    {
        $user_data = array(
            'name_product' => $this->input->post('nameProduct', TRUE),
            'category' => $this->input->post('categoryProduct', TRUE),
            'barcode_product' => $this->input->post('barcodeProduct', TRUE),
            'size_printing' => $this->input->post('sizePrinting', TRUE),
            'price_shirt' => $this->input->post('priceProduct', TRUE),
            'price_packing' => $this->input->post('pricePacking', TRUE),
            'price_printing' => $this->input->post('pricePrinting', TRUE),
            'price_press' => $this->input->post('pricePress', TRUE),
            'price_marketing' => $this->input->post('priceMarketing', TRUE),
            'price_production' => $this->input->post('priceProduction', TRUE),
            'persentase_margin' => $this->input->post('persentaseMargin', TRUE),
            'selling_price' => $this->input->post('priceSelling', TRUE),
            'persentase_admin' => $this->input->post('persentaseAdmin', TRUE),
            'admin' => $this->input->post('priceAdmin', TRUE),
            'stock' => $this->input->post('stockProduct', TRUE),
            'profit' => $this->input->post('profit', TRUE),
        );
        // $user_data = array(
        //     'name_product' => "heheh",
        //     'category' => "heheh",
        //     'barcode_product' => "heheh",
        //     'size_printing' => "heheh",
        //     'price_shirt' => "heheh",

        // );

        $this->Product_model->_add_product($user_data);
        $id_product = $this->Product_model->_add_product($user_data);
        if ($id_product) {
            $this->upload_images($id_product);
            $this->session->set_flashdata('notify', [
                'message' => 'Produk berhasil ditambahkan!',
                'type' => 'success'
            ]);
        } else {
            $this->session->set_flashdata('notify', [
                'message' => 'Gagal menambahkan produk!',
                'type' => 'danger'
            ]);
        }

        redirect("product");
    }
    public function upload_images()
    {
        $id_product = 105; // Contoh id_product
        $config['upload_path'] = './uploads/products/'; // Folder penyimpanan
        $config['allowed_types'] = 'jpg|jpeg|png|gif'; // Jenis file yang diperbolehkan
        $config['max_size'] = 2048; // Maks 2MB
        $this->load->library('upload', $config);

        // Periksa apakah ada file yang diunggah
        if (empty($_FILES['imgProduct']['name'][0])) {
            echo "Tidak ada file yang diunggah.";
            return;
        }

        $uploaded_files = [];

        foreach ($_FILES['imgProduct']['name'] as $key => $name) {
            $_FILES['file']['name'] = $_FILES['imgProduct']['name'][$key];
            $_FILES['file']['type'] = $_FILES['imgProduct']['type'][$key];
            $_FILES['file']['tmp_name'] = $_FILES['imgProduct']['tmp_name'][$key];
            $_FILES['file']['error'] = $_FILES['imgProduct']['error'][$key];
            $_FILES['file']['size'] = $_FILES['imgProduct']['size'][$key];

            $config['file_name'] = time() . '_' . $name;
            $this->upload->initialize($config);

            if ($this->upload->do_upload('file')) {
                $upload_data = $this->upload->data();
                $uploaded_files[] = $upload_data['file_name'];
            } else {
                echo "Upload gagal untuk file: " . $name . "<br>";
                echo $this->upload->display_errors();
            }
        }

        // Debug output
        // var_dump($uploaded_files);
        // Simpan ke database
        if (!empty($uploaded_files)) {
            $this->Product_model->insert_images($id_product, $uploaded_files);
        }
    }

    public function edit_product()
    {
        $csrf_token = $this->input->post($this->security->get_csrf_token_name());
        log_message('info', 'CSRF token: ' . $csrf_token);

        // Debug data form
        log_message('info', 'Data form: ' . json_encode($this->input->post()));

        $id_product = $this->input->post('idProductEdit', TRUE);
        $user_data = array(
            'name_product' => $this->input->post('nameProductEdit', TRUE),
            'category' => $this->input->post('categoryProductEdit', TRUE),
            'barcode_product' => $this->input->post('barcodeProductEdit', TRUE),
            'size_printing' => $this->input->post('sizePrintingEdit', TRUE),
            'price_shirt' => $this->input->post('priceProductEdit', TRUE),
            'price_packing' => $this->input->post('pricePackingEdit', TRUE),
            'price_printing' => $this->input->post('pricePrintingEdit', TRUE),
            'price_press' => $this->input->post('pricePressEdit', TRUE),
            'price_marketing' => $this->input->post('priceMarketingEdit', TRUE),
            'price_production' => $this->input->post('priceProductionEdit', TRUE),
            'persentase_margin' => $this->input->post('persentaseMarginEdit', TRUE),
            'selling_price' => $this->input->post('priceSellingEdit', TRUE),
            'persentase_admin' => $this->input->post('persentaseAdminEdit', TRUE),
            'admin' => $this->input->post('priceAdminEdit', TRUE),
            'profit' => $this->input->post('profitEdit', TRUE),
        );


        if ($this->Product_model->_edit_product($id_product, $user_data)) {
            // Jika berhasil, set flashdata untuk notifikasi sukses
            $this->session->set_flashdata('notify', [
                'message' => 'Produk berhasil di ubah!',
                'type' => 'success'
            ]);
        } else {
            // Jika gagal, set flashdata untuk notifikasi error
            $this->session->set_flashdata('notify', [
                'message' => 'Gagal menambahkan produk!',
                'type' => 'danger'
            ]);
        }

        // var_dump($user_data);
        redirect("product");
    }




    public function get_csrf()
    {
        //
        echo $this->security->get_csrf_hash();
    }

    public function get_csrf_json()
    {
        //
        $data['status'] = "Success";
        $data['get_csrf_hash'] = $this->security->get_csrf_hash();
        echo json_encode($data);
    }

}