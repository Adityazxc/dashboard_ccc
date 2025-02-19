<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Admin_model');
        $this->load->model('User_model');
        $this->load->model('Product_model');
        $this->session->set_userdata('pages', 'dashboard_page');
    }

    public function index()
    {
        $user_role = $this->session->userdata('role');  
        $password = $this->session->userdata('password');
        if ($password == "e10adc3949ba59abbe56e057f20f883e") {
            redirect('reset_password/input_password');
        }else if($this->session->userdata('logged_in') && ($user_role == 'Upper' ||$user_role == 'Admin' || $user_role == 'Finance' || $user_role == 'Production'|| $user_role == 'Marketing')) {        
        $data['title'] = 'Dashboard Admin';
        $data['page_name'] = 'dashboard_admin';
        $data['role'] = $user_role;
        $this->load->view('dashboard', $data);
        } else {
            redirect('auth');
        }
        //
    }
    public function getSourceData()
    {
        $dateFrom = $this->input->post('dateFrom');
        $dateThru = $this->input->post('dateThru');
        $source_data = []; // Default empty array

        if ($dateFrom && $dateThru) {
            $source_data = $this->Product_model->getSourceData($dateFrom, $dateThru);
        }

        $mapped_data = [];
        $label_mapping = [
            'S' => 'Shopee',
            'T' => 'Tokopedia',
            'TK' => 'Tiktok',
            'O' => 'Offline'
        ];

        $sourceLabels = [];
        $sourceCounts = [];

        foreach ($source_data as $data) {
            $sourceLabels[] = $label_mapping[$data['source']] ?? $data['source'];
            $sourceCounts[] = (int) $data['count'];
        }

        echo json_encode([
            'success' => true,
            'sourceLabels' => $sourceLabels,
            'sourceCounts' => $sourceCounts
        ]);
    }
    public function getSourceDataMultiple()
{
    $dateFrom = $this->input->post('dateFrom');
    $dateThru = $this->input->post('dateThru');
    $source_data = []; // Default empty array

    // Inisialisasi array Labels dan Counts
    $Labels = [];
    $Counts = [];

    if ($dateFrom && $dateThru) {
        // Ambil data dari model
        $source_data = $this->Product_model->getSourceDataMultiple($dateFrom, $dateThru);
    }

    // Pemetaan label sumber data
    $label_mapping = [
        'S' => 'Shopee',
        'T' => 'Tokopedia',
        'TK' => 'Tiktok',
        'O' => 'Offline'
    ];

    // Olah data untuk dikirimkan ke frontend
    foreach ($source_data as $data) {
        $Labels[] = $label_mapping[$data['source']] ?? $data['source']; // Gunakan label mapping
        $Counts[] = (int) $data['count']; // Pastikan jumlah dalam format integer
    }

    // Kirim respons JSON ke frontend
    echo json_encode([
        'success' => true,
        'Labels' => $Labels,
        'Counts' => $Counts
    ]);
}


    public function upload()
    {
        //
        $user_role = $this->session->userdata('role');
        // if ($this->session->userdata('logged_in') && ($user_role == 'Admin')) {
        $data['title'] = 'Dashboard Admin';
        $data['page_name'] = 'upload_data';
        $data['role'] = 'Admin';
        $this->load->view('dashboard', $data);
        // } else {
        //     redirect('auth');
        // }
    }

    public function detail_user($id_user = NULL)
    {
        // Periksa apakah id_user tidak kosong
        $user_role = $this->session->userdata('role');
        if ($id_user !== NULL) {
            $data['title'] = 'Dashboard Admin';
            $data['page_name'] = 'detail_users';
            $data['role'] = $user_role;
            // $data['id_user'] = $id_user;
            $data['id_user'] = $id_user;

            // Load halaman detail user dengan data
            $this->load->view('dashboard', $data);
        } else {
            // Jika id_user tidak ada, redirect ke halaman admin
            redirect('admin');
        }
    }

    public function history()
    {
        //
        $user_role = $this->session->userdata('role');
        if ($this->session->userdata('logged_in') && ($user_role == 'Admin')) {
            $data['title'] = 'Dashboard Admin';
            $data['page_name'] = 'history';
            $data['role'] = 'Admin';
            $this->load->view('dashboard', $data);
        } else {
            redirect('auth');
        }


    }
    public function master_data_users()
    {

        $user_role = $this->session->userdata('role');
        if ($this->session->userdata('logged_in') && ($user_role == 'Admin' || $user_role == 'Super User' || $user_role == 'HC')) {
            $data['title'] = 'Dashboard Admin';
            $data['page_name'] = 'master_data_users';
            $data['role'] = $user_role;
            $data['employee_positions'] = $this->User_model->get_employee_positions();
            $this->load->view('dashboard', $data);
        } else {
            redirect('auth');
        }


    }


    public function view_users_logs()
    {
        $list = $this->Upload_model->getdatatables_customer();

        $data = array();
        $no = $this->input->post('start', true);
        foreach ($list as $item) {


            $row = array();
            $row[] = '<small style="font-size:12px">' . $no . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->nik) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->nama) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->no_tlp) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->email) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->orion_id) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->sca_id) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->dashboard_id) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->apex_id) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->create_date) . '</small>';
            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->Upload_model->count_all_customer(),
            "recordsFiltered" => $this->Upload_model->count_filtered_customer(),
            "data" => $data,
        );
        echo json_encode($output);
    }
    public function summary_customer()
    {
        $dateFrom = $this->input->post('dateFrom');
        $dateThru = $this->input->post('dateThru');
        
        
        $this->db->select('
        COUNT(*) as num_rows, 
        SUM(amount) as sum_selling, 
        SUM(sub_total) as gross_profit,
        SUM(profit) as sum_profit,
        SUM(IF(source = "T", sub_total, 0)) as sum_tokopedia,
        SUM(IF(source = "S", sub_total, 0)) as sum_shopee,
        SUM(IF(source = "TK", sub_total, 0)) as sum_Tiktok,
        SUM(IF(source = "O", sub_total, 0)) as sum_offline,                
        ');

        $this->db->from('selling');

        

        if ($dateFrom && $dateThru) {
            $this->db->where('DATE(date_selling) >=', $dateFrom);
            $this->db->where('DATE(date_selling) <=', $dateThru);
        }


        $query = $this->db->get();
        $result = $query->row();

        echo json_encode([
            'sum_selling' => htmlspecialchars($result->sum_selling),
            'sum_profit' => htmlspecialchars($result->sum_profit),
            'sum_tokopedia' => htmlspecialchars($result->sum_tokopedia),
            'sum_shopee' => htmlspecialchars($result->sum_shopee),
            'sum_Tiktok' => htmlspecialchars($result->sum_Tiktok),
            'sum_offline' => htmlspecialchars($result->sum_offline),
            'gross_profit' => htmlspecialchars($result->gross_profit),

        ]);

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
    public function edit_users()
    {

        $origin = $this->input->post('origin', TRUE);
        $zone = $this->input->post('zone', TRUE);
        $kpi = $this->input->post('kpi', TRUE);
        $role = $this->input->post('defaulRole', TRUE);
        $nik = $this->input->post('nik', TRUE);
        if ($this->User_model->edit_users($nik, $origin, $zone, $kpi, $role)) {
            $this->session->set_flashdata('success_message', 'Data user berhasil diubah!');
        } else {
            $this->session->set_flashdata('error_message', 'Data user gagal diubah!');
        }

        redirect("admin/master_data_users");
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