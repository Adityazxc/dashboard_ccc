<?php
defined('BASEPATH') or exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\IOFactory;

class Upload extends CI_Controller
{
    var $customer_column_order = array(null, 'id', 'destination_code', 'awb_no', 'kpi', null); //set column field database for datatable orderable
    var $customer_column_search = array('destination_code', 'awb_no');
    var $customer_order = array('id' => 'DESC');

    public function __construct()
    {
        parent::__construct();
        // $this->load->model('Customer_model');
        $this->load->model('Leaderboard_model');
        $this->load->model('Checker_model');
        $this->load->library('session');
        $this->session->set_userdata('pages', 'ccc_role');
        $this->load->helper('url');

    }



    public function saveDataCTC()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        // Debugging: Log the received data
        error_log(print_r($data, true));

        if (is_array($data)) {
            // Simpan data ke database
            foreach ($data as $row) {
                $this->Customer_model->tambah($row);
            }

            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'Invalid data format']);
        }

        exit;
    }

    public function import_data()
{
    $upload_by = $this->session->userdata('id_user');
    $this->load->library('upload');
    $this->load->model('Checker_model');
    $this->load->model('Leaderboard_model');
    
    ini_set('memory_limit', '2G');
    ini_set('max_execution_time', 1800);
    
    $this->upload->initialize([
        'upload_path' => './uploads/excel',
        'allowed_types' => 'xlsx|xls|csv',
        'encrypt_name' => TRUE,
    ]);

    if (!$this->upload->do_upload('excel_file')) {
        $this->session->set_flashdata('notify', [
            'message' => 'File gagal diunggah!',
            'type' => 'warning'
        ]);
        redirect('admin');
    }

    $data = $this->upload->data();
    $file_path = $data['full_path'];
    $start_time = microtime(true);

    $spreadsheet = IOFactory::load($file_path);
    $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
    unset($sheetData[1]);
    
    $chunk_size = 2000;
    $temp_batch = [];
    $duplicate_count = 0; // Track duplikat
    
    $this->db->query('SET autocommit=0');
    $this->db->trans_start();

    foreach ($sheetData as $row) {
        $awb = trim($row['B']);
        if (empty($awb)) continue;       
        
        
        $awb = ltrim($awb, "'\"");

        $id_courier = $row['E'];
        if (empty($id_courier)) continue;

        $raw_status = trim(preg_replace('/[[:^print:]]/', '', $row['V']));
        $status_cod = ($raw_status === '') ? NULL : $raw_status;

        $address = preg_replace("/\s+/", " ", trim($row['J'] . ' ' . $row['K']));

        $link_maps = 'https://www.google.com/maps?q=' . $row['Z'] . ',' . $row['AA'];
        $default_image = 'public/img/Image-not-found.png';
        $photo_url = !empty($row['AB']) ? $row['AB'] : $default_image;
        $photo_pod_url = !empty($row['AD']) ? $row['AD'] : $default_image;

        $temp_batch[$awb] = [
            'awb' => $awb,
            'no_runsheet' => $row['C'],
            'id_courier' => $id_courier,
            'destination_code' => $row['G'],
            'receiver_name' => $row['I'],
            'receiver_address' => $address,
            'receiver_city' => $row['L'],
            'qty' => (int)$row['M'],
            'weight' => (float)$row['N'],
            'service' => $row['O'],
            'paymeny_type' => $row['Q'],
            'amount' => (int)$row['R'],
            'runsheet_date' => $this->normalizeDate($row['T']),
            'received_date' => $this->normalizeDate($row['U']),
            'status_cod' => $status_cod,
            'remarks' => $row['W'],
            'link_maps' => $link_maps,
            'url_photo' => $photo_url,
            'url_pod' => $photo_pod_url,
            'zone' => $row['AN'],
            'no_hrs' => $row['AO'] ?: NULL,
            'hrs_date' => $this->normalizeDate($row['AP']),
            'status_checker' => 'Sesuai',
            'create_date' => date('Y-m-d H:i:s'),
            'upload_by' => $upload_by,
            'status_via' => $row['Y'],
            'id_customers' => $row['D'],
        ];

        if (count($temp_batch) >= $chunk_size) {
            $this->_rewrite_and_insert_optimized($temp_batch);
            $temp_batch = [];
        }
    }

    if (!empty($temp_batch)) {
        $this->_rewrite_and_insert_optimized($temp_batch);
    }
    
    $this->db->trans_complete();
    $this->db->query('SET autocommit=1');
    
    $import_time = microtime(true) - $start_time;
    
    // Refresh MV
    $refresh_start = microtime(true);
    $this->Checker_model->refresh_mv_checker_summary();
    $this->Leaderboard_model->refresh_mv_leaderboard_summary();
    
    // CALCULATE LEADERBOARD POINTS
    $this->Leaderboard_model->calculate_and_insert_leaderboard();
    
    $refresh_time = microtime(true) - $refresh_start;
    $total_time = microtime(true) - $start_time;

    $this->session->set_flashdata('notify', [
        'message' => sprintf(
            'Import: %.1fs | MV: %.1fs | Total: %.1fs | Duplikat skip: %d',
            $import_time, $refresh_time, $total_time, $duplicate_count
        ),
        'type' => 'success'
    ]);

    redirect('admin');
}

