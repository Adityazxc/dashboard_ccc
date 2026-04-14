<?php
defined("BASEPATH") or exit("No direct script access allowed");

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->library('GoogleAuthenticator');
        // Jika tanpa composer:         


    }

    public function index()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('http://localhost/validasi/auth');
            // redirect('http://192.168.154.16//validasi/auth');
        }
    
        $this->load->view('index');
    }
   
  

    public function logout()
    {
        $this->session->sess_destroy();
        if (!$this->session->userdata('logged_in')) {
            redirect('http://localhost/validasi/auth');
            // redirect('http://192.168.154.16//validasi/auth');
        }
    
        $this->load->view('index');
        redirect('auth');
    }




}
?>