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
        $username = trim($this->input->post('username', TRUE));
        $password = trim($this->input->post('password', TRUE));

        // Debug input
        log_message('debug', 'Input Username: ' . $username);
        log_message('debug', 'Input Password Hash: ' . md5($password));

        if (empty($username) || empty($password)) {
            $this->session->set_flashdata('error_message', 'Username dan Password tidak boleh kosong!');
            redirect('auth');
            return;
        }

        $this->db->group_start();
        $this->db->where('username', $username);        
        $this->db->group_end();
        $this->db->where('pass', md5($password));

        $query = $this->db->get('users');

        if ($query->num_rows() == 1) {
            $user = $query->row();

            $redirect_page = 'auth';
            if (md5($password) == md5("123456")) {
                $redirect_page = "reset_password/input_password";
            } else {
                switch ($user->role) {                    
                    case "Koordinator":
                    case "Admin":
                    case "BPS":
                    case "Kepala Cabang":
                    case "CS":
                    case "CCC":
                    case "HC":
                    case "Kepala Cabang BDO2":
                    case "Super User":
                    case "BBP":
                    case "PAO":
                        $redirect_page = "dashboard";
                        break;
                }
            }

            $data_user = array(
                'id_user' => $user->id_user,
                'username' => $user->username,
                'account_name' => $user->name,
                'role' => $user->role,  
                'password'=>$user->pass,                                          
                'location'=>$user->location,                                          
                'logged_in' => TRUE
            );

            $this->session->set_userdata($data_user);

            log_message('debug', 'Login successful. Redirecting to: ' . $redirect_page);
            redirect($redirect_page);
        } else {
            log_message('debug', 'Login failed for username: ' . $username);
            $this->session->set_flashdata('error_message', 'Username dan Password tidak sesuai!');
            redirect('auth');
        }

    }


    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth');
    }


}

?>