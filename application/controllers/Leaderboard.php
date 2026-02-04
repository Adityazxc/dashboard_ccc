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

    // 🔥 ambil filter dari request GET/POST
    $dateFrom = $this->input->get('dateFrom') ?? date('Y-m');
    $selected_origin = $this->input->get('origin') ?? $origin->origin_code;
    $selected_zone   = $this->input->get('zone') ?? $zone;

    // panggil model dengan filter
    $top3 = $this->Leaderboard_model->get_top3_courier($selected_origin, $dateFrom, $selected_zone);

    if ($password == "e10adc3949ba59abbe56e057f20f883e") {
        redirect('reset_password/input_password');
    } else if ($this->session->userdata('logged_in') && in_array($user_role, [
        "Koordinator","Admin","Super User","CS","CCC",
        "BPS","HC","Kepala Cabang BDO2","Kepala Cabang",
        "BBP","PAO","POD", "Koordinator BDO2", "Admin BDO2"
    ])) {
        $data['title'] = 'Leaderboard';
        $data['page_name'] = 'leaderboard';
        $data['get_origins'] = $get_origins;
        $data['role'] = $user_role;
        $data['zone'] = $zone;
        $data['origin'] = $origin->origin_code;

        // 🔥 kirim filter supaya tetap terisi di form
        $data['dateFrom'] = $dateFrom;
        $data['selected_origin'] = $selected_origin;
        $data['selected_zone'] = $selected_zone;

        $data['top3'] = $top3;

        $this->load->view('dashboard', $data);
    } else {
        redirect('auth');
    }
}

public function get_top3_ajax(){
    $selected_origin=$this->input->post('origin');
    $dateFrom=$this->input->post('dateFrom');
    $selected_zone=$this->input->post('zone');
     $result=$this->Leaderboard_model->get_top3_courier($selected_origin, $dateFrom, $selected_zone);
      
    echo json_encode($result);
}

    public function viewer()
{
    $zone = "BDO026";
    $origin = $this->Checker_model->_get_origin($zone);
    $get_origins = json_encode($this->Admin_model->_get_origins());
    
    $data['title'] = 'Leaderboard';
    $data['page_name'] = 'view_leaderboard';
    $data['get_origins'] = $get_origins;
    $data['role'] = "";
    $data['zone'] = "";
    $data['origin'] = $origin->origin_code;
    

    $this->load->view('dashboard', $data);
}

   


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
                || $user_role == "POD"
                 || $user_role == "Admin BDO2"
                || $user_role == "Koordinator BDO2"

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
  
  public function leaderboard()
{
    $dateFrom = $this->input->post('dateFrom');
    $origin   = $this->input->post('origin');

    $data['top3'] = $this->Leaderboard_model->get_top3_courier($dateFrom, $origin);

    $this->load->view('leaderboard_view', $data);
}



    public function getdatatables_courier()
{
    $type = $this->input->post('type'); // 'top' atau 'bottom'
    $user_role = $this->session->userdata('role');

    $list = $this->Leaderboard_model->get_datatables_courier($type);
    $data = array();
    $no = $this->input->post('start', true);
    // $no = 3;
    foreach ($list as $item) {
        $awb_sesuai = $item->total_qty_sesuai + $item->total_qty_revisi;
        $total_awb = $item->total_qty_awb;
        $photo_pod = $total_awb > 0 ? ($awb_sesuai / $total_awb) * 100 : 0;

        if ($photo_pod == 100) {
            $poin_photo_pod = 20;
        } else if ($photo_pod >= 90) {
            $poin_photo_pod = 10;
        } else {
            $poin_photo_pod = 0;
        }

        $img_path = base_url('uploads/image_courier/CJR171.JPG');

        // fallback kalau foto tidak ada
                    $img_html = '
        <img src="' . $img_path . '"
            onerror="this.src=\'' . base_url('assets/img/avatar.png') . '\'"
            class="courier-img"
            onclick="showCourierImage(\'' . $img_path . '\')"
            alt="Foto Kurir">';

        $no++;
        $row = array();
        $row[] = '<small style="font-size:12px">' . $no . '</small>';
        $row[] = $img_html;
        $row[] = '<small style="font-size:12px"><b>' . htmlspecialchars($item->courier_name) . '</b><br>' . htmlspecialchars($item->id_courier) . '</small>';
        $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->total_success_rate) . '</small>';
        $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->total_hrs) . '</small>';
        $row[] = '<small style="font-size:12px">' . $poin_photo_pod . '</small>';
        $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->total_hrs) . '</small>';
        $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->total_minus_poin) . '</small>';
        $row[] = '<div class="btn custom-primary-btn" style="    background-color: #383486;
        color: #fff;
        border: none;
        border-radius: 10px;
        font-weight: bold;
        ">' . htmlspecialchars($item->total_poin) . 'PTS</div>';
        $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->zone) . '</small>';
        $button = '<div>';

        if (!empty($user_role)) {
            $button .= '
                <a href="' . base_url('leaderboard/statistik/' . urlencode(base64_encode($item->id_courier)) . '/' . urlencode(base64_encode($item->first_runsheet))) . '" 
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