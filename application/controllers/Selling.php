<?php
defined('BASEPATH') or exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\IOFactory;

class Selling extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Selling_model');
        $this->load->model('Scan_product_model');
        $this->load->library('session');
        $this->session->set_userdata('pages', 'selling_page');
        $this->load->helper(['url', 'form']);
    }

    public function index()
    {
        $user_role = $this->session->userdata('role');
        $password = $this->session->userdata('password');
        if ($password == "e10adc3949ba59abbe56e057f20f883e") {
            redirect('reset_password/input_password');
        }else if($this->session->userdata('logged_in') && ($user_role == 'Upper' || $user_role == 'Finance' || $user_role == 'Production'|| $user_role == 'Admin')) {        
        $data['title'] = 'Dashboard Selling';
        $data['page_name'] = 'dashboard_selling';
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
    public function getdatatables_selling()
    {

        $list = $this->Selling_model->getdatatables_selling();
        $data = array();
        $no = $this->input->post('start', true);


        foreach ($list as $item) {
            $no++;

            $row = array();
            $row[] = '<small style="font-size:12px">' . $no . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->date_selling) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->name_product) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->barcode_product) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->amount) . '</small>';
            $row[] = '<small style="font-size:12px">' . $this->rupiah($item->selling_price) . '</small>';
            $row[] = '<small style="font-size:12px">' . $this->rupiah($item->sub_total) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->source) . '</small>';

                $row[] = '<div class="form-button-action"> 
                <button class="btn btn-link btn-simple-primary btn-lg" onclick="editProduct(
                    ' . htmlspecialchars($item->id_product) . ', \'' . htmlspecialchars($item->name_product) . '\', \'' . 
                    htmlspecialchars($item->barcode_product) . '\', \'' . htmlspecialchars($item->size_printing) . '\', \'' . 
                    htmlspecialchars($item->price_shirt) . '\', \'' . htmlspecialchars($item->price_packing) . '\', \'' . 
                    htmlspecialchars($item->price_printing) . '\', \'' . htmlspecialchars($item->price_press) . '\', \'' . 
                    htmlspecialchars($item->sub_total) . '\')"
                    data-bs-toggle="modal" data-bs-target="#ModalEditProduct">
                    <i class="fa fa-edit"></i>
                </button>

                <button class="btn btn-link btn-danger btn-lg" onclick="detailProduct(
                    ' . htmlspecialchars($item->id_product) . ', \'' . htmlspecialchars($item->name_product) . '\')"
                    data-bs-toggle="modal" data-bs-target="#ModalRemoveProduct">
                    <i class="fa fa-times"></i>
                </button>
            </div>';

            $data[] = $row;
        }


        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->Selling_model->count_all_selling(),
            "recordsFiltered" => $this->Selling_model->count_filtered_selling(),
            "data" => $data,
        );
        // output to json format
        echo json_encode($output);
    }
    function get_product()
    {
        $product = $this->input->post('barcodeProduct');
        if ($result = $this->Scan_product_model->_getDetails($product)) {

            $response = [
                'nameProduct' => $result->name_product,
                'idProduct' => $result->id_product,
                'amount' => 1,
                'priceSelling' => $result->selling_price,
                'subTotal' => $result->name_product,
                'profit' => $result->profit,
                'source' => 'Shopee',
            ];
            // var_dump($response);
        } else {
            $response = [
                'nameProduct' => "Produk tidak ditemukan periksa kembali!",
           
            ];
        }
        ;
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
    function rupiah($angka)
    {

        $hasil_rupiah = "Rp " . number_format($angka, 0, ',', '.');
        return $hasil_rupiah;

    }
    public function delete_product()
    {
        $id_product = $this->input->post("idProduct");
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

        // var_dump($hehe);
        redirect("admin");
    }
    public function add_selling()
    {
        if ($name_product = $this->input->post('nameSellingProduct', TRUE) == NULL) {
            $this->session->set_flashdata('notify', [
                'message' => 'Gagal menambahkan Selling!',
                'type' => 'danger'
            ]);
            redirect('selling');
        }
        $barcode = $this->input->post('barcodeSellingProduct', TRUE);
        // Debug data form
        log_message('info', 'Data form: ' . json_encode($this->input->post()));
        $user_data = array(
            'id_product' => $this->input->post('idSellingProduct', TRUE),
            'amount' => $this->input->post('amount', TRUE),
            'sub_total' => $this->input->post('subTotalSelling', TRUE),
            'source' => $this->input->post('source', TRUE),
            'profit' => $this->input->post('Profit', TRUE),
            'date_selling' => date('Y-m-d H:i:s'),
        );


    
        if ($this->Selling_model->_add_selling($user_data, $barcode)) {
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

        //    return var_dump($testing);
        // var_dump($user_data);
        redirect("selling");
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
            'barcode_product' => $this->input->post('barcodeProductEdit', TRUE),
            'size_printing' => $this->input->post('sizePrintingEdit', TRUE),
            'price_shirt' => $this->input->post('priceProductEdit', TRUE),
            'price_packing' => $this->input->post('pricePackingEdit', TRUE),
            'price_printing' => $this->input->post('pricePrintingEdit', TRUE),
            'price_press' => $this->input->post('pricePressEdit', TRUE),
            'total' => $this->input->post('totalEdit', TRUE),
        );

        if ($user_data['total'] == 0) {
            redirect('admin');
        }
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
        redirect("selling");
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