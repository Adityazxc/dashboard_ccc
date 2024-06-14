<?php

defined('BASEPATH') or exit('No direct script access allowed');
// require APPPATH . 'third_party/PHPExcel/PHPExcel.php';

class Finance extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Customer_model');
        $this->load->model('Finance_model');
        $this->session->set_userdata('pages', 'finance_role');
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
            $data['voucher_data'] = $this->Customer_model->getVoucherData();
            $this->load->view('dashboard', $data);
        } else {
            redirect('auth');
        }
    }
    public function getdatatables_customer()
    {
        // echo $this->input->post('dateFrom');
        $list = $this->Finance_model->getdatatables_finance();

        $data = array();
        $no = $this->input->post('start', true);
        foreach ($list as $item) {

            $no++;
            $row = array();
            $row[] = '<small style="font-size:12px">' . $no . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->date) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->voucher) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->awbno_claim) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->harga) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->account_number) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->account_name) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->customer_name) . '</small>';
            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->Finance_model->count_all_customer(),
            "recordsFiltered" => $this->Finance_model->count_filtered_customer(),
            "data" => $data,
        );
        // output to json format
        echo json_encode($output);
    }

    public function summary_customer()
    {
        $this->db->select('COUNT(*) as num_rows, 
                       SUM(CASE WHEN customers.status IS NULL THEN 1 ELSE 0 END) as sum_status1,
                       SUM(CASE WHEN customers.status = "Y" THEN 1 ELSE 0 END) as sum_status2,
                       SUM(CASE WHEN customers.status = "N" AND customers.status_email = "Y" THEN 1 ELSE 0 END) as sum_status3,
                       SUM(CASE WHEN customers.status_email = "Y" AND expired_date < CURDATE() THEN 1 ELSE 0 END) as sum_status4,
                       SUM(CASE WHEN customers.status = "Y" THEN customers.harga ELSE 0 END) as totalharga');

        $this->db->from('customers');
        $this->db->join('users', 'customers.id_user = users.id_user', 'left');
        $this->db->where('customers.type', 'customer');
        $this->db->where('DATE(customers.date) >=', $this->security->xss_clean($this->input->post('dateFrom')));
        $this->db->where('DATE(customers.date) <=', $this->security->xss_clean($this->input->post('dateThru')));

        $accountNameFilter = $this->security->xss_clean($this->input->post('account_name'));
        if (!empty($accountNameFilter)) {
            $this->db->like('users.account_name', $accountNameFilter);
        }

        $query = $this->db->get();
        $result = $query->row();

        echo json_encode([
            'sum_status1' => htmlspecialchars($result->sum_status1),
            'sum_status2' => htmlspecialchars($result->sum_status2),
            'sum_status3' => htmlspecialchars($result->sum_status3),
            'sum_status4' => htmlspecialchars($result->sum_status4),
            'sum_status5' => htmlspecialchars($result->totalharga),
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