<?php
defined('BASEPATH') or exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\IOFactory;

class Customers_lm extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->model('Customers_lm_model');
        $this->load->model('Lm_model');
        $this->load->model('Users_model');

        $this->load->library('session');
        $this->session->set_userdata('pages', 'customers_lm_page');
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
            $data['get_type_cust'] = json_encode($this->Lm_model->_get_type_cust());




            $this->load->view('dashboard', $data);
        } else {
            redirect('auth');
        }
    }


    public function view_customers()
    {
        $list = $this->Customers_lm_model->getdatatables_customers_lm();

        $data = array();
        $no = $this->input->post('start', true);
        foreach ($list as $item) {

            $no++;
            $row = array();
            $row[] = '<small style="font-size:12px">' . $no . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->account_number) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->cust_branch) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->cust_name) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->cust_name2) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->payment_metode) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->big_grouping_cust) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->cust_industry) . '</small>';
            if (htmlspecialchars($item->status_customer) == "Active") {
                $row[] = '<span class="badge rounded-pill bg-success"> Active </span>
                 <div class="form-button-action"> 

                </div>';
                
                
            } else {
                $row[] = '<span class="badge rounded-pill bg-danger"> Deactive </span>
                 <div class="form-button-action"> 

                </div>';
                


            }
        
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->cek) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->name) . '</small>';
            $button = '
            
            <div class="form-button-action"> 
            <button class="btn btn-link btn-simple-primary btn-lg" 
        onclick="editCustomers(
            ' . $item->id_cus_lm . ', 
            \'' . addslashes(trim($item->account_number)) . '\', 
            \'' . addslashes(trim($item->cust_branch)) . '\', 
            \'' . addslashes(trim($item->cust_name)) . '\', 
            \'' . addslashes(trim($item->cust_name2)) . '\',             
            \'' . addslashes(trim($item->payment_metode)) . '\',             
            \'' . addslashes(trim($item->big_grouping_cust)) . '\',             
            \'' . addslashes(trim($item->cust_industry)) . '\',             
            \'' . addslashes(trim($item->status_customer)) . '\',             
            \'' . addslashes(trim($item->cek)) . '\',             
            \'' . addslashes(trim($item->pic_bdo)) . '\')"
        data-bs-toggle="modal" data-bs-target="#ModalEditCustomer">
        <i class="fa fa-edit"></i>
    </button>                        
    
    ';

            if (htmlspecialchars($item->status_customer) == "Active") {


                $button .= '<button class="btn btn-link btn-danger btn-lg" onclick="deactiveCustomer(
                        ' . htmlspecialchars($item->id_cus_lm) . ', \'' . htmlspecialchars($item->cust_name) . '\')"
                        data-bs-toggle="modal" data-bs-target="#deactiveCustomer">
                        <i class="fa fa-user-slash"></i>
                        </button>
    
    
                        </div>';

            } else {

                $button .= '<button class="btn btn-link btn-danger btn-lg" onclick="activeCustomer(
                        ' . htmlspecialchars($item->id_cus_lm) . ', \'' . htmlspecialchars($item->cust_name) . '\')"
                        data-bs-toggle="modal" data-bs-target="#activeCustomer">
                        <i class="fa fa-person"></i>
                        </button>
    
    
                        </div>';

            }



            $row[] = $button;
            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->Customers_lm_model->count_all_customers_lm(),
            "recordsFiltered" => $this->Customers_lm_model->count_filtered_customers_lm(),
            "data" => $data,
        );
        echo json_encode($output);
    }
    public function add_customers_lm()
    {
        $cust_name = $this->input->post('cust_name');



        $user_data = array(
            'account_number' => $this->input->post('account_number'),
            'cust_branch' => $this->input->post('cust_branch'),
            'cust_name' => $cust_name,
            'cust_name2' => $this->input->post('cust_name2'),
            'payment_metode' => $this->input->post('payment_metode'),
            'big_grouping_cust' => $this->input->post('big_grouping_cust'),
            'cust_industry' => $this->input->post('cust_industry'),
            'status_customer' => "Active",
            'cek' => $this->input->post('cek'),
            'pic_bdo' => $this->input->post('pic_bdo'),

        );
        if ($this->Customers_lm_model->add_Customers_lm_model($user_data)) {
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
        redirect('customers_lm');
    }
    public function edit_customer_lm()
    {

        $id_customers_lm = $this->input->post('id_cus_lm_edit');
        $user_data = array(
            'account_number' => $this->input->post('account_number_edit'),
            'cust_branch' => $this->input->post('cust_branch_edit'),
            'cust_name' => $this->input->post('cust_name_edit'),
            'cust_name2' => $this->input->post('cust_name2_edit'),
            'payment_metode' => $this->input->post('payment_metode_edit'),
            'big_grouping_cust' => $this->input->post('big_grouping_cust_edit'),
            'cust_industry' => $this->input->post('cust_industry_edit'),
            'status_customer' => $this->input->post('status_customer_edit'),
            'cek' => $this->input->post('cek_edit'),
            'pic_bdo' => $this->input->post('pic_bdo_edit'),

        );
        if ($this->Customers_lm_model->edit_customers_lm($user_data, $id_customers_lm)) {
            // Jika berhasil, set flashdata untuk notifikasi sukses
            $this->session->set_flashdata('notify', [
                'message' => 'Customer berhasil diedit!',
                'type' => 'success'
            ]);
        } else {
            // Jika gagal, set flashdata untuk notifikasi error
            $this->session->set_flashdata('notify', [
                'message' => 'Gagal edit Customer!',
                'type' => 'warning'
            ]);
        }
        // var_dump($user_data, $id_customers_lm);
        redirect('customers_lm');
    }

    public function deactive()
    {
        $id_user = $this->input->post('id_customer_deactive');
        $status_customer = "Deactive";
        if ($this->Customers_lm_model->deactive_customers_lm($id_user, $status_customer)) {
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
        redirect('customers_lm');
    }
    public function active()
    {
        $id_user = $this->input->post('id_customer_active');
        $status_customer = "Active";
        if ($this->Customers_lm_model->active_customers_lm($id_user, $status_customer)) {
            // Jika berhasil, set flashdata untuk notifikasi sukses
            $this->session->set_flashdata('notify', [
                'message' => 'Customer berhasil diaktifkan!',
                'type' => 'success'
            ]);
        } else {
            // Jika gagal, set flashdata untuk notifikasi error
            $this->session->set_flashdata('notify', [
                'message' => 'Customers gagal diaktifkan!',
                'type' => 'warning'
            ]);
        }
        // var_dump($id_user);
        redirect('customers_lm');
    }

}