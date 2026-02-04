<?php

defined('BASEPATH') or exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Dashboard extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Admin_model');
        $this->load->model('Export_model');
        $this->load->model('Checker_model');
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('User_model');
        $this->load->library('encryption');
        $this->session->set_userdata('pages', 'dashboard_page');
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
        } else if (
            $this->session->userdata('logged_in') && (
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
                || $user_role == "POD"
                || $user_role == "Admin BDO2"
                || $user_role == "Koordinator BDO2"

            )
        ) {
            $data['title'] = 'Dashboard Admin';
            $data['page_name'] = 'dashboard_admin';
            $data['get_origins'] = $get_origins;
            $data['role'] = $user_role;
            $data['zone'] = $zone;
            $data['origin'] = $origin->origin_code;

            $this->load->view('dashboard', $data);
        } else {
            redirect('auth');
        }

    }

    public function export_revision()
    {
        $dateFrom = $this->input->post('dateFrom');
        $dateThru = $this->input->post('dateThru');
    
        $filters = [
            'dateFrom' => $dateFrom,
            'dateThru' => $dateThru,
            'action' => "revision",
        ];
    
        $data = $this->Export_model->getDataNotApprove($filters);
    
        // 1️⃣ Buat folder sementara
        $tmpDir = FCPATH . 'uploads/tmp_export/export_' . time();
        if (!is_dir($tmpDir)) mkdir($tmpDir, 0777, true);
    
        // 2️⃣ Buat subfolder untuk images_revision
        $imgDir = $tmpDir . '/images_revision';
        if (!is_dir($imgDir)) mkdir($imgDir, 0777, true);
    
        // 3️⃣ Generate Excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
    
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'AWB');
        $sheet->setCellValue('C1', 'ID KURIR');
        $sheet->setCellValue('D1', 'NAMA KURIR');
        $sheet->setCellValue('E1', 'ZONA');
        $sheet->setCellValue('F1', 'FOTO URL');
    
        $row = 2;
        $no = 1;
        foreach ($data as $d) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $d['awb']);
            $sheet->setCellValue('C' . $row, $d['id_courier']);
            $sheet->setCellValue('D' . $row, $d['courier_name']);
            $sheet->setCellValue('E' . $row, $d['zone']);
            $sheet->setCellValue('F' . $row, $d['url_photo']);
            $row++;
        }
    
        foreach (range('A', 'F') as $col) $sheet->getColumnDimension($col)->setAutoSize(true);
    
        // Simpan Excel ke folder sementara
        $excelFile = $tmpDir . '/Summary_POD.xlsx';
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($excelFile);
    
        // 4️⃣ Copy semua img_revision ke folder images_revision
        foreach ($data as $d) {
            if (!empty($d['url_revision'])) {
                $source = FCPATH . $d['url_revision'];
                if (file_exists($source)) {
                    $dest = $imgDir . '/' . basename($d['url_revision']);
                    copy($source, $dest);
                }
            }
        }
    
        // 5️⃣ Buat ZIP dari folder sementara (Excel + gambar)
        $zip = new ZipArchive();
        $zipFile = $tmpDir . '.zip';
        if ($zip->open($zipFile, ZipArchive::CREATE) === TRUE) {
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($tmpDir, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::LEAVES_ONLY
            );
    
            foreach ($files as $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($tmpDir) + 1); // path relatif di ZIP
                    $zip->addFile($filePath, $relativePath);
                }
            }
            $zip->close();
        }
    
        // 6️⃣ Kirim ZIP ke browser
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="export_' . date('Y-m-d') . '.zip"');
        header('Content-Length: ' . filesize($zipFile));
        readfile($zipFile);
    
        // 7️⃣ Hapus folder sementara & ZIP
        $it = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($tmpDir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($it as $file) {
            if ($file->isDir()) rmdir($file->getRealPath());
            else unlink($file->getRealPath());
        }
        rmdir($tmpDir);
        unlink($zipFile);
    }
    
    

    public function export_not_approve()
    {

        $dateFrom = $this->input->post('dateFrom');
        $dateThru = $this->input->post('dateThru');

        $filters = [
            'dateFrom' => $dateFrom,
            'dateThru' => $dateThru,
            'action' => "Tidak Sesuai",

        ];
        $data = $this->Export_model->getDataNotApprove($filters);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'AWB');
        $sheet->setCellValue('C1', 'ID KURIR');
        $sheet->setCellValue('D1', 'NAMA KURIR');
        $sheet->setCellValue('E1', 'ZONA');
        $sheet->setCellValue('F1', 'FOTO URL');

        $row = 2;
        $no = 1;
        foreach ($data as $d) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $d['awb']);
            $sheet->setCellValue('C' . $row, $d['id_courier']);
            $sheet->setCellValue('D' . $row, $d['courier_name']);
            $sheet->setCellValue('E' . $row, $d['zone']);
            $sheet->setCellValue('F' . $row, $d['url_photo']);
            $row++;
        }

        foreach (range('A', 'F') as $col)
            $sheet->getColumnDimension($col)->setAutoSize(true);
        $date = date('Y-m-d');

        $writer = new Xlsx($spreadsheet);
        $filename = $date . ' Summary POD Tidak Sesuai-.xlsx';


        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        $writer->save('php://output');

    }
    public function not_approve()
    {
        $user_role = $this->session->userdata('role');
        $password = $this->session->userdata('password');
        $zone = $this->session->userdata('location');
        $origin = $this->Checker_model->_get_origin($zone);
        $get_origins = json_encode($this->Admin_model->_get_origins());
        if ($password == "e10adc3949ba59abbe56e057f20f883e") {
            redirect('reset_password/input_password');
        } else if (
            $this->session->userdata('logged_in') && (
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
                || $user_role == "Admin BDO2"
                || $user_role == "Koordinator BDO2"

            )
        ) {
            $filter = isset($_GET['filter']) ? base64_decode($_GET['filter']) : 'Semua';

            $data['filter'] = $filter;
            $data['title'] = 'Tidak Sesuai';
            $data['page_name'] = 'not_approve';
            $data['get_origins'] = $get_origins;
            $data['role'] = $user_role;
            $data['zone'] = $zone;
            $data['origin'] = $origin->origin_code;

            $this->load->view('dashboard', $data);
        } else {
            redirect('auth');
        }

    }
    // public function index()
    // {
    //     $user_role = $this->session->userdata('role');  
    //     $password = $this->session->userdata('password');
    //     // if ($password == "e10adc3949ba59abbe56e057f20f883e") {
    //     //     redirect('reset_password/input_password');
    //     // }else if($this->session->userdata('logged_in') && ($user_role == 'Upper' ||$user_role == 'Admin' || $user_role == 'Finance' || $user_role == 'Production'|| $user_role == 'Marketing')) {        
    //     $data['title'] = 'Dashboard Admin';
    //     $data['page_name'] = 'dashboard_admin';
    //     $data['role'] = $user_role;
    //     $this->load->view('dashboard', $data);
    //     // } else {
    //     //     redirect('auth');
    //     // }
    //     //
    // }



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




    public function master_data_users()
    {

        $user_role = $this->session->userdata('role');
        // if ($this->session->userdata('logged_in') && ($user_role == 'Admin' || $user_role == 'Super User' || $user_role == 'HC')) {
        $data['title'] = 'Dashboard Admin';
        $data['page_name'] = 'master_data_users';
        $data['role'] = $user_role;
        // $data['employee_positions'] = $this->User_model->get_employee_positions();
        $this->load->view('dashboard', $data);
        // } else {
        //     redirect('auth');
        // }


    }
    public function detail($encrypted_id, $runsheet_date)
    {

        $user_role = $this->session->userdata('role');
        $id_courier = base64_decode(urldecode($encrypted_id));
        $runsheet_date = base64_decode(urldecode($runsheet_date));
        $data['courier'] = $this->Checker_model->_get_data_courier($id_courier);
        $data['data_checkers_approve'] = $this->Checker_model->_get_data_approve($id_courier, $runsheet_date);
        $data['data_checkers_not_approve'] = $this->Checker_model->_get_data_not_approve($id_courier, $runsheet_date);
        $data['page_name'] = 'detail_courier';
        $data['runsheet_date'] = $runsheet_date;
        $data['role'] = $user_role;
        $data['hrs'] = $runsheet_date;
        $data['id_courier'] = $id_courier;
        $data['title'] = 'Detail Courier';
        $this->load->view('dashboard', $data);


        // var_dump($id_courier);
        // var_dump($encrypted_id);
    }

    public function change_status()
    {
        $id_checker = $this->input->post("ids");
        $id_courier = $this->input->post("id_courier");

        if ($this->Checker_model->_change_status($id_courier, $id_checker)) {
            $response = [
                'status' => 'success',
                'message' => 'Status POD berhasil diperbarui!'
            ];
        } else {

            $response = [
                'status' => 'danger',
                'message' => 'Status POD gagal diperbarui!'
            ];
        }

        echo json_encode($response);
    }
    public function change_status_approve()
    {
        $id_checker = $this->input->post("ids_tidak_sesuai");
        $id_courier = $this->input->post("id_courier");

        if ($this->Checker_model->_change_status_approve($id_courier, $id_checker)) {
            $response = [
                'status' => 'success',
                'message' => 'Status POD berhasil diperbarui!'
            ];
        } else {

            $response = [
                'status' => 'danger',
                'message' => 'Status POD gagal diperbarui!'
            ];
        }

        // var_dump($id_checker);

        echo json_encode($response);
    }



    public function getdatatables_checker()
    {
        $list = $this->Checker_model->getdatatables_checker();
        $data = array();
        $no = $this->input->post('start', true);
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row[] = '<small style="font-size:12px">' . $no . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->id_courier) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->courier_name) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->qty_awb) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->qty_sesuai) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->qty_tidak_sesuai) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->runsheet_date) . '</small>';
            $row[] = '
            <a href="' . base_url('admin/detail/' . urlencode(base64_encode($item->id_courier)) . '/' . urlencode(base64_encode($item->runsheet_date))) . '"  
                class="btn btn-dark waves-effect waves-light btn-sm me-1" 
                title="Detail" data-plugin="tippy" data-tippy-placement="top">
                <i class="fa fa-info-circle"> Detail</i>
            </a>';

            // $row[] = '<div class="form-button-action"> 
            // <button class="btn btn-link btn-simple-primary btn-lg" onclick="editSelling(
            //         ' . htmlspecialchars($item->product_id) . ',
            //         \'' . htmlspecialchars($item->product_name) . '\', 
            //         \'' . htmlspecialchars($item->product_qr) . '\',
            //         \'' . htmlspecialchars($item->product_size) . '\',
            //         \'' . htmlspecialchars($item->product_hpp) . '\',
            //         \'' . htmlspecialchars($item->source) . '\',
            //         \'' . htmlspecialchars($item->product_image) . '\',
            //         \'' . htmlspecialchars($item->product_id) . '\',
            //         \'' . htmlspecialchars($item->stock_id) . '\',
            //         \'' . htmlspecialchars($item->id_selling) . '\',
            //         \'' . htmlspecialchars($item->sub_total) . '\'
            //         )"
            //         data-bs-toggle="modal" data-bs-target="#ModalEditSelling">
            //         <i class="fa fa-edit"></i>
            //     </button>';           

            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->Checker_model->count_all_checker(),
            "recordsFiltered" => $this->Checker_model->count_filtered_checker(),
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

        $this->db->select('
        COUNT(*) as num_rows, 
        SUM(nominal_spending) as spending,                                    
        ');

        $this->db->from('spending');



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
            'spending' => htmlspecialchars($result->spending),

        ]);

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