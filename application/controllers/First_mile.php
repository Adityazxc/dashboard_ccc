<?php

defined('BASEPATH') or exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class First_mile extends CI_Controller
{
    private $get_regional;
    private $get_origins;
    private $get_users_ccc;
    private $get_service;
    private $get_status_pod;
    private $get_customer_lm;
    private $get_type_cust;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Admin_model');
        $this->load->model('Upload_model');
        $this->load->model('Lm_model');
        $this->load->model('Fm_model');
        $this->load->model('Customers_fm_model');
        $this->load->model('Customers_lm_model');
        $this->load->model('Checker_model');
        $this->load->model('Users_model');
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->library('encryption');
        $this->session->set_userdata('pages', 'First_mile_page');
        $this->db_checker = $this->load->database('checker_pod', TRUE);
        // Ambil data dari model dan simpan sebagai property
        $this->get_regional = json_encode($this->Lm_model->_get_regional());
        $this->get_origins = json_encode($this->Fm_model->_get_origins());
        $this->get_users_ccc = json_encode($this->Users_model->_get_user_());
        $this->get_service = json_encode($this->Lm_model->_get_service());
        $this->get_status_pod = json_encode($this->Lm_model->_get_status_pod());
        $this->get_customer_lm = json_encode($this->Customers_lm_model->_get_customer_name());
        $this->get_type_cust = json_encode($this->Lm_model->_get_type_cust());
        $this->get_grouping_customer = json_encode($this->Customers_fm_model->_get_grouping_customer());
        $this->get_cnote_cust_no = json_encode($this->Fm_model->_get_cnote_cust_no());
    }
    public function safe_refresh_mv()
    {

        $this->Fm_model->refresh_mv_shipment_fm();

    }

    public function index()
    {
        $user_role = $this->session->userdata('role');
        $password = $this->session->userdata('password');
        $zone = $this->session->userdata('location');
        $origin = $this->Checker_model->_get_origin($zone);
    

        if ($password == "e10adc3949ba59abbe56e057f20f883e") {
            redirect('reset_password/input_password');
        } else if (
            $this->session->userdata('logged_in') && (
                $user_role == "CCC"
                || $user_role == "Super User"
                || $user_role == "HC"


            )
        ) {
            $title = 'Dashboard Fast Mile';
            $page_name = 'dashboard_first_mile';
            $data = [
                'title' => $title,
                'page_name' => $page_name,
                'role' => $user_role,
                'zone' => $zone,
                'origin' => $origin->origin_code,
                'get_regional' => $this->get_regional,
                'get_origins' => $this->get_origins,
                'get_users_ccc' => $this->get_users_ccc,
                'get_service' => $this->get_service,
                'get_status_pod' => $this->get_status_pod,
                'get_customer_lm' => $this->get_customer_lm,
                'get_grouping_customer' => $this->get_grouping_customer,
                'get_type_cust' => $this->get_type_cust,
                'get_cnote_cust_no' => $this->get_cnote_cust_no
            ];


            $this->load->view('dashboard', $data);
        } else {
            redirect('auth');
        }
    }
    public function import()
    {
        $user_role = $this->session->userdata('role');
        $password = $this->session->userdata('password');
        $zone = $this->session->userdata('location');
        $origin = $this->Checker_model->_get_origin($zone);




        if ($password == "e10adc3949ba59abbe56e057f20f883e") {
            redirect('reset_password/input_password');
        } else if (
            $this->session->userdata('logged_in') && (
                $user_role == "CCC"
                || $user_role == "Super User"
                || $user_role == "HC"


            )
        ) {

            $title = 'Upload Data Last Mile';
            $page_name = 'upload_fm';
            $data = [
                'title' => $title,
                'page_name' => $page_name,
                'role' => $user_role,
                'zone' => $zone,
                'origin' => $origin->origin_code,
                'get_regional' => $this->get_regional,
                'get_origins' => $this->get_origins,
                'get_users_ccc' => $this->get_users_ccc,
                'get_service' => $this->get_service,
                'get_status_pod' => $this->get_status_pod,
                'get_customer_lm' => $this->get_customer_lm,
                'get_grouping_customer' => $this->get_grouping_customer,
                'get_type_cust' => $this->get_type_cust,
                'get_cnote_cust_no' => $this->get_cnote_cust_no
            ];


            $this->load->view('dashboard', $data);
        } else {
            redirect('auth');
        }
    }
    public function status_shipment_fm()
    {
        $user_role = $this->session->userdata('role');
        $password = $this->session->userdata('password');
        $zone = $this->session->userdata('location');
        $origin = $this->Checker_model->_get_origin($zone);

        if ($password == "e10adc3949ba59abbe56e057f20f883e") {
            redirect('reset_password/input_password');
        } else if (
            $this->session->userdata('logged_in') && (
                $user_role == "CCC"
                || $user_role == "Super User"
                || $user_role == "HC"


            )
        ) {
            $title = 'Status Shipment First Mile';
            $page_name = 'status_shipment_fm';
            $data = [
                'title' => $title,
                'page_name' => $page_name,
                'role' => $user_role,
                'zone' => $zone,
                'origin' => $origin->origin_code,
                'get_regional' => $this->get_regional,
                'get_origins' => $this->get_origins,
                'get_users_ccc' => $this->get_users_ccc,
                'get_service' => $this->get_service,
                'get_status_pod' => $this->get_status_pod,
                'get_customer_lm' => $this->get_customer_lm,
                'get_grouping_customer' => $this->get_grouping_customer,
                'get_type_cust' => $this->get_type_cust,
                'get_cnote_cust_no' => $this->get_cnote_cust_no
            ];


            $this->load->view('dashboard', $data);
        } else {
            redirect('auth');
        }
    }

    public function performance_shipment_fm()
    {
        $user_role = $this->session->userdata('role');
        $password = $this->session->userdata('password');
        $zone = $this->session->userdata('location');
        $origin = $this->Checker_model->_get_origin($zone);
    

        if ($password == "e10adc3949ba59abbe56e057f20f883e") {
            redirect('reset_password/input_password');
        } else if (
            $this->session->userdata('logged_in') && (
                $user_role == "CCC"
                || $user_role == "Super User"
                || $user_role == "HC"


            )
        ) {
            $title = 'Performance Shipment First Mile';
            $page_name = 'performance_shipment_fm';
            $data = [
                'title' => $title,
                'page_name' => $page_name,
                'role' => $user_role,
                'zone' => $zone,
                'origin' => $origin->origin_code,
                'get_regional' => $this->get_regional,
                'get_origins' => $this->get_origins,
                'get_users_ccc' => $this->get_users_ccc,
                'get_service' => $this->get_service,
                'get_status_pod' => $this->get_status_pod,
                'get_customer_lm' => $this->get_customer_lm,
                'get_grouping_customer' => $this->get_grouping_customer,
                'get_type_cust' => $this->get_type_cust,
                'get_cnote_cust_no' => $this->get_cnote_cust_no
            ];


            $this->load->view('dashboard', $data);
        } else {
            redirect('auth');
        }
    }

    public function getdatatables_first_mile()
    {
        $user_role = $this->session->userdata('role');
        $list = $this->Fm_model->getdatatables_fm();
        $data = array();
        $no = $this->input->post('start', true);
        foreach ($list as $item) {

            $no++;
            $row = array();
        
            $row[] = '<small style="font-size:12px">' . $no . '</small>';
        
            
            $row[] = $item->cust_name;        
            $row[] = $item->pic_bdo;          
            $row[] = $item->tgl;              
            $row[] = $item->origin;           
            $row[] = $item->zone_code;        
            $row[] = $item->service;          
            $row[] = $item->shipment;         
            $row[] = $item->cnote_pay_type;   
            $row[] = $item->zone_delivery;    
            $row[] = $item->pod_code;         
            $row[] = $item->total_shipment;   
            $row[] = $item->total_amount;     
            $row[] = $item->total_weight;     
            $row[] = $item->delivered_count;  
            $row[] = $item->on_proses_count;  
            $row[] = $item->return_count;     
            $row[] = $item->cnote_cust_no;    
            $row[] = $item->un_inbound;       
            $row[] = $item->un_runsheet;      
            $row[] = $item->open_pod;         
            $row[] = $item->undelivered;      
            $row[] = $item->customers_request;
            $row[] = $item->un_receiving;     
            $row[] = $item->un_manifest;      
            $row[] = $item->auto_close_irreg; 
            $row[] = $item->auto_close_system;
            $row[] = $item->claim;            
            $row[] = $item->irregularity;     
            $row[] = $item->weight;           
            $row[] = $item->first_attemp;     
        
            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->Fm_model->count_all_fm(),
            "recordsFiltered" => $this->Fm_model->count_filtered_fm(),
            "data" => $data,
        );
        echo json_encode($output);
    }


    public function summary_dashboard()
    {
        $post = $this->input->post();
        $result = $this->Fm_model->summary_dashboard($post);
        


    
        echo json_encode([
            'total_shipment' => (int) ($result['total_shipment'] ?? 0),
            'delivered_count' => (int) ($result['delivered_count'] ?? 0),
            'on_proses_count' => (int) ($result['on_proses_count'] ?? 0),            
            'total_amount' => (float) ($result['total_amount'] ?? 0),
            
            'post' => $post
        ]);
    }
    
    public function corp_stat_shp()
    {
        $user_role = $this->session->userdata('role');
        $list = $this->Fm_model->corp_stat_shp();
        $data = array();
        $no = $this->input->post('start', true);
        foreach ($list as $item) {


            $no++;
            $row = array();
            $row[] = '<small style="font-size:12px">' . $no . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->cnote_cust_no) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->cust_name) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->delivered_count) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->un_inbound) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->un_runsheet) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->open_pod) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->undelivered) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->customers_request) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->irregularity) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->return_count) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->un_receiving) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->un_manifest) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->auto_close_irreg) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->auto_close_system) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->claim) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->total_shipment) . '</small>';







            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->Fm_model->count_all_corp_stat_shp(),
            "recordsFiltered" => $this->Fm_model->count_filtered_corp_stat_shp(),
            "data" => $data,
        );
        echo json_encode($output);
    }
    public function corp_perf_shp()
    {
        $user_role = $this->session->userdata('role');
        $list = $this->Fm_model->corp_stat_shp();
        $data = array();
        $no = $this->input->post('start', true);
        foreach ($list as $item) {

            $no++;
            $row = array();
            $row[] = '<small style="font-size:12px">' . $no . '</small>';
            if ($item->cust_name == null || $item->cust_name == '-') {

                $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->cnote_cust_no) . '</small>';
            } else {
                $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->cust_name) . '</small>';

            }
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->cnote_cust_no) . '</small>';
            // $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->pic_bdo) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->delivered_count) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->un_inbound) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->un_runsheet) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->open_pod) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->total_shipment) . '</small>';






            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->Lm_model->count_all_corp_stat_shp(),
            "recordsFiltered" => $this->Lm_model->count_filtered_corp_stat_shp(),
            "data" => $data,
        );
        echo json_encode($output);
    }
    public function getServiceChart()
    {
        $post = $this->input->post();
        $result = $this->Fm_model->getSourceService($post);

        $labels = [];
        $counts = [];

        foreach ($result as $row) {
            $labels[] = $row->service;
            $counts[] = (int) $row->total;
        }

        echo json_encode([
            'success' => true,
            'serviceLabels' => $labels,
            'serviceCounts' => $counts
        ]);
    }
   
    public function getPayTypeChart()
    {

        $post = $this->input->post();
        $result = $this->Fm_model->getSourcePayTypeShipment($post);

        $labels = [];
        $counts = [];

        foreach ($result as $row) {
            $labels[] = $row->cnote_pay_type;
            $counts[] = (int) $row->total;
        }

        echo json_encode([
            'success' => true,
            'PayTypeLabels' => $labels,
            'PayTypeCounts' => $counts
        ]);
    }

    public function getTopCustomers()
    {

        $post = $this->input->post();
        $result = $this->Fm_model->getTopCustomers($post);

        // print_r($result);

        $labels = [];
        $total_shipment = [];

        foreach ($result as $row) {
            $labels[] = $row->segmentasi;
            $total_shipment[] =  $row->total_shipment;
        }

        echo json_encode([
            'success' => true,
            'labels' => $labels,
            'total_shipment' => $total_shipment,

        ]);
    }
    public function import_data()
    {
        $this->load->library('upload');
    
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
    
        $reader = IOFactory::createReaderForFile($file_path);
        $reader->setReadDataOnly(true);
    
        $spreadsheet = $reader->load($file_path);
        $sheet = $spreadsheet->getActiveSheet();
    
        $chunk_size = 500;
        $temp_batch = [];
    
        $this->db->query("SET autocommit=0");
        $this->db->query("SET unique_checks=0");
        $this->db->query("SET foreign_key_checks=0");
    
        $this->db->trans_start();
    
        foreach ($sheet->getRowIterator(2) as $row) {
    
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
    
            $cells = [];
    
            foreach ($cellIterator as $cell) {
                $cells[] = $cell->getValue();
            }
    
            $cnote_no = trim($cells[12]);
    
            if (empty($cnote_no)) continue;
    
            $cnote_no = ltrim($cnote_no, "'\"");
    
            $temp_batch[] = [
    
                'hari_rec' => $cells[0],
                'tgl' => $this->normalizeDate($cells[1]),
                'kode_cabang' => $cells[2],
                'service' => $cells[3],
                'area' => $cells[4],
                'zona_delivery' => $cells[5],
                'zone_code' => $cells[6],
    
                'cnote_pay_type' => $cells[7],
                'shipment' => $cells[8],
                'cnote_origin' => $cells[9],
                'cnote_destination' => $cells[10],
                'cnote_branch_dest_id' => $cells[11],
    
                'cnote_no' => $cells[12],
                'cnote_branch_id' => $cells[13],
    
                'cnote_date' => $this->normalizeDate($cells[14]),
                'cnote_datetime' => $this->normalizeDate($cells[15]),
    
                'cnote_services_code' => $cells[16],
                'cnote_cust_no' => ($cells[17] == "") ? null : $cells[17],
    
                'cnote_shipper_name' => $cells[18],
                'cnote_shipper_addres' => $cells[19],
                'normalized_address' => $this->normalize_address($cells[19]),
    
                'cnote_qty' => $cells[20],
                'cnote_weight' => $cells[21],
    
                'cnote_goods_descr' => $cells[22],
                'cnote_goods_value' => $cells[23],
                'cnote_amount' => $cells[24],
    
                'cnote_refno' => $cells[26],
                'cod_amount' => $cells[27],
    
                'cnote_crdate' => $this->normalizeDate($cells[28]),
                'cnote_cancel' => $cells[29],
                'status' => $cells[30],
    
                'pod_code' => $cells[31],
                'irreg_code' => $cells[32],
    
                'im_date' => $this->normalizeDate($cells[33]),
                'runsheet_date' => $this->normalizeDate($cells[34]),
                'tgl_im' => $cells[35],
    
                'pod_date' => $this->normalizeDate($cells[36]),
                'pod_delivered' => $this->normalizeDate($cells[37]),
                'pod_doc_no' => $cells[38],
                'pod_attempt' => $cells[39],
    
                'sm_date' => $this->normalizeDate($cells[40]),
                'sm_origin' => $cells[41],
    
                'sla_cust_group' => $cells[42],
                'sla_due_date' => $this->normalizeDate($cells[43]),
    
                'manifest_date' => $this->normalizeDate($cells[44]),
                'receiving_date' => $this->normalizeDate($cells[45]),
    
                'hvo_date' => $this->normalizeDate($cells[46]),
                'hvo_branch' => $cells[47],
    
                'irreg_date' => $this->normalizeDate($cells[48]),
                'irreg_status' => $cells[49],
                'irreg_status_date' => $this->normalizeDate($cells[50]),
    
                'key_search' => $cells[17]."_".$cells[18]
            ];
    
            if (count($temp_batch) >= $chunk_size) {
    
                $this->insert_batch_on_duplicate('shipment_fm', $temp_batch);
    
                $temp_batch = [];
            }
        }
    
        if (!empty($temp_batch)) {
    
            $this->insert_batch_on_duplicate('shipment_fm', $temp_batch);
        }
    
        $this->db->trans_complete();
    
        $this->db->query("SET autocommit=1");
        $this->db->query("SET unique_checks=1");
        $this->db->query("SET foreign_key_checks=1");
    
        redirect('first_mile/import');
    }
    private function insert_batch_on_duplicate($table, $data)
{
    if (empty($data)) return;

    $columns = array_keys($data[0]);
    $values = [];
    $update = [];

    foreach ($columns as $col) {
        $update[] = "$col = VALUES($col)";
    }

    foreach ($data as $row) {
        $escaped = array_map([$this->db, 'escape'], array_values($row));
        $values[] = "(" . implode(",", $escaped) . ")";
    }

    $sql = "
        INSERT INTO $table (" . implode(",", $columns) . ")
        VALUES " . implode(",", $values) . "
        ON DUPLICATE KEY UPDATE " . implode(",", $update);

    $this->db->query($sql);
}

    function normalize_address($alamat)
    {
        $alamat = strtolower($alamat);

        $replace = [
            'jalan' => 'jl',
            'jln' => 'jl',
            'jl.' => 'jl',
        ];

        $alamat = str_replace(array_keys($replace), $replace, $alamat);

        $alamat = preg_replace('/\s+/', ' ', $alamat);

        return trim($alamat);
    }


    private function normalizeDate($value)
    {
        if (empty($value))
            return null;

        if (is_numeric($value)) {
            return Date::excelToDateTimeObject($value)->format('Y-m-d H:i:s');
        }

        $timestamp = strtotime($value);

        if ($timestamp !== false) {
            return date('Y-m-d H:i:s', $timestamp);
        }

        return null;
    }

    public function hehe()
    {
        $result = $this->Fm_model->hehe();
        print_r($result);

    }

    public function get_cust_mp()
    {
        $result = $this->Fm_model->get_cust_mp();
        foreach ($result as $row) {
            echo "Customer: " . $row->cust_key . " - " . $row->source . "<br>";
        }

        // print_r($result);
    }

    public function export_data_first_mile()
    {
        $post = $this->input->post();
        $data = $this->Fm_model->getDataNotApprove($post);
    
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
    
        // HEADER (TIDAK DIUBAH)
        $headers = [
            'Hari','Tgl','Tgl Im','Kode Cabang','Service','Area','Zona Delivery','Zone Code',
            'Cnote Pay Type','Shipment','Cnote Date','Cnote Origin','Cnote Destination',
            'Cnote No','Cnote Branch Id','Cnote Services Code','Cnote Qty','Cnote Weight',
            'Cnote Goods Descr','Cnote Goods Value','Cnote Amount','Cnote Refno','Cod Amount',
            'Cnote Crdate','Cnote Cancel','Status','Reason Code','Pod Code','Irreg Code',
            'Im Date','Runsheet Date','Pod Date','Status Shipment','Pod Doc No','Pod Attempt',
            'Sm Date','Sm Origin','Sla Due Date','Manifest Date','Receiving Date','Hvo Date',
            'Hvo Branch','Irreg Date','Irreg Status','Irreg Status Date',
            'Cnote Branch Dest Id','PIC','Cust Group','Cnote Cust','Customer Name',
            '1st Attempt','Carrier','3 LC','Cust Industry'
        ];
    
        $sheet->fromArray($headers, NULL, 'A1');
    
        // DATA (URUTAN HARUS SAMA PERSIS)
        $rows = [];
        foreach ($data as $d) {
            $rows[] = [
                $d['Hari'] ?? '',
                $d['Tgl'] ?? '',
                $d['tgl_lm'] ?? '',
                $d['origin'] ?? '',
                $d['service'] ?? '',
                $d['city_name'] ?? '',
                $d['zona_delivery'] ?? '',
                $d['zone_code'] ?? '',
                $d['cnote_pay_type'] ?? '',
                $d['shipment'] ?? '',
                $d['cnot_date'] ?? '',
                $d['cnote_origin'] ?? '',
                $d['cnote_destination'] ?? '',
                "'" . ($d['cnote_no'] ?? ''),
                $d['cnote_branch_id'] ?? '',
                $d['cnote_services_code'] ?? '',
                $d['cnote_qty'] ?? '',
                $d['cnote_weight'] ?? '',
                $d['cnote_goods_desc'] ?? '',
                $d['cnote_goods_value'] ?? '',
                $d['cnote_amount'] ?? '',
                $d['cnote_refnoi'] ?? '',
                $d['cod_amount'] ?? '',
                $d['cnote_crdate'] ?? '',
                $d['cnote_cancel'] ?? '',
                $d['filter'] ?? '',
                $d['reason_code'] ?? '',
                $d['pod_code'] ?? '',
                $d['irreg_code'] ?? '',
                $d['lm_date'] ?? '',
                $d['runsheet_date'] ?? '',
                $d['pod_date'] ?? '',
                $d['status_shipment'] ?? '',
                $d['pod_doc_no'] ?? '',
                $d['pod_attempt'] ?? '',
                $d['sm_date'] ?? '',
                $d['sm_origin'] ?? '',
                $d['sla_due_date'] ?? '',
                $d['manifest_date'] ?? '',
                $d['receiving_date'] ?? '',
                $d['hvo_date'] ?? '',
                $d['hvo_branch'] ?? '',
                $d['irreg_date'] ?? '',
                $d['irreg_status'] ?? '',
                $d['irreg_status_date'] ?? '',
                $d['cnote_branch_dest_id'] ?? '',
                $d['pic_bdo'] ?? '',
                $d['big_grouping_cust'] ?? '',
                $d['cnote_cust_no'] ?? '',
                $d['cust_name'] ?? '',
                $d['sm_date'] ?? '',
                (isset($d['carrier']) && $d['carrier'] < 0) 
            ? 'Over Time SLA' 
            : 'On SLA',
                $d['three_letter_code'] ?? '',
                $d['cust_industry'] ?? ''
            ];
            // print_r($rows);
        }
        // INSERT DATA SEKALI (INI YANG NGEGAS ⚡)
        $sheet->fromArray($rows, NULL, 'A2');   
    
        $writer = new Xlsx($spreadsheet);
        $filename = date('Y-m-d') . ' Summary First Mile.xlsx';
    
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        $writer->save('php://output');
    }


}