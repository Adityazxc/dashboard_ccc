<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Leaderboard extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Admin_model');
        $this->load->model('Leaderboard_model');
        $this->load->model('Checker_model');
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('User_model');
        $this->load->library('encryption');
        $this->session->set_userdata('pages', 'leaderboard_page');
    }

    public function tanggal()
    {
        $dateFrom = date('Y-m-01 00:00:00'); // contoh: 2025-08-01 00:00:00
        $dateThru = date('Y-m-t 23:59:59');

        var_dump($dateFrom, $dateThru);

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

            )
        ) {
            $data['title'] = 'Leaderboard';
            $data['page_name'] = 'leaderboard';
            $data['get_origins'] = $get_origins;
            $data['role'] = $user_role;
            $data['zone'] = $zone;
            $data['origin'] = $origin->origin_code;

            $this->load->view('dashboard', $data);
        } else {
            redirect('auth');
        }

    }
    public function viewer()
    {
        $zone="BDO026";
        $origin = $this->Checker_model->_get_origin($zone);
        $get_origins = json_encode($this->Admin_model->_get_origins());
      
            $data['title'] = 'Leaderboard';
            $data['page_name'] = 'view_leaderboard';
            $data['get_origins'] = $get_origins;
            $data['role'] = "";
            $data['zone'] = "";
            // $data['user_role'] = "d";
            $data['origin'] = $origin->origin_code;

            $this->load->view('dashboard', $data);
      

    }



    // public function statistik_photo_pod()
// {
//     $id_courier = $this->input->post('id_courier');

    //     $start_date = date('Y-m-01 00:00:00');
//     $end_date   = date('Y-m-t 23:59:59');

    //     $this->db->select('
//         SUM(qty_awb) as total_awb,
//         SUM(qty_sesuai) as total_valid,
//         SUM(qty_revisi) as total_revision,
//         SUM(qty_tidak_sesuai) as total_invalid
//     ');
//     $this->db->where('id_courier', $id_courier);
//     $this->db->where('create_date >=', $start_date);
//     $this->db->where('create_date <=', $end_date);
//     $this->db->from('mv_checker_summary');

    //     $query  = $this->db->get()->row_array();

    //     $total_awb = (int) $query['total_awb'];

    //     $result = [
//         'success' => true,
//         'sourceLabels' => ['Sesuai', 'Tidak Sesuai', 'Revisi'],
//         'sourceCounts' => [
//             (int) $query['total_valid'],
//             (int) $query['total_invalid'],
//             (int) $query['total_revision']
//         ],
//         'total_awb' => $total_awb
//     ];

    //     echo json_encode($result);
