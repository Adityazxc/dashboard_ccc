<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class Upload extends CI_Controller
{
    var $customer_column_order = array(null, 'id', 'destination_code', 'awb_no', 'kpi', null); //set column field database for datatable orderable
    var $customer_column_search = array('destination_code', 'awb_no');
    var $customer_order = array('id' => 'DESC');

    public function __construct()
    {
        parent::__construct();
        // $this->load->model('Customer_model');        
        $this->load->model('Checker_model');
        $this->load->model('Lm_model');

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
            redirect('last_mile/import');
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
            $cnote_no = trim($row['N']);
            if (empty($cnote_no))
                continue;


            $cnote_no = ltrim($cnote_no, "'\"");

            // $id_courier = $row['E'];
            // if (empty($id_courier)) continue;
            $cnote_branch_id = isset($row['O']) && trim($row['O']) !== ''
                ? $row['O']
                : null;

            $temp_batch[$cnote_no] = [

                'cnote_no' => $cnote_no, // Cnote No

                'hari' => $row['A'],
                'tgl' => $this->normalizeDate($row['B']),
                'tgl_lm' => $this->normalizeDate($row['C']),
                'origin' => $row['D'], // Kode Cabang
                'service' => $row['E'],
                'zone_code' => $row['H'],
                'cnote_pay_type' => $row['I'],
                'shipment' => $row['J'],
                'cnot_date' => $this->normalizeDate($row['K']),
                'cnote_origin' => $row['L'],
                'cnote_destination' => $row['M'],
                'cnote_branch_id' => $cnote_branch_id,
                'cnote_services_code' => $row['P'],
                'cnote_cust_no' => $row['Q'],
                'cnote_qty' => (int) $row['S'],
                'cnote_weight' => (float) $row['T'],
                'cnote_goods_desc' => $row['U'],
                'cnote_goods_value' => (float) $row['V'],
                'cnote_amount' => (int) $row['W'],
                'cnote_refnoi' => $row['X'],
                'cod_amount' => (int) $row['Y'],
                'cnote_crdate' => $this->normalizeDate($row['Z']),                                                                
                'cnote_cancel' => $row['AA'],
                'pod_code' => $row['AC'],
                'irreg_code' => $row['AD'],
                'lm_date' => $this->normalizeDate($row['AE']),
                'runsheet_date' => $this->normalizeDate($row['AF']),
                'pod_date' => $this->normalizeDate($row['AG']),
                'pod_delivered' => $row['AH'],
                'pod_doc_no' => $row['AI'],
                'pod_attempt' => (int) $row['AJ'],
                'sm_date' => $this->normalizeDate($row['AK']),
                'sm_origin' => $row['AL'],
                'sla_cust_group' => $row['AM'],
                'sla_due_date' => $this->normalizeDate($row['AN']),
                'manifest_date' => $this->normalizeDate($row['AO']),
                'receiving_date' => $this->normalizeDate($row['AP']),
                'hvo_date' => $this->normalizeDate($row['AQ']),
                'hvo_branch' => $row['AR'],
                'irreg_date' => $this->normalizeDate($row['AS']),
                'irreg_status' => $row['AT'],
                'irreg_status_date' => $this->normalizeDate($row['AU']),
                'cnote_branch_dest_id' => $row['AV'],
                
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
        $this->Lm_model->refresh_mv_shipment_lm();



        // CALCULATE LEADERBOARD POINTS


        $refresh_time = microtime(true) - $refresh_start;
        $total_time = microtime(true) - $start_time;

        $this->session->set_flashdata('notify', [
            'message' => sprintf(
                'Import: %.1fs | MV: %.1fs | Total: %.1fs | Duplikat skip: %d',
                $import_time,
                $refresh_time,
                $total_time,
                $duplicate_count
            ),
            'type' => 'success'
        ]);

        redirect('last_mile/import');
    }

    private function _rewrite_and_insert_optimized($batch)
    {
        if (empty($batch))
            return;

        $keys = array_keys(current($batch));
        $columns = implode(',', $keys);

        $values = [];
        foreach ($batch as $row) {
            $escaped = array_map([$this->db, 'escape'], array_values($row));
            $values[] = "(" . implode(',', $escaped) . ")";
        }

        $sql = "
        REPLACE INTO shipment_lm ($columns)
        VALUES " . implode(',', $values);

        $this->db->query($sql);
    }





    private function normalizeDate($value)
    {
        if (empty($value)) {
            return null;
        }

        // Kalau numeric berarti format serial Excel
        if (is_numeric($value)) {
            return Date::excelToDateTimeObject($value)->format('Y-m-d H:i:s');
        }

        // Kalau string biasa
        $timestamp = strtotime($value);
        if ($timestamp !== false) {
            return date('Y-m-d H:i:s', $timestamp);
        }

        return null;
    }









}