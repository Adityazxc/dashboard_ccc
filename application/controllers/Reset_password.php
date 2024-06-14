<?php

defined('BASEPATH') or exit('No direct script access allowed');

class reset_password extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Admin_model');
        $this->load->model('User_model');
        $this->session->set_userdata('pages', 'admin_role');
    }

    public function index()
    {
        $data['username'] = $this->session->userdata('username');
        $data['role'] = $this->session->userdata('role');
        // var_dump($data);
        $this->load->view('reset_password',$data);        

    }
   
    
}
