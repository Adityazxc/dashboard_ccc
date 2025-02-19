<?php
use PHPStan\PhpDocParser\Ast\PhpDoc\TypeAliasImportTagValueNode;

defined('BASEPATH') or exit('No direct script access allowed');
// require APPPATH . 'third_party/PHPExcel/PHPExcel.php';

class Spending extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->model('Spending_model');
        $this->session->set_userdata('pages', 'spending_page');
        $this->load->library('session');
        $this->load->helper('url');
        // $this->load->library('email');
        $this->load->library('encryption');

    }

    public function index()
    {
        $user_role = $this->session->userdata('role');
        $password = $this->session->userdata('password');
        if ($password == "e10adc3949ba59abbe56e057f20f883e") {
            redirect('reset_password/input_password');
        } else if ($this->session->userdata('logged_in') && ($user_role == 'Upper')) {
            $data['title'] = 'Dashboard User';
            $data['page_name'] = 'list_spending';
            $data['role'] = $user_role;
            $this->load->view('dashboard', $data);
        } else {
            redirect('auth');
        }
        //
    }
    public function view_spending()
    {
        $list = $this->Spending_model->getdatatables_spending();

        $data = array();
        $no = $this->input->post('start', true);
        foreach ($list as $item) {

            $no++;
            $row = array();
            $row[] = '<small style="font-size:12px">' . $no . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->name_spending) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->create_date) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->description) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->nominal_spending) . '</small>';
            $row[] = '
            <td>              
                  <button  data-bs-toggle="modal"  class="btn btn-link btn-prmary btn-lg" data-bs-target="#editSpending" onclick="editSpending(
                  ' . htmlspecialchars($item->id_spending) . ',
                  \'' . htmlspecialchars($item->name_spending) . '\',
                  \'' . htmlspecialchars($item->description) . '\',
                  \'' . htmlspecialchars($item->nominal_spending) . '\',
                  )">  <i class="fa fa-edit"></i>        
                  </button>                                                         
                  <a href="#" data-bs-toggle="modal"  class="btn btn-link btn-danger btn-lg" data-bs-target="#deleteSpending" onclick="deleteSpending(' . htmlspecialchars($item->id_spending) . ',\'' . htmlspecialchars($item->name_spending) . '\' )">  <i class="fa fa-times"></i>                                                
            </td>';
            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->Spending_model->count_all_spending(),
            "recordsFiltered" => $this->Spending_model->count_filtered_spending(),
            "data" => $data,
        );
        echo json_encode($output);
    }
    public function add_spending()
    {
        
        $user_data = array(
            'name_spending' => $this->input->post('nameSpending'),            
            'create_date' => date('Y-m-d H:i:s'),
            'description' => $this->input->post('desc'),
            'nominal_spending' => $this->input->post('nominal_spending'),
            
        );
        if ($this->Spending_model->_add_spending($user_data)) {
            // Jika berhasil, set flashdata untuk notifikasi sukses
            $this->session->set_flashdata('notify', [
                'message' => 'Spending berhasil ditambahkan!',
                'type' => 'success'
            ]);
        } else {
            // Jika gagal, set flashdata untuk notifikasi error
            $this->session->set_flashdata('notify', [
                'message' => 'Gagal menambahkan spending',
                'type' => 'warning'
            ]);
        }
        // var_dump($user_data);
        redirect('spending');
    }
    public function edit_spending()
    {

        $id_spending = $this->input->post('idSpendingEdit');
        $user_data = array(
            'name_spending' => $this->input->post('nameSpendingEdit'),
            'description' => $this->input->post('descriptionEdit'),
            'nominal_spending' => $this->input->post('nominalEdit'),

        );
        if ($this->Spending_model->_edit_spending($user_data, $id_spending)) {
            // Jika berhasil, set flashdata untuk notifikasi sukses
            $this->session->set_flashdata('notify', [
                'message' => 'Spending berhasil diubah!',
                'type' => 'success'
            ]);
        } else {
            // Jika gagal, set flashdata untuk notifikasi error
            $this->session->set_flashdata('notify', [
                'message' => 'Gagal mengubah Spending!',
                'type' => 'warning'
            ]);
        }

        redirect('spending');
    }

    public function delete_spending()
    {
        $id_spending = $this->input->post('idSpendingDelete');
        if ($this->Spending_model->_delete_spending($id_spending)) {
            // Jika berhasil, set flashdata untuk notifikasi sukses
            $this->session->set_flashdata('notify', [
                'message' => 'Produk berhasil ditambahkan!',
                'type' => 'success'
            ]);
        } else {
            // Jika gagal, set flashdata untuk notifikasi error
            $this->session->set_flashdata('notify', [
                'message' => 'Gagal menambahkan produk!',
                'type' => 'success'
            ]);
        }
        // var_dump($id_user);
        redirect('spending');
    }
    public function default_password()
    {
        $id_user = $this->input->post('idUserReset');

        if ($this->User_model->default_password($id_user)) {
            // Jika berhasil, set flashdata untuk notifikasi sukses
            $this->session->set_flashdata('notify', [
                'message' => 'Produk berhasil ditambahkan!',
                'type' => 'success'
            ]);
        } else {
            // Jika gagal, set flashdata untuk notifikasi error
            $this->session->set_flashdata('notify', [
                'message' => 'Gagal menambahkan produk!',
                'type' => 'success'
            ]);
        }
        redirect('users');
    }

    public function get_csrf()
    {
        echo $this->security->get_csrf_hash();
    }

    public function get_csrf_json()
    {
        $data['status'] = "Success";
        $data['get_csrf_hash'] = $this->security->get_csrf_hash();
        echo json_encode($data);
    }




}

