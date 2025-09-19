<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Pod extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();


        // $this->load->model('User_model');        
        $this->load->model('Courier_model');
        $this->load->model('Checker_model');
        $this->load->model('Admin_model');
        $this->load->model('Leaderboard_model');
        $this->load->model('Pod_model');
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('User_model');
        $this->load->library('encryption');
        $this->session->set_userdata('pages', 'pod_page');
    }

    public function index()
    {
        $user_role = $this->session->userdata('role');
        $password = $this->session->userdata('password');
        $zone = $this->session->userdata('location');
        $origin = $this->Checker_model->_get_origin($zone);
        $get_origins = json_encode($this->Admin_model->_get_origins());
        $get_origins_array = json_decode($get_origins, true);


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
            )
        ) {
            $data['title'] = 'Dashboard Admin';
            $data['page_name'] = 'list_pod';
            $data['get_origins'] = $get_origins;
            $data['role'] = $user_role;
            $data['zone'] = $zone;
            $data['origin'] = $origin->origin_code;
            // var_dump($origin);

            $this->load->view('dashboard', $data);
        } else {
            redirect('auth');
        }
    }
    public function summary_dashboard()
    {
        $dateFrom = $this->input->post('dateFrom');
        $dateThru = $this->input->post('dateThru');
        $origin = $this->input->post('origin', TRUE);
        $zone = $this->input->post('zone', TRUE);

        $this->db->select("
        sum(cn.minus_cod) as minus_cod,
        sum(cn.cod_paid) as cod_paid,
        sum(cn.total_paid_cod) as total_paid_cod,
        sum(cn.transfer) as transfer,
       
        
        ");
        if (!empty($origin)) {

            $this->db->where('z.origin_code', $origin);
        }

        if (!empty($zone)) {

            $this->db->where('z.zone_code', $zone);
        }
        $this->db->from("checker_notes cn");
        $this->db->join("mv_checker_summary mv", "mv.no_runsheet=cn.no_runsheet", "left");
        $this->db->join("zone z", "z.zone_code=mv.zone", "left");
        $query = $this->db->get();
        $result = $query->row();


        echo json_encode([
            'minus_cod' => (int) $result->minus_cod,
            'cod_paid' => (int) $result->cod_paid,
            'total_paid_cod' => (int) $result->total_paid_cod,
            'transfer' => (int) $result->transfer
        ]);
    }

    public function dashboard_pod()
    {
        $user_role = $this->session->userdata('role');
        $password = $this->session->userdata('password');
        $zone = $this->session->userdata('location');
        $origin = $this->Checker_model->_get_origin($zone);
        $get_origins = json_encode($this->Admin_model->_get_origins());
        $get_origins_array = json_decode($get_origins, true);


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
            )
        ) {
            $data['title'] = 'Dashboard POD';
            $data['page_name'] = 'dashboard_pod';
            $data['get_origins'] = $get_origins;
            $data['role'] = $user_role;
            $data['zone'] = $zone;
            $data['origin'] = $origin->origin_code;
            // var_dump($origin);

            $this->load->view('dashboard', $data);
        } else {
            redirect('auth');
        }
    }



    public function get_zone()
    {
        $origin = $this->input->post('origin'); // Ambil kategori dari AJAX       
        $zones = $this->Admin_model->_get_zone($origin); // Ambil case berdasarkan kategori

        echo json_encode($zones); // Kembalikan sebagai JSON
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
    public function detail_pod()
    {
        $data['page_name'] = 'detail_pod';
        $user_role = $this->session->userdata('role');
        $data['title'] = "Detail POD";
        $data['id_user'] = $this->session->userdata('id_user');
        $data['role'] = $user_role;
        $data['mode'] = "readonly";
        $data_courier = $this->get_data_courier();
        $data['data_courier'] = $data_courier;

        $this->load->view('dashboard', $data);
    }

    public function get_data_courier()
    {
        $data_courier = $this->Courier_model->_get_data_courier();

        // return var_dump($customers);
        return $data_courier;
    }

    public function edit_detail_pod($encrypted_id, $no_runsheet)
    {

        $id_chechker_notes = base64_decode(urldecode($encrypted_id));
        $no_runsheet = base64_decode(urldecode($no_runsheet));
        $get_cod_pod = $this->Pod_model->get_cod_pod($no_runsheet);
        $data['page_name'] = 'detail_pod';
        $user_role = $this->session->userdata('role');
        $data['title'] = "Edit Detail POD";
        $data['id_user'] = $this->session->userdata('id_user');
        $data['role'] = $user_role;
        $data['mode'] = "edit";
        $data['get_cod_pod'] = $get_cod_pod;

        $this->load->view('dashboard', $data);

    }
    public function read_detail_pod($encrypted_id, $no_runsheet)
    {

        $id_chechker_notes = base64_decode(urldecode($encrypted_id));
        $no_runsheet = base64_decode(urldecode($no_runsheet));
        $get_cod_pod = $this->Pod_model->get_cod_pod($no_runsheet);
        $data['page_name'] = 'detail_pod';
        $user_role = $this->session->userdata('role');
        $data['title'] = "Detail POD";
        $data['id_user'] = $this->session->userdata('id_user');
        $data['role'] = $user_role;
        $data['mode'] = "read";
        $data['get_cod_pod'] = $get_cod_pod;

        $this->load->view('dashboard', $data);

    }



    public function getSourceDataMultiple()
    {
        $year = $this->input->post('year') ?? date('Y'); // Default tahun sekarang
        $origin = $this->input->post('origin');
        $zone = $this->input->post('zone');
        $source_data = $this->Pod_model->getSourceDataMultiple($year, $origin, $zone);

        $dataBySource = [];
        $allMonths = range(1, 12);

        foreach ($source_data as $data) {
            $source = $data['status_checker'];
            $month = (int) $data['month'];
            $count = (int) $data['count'];

            if (!isset($dataBySource[$source])) {
                $dataBySource[$source] = array_fill(1, 12, 0); // Isi default 0 untuk semua bulan
            }

            $dataBySource[$source][$month] = $count;
        }


        echo json_encode([
            'success' => true,
            'dataBySource' => $dataBySource,
            'months' => ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]
        ]);
    }

    public function getSourceData()
    {
        $dateFrom = $this->input->post('dateFrom');
        $dateThru = $this->input->post('dateThru');
        $origin = $this->input->post('origin');
        $zone = $this->input->post('zone');
        $source_data = []; // Default empty array

        if ($dateFrom && $dateThru) {
            $source_data = $this->Pod_model->getSourceData($dateFrom, $dateThru, $origin, $zone);
        }

        $mapped_data = [];
        $label_mapping = [
            'Sesuai' => 'Sesuai',
            'Tidak Sesuai' => 'Tidak Sesuai',
            'Revisi' => 'Revisi',
        ];

        $sourceLabels = [];
        $sourceCounts = [];

        foreach ($source_data as $data) {
            $sourceLabels[] = $label_mapping[$data['status_checker']] ?? $data['status_checker'];
            $sourceCounts[] = (int) $data['count'];
        }

        echo json_encode([
            'success' => true,
            'sourceLabels' => $sourceLabels,
            'sourceCounts' => $sourceCounts
        ]);
    }
    public function getdatatables_cod_pod()
    {
        $user_role = $this->session->userdata('role');
        $list = $this->Pod_model->get_datatables_cod_pod();
        $data = array();
        $no = $this->input->post('start', true);
        foreach ($list as $item) {

            if ($item->status_cod == "L") {
                $status_cod = "Lunas";
            }
            $closing_name = $item->closing_name;
            $no++;
            $row = array();
            $row[] = '<small style="font-size:12px">' . $no . '</small>';
            $row[] = '<small style="font-size:12px"><b>' . htmlspecialchars($item->courier_name) . '</b><br>
            ' . htmlspecialchars($item->id_courier) . '
            </small>';
            if ($item->status_cod == "L") {
                $row[] = '<span class="badge rounded-pill bg-success"> Lunas </span>';
            } else {
                $row[] = '<span class="badge rounded-pill bg-danger"> Belum Lunas </span>';
            }
            if (isset($item->closing_hrs_by)) {
                $row[] = '<small style="font-size:12px"><b>' . htmlspecialchars($item->create_name) . ',</b><br>
                closing by <b> ' . htmlspecialchars(($closing_name)) .'</b> 
                </small>';
            } else {
                $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->create_name) . '</small>';
            }
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->no_runsheet) . '</small>';
            $row[] = '<b style="font-size:12px"> Rp ' . number_format($item->cod_paid) . '/ Rp ' . number_format($item->total_paid_cod) . '</b>
            <br>
            <div class="progress">
                <div class="progress-bar progress-bar-striped" style="width:' . $item->persentase_cod . '%">' . number_format($item->persentase_cod, 1) . '%</div>
            </div>
            ';

            if ($item->minus_cod < 0) {
                $plus_minus_cod = "+ " . abs($item->minus_cod);
            } else {
                $plus_minus_cod = $item->minus_cod;
            }
            $row[] = '<small style="font-size:12px">' . $plus_minus_cod . '</small>';
            $row[] = '<small style="font-size:12px">' . date('Y-m-d', strtotime($item->runsheet_date)) . '</small>';
            $row[] = '<small style="font-size:12px">' . $item->poin_hrs . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->paid_off_date) . '</small>';

            $button = ' <div class="form-button-action"> ';
            if ($item->status_cod === 'BL') {
                $button .= '
        <a href="' . base_url('pod/edit_detail_pod/' . urlencode(base64_encode($item->id_checker_notes)) . '/' . urlencode(base64_encode($item->no_runsheet))) . '"  
            class="btn btn-dark waves-effect waves-light btn-sm me-1" 
            title="Detail" data-plugin="tippy" data-tippy-placement="top">
            <i class="fa fa-info-circle"> Detail</i>
        </a>';

            } else if ($item->status_cod === 'L') {
                $button .= '
                <a href="' . base_url('pod/read_detail_pod/' . urlencode(base64_encode($item->id_checker_notes)) . '/' . urlencode(base64_encode($item->no_runsheet))) . '"  
                    class="btn btn-dark waves-effect waves-light btn-sm me-1" 
                    title="Detail" data-plugin="tippy" data-tippy-placement="top">
                    <i class="fa fa-info-circle"> Detail</i>
                </a>';

            }
            $button .= '</div>';

            $row[] = $button;



            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->Pod_model->count_all_cod_pod(),
            "recordsFiltered" => $this->Pod_model->count_filtered_cod_pod(),
            "data" => $data,
        );
        echo json_encode($output);
    }


    public function progress()
    {
        // $no_runsheet = "BDO/DRI/15150803";
        $runsheet_date = "2025-07-01";
        // $runsheet_date = "2025-07-03";
        // $id_courier = "BDO1476";
        $id_courier = "BDO3458";


        $progress = $this->Checker_model->_get_progress($id_courier, $runsheet_date);
        $persentase_progres = ($progress->success_pod / ($progress->success_pod + $progress->in_progress_pod)) * 100;
        // $response = [
        //     'remarks' => "No Runsheet sudah ada!",
        // ];

        // echo json_encode($response);
        print_r($progress);
        if ($persentase_progres == 100) {
            print_r("Anda keren");
        } else {

            print_r("Anda jelek");
        }

    }


    public function search_courier()
    {
        // $no_runsheet = "BDO/DRI/15150803";   
        $id_courier =  $this->input->post('id_courier');
        $start_date =  $this->input->post('dateFrom');
        $end_date =  $this->input->post('dateThru');
        // var_dump($id_courier,$start_date,$end_date);    

        if ($this->Pod_model->_get_no_runsheet_by_id($id_courier,$start_date,$end_date)) {

            $response = [
                'remarks' => "No Runsheet sudah ada!",
            ];

        } else {

            $get_detail_cod = $this->Pod_model->get_detail_cod_by_id($id_courier,$start_date,$end_date);
            if ($get_detail_cod) {
                $id_courier = $get_detail_cod->id_courier;
                // var_dump($get_detail_cod);

                $cod_data = [
                    'cod_display' => $get_detail_cod->amount,
                    'display_undelivered' => $get_detail_cod->cod_undelivered,
                    'total_awb' => $get_detail_cod->qty_awb,
                    'runsheet_date' =>
                        date('Y-m-d', strtotime(
                            $get_detail_cod->runsheet_date
                        )),
                ];
                if ($response = $get_detail_cod = $this->Courier_model->search_courier($id_courier)) {

                    $runsheet_date = $cod_data['runsheet_date'];
                    $progress = $this->Checker_model->_get_progress($id_courier, $runsheet_date);
                    $persentase_progres = ($progress->success_pod / ($progress->success_pod + $progress->in_progress_pod)) * 100;
                    $get_status_pod=$this->Pod_model->get_status_pod_by_id($id_courier,$start_date,$end_date);
                    $status_awb=$this->Pod_model->get_status_awb_by_id($id_courier,$start_date,$end_date);
                    $get_no_runsheet=$this->Checker_model->_select_runsheet($id_courier,$start_date,$end_date);
                    if(empty($get_status_pod->status_pod)){
                        $response = [
                            'remarks' => "Status POD belum di submit, hubungi tim checker POD",
    
                        ];
                    }else{
                        if ($persentase_progres == 100) {
    
                            $response = [
                                'courier_name' => $response->courier_name,
                                'nik' => $response->nik,
                                'type_courier' => $response->tipe_courier,
                                'area' => $response->area,
                                'zone' => $response->zone,
                                'no_tlp' => $response->no_tlp,
                                'location' => $response->location,
                                'id_courier' => $response->id_courier,
                                'cod_display' => $cod_data['cod_display'],
                                'total_awb' => $cod_data['total_awb'],
                                'display_undelivered' => $cod_data['display_undelivered'],
                                'undelivered' => $cod_data['display_undelivered'],
                                'runsheet_date' => $cod_data['runsheet_date'],
                                'dri' => $get_no_runsheet,
                                'dl' => $status_awb->delivered,
                                'undel' => $status_awb->undelivered,
                                'other' => $status_awb->other,

    
                            ];
                        } else {
                            $response = [
                                'remarks' => "Tidak bisa di proses karena runsheet ini masih ada paket OTS!"
                            ];
                        }
                       
                    }


                } else {


                    $response = [
                        'remarks' => "Kurir tidak ditemukan, silahkan periksa kembali!",

                    ];
                }

            } else {

                $response = [
                    'remarks' => "Detail cod tidak ditemukan, silahkan periksa kembali!",

                ];
                print_r($get_detail_cod);
            }
        }
        ;
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
        // print_r($this->Checker_model->_select_runsheet($id_courier,$start_date,$end_date));
    // print_r ($this->Pod_model->_get_no_runsheet_by_id($id_courier,$start_date,$end_date));
    }
    // public function search_courier()
    // {
    //     // $no_runsheet = "BDO/DRI/15150803";   
    //     $no_runsheet =  $this->input->post('no_runsheet');
    //     // var_dump($no_runsheet);    

    //     if ($this->Pod_model->_get_no_runsheet($no_runsheet)) {

    //         $response = [
    //             'remarks' => "No Runsheet sudah ada!",
    //         ];

    //     } else {

    //         $get_detail_cod = $this->Pod_model->get_detail_cod($no_runsheet);
    //         if ($get_detail_cod) {
    //             $id_courier = $get_detail_cod->id_courier;
    //             // var_dump($get_detail_cod);

    //             $cod_data = [
    //                 'cod_display' => $get_detail_cod->amount,
    //                 'display_undelivered' => $get_detail_cod->cod_undelivered,
    //                 'total_awb' => $get_detail_cod->qty_awb,
    //                 'runsheet_date' =>
    //                     date('Y-m-d', strtotime(
    //                         $get_detail_cod->runsheet_date
    //                     )),
    //             ];
    //             if ($response = $get_detail_cod = $this->Courier_model->search_courier($id_courier)) {

    //                 $runsheet_date = $cod_data['runsheet_date'];
    //                 $progress = $this->Checker_model->_get_progress($id_courier, $runsheet_date);
    //                 $persentase_progres = ($progress->success_pod / ($progress->success_pod + $progress->in_progress_pod)) * 100;
    //                 $get_status_pod=$this->Pod_model->get_status_pod($no_runsheet);
    //                 $status_awb=$this->Pod_model->get_status_awb($no_runsheet);
    //                 if(empty($get_status_pod->status_pod)){
    //                     $response = [
    //                         'remarks' => "Status POD belum di submit, hubungi tim checker POD",
    
    //                     ];
    //                 }else{
    //                     if ($persentase_progres == 100) {
    
    //                         $response = [
    //                             'courier_name' => $response->courier_name,
    //                             'nik' => $response->nik,
    //                             'type_courier' => $response->tipe_courier,
    //                             'area' => $response->area,
    //                             'zone' => $response->zone,
    //                             'no_tlp' => $response->no_tlp,
    //                             'location' => $response->location,
    //                             'id_courier' => $response->id_courier,
    //                             'cod_display' => $cod_data['cod_display'],
    //                             'total_awb' => $cod_data['total_awb'],
    //                             'display_undelivered' => $cod_data['display_undelivered'],
    //                             'undelivered' => $cod_data['display_undelivered'],
    //                             'runsheet_date' => $cod_data['runsheet_date'],
    //                             'dri' => $no_runsheet,
    //                             'dl' => $status_awb->delivered,
    //                             'undel' => $status_awb->undelivered,

    
    //                         ];
    //                     } else {
    //                         $response = [
    //                             'remarks' => "Tidak bisa di proses karena runsheet ini masih ada paket OTS!"
    //                         ];
    //                     }
                       
    //                 }


    //             } else {


    //                 $response = [
    //                     'remarks' => "Kurir tidak ditemukan, silahkan periksa kembali!",

    //                 ];
    //             }

    //         } else {

    //             $response = [
    //                 'remarks' => "Detail cod tidak ditemukan, silahkan periksa kembali!",

    //             ];
    //             print_r($get_detail_cod);
    //         }
    //     }
    //     ;
    //     header('Content-Type: application/json');
    //     echo json_encode($response);
    //     exit;
    // }
    public function poin_hrs($runsheet_date, $create_date,$no_runsheet)
    {
        $create_date_only = date('Y-m-d', strtotime($create_date));
        $create_time = date('H:i:s', strtotime($create_date));

        
        // Validasi poin
        if ($runsheet_date == $create_date_only) {
            $poin = 30;
        } else {
            // Tambahkan 1 hari ke runsheet_date untuk dibandingkan
            $next_day = date('Y-m-d', strtotime('+1 day', strtotime($runsheet_date)));

            if ($next_day == $create_date_only) {
                if ($create_time < '10:00:00') {
                    $poin = 10;
                } else{
                    $poin_obj = $this->Leaderboard_model->get_total_poin_courier($no_runsheet);
                $poin = isset($poin_obj->total_poin) ? (int) $poin_obj->total_poin : 0; 
                }
            } else {
                $poin_obj = $this->Leaderboard_model->get_total_poin_courier($no_runsheet);
                $poin = isset($poin_obj->total_poin) ? (int) $poin_obj->total_poin : 0;
            }
        }

        return $poin;
    }

    public function get_total_poin_courier(){
        $no_runsheet="BDO/DRI/15405122";
        $poin=$this->Leaderboard_model->get_total_poin_courier($no_runsheet);
        var_dump($poin);
    }

    public function create_pod()
    {

        $no_runsheet = $this->input->post('dri');

        if ($this->Pod_model->_get_no_runsheet($no_runsheet)) {
            $this->session->set_flashdata('notify', [
                'message' => 'No Runsheet sudah ada',
                'type' => 'danger'
            ]);
            redirect('pod');
        } else {

            $runsheet_date = $this->input->post('runsheet_date'); // format: 'Y-m-d'
            $create_date = date('Y-m-d H:i:s');
            // $create_date = "2025-08-18 09:53:36"; 

            $poin_hrs = $this->poin_hrs($runsheet_date, $create_date,$no_runsheet);            
            



            // status cod
            $cod_paid = $this->input->post('cod_called') + $this->input->post('transfer');
            $total_cod = $this->input->post('total_cod');
            $total_undelivered = $this->input->post('undelivered');
            $total_paid_cod = $total_cod - $total_undelivered;


            if ($cod_paid >= $total_paid_cod) {
                $status_cod = "L";
                $closing_by = "Closing_hrs_by";
            } else {
                $status_cod = "BL";
                $closing_by = "create_hrs_by";
            }

            $data_pod = array(
                $closing_by => $this->input->post('id_user'),
                'id_courier' => $this->input->post('display_id_courier'),
                'persentase_cod' => $this->input->post('persentase_cod'),
                'cod_paid' => $cod_paid,
                'minus_cod' => $this->input->post('plus_minus'),
                'no_runsheet' => $no_runsheet,
                'poin_hrs' => $poin_hrs,
                'total_paid_cod' => $total_paid_cod,
                'paid_off_date' => $create_date,
                'status_cod' => $status_cod,
                'transfer' => $this->input->post("transfer")
            );

            $this->Leaderboard_model->update_poin_hrs($poin_hrs,$no_runsheet);
            $this->Leaderboard_model->refresh_mv_leaderboard_summary();

            try {
                if ($this->Pod_model->_add_checker_pod($data_pod)) {
                    $this->session->set_flashdata('notify', [
                        'message' => 'COD POD berhasil di tambahkan!!',
                        'type' => 'success'
                    ]);
                }
                redirect('pod');
            } catch (Exception $e) {
                $this->session->set_flashdata('notify', [
                    'message' => 'Terjadi kesalahan saat menambahkan COD POD: ' . $e->getMessage(),
                    'type' => 'danger'
                ]);
                redirect('pod');
            }

        }

    }
    public function edit_pod()
    {

        $no_runsheet = $this->input->post('dri');



        $id_checker_notes = $this->input->post('id_checker_notes'); // format: 'Y-m-d'
        $runsheet_date = $this->input->post('runsheet_date'); // format: 'Y-m-d'
        
        $create_date = date('Y-m-d H:i:s');
        $poin_hrs = $this->poin_hrs($runsheet_date, $create_date,$no_runsheet);    
       
        $cod_paid = (int)$this->input->post('cod_called') + (int)$this->input->post('transfer');
        $total_cod =(int) $this->input->post('total_cod');
        $total_undelivered =(int) $this->input->post('undelivered');
        $total_paid_cod = $total_cod - $total_undelivered;




        if ($cod_paid >= $total_paid_cod) {
            $status_cod = "L";
            $closing_by = "Closing_hrs_by";
        } else {
            $status_cod = "BL";
            $closing_by = "create_hrs_by";
        }

        $data_pod = array(
            $closing_by => $this->input->post('id_user'),
            'persentase_cod' => $this->input->post('persentase_cod'),
            'cod_paid' => $cod_paid,
            'minus_cod' => $this->input->post('plus_minus'),
            'poin_hrs' => $poin_hrs,
            'total_paid_cod' => $total_paid_cod,
            'paid_off_date' => $create_date,
            'status_cod' => $status_cod,
            'transfer' => $this->input->post("transfer")
        );

        // var_dump($data_pod);
        // var_dump($poin_hrs);
        $this->Leaderboard_model->update_poin_hrs($poin_hrs,$no_runsheet);
        $this->Leaderboard_model->refresh_mv_leaderboard_summary();


        try {
            if ($this->Pod_model->_edit_checker_pod($id_checker_notes, $data_pod)) {
                $this->session->set_flashdata('notify', [
                    'message' => 'COD POD berhasil di tambahkan!!',
                    'type' => 'success'
                ]);
            }
            redirect('pod');
        } catch (Exception $e) {
            $this->session->set_flashdata('notify', [
                'message' => 'Terjadi kesalahan saat menambahkan COD POD: ' . $e->getMessage(),
                'type' => 'danger'
            ]);
            redirect('pod');
        }



    }


}
