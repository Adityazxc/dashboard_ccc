<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Users extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();                
        $this->load->model('Users_model');        
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->library('encryption');
        $this->session->set_userdata('pages', 'user_page');
        $this->db_checker = $this->load->database('checker_pod', TRUE);
    }

    public function index()
    {
        $user_role = $this->session->userdata('role');
        $password = $this->session->userdata('password');
        $get_origins = json_encode($this->Users_model->_get_origins());
        if ($password == "e10adc3949ba59abbe56e057f20f883e") {
            redirect('reset_password/input_password');
        } else if ($this->session->userdata('logged_in') && ($user_role == 'Super User')) {
            $data['title'] = 'Dashboard User';
            $data['page_name'] = 'list_users';
            $data['get_origins'] = $get_origins;
            $data['role'] = $user_role;
            $this->load->view('dashboard', $data);
        } else {
            redirect('auth');
        }
        // var_dump($get_locations);
    }

    public function view_users()
    {
        $list = $this->Users_model->getdatatables_users();

        $data = array();
        $no = $this->input->post('start', true);
        foreach ($list as $item) {

            $no++;
            $row = array();
            $row[] = '<small style="font-size:12px">' . $no . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->name) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->username) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->zone) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->no_hp) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->role) . '</small>';
            $row[] = '<div class="form-button-action"> 
            <button class="btn btn-link btn-simple-primary btn-lg" 
        onclick="editUsers(
            ' . $item->id_user . ', 
            \'' . addslashes(trim($item->username)) . '\', 
            \'' . addslashes(trim($item->location)) . '\', 
            \'' . addslashes(trim($item->no_hp)) . '\', 
            \'' . addslashes(trim($item->role)) . '\',             
            \'' . addslashes(trim($item->name)) . '\')"
        data-bs-toggle="modal" data-bs-target="#ModalEditUser">
        <i class="fa fa-edit"></i>
    </button>                
                <button class="btn btn-link btn-warning btn-lg" onclick="resetPassword(
                    ' . htmlspecialchars($item->id_user) . ', \'' . htmlspecialchars($item->name) . '\')"
                    data-bs-toggle="modal" data-bs-target="#resetPassword">
                    <i class="fa fa-key"></i>
                    </button>
                <button class="btn btn-link btn-danger btn-lg" onclick="deleteUsers(
                    ' . htmlspecialchars($item->id_user) . ', \'' . htmlspecialchars($item->name) . '\')"
                    data-bs-toggle="modal" data-bs-target="#deleteUsers">
                    <i class="fa fa-times"></i>
                    </button>
                <button class="btn btn-link btn-danger btn-lg" onclick="lockedUsers(
                    ' . htmlspecialchars($item->id_user) . ', \'' . htmlspecialchars($item->name) . '\')"
                    data-bs-toggle="modal" data-bs-target="#lockedUsers">
                    <i class="fa fa-lock"></i>
                    </button>
                    </div>';
            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->Users_model->count_all_users(),
            "recordsFiltered" => $this->Users_model->count_filtered_users(),
            "data" => $data,
        );
        echo json_encode($output);
    }
    public function add_users()
    {
        $username = $this->input->post('username');
        $no_hp = $this->input->post('no_hp');

        // Cek jika nomor diawali dengan "0"
        if (substr($no_hp, 0, 1) == '0') {
            // Ganti angka 0 pertama dengan 62
            $no_hp = '62' . substr($no_hp, 1);
        }
        $user_data = array(
            'name' => $this->input->post('accountName'),
            'username' => $username,
            'role' => $this->input->post('role'),
            'location' => $this->input->post('location'),
            'no_hp' => $no_hp,
            'pass' => md5('123456'),
        );
        if ($this->Users_model->add_Users_model($user_data)) {
            // Jika berhasil, set flashdata untuk notifikasi sukses
            $this->session->set_flashdata('notify', [
                'message' => 'User berhasil ditambahkan!',
                'type' => 'success'
            ]);
        } else {
            // Jika gagal, set flashdata untuk notifikasi error
            $this->session->set_flashdata('notify', [
                'message' => 'Gagal menambahkan, username <b>' . $username . '</b> sudah ada!',
                'type' => 'warning'
            ]);
        }
        // var_dump($user_data, $username);
        redirect('users');
    }
    public function edit_users()
    {

        $id_user = $this->input->post('idUserEdit');
        $user_data = array(
            'name' => $this->input->post('accountNameEdit'),
            'username' => $this->input->post('usernameEdit'),
            'role' => $this->input->post('defaultRoleEdit'),
            'location' => $this->input->post('locationEdit'),
            'no_hp' => $this->input->post('noHpEdit'),

        );
        if ($this->Users_model->edit_users($user_data, $id_user)) {
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
        // var_dump($user_data,$id_user);
        redirect('users');
    }

    public function delete_users()
    {
        $id_user = $this->input->post('idUserDelete');
        if ($this->Users_model->delete_users($id_user)) {
            // Jika berhasil, set flashdata untuk notifikasi sukses
            $this->session->set_flashdata('notify', [
                'message' => 'User berhasil dihapus!',
                'type' => 'success'
            ]);
        } else {
            // Jika gagal, set flashdata untuk notifikasi error
            $this->session->set_flashdata('notify', [
                'message' => 'Gagal hapus User!',
                'type' => 'warning'
            ]);
        }
        // var_dump($id_user);
        redirect('users');
    }
    public function locked_users()
    {
        $id_user = $this->input->post('idUserlocked');
        $user_data = array(
            'secret_2fa' => null,
            'is_2fa_enabled' => 0,           

        );
        
        if ($this->Users_model->locked_users($user_data,$id_user)) {
            // Jika berhasil, set flashdata untuk notifikasi sukses
            $this->session->set_flashdata('notify', [
                'message' => 'User berhasil di lock!',
                'type' => 'success'
            ]);
        } else {
            // Jika gagal, set flashdata untuk notifikasi error
            $this->session->set_flashdata('notify', [
                'message' => 'Gagal hapus Di Lock!',
                'type' => 'warning'
            ]);
        }
        // // var_dump($id_user);
        redirect('users');
    }
    public function default_password()
    {
        $id_user = $this->input->post('idUserReset');

        if ($this->Users_model->default_password($id_user)) {
            // Jika berhasil, set flashdata untuk notifikasi sukses
            $this->session->set_flashdata('notify', [
                'message' => 'Password berhasil diubah ke default!',
                'type' => 'success'
            ]);
        } else {
            // Jika gagal, set flashdata untuk notifikasi error
            $this->session->set_flashdata('notify', [
                'message' => 'Gagal menambahkan mengubah password ke default!',
                'type' => 'warning'
            ]);
        }
        redirect('users');
    }

}