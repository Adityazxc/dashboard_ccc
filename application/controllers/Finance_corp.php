<?php

defined('BASEPATH') or exit('No direct script access allowed');
// require APPPATH . 'third_party/PHPExcel/PHPExcel.php';

class Finance_corp extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Customer_model_corp');
        $this->load->model('Ccc_model_corp');
        $this->session->set_userdata('pages', 'finance_role_corp');
    }

    public function index()
    {
        $user_role = $this->session->userdata('role');
        if ($this->session->userdata('logged_in') && ($user_role == 'Finance' || $user_role == 'Admin' || $user_role == 'Kacab')) {
            $data['title'] = 'Dashboard Finance';
            $data['page_name'] = 'dashboard_finance';
            if ($user_role == 'Finance') {
                $data['role'] = 'Finance';
            } else if ($user_role == 'Kacab') {
                $data['role'] = 'Kacab';
            } else {
                $data['role'] = 'Admin';
            }
            $data['voucher_data'] = $this->Customer_model_corp->getVoucherData();
            $this->load->view('dashboard', $data);
        } else {
            redirect('auth');
        }
    }
    public function getdatatables_customer()
    {
        // echo $this->input->post('dateFrom');
        $list = $this->Ccc_model_corp->getdatatables_finance();

        $data = array();
        $no = $this->input->post('start', true);
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row[] = '<small style="font-size:12px">' . $no . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->date) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->awb_no) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->id_customer) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->customer_name) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->consignee) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->qty) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->weight) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->harga) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->create_at) . '</small>';
            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->Ccc_model_corp->count_all_customer(),
            "recordsFiltered" => $this->Ccc_model_corp->count_filtered_customer(),
            "data" => $data,
        );
        // output to json format
        echo json_encode($output);
    }
    public function summary_customer()
    {
        // Get the date range from the POST data
        $dateFrom= $this->security->xss_clean($this->input->post('dateFrom'));
        $dateThru= $this->security->xss_clean($this->input->post('dateThru'));

        $this->db->where('DATE(date) >=',  $dateFrom);
        $this->db->where('DATE(date) <=', $dateThru);
        $customers_status1 = $this->db->count_all_results('corporate');

        // Summing up the 'harga' for records within the specified date range
        $this->db->select('SUM(harga) as totalharga');
        $this->db->where('DATE(date) >=',  $dateFrom);
        $this->db->where('DATE(date) <=', $dateThru);
        $customersTotalHarga = $this->db->get('corporate')->row();
        // Return the total harga as JSON
        echo json_encode([
            'sum_status1' => $customers_status1,
            'sum_totalharga' => $customersTotalHarga->totalharga,
        ]);
    }




    public function session()
    {
        $session_data = $this->session->all_userdata();

        echo '<pre>';
        print_r($session_data);
    }

    public function test()
    {
        $data['email'] = $this->input->post('email');

        echo "<pre>";
        echo print_r($data);
        echo "</pre>";
    }




}