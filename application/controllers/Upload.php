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
    
        ini_set('memory_limit', '-1');
        set_time_limit(0);
    
        $this->upload->initialize([
            'upload_path' => './uploads/excel',
            'allowed_types' => 'xlsx|xls|csv',
            'encrypt_name' => TRUE,
        ]);
    
        if (!$this->upload->do_upload('excel_file')) {
            $this->session->set_flashdata('notify', ['message' => 'File gagal diunggah!', 'type' => 'warning']);
            redirect('last_mile/import');
        }
    
        $data       = $this->upload->data();
        $file_path  = $data['full_path'];
        $start_time = microtime(true);
    
        // ✅ Gunakan ReadFilter + chunk reader agar tidak load semua ke memory
        $reader = IOFactory::createReaderForFile($file_path);
        $reader->setReadDataOnly(true); // Tidak baca style/format → hemat memory
    
        $spreadsheet = $reader->load($file_path);
        $sheet       = $spreadsheet->getActiveSheet();
    
        // Ambil mapping data (tetap sama)
        $cust_map = [];
        foreach ($this->db->get('cus_lm')->result_array() as $c) {
            $cust_map[$c['account_number']] = [
                'cust_name'        => $c['cust_name'],
                'cust_industry'    => $c['cust_industry'],
                'big_grouping_cust'=> $c['big_grouping_cust'],
                'pic_bdo'          => $c['pic_bdo'],
            ];
        }
    
        $user_map = [];
        foreach ($this->db->get('checker_pod.users')->result_array() as $u) {
            $user_map[$u['username']] = $u['name'];
        }
    
        $pod_map = [];
        foreach ($this->db->get('pod_status')->result_array() as $p) {
            $pod_map[$p['pod_code']] = ['filter' => $p['filter'], 'pod_status' => $p['pod_status']];
        }
    
        $dest_map = [];
        foreach ($this->db->get('dest')->result_array() as $d) {
            $dest_map[$d['tariff_code']] = $d;
        }
    
        $this->db->query('SET autocommit=0');
        $this->db->trans_start();
    
        $chunk_size      = 500; // Turunkan chunk agar lebih aman
        $temp_batch      = [];
        $is_first_row    = true;
    
        // ✅ Iterasi per-baris, tidak load semua ke array
        foreach ($sheet->getRowIterator() as $rowObj) {
            if ($is_first_row) {
                $is_first_row = false;
                continue; // Skip header
            }
    
            $cellIter = $rowObj->getCellIterator();
            $cellIter->setIterateOnlyExistingCells(false);
    
            $row = [];
            foreach ($cellIter as $cell) {
                $row[$cell->getColumn()] = $cell->getValue();
            }
    
            $cnote_no = trim($row['N'] ?? '');
            if (empty($cnote_no)) continue;
    
            $cnote_no        = ltrim($cnote_no, "'\"");
            $cnote_branch_id = isset($row['O']) && trim($row['O']) !== '' ? $row['O'] : null;
            $cust_no         = (string) trim($row['Q'] ?? '');
            $cust            = $cust_map[$cust_no] ?? [];
            $pic_username    = $cust['pic_bdo'] ?? null;
            $origin          = $row['D'] ?? null;
            $pod_code        = $row['AC'] ?? null;
    
            $temp_batch[$cnote_no] = [
                'cnote_no'            => $cnote_no,
                'hari'                => $row['A'] ?? null,
                'tgl'                 => $this->normalizeDate($row['B'] ?? null),
                'tgl_lm'              => $this->normalizeDate($row['C'] ?? null),
                'origin'              => $origin,
                'service'             => $row['E'] ?? null,
                'zone_code'           => $row['H'] ?? null,
                'cnote_pay_type'      => $row['I'] ?? null,
                'shipment'            => $row['J'] ?? null,
                'cnot_date'           => $this->normalizeDate($row['K'] ?? null),
                'cnote_origin'        => $row['L'] ?? null,
                'cnote_destination'   => $row['M'] ?? null,
                'cnote_branch_id'     => $cnote_branch_id,
                'cnote_services_code' => $row['P'] ?? null,
                'cnote_cust_no'       => $cust_no,
                'cnote_qty'           => (int)   ($row['S'] ?? 0),
                'cnote_weight'        => (float)  ($row['T'] ?? 0),
                'cnote_goods_desc'    => $row['U'] ?? null,
                'cnote_goods_value'   => (float)  ($row['V'] ?? 0),
                'cnote_amount'        => (int)   ($row['W'] ?? 0),
                'cnote_refnoi'        => $row['X'] ?? null,
                'cod_amount'          => (int)   ($row['Y'] ?? 0),
                'cnote_crdate'        => $this->normalizeDate($row['Z'] ?? null),
                'cnote_cancel'        => $row['AA'] ?? null,
                'pod_code'            => $pod_code,
                'irreg_code'          => $row['AD'] ?? null,
                'lm_date'             => $this->normalizeDate($row['AE'] ?? null),
                'runsheet_date'       => $this->normalizeDate($row['AF'] ?? null),
                'pod_date'            => $this->normalizeDate($row['AG'] ?? null),
                'pod_delivered'       => $row['AH'] ?? null,
                'Pod_receiver_reason' => $row['AI'] ?? null,
                'pod_doc_no'          => $row['AJ'] ?? null,
                'pod_attempt'         => (int)   ($row['AK'] ?? 0),
                'sm_date'             => $this->normalizeDate($row['AL'] ?? null),
                'sm_origin'           => $row['AM'] ?? null,
                'sla_cust_group'      => $row['AN'] ?? null,
                'sla_due_date'        => $this->normalizeDate($row['AO'] ?? null),
                'manifest_date'       => $this->normalizeDate($row['AP'] ?? null),
                'receiving_date'      => $this->normalizeDate($row['AQ'] ?? null),
                'hvo_date'            => $this->normalizeDate($row['AR'] ?? null),
                'hvo_branch'          => $row['AS'] ?? null,
                'irreg_date'          => $this->normalizeDate($row['AT'] ?? null),
                'irreg_status'        => $row['AU'] ?? null,
                'irreg_status_date'   => $this->normalizeDate($row['AV'] ?? null),
                'cnote_branch_dest_id'=> $row['AW'] ?? null,
                'cust_name'           => $cust['cust_name'] ?? '-',
                'cust_industry'       => $cust['cust_industry'] ?? '-',
                'id_pic'              => $pic_username,
                'pic'                 => $user_map[$pic_username] ?? '-',
                'zona_delivery'       => $dest_map[$origin]['zona_delivery'] ?? '-',
                'three_letter_code'   => $dest_map[$origin]['three_letter_code'] ?? null,
                'sla'                 => $dest_map[$origin]['sla'] ?? null,
                'filter'              => $pod_map[$pod_code]['filter'] ?? null,
                'city_name'           => $dest_map[$origin]['city_name'] ?? null,
                'big_grouping_cust'   => $cust['big_grouping_cust'] ?? '-',
                'pod_status'          => $pod_map[$pod_code]['pod_status'] ?? null,
            ];
    
            if (count($temp_batch) >= $chunk_size) {
                $this->_rewrite_and_insert_optimized($temp_batch);
                $temp_batch = [];
                
                // ✅ Bebaskan memory cache PhpSpreadsheet
                $spreadsheet->garbageCollect();
            }
        }
    
        if (!empty($temp_batch)) {
            $this->_rewrite_and_insert_optimized($temp_batch);
        }
    
        // ✅ Bebaskan memory spreadsheet setelah selesai
        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);
    
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
                $total_time
                // $duplicate_count
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