// }


    public function get_succes_cod()
    {

        // $id_courier = "BDO1007";
        $id_courier = $this->input->post('id_courier');
        $source_data = []; // Default empty array

        $source_data = $this->Leaderboard_model->_get_success_cod($id_courier);

        $mapped_data = [];
        $label_mapping = [
            'D01' => 'D01',
            'D04' => 'D04',
            'D07' => 'D07',
            'D09' => 'D09',

        ];

        $sourceLabels = [];
        $sourceCounts = [];

        foreach ($source_data as $data) {
            $sourceLabels[] = $label_mapping[$data['status_cod']] ?? $data['status_cod'];
            $sourceCounts[] = (int) $data['count'];
        }

        echo json_encode([
            'success' => true,
            'sourceLabels' => $sourceLabels,
            'sourceCounts' => $sourceCounts
        ]);
    }
    public function statistik_photo_pod()
    {

        // $id_courier = "BDO1007";
        $id_courier = $this->input->post('id_courier');
        $source_data = []; // Default empty array

        $source_data = $this->Leaderboard_model->get_quality_foto_pod($id_courier);

        $mapped_data = [];
        $label_mapping = [
            'total_valid' => 'Sesuai',
            'total_revision' => 'Revisi',
            'total_invalid' => 'Tidak Sesuai',

        ];

        $sourceLabels = [];
        $sourceCounts = [];

        foreach ($source_data as $data) {
            $sourceLabels[] = $label_mapping[$data['status_cod']] ?? $data['status_cod'];
            $sourceCounts[] = (int) $data['count'];
        }

        echo json_encode([
            'success' => true,
            'sourceLabels' => $sourceLabels,
            'sourceCounts' => $sourceCounts
        ]);
    }
    public function get_failed_cod()
    {

        // $id_courier = "BDO1007";
        $id_courier = $this->input->post('id_courier');
        $source_data = []; // Default empty array

        $source_data = $this->Leaderboard_model->_get_failed_cod($id_courier);

        $mapped_data = [];
        $label_mapping = [
            'U05' => 'U05',
            'U09' => 'U09',
            'U12' => 'U12',
            'Other' => 'Other',


        ];

        $sourceLabels = [];
        $sourceCounts = [];

        foreach ($source_data as $data) {
            $sourceLabels[] = $label_mapping[$data['status_cod']] ?? $data['status_cod'];
            $sourceCounts[] = (int) $data['count'];
        }

        echo json_encode([
            'success' => true,
            'sourceLabels' => $sourceLabels,
            'sourceCounts' => $sourceCounts
        ]);
    }
    public function statistik($id_courier)
    {

        $id_courier = base64_decode(urldecode($id_courier));
        $data_courier = $this->Leaderboard_model->_get_data_courier($id_courier);
        $data_awb = $this->Leaderboard_model->_get_data_awb($id_courier);
        $get_summmary_awb = $this->Leaderboard_model->_get_awb_status_summary($id_courier);
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

            )
        ) {
            $data['title'] = 'Statistik Courier';
            $data['page_name'] = 'statistik_courier';
            $data['get_origins'] = $get_origins;
            $data['role'] = $user_role;
            $data['id_courier'] = $id_courier;
            $data['zone'] = $zone;
            $data['origin'] = $origin->origin_code;
            $data['data_courier'] = $data_courier;
            $data['get_summmary_awb'] = $get_summmary_awb;
            $data['data_awb'] = $data_awb;

            $this->load->view('dashboard', $data);
        } else {
            redirect('leaderboard');
        }
    }

    public function get_data_courier()
    {
        $id_courier = "BDO1007";
        $get_data = $this->Leaderboard_model->_get_data_courier($id_courier);
        print_r($get_data);
    }
    // public function getdatatables_top_courier()
    // {
    //     $user_role = $this->session->userdata('role');
    //     $list = $this->Leaderboard_model->get_datatables_top_courier();
    //     $data = array();
    //     $no = $this->input->post('start', true);
    //     foreach ($list as $item) {
    //         $awb_sesuai = $item->qty_sesuai + $item->qty_revisi;

    //         $total_awb = $item->qty_awb;
    //         $photo_pod = $total_awb > 0 ? ($awb_sesuai / $total_awb) * 100 : 0;

    //         if ($photo_pod == 100) {
    //             $poin_photo_pod = 20;
    //         } else if ($photo_pod >= 90) {
    //             $poin_photo_pod = 10;
    //         } else {
    //             $poin_photo_pod = 0;

    //         }

    //         // $zone = $item->zone;  
    //         // $persentase_progres= ($item->success_pod / ($item->success_pod +$item->in_progress_pod)) * 100;          
    //         $no++;
    //         $row = array();
    //         $row[] = '<small style="font-size:12px">' . $no . '</small>';
    //         $row[] = '<small style="font-size:12px"><b>' . htmlspecialchars($item->courier_name) . '</b><br>
    //         ' . htmlspecialchars($item->id_courier) . '
    //         </small>';
    //         $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->succes_rate) . '</small>';
    //         $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->kpi) . '</small>';
    //         $row[] = '<small style="font-size:12px">' . $poin_photo_pod . '</small>';
    //         $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->hrs) . '</small>';
    //         $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->minus_poin) . '</small>';
    //         $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->total_poin) . '</small>';
    //         $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->zone) . '</small>';
    //         $button = ' <div class="form-button-action"> ';
    //         if ($user_role === 'Super User') {
    //             $button .= '
    //     <a href="' . base_url('leaderboard/statistik/' . urlencode(base64_encode($item->id_courier)) . '/' . urlencode(base64_encode($item->runsheet_date))) . '" 
    //         class="btn btn-dark waves-effect waves-light btn-sm me-1" 
    //         title="Detail" data-plugin="tippy" data-tippy-placement="top">
    //         <i class="fa fa-info-circle"> Detail</i>
    //     </a>';

    //         }
    //         $button .= '</div>';

    //         $row[] = $button;
    //         // $row[] = '<b style="font-size:12px">' . htmlspecialchars($item->success_pod).' / '. htmlspecialchars($item->in_progress_pod) . '</b>
    //         // <br>
    //         // <div class="progress">
    //         //     <div class="progress-bar progress-bar-striped" style="width:'.$persentase_progres.'%">'.number_format($persentase_progres,1).'%</div>
    //         // </div>
    //         // ';          
    //         // $row[] = '<small style="font-size:12px">' . htmlspecialchars(round($item->persentase,1)).' %</small>';            

    //         $data[] = $row;
    //     }
    //     $output = array(
    //         "draw" => @$_POST['draw'],
    //         "recordsTotal" => $this->Leaderboard_model->count_all_top_courier(),
    //         "recordsFiltered" => $this->Leaderboard_model->count_filtered_top_courier(),
    //         "data" => $data,
    //     );
    //     echo json_encode($output);
    // }

    public function getdatatables_courier()
{
    $type = $this->input->post('type'); // 'top' atau 'bottom'
    $user_role = $this->session->userdata('role');

    $list = $this->Leaderboard_model->get_datatables_courier($type);
    $data = array();
    $no = $this->input->post('start', true);
    foreach ($list as $item) {
        $awb_sesuai = $item->qty_sesuai + $item->qty_revisi;
        $total_awb = $item->qty_awb;
        $photo_pod = $total_awb > 0 ? ($awb_sesuai / $total_awb) * 100 : 0;

        if ($photo_pod == 100) {
            $poin_photo_pod = 20;
        } else if ($photo_pod >= 90) {
            $poin_photo_pod = 10;
        } else {
            $poin_photo_pod = 0;
        }

        $no++;
        $row = array();
        $row[] = '<small style="font-size:12px">' . $no . '</small>';
        $row[] = '<small style="font-size:12px"><b>' . htmlspecialchars($item->courier_name) . '</b><br>' . htmlspecialchars($item->id_courier) . '</small>';
        $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->succes_rate) . '</small>';
        $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->kpi) . '</small>';
        $row[] = '<small style="font-size:12px">' . $poin_photo_pod . '</small>';
        $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->hrs) . '</small>';
        $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->minus_poin) . '</small>';
        $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->total_poin) . '</small>';
        $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->zone) . '</small>';

        $button = '<div class="form-button-action">';
        if ($user_role === 'Super User') {
            $button .= '
                <a href="' . base_url('leaderboard/statistik/' . urlencode(base64_encode($item->id_courier)) . '/' . urlencode(base64_encode($item->runsheet_date))) . '" 
                    class="btn btn-dark waves-effect waves-light btn-sm me-1" 
                    title="Detail">
                    <i class="fa fa-info-circle"> Detail</i>
                </a>';
        }
        $button .= '</div>';
        $row[] = $button;

        $data[] = $row;
    }

    $output = array(
        "draw" => @$_POST['draw'],
        "recordsTotal" => $this->Leaderboard_model->count_all_courier(),
        "recordsFiltered" => $this->Leaderboard_model->count_filtered_courier($type),
        "data" => $data,
    );
    echo json_encode($output);
}


    public function output()
    {

        $dateFrom = "2025-09";


        $bulan = date('m', strtotime($dateFrom));
        $tahun = date('Y', strtotime($dateFrom));
        $this->db->select('
       l.*,
   
            c.courier_name,
              l.kpi,
            l.hrs,
            l.total_poin,
            l.succes_rate,
            z.zone
            
            
                     
    ');
        $this->db->from('mv_leaderboard_summary l');
        $this->db->join('courier c', 'c.id_courier = l.id_courier', 'left');
        $this->db->join('mv_checker_summary m', 'm.id_courier = l.id_courier ');
        $this->db->join('zone z', 'z.zone_code=m.zone', 'left');
        $query = $this->db->get();
        $result = $query->result();
        print_r($result);


    }

    public function refresh_poin(){
        $this->Leaderboard_model->refresh_total_poin_all();
        $this->Leaderboard_model->refresh_mv_leaderboard_summary();
        redirect('leaderboard');
    }
}