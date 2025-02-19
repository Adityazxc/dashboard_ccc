<?php
defined('BASEPATH') or exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\IOFactory;

class Stock extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Product_model');
        $this->load->model('Stock_model');
        $this->load->model('Scan_product_model');
        $this->load->library('session');
        $this->session->set_userdata('pages', 'stock_page');
        $this->load->helper(['url', 'form']);
    }

    public function index()
    {
        $user_role = $this->session->userdata('role');        
        $password = $this->session->userdata('password');
        if ($password == "e10adc3949ba59abbe56e057f20f883e") {
            redirect('reset_password/input_password');
        }else if($this->session->userdata('logged_in') && ($user_role == 'Upper' || $user_role == 'Admin' || $user_role == 'Production'|| $user_role == 'Admin')) {        
        $data['title'] = 'Dashboard Stock';
        $data['page_name'] = 'dashboard_stock';
        $data['role'] = $user_role;
        $this->load->view('dashboard', $data);
        } else {
            redirect('auth');
        }
        
    }

    public function generate($code = '123456789')
    {
        header('Content-Type: image/png');
        echo $this->barcode->generate($code);
    }
    public function getdatatables_stock()
    {

        $list = $this->Stock_model->getdatatables_stock();
        $data = array();
        $no = $this->input->post('start', true);


        foreach ($list as $item) {
            $no++;

            $row = array();
            $row[] = '<small style="font-size:12px">' . $no . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->name_product) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->barcode_product) . '</small>';
            $row[] = '<small style="font-size:12px">' . $this->rupiah($item->selling_price) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->stock) . '</small>';

                $row[] = '<div class="form-button-action"> 
                <button class="btn btn-link btn-simple-primary btn-lg" onclick="editStock(
                    ' . htmlspecialchars($item->id_product) . ', \'' . $item->stock. '\')"
                    data-bs-toggle="modal" data-bs-target="#ModalEditStock">
                    <i class="fa fa-edit"></i>
                </button>             
            </div>';

            $data[] = $row;
        }


        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->Stock_model->count_all_stock(),
            "recordsFiltered" => $this->Stock_model->count_filtered_stock(),
            "data" => $data,
        );
        // output to json format
        echo json_encode($output);
    }
    function get_product()
    {
        $product = $this->input->post('barcodeProduct');
        if($result = $this->Scan_product_model->_getDetails($product)){

            $response = [
                'nameProduct' => $result->name_product,
                'idProduct' => $result->id_product,
                'amount' => 1,
                'priceSelling' => $result->selling_price,
                'subTotal' => $result->name_product,
                'stock' => $result->stock,
                'source' => 'Shopee',
            ];
        } else {
            $response = [
                'nameProduct' => "Produk tidak ditemukan periksa kembali!",
           
            ];
        };

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
    function rupiah($angka)
    {

        $hasil_rupiah = "Rp " . number_format($angka, 0, ',', '.');
        return $hasil_rupiah;

    }
    public function add_stock()
    {
        $addStock = $this->input->post("addStock");
        $currentStock = $this->input->post("stock");
        $newStock = $addStock + $currentStock;
        // Debug data form
        log_message('info', 'Data form: ' . json_encode($this->input->post()));
        $id_product = $this->input->post('idStockProduct', TRUE);
        $user_data = array(    
            'stock' => $newStock,            
        );
        if ($this->Product_model->_add_stock($id_product, $user_data)) {
            // Jika berhasil, set flashdata untuk notifikasi sukses
            $this->session->set_flashdata('notify', [
                'message' => 'Produk berhasil ditambahkan!',
                'type' => 'success'
            ]);
        } else {
            // Jika gagal, set flashdata untuk notifikasi error
            $this->session->set_flashdata('notify', [
                'message' => 'Gagal menambahkan produk!',
                'type' => 'danger'
            ]);
        }
        redirect("stock");
    }
    public function edit_stock()
    {      
        $id_product = $this->input->post('id_product_add_stock', TRUE);
        $user_data = array(
            'stock' => $this->input->post('stock_pro', TRUE),            
        );
     
        if ($this->Stock_model->_edit_stock($id_product, $user_data)) {
            // Jika berhasil, set flashdata untuk notifikasi sukses
            $this->session->set_flashdata('notify', [
                'message' => 'Stok berhasil di ubah!',
                'type' => 'success'
            ]);
        } else {
            // Jika gagal, set flashdata untuk notifikasi error
            $this->session->set_flashdata('notify', [
                'message' => 'Gagal menambahkan Stok!',
                'type' => 'danger'
            ]);
        }        
        redirect("stock");
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