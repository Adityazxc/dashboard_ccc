<?php

defined('BASEPATH') or exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Last_mile extends CI_Controller
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
        $this->load->model('Customers_lm_model');
        $this->load->model('Upload_model');
        $this->load->model('Lm_model');
        $this->load->model('Checker_model');
        $this->load->model('Users_model');
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->library('encryption');
        $this->session->set_userdata('pages', 'Last_mile_page');
        $this->db_checker = $this->load->database('checker_pod', TRUE);
        


        // Ambil data dari model dan simpan sebagai property
        $this->get_regional = json_encode($this->Lm_model->_get_regional());
        $this->get_origins = json_encode($this->Admin_model->_get_origins());
        $this->get_users_ccc = json_encode($this->Users_model->_get_user_());
        $this->get_service = json_encode($this->Lm_model->_get_service());
        $this->get_status_pod = json_encode($this->Lm_model->_get_status_pod());
        $this->get_customer_lm = json_encode($this->Customers_lm_model->_get_customer_name());
        $this->get_grouping_customer = json_encode($this->Customers_lm_model->_get_grouping_customer());
        $this->get_type_cust = json_encode($this->Lm_model->_get_type_cust());
    }

    public function safe_refresh_mv()
    {

        $this->Lm_model->refresh_mv_shipment_lm();

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
            $title = 'Dashboard Last Mile';
            $page_name = 'dashboard_last_mile';
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
                'get_type_cust' => $this->get_type_cust
            ];


            $this->load->view('dashboard', $data);
        } else {
            redirect('auth');
        }
    }
    public function status_shipment_lm()
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
            $title = 'Status Shipment Last Mile';
            $page_name = 'status_shipment_lm';
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
                'get_type_cust' => $this->get_type_cust
            ];


            $this->load->view('dashboard', $data);
        } else {
            redirect('auth');
        }
    }
    public function performance_shipment_lm()
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
            $title = 'Performance Shipment Last Mile';
            $page_name = 'performance_shipment_lm';
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
                'get_type_cust' => $this->get_type_cust
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
            $page_name = 'upload_lm';
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
                'get_type_cust' => $this->get_type_cust
            ];


            $this->load->view('dashboard', $data);
        } else {
            redirect('auth');
        }
    }


    public function getdatatables_last_mile()
    {
        $user_role = $this->session->userdata('role');
        $list = $this->Lm_model->getdatatables_lm();
        $data = array();
        $no = $this->input->post('start', true);
        foreach ($list as $item) {


            $no++;
            $row = array();
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($no) . '</small>';
            if ($item->customer_name == null || $item->customer_name == '-') {

                $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->cnote_cust_no) . '</small>';
            } else {
                $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->customer_name) . '</small>';

            }
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->pic_bdo) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->tgl) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->origin) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->zone_code) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->service) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->shipment) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->cust_type) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->cnote_pay_type) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->zone_delivery) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->pod_code) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->total_shipment) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->total_amount) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->total_weight) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->delivered_count) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->on_proses_count) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->return_count) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->cnote_cust_no) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->un_inbound) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->un_runsheet) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->open_pod) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->undelivered) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->customers_request) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->un_receiving) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->un_manifest) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->auto_close_irreg) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->auto_close_system) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->claim) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->irregularity) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->weight) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->first_attemp) . '</small>';

            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->Lm_model->count_all_lm(),
            "recordsFiltered" => $this->Lm_model->count_filtered_lm(),
            "data" => $data,
        );
        echo json_encode($output);
    }
    public function corp_stat_shp()
    {
        $user_role = $this->session->userdata('role');
        $list = $this->Lm_model->corp_stat_shp();
        $data = array();
        $no = $this->input->post('start', true);
        foreach ($list as $item) {


            $no++;
            $row = array();
            $row[] = '<small style="font-size:12px">' . $no . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->customer_name) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->cust_type) . '</small>';
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
            "recordsTotal" => $this->Lm_model->count_all_corp_stat_shp(),
            "recordsFiltered" => $this->Lm_model->count_filtered_corp_stat_shp(),
            "data" => $data,
        );
        echo json_encode($output);
    }
    public function corp_perf_shp()
    {
        $user_role = $this->session->userdata('role');
        $list = $this->Lm_model->corp_stat_shp();
        $data = array();
        $no = $this->input->post('start', true);
        foreach ($list as $item) {

            $no++;
            $row = array();
            $row[] = '<small style="font-size:12px">' . $no . '</small>';
            if ($item->customer_name == null || $item->customer_name == '') {

                $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->cnote_cust_no) . '</small>';
            } else {
                $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->customer_name) . '</small>';

            }
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->pic_bdo) . '</small>';
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
    public function summary_dashboard()
    {
        $post = $this->input->post();

        $result = $this->Lm_model->summary_dashboard($post);

        echo json_encode([
            'total_shipment' => (int) ($result['total_shipment'] ?? 0),
            'delivered_count' => (int) ($result['delivered_count'] ?? 0),
            'on_proses_count' => (int) ($result['on_proses_count'] ?? 0),
            'return_count' => (int) ($result['return_count'] ?? 0),
            'total_amount' => (float) ($result['total_amount'] ?? 0),
            'total_weight' => (float) ($result['total_weight'] ?? 0),
            'post' => $post
        ]);
    }
    public function getServiceChart()
    {
        $post = $this->input->post();
        $result = $this->Lm_model->getSourceService($post);

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
        $result = $this->Lm_model->getSourcePayTypeShipment($post);

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

    public function getDeliveryComparisonChart()
    {

        $post = $this->input->post();
        $result = $this->Lm_model->getDeliveryComparisonChart($post);

        $labels = [];
        $delivered = [];
        $onProses = [];

        foreach ($result as $row) {
            $labels[] = ['Delivered', 'On Proses'];
            $delivered[] = (int) $row->delivered_total;
            $onProses[] = (int) $row->on_proses_total;
        }

        echo json_encode([
            'success' => true,
            'labels' => $labels,
            'delivered' => $delivered,
            'onProses' => $onProses
        ]);
    }

    public function getTopCustomers()
    {

        $post = $this->input->post();
        $result = $this->Lm_model->getTopCustomers($post);

        $labels = [];
        $total_shipment = [];

        foreach ($result as $row) {
            $labels[] = $row->cust_industry;
            $total_shipment[] = (int) $row->total_shipment;
        }

        echo json_encode([
            'success' => true,
            'labels' => $labels,
            'total_shipment' => $total_shipment,

        ]);
    }
   
    public function export_data_last_mile()
    
{
    ini_set('memory_limit', '-1');
    set_time_limit(0);
    $post = $this->input->post();
    $data = $this->Lm_model->getDataNotApprove($post);

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // HEADER (TIDAK DIUBAH)
    $headers = [
        'Hari','Tgl','Tgl Im','Kode Cabang','Service','Area','Zona Delivery','Zone Code',
        'Cnote Pay Type','Shipment','Cnote Date','Cnote Origin','Cnote Destination',
        'Cnote No','Cnote Branch Id','Cnote Services Code','Cnote Qty','Cnote Weight',
        'Cnote Goods Descr','Cnote Goods Value','Cnote Amount','Cnote Refno','Cod Amount',
        'Cnote Crdate','Cnote Cancel','Status','Reason Code','Pod Code','Pod Receiver Reason','Irreg Code',
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
            $d['pod_status'] ?? '',
            $d['pod_code'] ?? '',
            $d['Pod_receiver_reason'] ?? '',
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
            $d['pic'] ?? '',
            $d['big_grouping_cust'] ?? '',
            $d['cnote_cust_no'] ?? '',
            $d['customer_name'] ?? '',
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
    $filename = date('Y-m-d') . ' Summary Last Mile.xlsx';

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment; filename=\"$filename\"");
    $writer->save('php://output');
}

    public function hehe()
    {
        $post = $this->input->post();
        $data = $this->Lm_model->getTopCustomers($post);
        echo json_encode($data);
    }

}