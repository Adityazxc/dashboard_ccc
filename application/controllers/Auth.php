<?php
defined("BASEPATH") or exit("No direct script access allowed");

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');

    }

    public function index()
    {

        $this->load->view('index');
    }

    public function a()
    {

        echo '<pre>';
        var_dump($_SESSION);
        echo '</pre>';
    }

    public function login()
    {
        $username = $this->input->post('username',TRUE);
        $password = $this->input->post('password',TRUE);
        // Load the user_agent library
        $this->load->library('user_agent');

        $this->db->where('account_number', $username);
        $this->db->where('password', md5($password));

        $query = $this->db->get('users');
        if ($query->num_rows() == 1) {
            $user = $query->row();

            // Additional update query to set status_account to true
            $this->db->where('id_user', $user->id_user);
            $this->db->update('users', array('status_account' => true));

            if ($user->password == "e10adc3949ba59abbe56e057f20f883e" && $user->role == "Agen") {
                $redirect_page = "Agen/reset_password_view";
            } else if ($user->password == "e10adc3949ba59abbe56e057f20f883e") {
                $redirect_page = "reset_password";
            } else if ($user->role == "CCC") {
                $redirect_page = "ccc";
            } else if ($user->role == "Marketing") {
                $redirect_page = "marketing";
            } else if ($user->role == "Agen") {
                $redirect_page = "agen";
            } else if ($user->role == "Finance" || $user->role == "Kacab") {
                $redirect_page = "finance";
            } else if ($user->role == "Admin") {
                $redirect_page = "admin";
            } else if ($user->role == "CS") {
                $redirect_page = "cs";
            } else {                
                redirect("auth");
            }

            $data_user = array(
                'id_user' => $user->id_user,
                'username' => $user->account_name,
                'logged_in' => TRUE,
                'role' => $user->role,
                'password' => $user->password
            );
            $this->session->set_userdata($data_user);
            $this->load->model('User_log_model');
            $this->User_log_model->log_user_access($user->id_user, $this->input->ip_address(), $this->agent->platform(), $this->agent->browser());
            redirect($redirect_page);

        } else {
            $this->session->set_flashdata('error_message', 'Username dan Password tidak sesuai!');
            redirect('auth');
        }

    }

    public function logout()
    {
        $user_id = $this->session->userdata('id_user');

        if ($user_id) {
            $this->db->where('id_user', $user_id);
            $this->db->update('users', array('status_account' => false));
        }
        $this->session->sess_destroy();
        redirect('auth');
    }


}

?>