private function _rewrite_and_insert_optimized($batch)
{
    if (empty($batch)) return;
    
    $awb_list = array_keys($batch);
    
    // Temp table strategy
    $this->db->query("CREATE TEMPORARY TABLE temp_awb_delete (awb VARCHAR(255) PRIMARY KEY)");
    
    $values = [];
    foreach ($awb_list as $awb) {
        $values[] = "(" . $this->db->escape($awb) . ")";
    }
    
    $chunks = array_chunk($values, 5000);
    foreach ($chunks as $chunk) {
        $this->db->query("INSERT INTO temp_awb_delete VALUES " . implode(',', $chunk));
    }
    
    $this->db->query("
        DELETE c FROM checker c
        INNER JOIN temp_awb_delete t ON c.awb = t.awb
        WHERE c.status_cod IS NULL OR c.status_cod = ''
    ");
    
    $this->db->query("DROP TEMPORARY TABLE temp_awb_delete");
    
    $this->Checker_model->_add_checker($batch);
}



    function normalizeDate($input)
    {
        $formats = [
            'd/m/Y H:i',
            'Y-m-d H:i:s',
            'm/d/Y h:i A',
            'd-m-Y H:i',
        ];

        foreach ($formats as $format) {
            $dt = DateTime::createFromFormat($format, $input);
            if ($dt && $dt->format($format) === $input) {
                return $dt->format('Y-m-d H:i:s');
            }
        }

        // Coba parse sebagai Excel float date (misal: 45156.739)
        if (is_numeric($input)) {
            // Excel date base: 1899-12-30
            $timestamp = ($input - 25569) * 86400; // 25569 = days since 1899-12-30 to 1970-01-01
            return date('Y-m-d H:i:s', $timestamp);
        }

        return null; // Gagal parsing
    }


    public function revision()
    {
        $awb = $this->input->post('awb');
        $reason = $this->input->post('reason');
        $id_courier = $this->input->post("id_courier_revision");
        $runsheet_date = $this->input->post("runsheet_date_revision");
        $no_runsheet = $this->input->post("no_runsheet_revision");


        $file = $_FILES['revision_img'];

        if ($file['name']) {
            $config['upload_path'] = './uploads/images_revision/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['max_size'] = 10048; // dalam KB (10MB)
            $config['file_name'] = 'revision_' . $awb . '_' . date('Y-m-d_H-i-s');


            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('revision_img')) {
                echo "Upload Error: " . $this->upload->display_errors();
                return;
            }

            $uploadData = $this->upload->data();
            $imgPath = 'uploads/images_revision/' . $uploadData['file_name'];

            //  CEK UKURAN FILE: jika >= 500 KB, baru kompres
            $fileSizeBytes = filesize($uploadData['full_path']); // dalam byte
            if ($fileSizeBytes >= 512000) { // 500 KB = 512000 byte
                // === COMPRESS IMAGE ===
                $compress_config['image_library'] = 'gd2';
                $compress_config['source_image'] = $uploadData['full_path'];
                $compress_config['create_thumb'] = FALSE;
                $compress_config['maintain_ratio'] = TRUE;
                $compress_config['quality'] = '60';
                $compress_config['width'] = 1280;
                $compress_config['height'] = 1280;
                $compress_config['overwrite'] = TRUE;

                $this->load->library('image_lib', $compress_config);
                $this->image_lib->initialize($compress_config);

                if (!$this->image_lib->resize()) {
                    echo "Compress Error: " . $this->image_lib->display_errors();
                    return;
                }
            }

            // Simpan ke database
            $data = [
                'reason_revision' => $reason,
                'url_revision' => $imgPath,
                'status_checker' => "Revisi",
            ];

            $this->Checker_model->_revision_image($awb, $data);
            // Refresh materialize
            $this->Checker_model->refresh_mv_checker_summary();



            $point_photo_pod = $this->Leaderboard_model->get_status_photo_pod($id_courier, $runsheet_date);


            if ($this->Leaderboard_model->update_poin_photo_pod($no_runsheet, $point_photo_pod)) {

                $this->session->set_flashdata('notify', [
                    'message' => 'Image berhasil direvisi.',
                    'type' => 'success'
                ]);
            } else {
                $this->session->set_flashdata('notify', [
                    'message' => 'Gagal mengubah poin POD',
                    'type' => 'warning'
                ]);
            }

            $this->Leaderboard_model->refresh_total_poin($no_runsheet);

        } else {
            $this->session->set_flashdata('notify', [
                'message' => 'Image gagal berhasil direvisi!',
                'type' => 'warning'
            ]);
        }
        redirect($_SERVER['HTTP_REFERER']);
    }



    public function getdatatables_upload_data()
    {
        //
        // echo $this->input->post('dateFrom');
        $list = $this->Upload_model->getdatatables_upload_data();
        $data = array();
        $no = $this->input->post('start', true);
        foreach ($list as $item) {
            $no++;

            $row = array();
            $row[] = '<small style="font-size:12px">' . $no . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->destination_code) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->awb_no) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->create_date) . '</small>';


            $data[] = $row;
        }


        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->Upload_model->count_all_upload_data(),
            "recordsFiltered" => $this->Upload_model->count_filtered_upload_data(),
            "data" => $data,
        );
        // output to json format
        echo json_encode($output);
    }

    public function getdatatables_detail_history()
    {
        $id_user = $this->input->post('id_user');
        $list = $this->History_model->getdatatables_detail_history($id_user);
        $data = array();
        $no = $this->input->post('start', true);
        foreach ($list as $item) {
            $no++;

            $row = array();
            $row[] = '<small style="font-size:12px">' . $no . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->awb_no) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->username) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->account_name) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->employee_position) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->start_date) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->destination_code) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->kecamatan) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->hub) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->sortir_number) . '</small>';

            $data[] = $row;
        }


        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->History_model->count_all_detail_history(),
            "recordsFiltered" => $this->History_model->count_filtered_detail_history($id_user),
            "data" => $data,
        );
        // output to json format
        echo json_encode($output);
    }

    public function summary_customer()
    {

        $dateFrom = $this->security->xss_clean($this->input->post('dateFrom'));
        $dateThru = $this->security->xss_clean($this->input->post('dateThru'));
        $id_user = $this->input->post('id_user');


        // $this->db->where('DATE(create_date) >=', $dateFrom);
        // $this->db->where('DATE(create_date) <=', $dateThru);
        $customers_status1 = $this->db->get('history')->num_rows();
        $this->db->where('id_user', $id_user);
        $this->db->where('DATE(end_date) >=', $dateFrom);
        $this->db->where('DATE(end_date) <=', $dateThru);

        // $this->db->where('status', 'Y');
        $this->db->where('id_user', $id_user);
        $this->db->where('DATE(end_date) >=', $dateFrom);
        $this->db->where('DATE(end_date) <=', $dateThru);
        $this->db->select_sum('qty');
        $query = $this->db->get('history');
        $total_qty = $query->row()->qty;
        if ($total_qty == null) {
            $total_qty = 0;
        }


        echo json_encode([
            'sum_status1' => htmlspecialchars($customers_status1),
            'sum_status2' => htmlspecialchars($total_qty),
        ]);
    }


    public function get_csrf()
    {
        //
        echo $this->security->get_csrf_hash();
    }

    public function get_csrf_json()
    {
        //
        $data['status'] = "Success";
        $data['get_csrf_hash'] = $this->security->get_csrf_hash();
        echo json_encode($data);
    }

}