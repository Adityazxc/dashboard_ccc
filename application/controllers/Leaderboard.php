<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Leaderboard extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Admin_model');              
        $this->load->model('Leaderboard_model');
        $this->load->model('Checker_model');
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('User_model');
        $this->load->library('encryption');
        $this->session->set_userdata('pages', 'leaderboard_page');
    }

    public function index()
    {
        $user_role = $this->session->userdata('role');
        $password = $this->session->userdata('password');
        $zone = $this->session->userdata('location');
        $origin = $this->Checker_model->_get_origin($zone);
        $get_origins = json_encode($this->Admin_model->_get_origins());
        if ($password == "e10adc3949ba59abbe56e057f20f883e") {
            redirect('reset_password/input_password');
        } else if ($this->session->userdata('logged_in') && (
            $user_role == "Koordinator" 
            || $user_role == "Admin" 
            || $user_role == "Super User" 
            || $user_role == "CS" 
            || $user_role == "CCC"
            || $user_role == "BPS"
            || $user_role == "HC"
            || $user_role == "Kepala Cabang BDO2"
            || $user_role == "Kepala Cabang"
            || $user_role == "BBP"
            || $user_role == "PAO"
            
            )) {
            $data['title'] = 'Leaderboard';
            $data['page_name'] = 'Leaderboard';
            $data['get_origins'] = $get_origins;
            $data['role'] = $user_role;
            $data['zone'] = $zone;
            $data['origin'] = $origin->origin_code;

            $this->load->view('dashboard', $data);
        } else {
            redirect('auth');
        }

    }

    public function getdatatables_top_courier()
    {
        $user_role = $this->session->userdata('role');
        $list = $this->Leaderboard_model->get_datatables_top_courier();
        $data = array();
        $no = $this->input->post('start', true);
        foreach ($list as $item) {
            $zone = $item->zone;            
            $no++;
            $row = array();
            $row[] = '<small style="font-size:12px">' . $no . '</small>';            
            $row[] = '<small style="font-size:12px"><b>' . htmlspecialchars($item->courier_name) . '</b><br>
            ' . htmlspecialchars($item->id_courier) . '
            </small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->qty_awb) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->qty_sesuai) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->qty_tidak_sesuai) . '</small>';            
            $row[] = '<small style="font-size:12px">' . htmlspecialchars(round($item->persentase,1)).' %</small>';            

            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->Leaderboard_model->count_all_top_courier(),
            "recordsFiltered" => $this->Leaderboard_model->count_filtered_top_courier(),
            "data" => $data,
        );
        echo json_encode($output);
    }
}