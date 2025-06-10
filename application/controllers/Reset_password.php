<?php

defined('BASEPATH') or exit('No direct script access allowed');

class reset_password extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Admin_model');
        $this->load->model('Customer_model');
        $this->load->model('User_model');
        $this->session->set_userdata('pages', 'users');
    }

  
    public function index()
    {
        $password = $this->session->userdata('password');
        if ($password == "e10adc3949ba59abbe56e057f20f883e") {
            redirect('reset_password/input_password');
        } else {
            $data['title'] = 'Reset Password';
            $data['page_name'] = 'update_password';
            $data['username'] = $this->session->userdata('username');
            $data['account_name'] = $this->session->userdata('account_name');
            $data['role'] = $this->session->userdata('role');                        
            $this->load->view('dashboard', $data);
        }

    }

    public function input_password()
    {
        $data['title'] = 'Reset Password';
        $data['page_name'] = 'input_password';
        $data['username'] = $this->session->userdata('username');
        $data['account_name'] = $this->session->userdata('account_name');
        $data['role'] = $this->session->userdata('role');
        $data['password'] = $this->session->userdata('password');
        // var_dump($data);
        $this->load->view('dashboard', $data);

    }

    public function process_reset_password()
    {

        $new_password = $this->input->post('password', TRUE);
        $id_user = $this->session->userdata('id_user');
        // var_dump($new_password,$id_user);
        $update_result = $this->Customer_model->update_password($id_user, $new_password);

        if ($update_result) {
            // Password berhasil diubah
            $this->session->set_flashdata('success', 'Password berhasil di rubah');
            redirect('auth');
        } else {
            // Terjadi kesalahan saat mengubah password
            $this->session->set_flashdata('error_message', 'Password gagal di rubah');
        }

        redirect('auth');

    }





}
