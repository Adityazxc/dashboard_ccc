<?php
defined('BASEPATH') or exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\IOFactory;

class Ccc extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Customer_model');
        $this->load->library('session');
        $this->session->set_userdata('pages', 'ccc_role');
        $this->load->helper('url');        

    }


    public function index()
    {
        $user_role = $this->session->userdata('role');
        if ($this->session->userdata('logged_in') && ($user_role == 'CCC' || $user_role == 'Admin')) {
            $data['title'] = 'Voucher Data';
            $data['page_name'] = 'dashboard_ccc';
            if ($user_role == 'CCC') {
                $data['role'] = 'CCC';
            } else {
                $data['role'] = 'Admin';
            }
            $data['voucher_data'] = $this->Customer_model->getVoucherData();
            $this->load->view('dashboard', $data);
        } else {
            redirect('auth');
        }
    }



    public function importData()
    {
        $this->load->library('upload');
        $this->upload->initialize(
            array(
                'upload_path' => './uploads/', // Sesuaikan dengan path yang sesuai
                'allowed_types' => 'xlsx|xls|csv', // Sesuaikan dengan jenis file yang diizinkan
            )
        );

        if ($this->upload->do_upload('excel_file')) {
            $data = $this->upload->data();
            $file_path = $data['full_path'];

            // Menggunakan library PhpSpreadsheet
            $spreadsheet = IOFactory::load($file_path);
            // null vallue, calculateformulas, format data, returCellRef
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            // Membuang baris pertama (header)
            unset($sheetData[1]);

            // Proses data dari $sheetData sesuai kebutuhan
            foreach ($sheetData as $row) {
                if (
                    empty($row['B']) ||
                    empty($row['D']) ||
                    empty($row['E']) ||
                    empty($row['F']) ||
                    empty($row['G'])
                ) {
                    // Handle empty values, show an error message, or skip the row
                    continue;
                }

                $data = array(
                    'customer_name' => $row['B'],
                    'email' => $row['C'],
                    'no_hp' => $row['D'],
                    'harga' => $row['E'],
                    'awb_no' => $row['F'],
                    'service' => $row['G'],
                );
                // Check if email is provided, allow null



                $voucher_code = $this->generateVoucherCode();
                $data['voucher'] = $voucher_code;
                $data['date'] = date('Y-m-d H:i:s');
                // $data['expired_date'] = date('Y-m-d', strtotime('+30 days'));
                $voucher_value = $row['E'];
                $data['value_voucher'] = $voucher_value;
                $data['status'] = 'N'; // Default status
                $data['status_email'] = 'N'; // Default status
                $data['type'] = 'customer';
                // Simpan data ke database atau lakukan proses lain sesuai kebutuhan
                $this->Customer_model->tambah($data);
            }

            $this->session->set_flashdata('success_message', 'File berhasil diunggah dan data berhasil ditambahkan.');
            // Tampilkan pesan sukses atau redirect ke halaman lain
            redirect('ccc/view_add_data');
        } else {
            // Tangani kesalahan upload file
            $this->session->set_flashdata('error_message', 'File gagal diunggah, file harus berformat excel!');
            redirect('Ccc/view_add_data');
        }
    }



    private function generateVoucherCode()
    {
        $length = 16;
        $characters = '23456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz';
        $voucher_code = '';

        // loop untuk menghasilkan 16 karakter uniq
        for ($i = 0; $i < $length; $i++) {

            // pilih karakter acak
            $voucher_code .= $characters[rand(0, strlen($characters) - 1)];
        }


        return $voucher_code;
    }




    public function view_add_data()
    {
        $user_role = $this->session->userdata('role');
        if ($this->session->userdata('logged_in') && ($user_role == 'CCC' || $user_role == 'Admin')) {
            $data['title'] = 'Add Data';
            $data['page_name'] = 'add_data';
            if ($user_role == 'CCC') {
                $data['role'] = 'CCC';
            } else {
                $data['role'] = 'Admin';
            }
            $data['voucher_data'] = $this->Customer_model->getImportData();
            $this->load->view('dashboard', $data);
        } else {
            redirect('auth');
        }
    }

    public function add_data()
    {
        // Jika data berhasil ditambahkan, set pesan sukses ke dalam flashdata
        $this->session->set_flashdata('success_message', 'Data berhasil ditambahkan.');

        $customer_data = array(
            'customer_name' => $this->input->post('customerName',TRUE),
            'awb_no' => $this->input->post('awb_no',TRUE),
            'email' => $this->input->post('email',TRUE),
            'no_hp' => $this->input->post('no_tlp',TRUE),
            'harga' => $this->input->post('ongkir',TRUE),
            'service' => $this->input->post('service',TRUE),
        );
        // Tambahkan tanggal ke dalam array
        $voucher_code = $this->generateVoucherCode();
        $customer_data['voucher'] = $voucher_code;
        $customer_data['date'] = date('Y-m-d H:i:s');
        // $customer_data['expired_date'] = date('Y-m-d', strtotime('+30 days'));
        $customer_data['value_voucher'] = $this->input->post('ongkir',TRUE);
        $customer_data['status'] = 'N'; // Default status
        $customer_data['status_email'] = 'N';
        $customer_data['type'] = 'customer'; // Default status
        $this->Customer_model->tambah($customer_data);
        $this->session->set_flashdata('notif', '<div class="alert alert-success" role="alert">Data Berhasil ditambahkan <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span><button><div> ');
        redirect(site_url('ccc/view_add_data'), 'refresh');
    }

    public function add_customer_modal()
    {
        // Load the add_customer_modal view
        $this->load->view('add_customer_modal');
    }



    public function getdatatables_customer()
    {
        // echo $this->input->post('dateFrom');
        $list = $this->ccc_model->getdatatables_customer();

        $data = array();
        $no = $this->input->post('start', true);
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row[] = '<small style="font-size:12px">' . $no . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->customer_name) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->email) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->no_hp) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->harga) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->awb_no) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->service) . '</small>';
            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->ccc_model->count_all_customer(),
            "recordsFiltered" => $this->ccc_model->count_filtered_customer(),
            "data" => $data,
        );
        // output to json format
        echo json_encode($output);
    }

    public function summary_customer()
    {
        $dateFrom= $this->security->xss_clean($this->input->post('dateFrom'));
        $dateThru= $this->security->xss_clean($this->input->post('dateThru'));

        $this->db->where('status_email', 'Y');
        $this->db->where('type', 'customer');
        $this->db->where('DATE(date) >=',  $dateFrom);
        $this->db->where('DATE(date) <=', $dateThru);
        $customers_status1 = $this->db->get('customers')->num_rows();

        $this->db->where('status', 'Y');
        $this->db->where('type', 'customer');
        $this->db->where('DATE(date) >=',  $dateFrom);
        $this->db->where('DATE(date) <=', $dateThru);
        $customers_status2 = $this->db->get('customers')->num_rows();

        $this->db->where('status', 'N');
        $this->db->where('status_email', 'Y');
        $this->db->where('type', 'customer');
        $this->db->where('DATE(date) >=',  $dateFrom);
        $this->db->where('DATE(date) <=', $dateThru);
        $customers_status3 = $this->db->get('customers')->num_rows();

        $this->db->where('status_email', 'Y');
        $this->db->where('type', 'customer');
        $this->db->where('expired_date <', date('Y-m-d'));
        $this->db->where('DATE(date) >=',  $dateFrom);
        $this->db->where('DATE(date) <=', $dateThru);
        $customers_status4 = $this->db->get('customers')->num_rows();


        $this->db->select('SUM(harga) as totalharga');
        $this->db->where('status', 'Y');
        $this->db->where('type', 'customer');
        $this->db->where('DATE(date) >=',  $dateFrom);
        $this->db->where('DATE(date) <=', $dateThru);
        $customers_status5 = $this->db->get('customers')->row();

        echo json_encode([
            'sum_status1' => htmlspecialchars($customers_status1),
            'sum_status2' => htmlspecialchars($customers_status2),
            'sum_status3' => htmlspecialchars($customers_status3),
            'sum_status4' => htmlspecialchars($customers_status4),
            'sum_status5' => htmlspecialchars($customers_status5->totalharga),
        ]);
    }


    public function data()
    {

        $customers = $this->db->get('customers');
        foreach ($customers->result() as $row) {
            $customer_data = array(
                'customer_name' => $row->customer_name,
                'awb_no' => $row->awb_no,
                'date' => $row->date,
                'email' => $row->email,
                'no_hp' => $row->no_hp,
                'harga' => $row->harga,
                'service' => $row->service,
                'expired_date' => $row->expired_date,
            );
            $this->db->insert('customers', $customer_data);
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