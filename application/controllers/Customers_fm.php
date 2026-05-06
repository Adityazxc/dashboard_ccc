<?php
defined('BASEPATH') or exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\IOFactory;

class Customers_fm extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->model('Customers_fm_model');
        $this->load->model('Fm_model');
        $this->load->model('Users_model');

        $this->load->library('session');
        $this->session->set_userdata('pages', 'customers_fm_page');
        $this->load->helper('url');


    }

    public function index()
    {
        $user_role = $this->session->userdata('role');
        $password = $this->session->userdata('password');
        $zone = $this->session->userdata('location');
        $get_users_ccc = json_encode($this->Users_model->_get_user_());




        if ($password == "e10adc3949ba59abbe56e057f20f883e") {
            redirect('reset_password/input_password');
        } else if (
            $this->session->userdata('logged_in') && (
                $user_role == "CCC"
                || $user_role == "Super User"
                || $user_role == "HC"


            )
        ) {
            $data['title'] = 'Dashboard Admin';
            $data['page_name'] = 'list_customers';
            $data['role'] = $user_role;
            $data['zone'] = $zone;
            $data['get_users_ccc'] = $get_users_ccc;
            $data['get_type_cust'] = json_encode($this->Fm_model->_get_type_cust());
             




            $this->load->view('dashboard', $data);
        } else {
            redirect('auth');
        }
    }


    public function view_customers()
    {
        $list = $this->Customers_fm_model->getdatatables_customers_fm();

        $data = array();
        $no = $this->input->post('start', true);
        foreach ($list as $item) {

            $no++;
            $row = array();
            $row[] = '<small style="font-size:12px">' . $no . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->cust_id) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->cust_name) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->grouping_cust) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->segmentasi) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->name) . '</small>';
            if (htmlspecialchars($item->status) == "Active") {
                $row[] = '<span class="badge rounded-pill bg-success"> Active </span>
                 <div class="form-button-action"> 

                </div>';
                
                
            } else {
                $row[] = '<span class="badge rounded-pill bg-danger"> Deactive </span>
                 <div class="form-button-action"> 

                </div>';
                


            }
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->address) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->source) . '</small>';

            $row[] = '<div class="form-button-action"> 
            <button class="btn btn-link btn-simple-primary btn-lg" 
            onclick="editCustomers(
                ' . $item->id . ', 
                \'' . addslashes(trim($item->cust_id)) . '\', 
                \'' . addslashes(trim($item->cust_name)) . '\', 
                \'' . addslashes(trim($item->grouping_cust)) . '\',             
                \'' . addslashes(trim($item->segmentasi)) . '\',             
                \'' . addslashes(trim($item->pic)) . '\',             
                \'' . addslashes(trim($item->status)) . '\',             
                \'' . addslashes(trim($item->address)) . '\',             
                \'' . addslashes(trim($item->source)) . '\')"
            data-bs-toggle="modal" data-bs-target="#ModalEditCustomer">
            <i class="fa fa-edit"></i>

            </button>                                          
            <button class="btn btn-link btn-danger btn-lg" onclick="deactiveCustomer(
                ' . htmlspecialchars($item->id) . ', 
                \'' . htmlspecialchars($item->cust_id) . '\')"
            data-bs-toggle="modal" data-bs-target="#deactiveCustomer">
            <i class="fa fa-user-slash"></i>
            </button>
            </div>';

            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->Customers_fm_model->count_all_customers_fm(),
            "recordsFiltered" => $this->Customers_fm_model->count_filtered_customers_fm(),
            "data" => $data,
        );
        echo json_encode($output);
    }
    public function add_customers_fm()
    {
        $cust_name = $this->input->post('cust_name');
        $user_data = array(
            'cust_id' => $this->input->post('account_number'),            
            'cust_name' => $cust_name,                        
            'grouping_cust' => $this->input->post('grouping_cust'),
            'segmentasi' => $this->input->post('segmentasi'),
            'status' => "Active",
            'address' => $this->input->post('address'),
            'source' => $this->input->post('source'),
            'pic' => $this->input->post('pic'),

        );
        if ($this->Customers_fm_model->add_customers($user_data)) {
            // Jika berhasil, set flashdata untuk notifikasi sukses
            $this->session->set_flashdata('notify', [
                'message' => 'Customer berhasil ditambahkan!',
                'type' => 'success'
            ]);
        } else {
            // Jika gagal, set flashdata untuk notifikasi error
            $this->session->set_flashdata('notify', [
                'message' => 'Gagal menambahkan, Customer <b>' . $cust_name . '</b> sudah ada!',
                'type' => 'warning'
            ]);
        }
        // var_dump($user_data, $cust_name);
        redirect('customers_fm');
    }
    public function edit_customer_fm()
    {

        $cust_name = $this->input->post('cust_name_edit');
        $id_customers_fm = $this->input->post('id_cus_fm_edit');
        $user_data = array(
            'cust_id' => $this->input->post('cust_id_edit'),            
            'cust_name' => $cust_name,                        
            'grouping_cust' => $this->input->post('grouping_cust_edit'),
            'segmentasi' => $this->input->post('segmentasi_edit'),
            'status' => $this->input->post('status_edit'),
            'address' => $this->input->post('address_edit'),
            'source' => $this->input->post('source_edit'),
            'pic' => $this->input->post('pic_bdo_edit'),          

        );
        if ($this->Customers_fm_model->edit_customers_fm($user_data, $id_customers_fm)) {
            // Jika berhasil, set flashdata untuk notifikasi sukses
            $this->session->set_flashdata('notify', [
                'message' => 'User berhasil diedit!',
                'type' => 'success'
            ]);
        } else {
            // Jika gagal, set flashdata untuk notifikasi error
            $this->session->set_flashdata('notify', [
                'message' => 'Gagal edit User!',
                'type' => 'warning'
            ]);
        }
        // var_dump($user_data, $id_customers_fm);
        redirect('customers_fm');
    }

    public function deactive()
    {
        $id_user = $this->input->post('id_customer_deactive');
        $status_customer = "Deactive";
        if ($this->Customers_fm_model->active_customers_lm($id_user, $status_customer)) {
            // Jika berhasil, set flashdata untuk notifikasi sukses
            $this->session->set_flashdata('notify', [
                'message' => 'Customer berhasil dinonaktifkan!',
                'type' => 'success'
            ]);
        } else {
            // Jika gagal, set flashdata untuk notifikasi error
            $this->session->set_flashdata('notify', [
                'message' => 'Customers gagal dinonaktifkan!',
                'type' => 'warning'
            ]);
        }
        // var_dump($id_user);
        redirect('customers_fm');
    }

}