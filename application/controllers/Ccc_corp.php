<?php
defined('BASEPATH') or exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\IOFactory;

class Ccc_corp extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Customer_model_corp');
        $this->load->model('Ccc_model_corp');
        $this->load->library('session');
        $this->session->set_userdata('pages', 'ccc_role_corp');
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
            $data['voucher_data'] = $this->Customer_model_corp->getVoucherData();
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
            $uploadedData = $this->upload->data();
            $file_path = $uploadedData['full_path'];

            // Menggunakan library PhpSpreadsheet
            $spreadsheet = IOFactory::load($file_path);
            // null value, calculate formulas, format data, returnCellRef
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            // Membuang baris pertama (header)
            unset($sheetData[1]);

            // Proses data dari $sheetData sesuai kebutuhan
            foreach ($sheetData as $row) {
                if (
                    empty($row['B']) ||
                    empty($row['C']) ||
                    empty($row['D']) ||
                    empty($row['E']) ||
                    empty($row['F']) ||
                    empty($row['G']) ||
                    empty($row['H']) ||
                    empty($row['I']) ||
                    empty($row['I'])
                ) {
                    // Handle empty values, show an error message, or skip the row
                    continue;
                }

                // Convert date format from "09/02/2024 22:43:00" to "YYYY-MM-DD"

                $inputDate = $row['B'];

                // Buat objek DateTime dari tanggal input
                $date = Date('Y-m-d', strtotime($inputDate));

                // Ubah format tanggal menjadi YYYY-MM-DD                

                $rowData = array(
                    'create_at' => $date,
                    'awb_no' => $row['C'],
                    'id_customer' => $row['D'],
                    'customer_name' => $row['E'],
                    'consignee' => $row['F'],
                    'qty' => $row['G'],
                    'weight' => $row['H'],
                    'harga' => $row['I'],
                );
                $rowData['date'] = date('Y-m-d');
                // Simpan data ke database atau lakukan proses lain sesuai kebutuhan
                $this->Customer_model_corp->tambah($rowData);
            }
            redirect('Ccc_corp/view_add_data');
        } else {
            $this->session->set_flashdata('error_message', 'File gagal diunggah, file harus berformat excel!');
            redirect('Ccc_corp/view_add_data');
        }
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
            $data['voucher_data'] = $this->Customer_model_corp->getImportData();
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
            'customer_name' => $this->input->post('customerName'),
            'awb_no' => $this->input->post('awb_no'),
            'email' => $this->input->post('email'),
            'no_hp' => $this->input->post('no_tlp'),
            'harga' => $this->input->post('ongkir'),
            'service' => $this->input->post('service'),
        );
        // Tambahkan tanggal ke dalam array
        $voucher_code = $this->generateVoucherCode();
        $customer_data['voucher'] = $voucher_code;
        $customer_data['date'] = date('Y-m-d H:i:s');
        $customer_data['expired_date'] = date('Y-m-d', strtotime('+30 days'));
        $customer_data['value_voucher'] = $this->input->post('ongkir');
        $customer_data['status'] = 'N'; // Default status
        $customer_data['status_email'] = 'N'; // Default status
        $customer_data['type'] = 'corporate';
        $this->Customer_model_corp->tambah($customer_data);
        $this->session->set_flashdata('notif', '<div class="alert alert-success" role="alert">Data Berhasil ditambahkan <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span><button><div> ');
        redirect(site_url('ccc_corp/view_add_data'), 'refresh');
    }

    public function add_customer_modal()
    {
        // Load the add_customer_modal view
        $this->load->view('add_customer_modal');
    }



    public function getdatatables_customer()
    {
        // echo $this->input->post('dateFrom');
        $list = $this->Ccc_model_corp->getdatatables_customer();

        $data = array();
        $no = @$_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row[] = '<small style="font-size:12px">' . $no . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->date) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->customer_name) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->email) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->no_hp) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->harga) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->awb_no) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->service) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->voucher) . '</small>';
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
        $this->db->select('SUM(harga) as totalharga');
        $this->db->where('status', 'Y');
        $this->db->where('type', 'corporate');
        $this->db->where('DATE(date) >=', $this->input->post('dateFrom'));
        $this->db->where('DATE(date) <=', $this->input->post('dateThru'));
        $customers_status5 = $this->db->get('customers')->row();
        echo json_encode([
            'sum_status5' => $customers_status5->totalharga,
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

}