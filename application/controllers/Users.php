<?php
use PHPStan\PhpDocParser\Ast\PhpDoc\TypeAliasImportTagValueNode;

defined('BASEPATH') or exit('No direct script access allowed');
// require APPPATH . 'third_party/PHPExcel/PHPExcel.php';

class Users extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->session->set_userdata('pages', 'user_page');
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
            $data['page_name'] = 'list_users';
            $data['role'] = $user_role;
            $this->load->view('dashboard', $data);
        } else {
            redirect('auth');
        }
        //
    }
    public function view_users()
    {
        $list = $this->User_model->getdatatables_users();

        $data = array();
        $no = $this->input->post('start', true);
        foreach ($list as $item) {

            $no++;
            $row = array();
            $row[] = '<small style="font-size:12px">' . $no . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->account_name) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->username) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->role) . '</small>';
            $row[] = '
            <td>              
                  <a href="#" data-bs-toggle="modal"  class="btn btn-link btn-prmary btn-lg" data-bs-target="#editusers" onclick="editUsers(
                  ' . htmlspecialchars($item->id_user) . ',
                  \'' . htmlspecialchars($item->account_name) . '\',
                  \'' . htmlspecialchars($item->role) . '\',
                  \'' . htmlspecialchars($item->username) . '\',
                  )">  <i class="fa fa-edit"></i>                                                
                  <a href="#" data-bs-toggle="modal"  class="btn btn-link btn-secondary btn-lg" data-bs-target="#resetPassword" onclick="resetPassword(' . htmlspecialchars($item->id_user) . ',\'' . htmlspecialchars($item->account_name) . '\' )">  <i class="fa fa-key"></i>                                                
                  <a href="#" data-bs-toggle="modal"  class="btn btn-link btn-danger btn-lg" data-bs-target="#deleteUsers" onclick="deleteUsers(' . htmlspecialchars($item->id_user) . ',\'' . htmlspecialchars($item->account_name) . '\' )">  <i class="fa fa-times"></i>                                                
            </td>';
            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->User_model->count_all_users(),
            "recordsFiltered" => $this->User_model->count_filtered_users(),
            "data" => $data,
        );
        echo json_encode($output);
    }
    public function add_users()
    {
        $username = $this->input->post('username');
        $user_data = array(
            'account_name' => $this->input->post('accountName'),
            'username' => $username,
            'role' => $this->input->post('role'),
            'password' => md5('123456'),
        );
        if ($this->User_model->add_user_model($user_data)) {
            // Jika berhasil, set flashdata untuk notifikasi sukses
            $this->session->set_flashdata('notify', [
                'message' => 'User berhasil ditambahkan!',
                'type' => 'success'
            ]);
        } else {
            // Jika gagal, set flashdata untuk notifikasi error
            $this->session->set_flashdata('notify', [
                'message' => 'Gagal menambahkan, username <b>'.$username.'</b> sudah ada!',
                'type' => 'warning'
            ]);
        }
        // var_dump($user_data);
        redirect('users');
    }
    public function edit_users()
    {

        $id_user = $this->input->post('idUserEdit');
        $user_data = array(
            'account_name' => $this->input->post('accountNameEdit'),
            'username' => $this->input->post('usernameEdit'),
            'role' => $this->input->post('defaultRoleEdit'),

        );
        if ($this->User_model->edit_users($user_data, $id_user)) {
            // Jika berhasil, set flashdata untuk notifikasi sukses
            $this->session->set_flashdata('notify', [
                'message' => 'Produk berhasil ditambahkan!',
                'type' => 'success'
            ]);
        } else {
            // Jika gagal, set flashdata untuk notifikasi error
            $this->session->set_flashdata('notify', [
                'message' => 'Gagal menambahkan produk!',
                'type' => 'warning'
            ]);
        }
        // var_dump($user_data);
        redirect('users');
    }

    public function delete_users()
    {
        $id_user = $this->input->post('idUserDelete');
        if ($this->User_model->delete_users($id_user)) {
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
        redirect('users');
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

