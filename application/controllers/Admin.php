<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
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
        if ($this->session->userdata('logged_in') &&( $this->session->userdata('role') == 'Admin'|| $this->session->userdata('role') == 'Kacab')){
            $data['title'] = 'Dashboard Admin';
            $data['page_name'] = 'dashboard_admin';
            if ( $this->session->userdata('role') == 'Admin'){
                $data['role'] = 'Admin';
            }else{
                $data['role'] = 'Kacab';
            }
            $this->load->view('dashboard', $data);
        } else {
            redirect('auth');
        }

    }
    public function user_log()
    {
        if ($this->session->userdata('logged_in') &&( $this->session->userdata('role') == 'Admin'|| $this->session->userdata('role') == 'Kacab')){
            $data['title'] = 'Dashboard Admin';
            $data['page_name'] = 'user_log';
            if ( $this->session->userdata('role') == 'Admin'){
                $data['role'] = 'Admin';
            }else{
                $data['role'] = 'Kacab';
            }
            $this->load->view('dashboard', $data);
        } else {
            redirect('auth');
        }

    }
   

    public function view_users_logs()
    {
        $list = $this->Admin_model->getdatatables_user();                    

        $data = array();
        $no = $this->input->post('start', true);
        foreach ($list as $item) {

            $no++;
            $row = array();
            $row[] = '<small style="font-size:12px">' . $no . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->account_name) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->role) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->ip_address) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->os) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->browser) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->login_time) . '</small>';                                
            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->Admin_model->count_all_user(),
            "recordsFiltered" => $this->Admin_model->count_filtered_user(),
            "data" => $data,
        );
        echo json_encode($output);
    }
    public function view_users()
    {
        $list = $this->Admin_model->getdatatables_customer();

        $data = array();
        $no = $this->input->post('start', true);
        foreach ($list as $item) {

            $no++;
            $row = array();
            $row[] = '<small style="font-size:12px">' . $no . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->account_name) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->agent_area) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->role) . '</small>';
            $row[] = '<a href="#" data-toggle="modal" data-target="#ModalEditPassword" onclick="detailUsers(' . htmlspecialchars($item->id_user) .',\''.htmlspecialchars($item->account_name).'\' )">' . htmlspecialchars($item->account_number) . '</a>';
            // $row[] = '<a href="#" onclick="detailUsers(' . $item->id_user . ')">' . $item->account_number . '</a>';

            if ($item->status_account == 1) {
                $status = 'Online';
                $row[] = '<small class="badge badge-boxed badge-soft-warning text-success" style="font-size:12px;">' . $status . '</small>';
            } else {
                $status = 'Offline';
                $row[] = '<small style="font-size:12px">' . htmlspecialchars($status) . '</small>';
            }
            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->Admin_model->count_all_customer(),
            "recordsFiltered" => $this->Admin_model->count_filtered_customer(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function reset_password()
    {
        
        $customerId = $this->input->post('customerId', TRUE);
        $newPassword = $this->input->post('new_password', TRUE);
        var_dump($newPassword);
        var_dump($customerId);
        if ($this->User_model->reset_password_model($customerId, $newPassword)) {
            echo "Email berhasil diperbarui!";
        } else {
            echo "Gagal memperbarui email.";
        }

        redirect("admin");
    }

    public function add_user(){        
        $user_data = array(
            'account_name' => $this->input->post('accountName', TRUE),
            'agent_area' => $this->input->post('agentArea', TRUE),
            'account_number' => $this->input->post('accountId', TRUE),
            'role' => $this->input->post('role', TRUE),                                    
        );              
        $user_data['password']=md5("123456");
        $user_data["agent_status"] ="Y";
        $user_data["status_account"] =0;
        $add_user=$this->User_model->add_user_model($user_data);
        // var_dump($user_data);
        $this->session->set_flashdata('notif', '<div class="alert alert-success" role="alert">Data Berhasil ditambahkan <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span><button><div> ');
        if ($add_user) {
            $this->session->set_flashdata('success', 'User berhasil ditambahkan');
            redirect(site_url('admin'), 'refresh');
        } else {
            // Proses selanjutnya jika voucher tidak berhasil diredeem
            $this->session->set_flashdata('error', 'User gagal ditambahkan');
            redirect(site_url('admin'), 'refresh');
        }
        


    }

    public function get_csrf()
    {
        echo $this->security->get_csrf_hash();
    }   

    public function get_csrf_json()
    {
        $data['status']             = "Success";
        $data['get_csrf_hash']      = $this->security->get_csrf_hash();
        echo json_encode($data);
    }
}
