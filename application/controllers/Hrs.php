<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Hrs extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Admin_model');
        $this->load->model('Leaderboard_model');
        $this->load->model('Checker_model');
        $this->load->model('Pod_model');
        $this->load->model('Hrs_model');
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('User_model');
        $this->load->library('encryption');
        $this->session->set_userdata('pages', 'pod_page');
    }
    public function add_hrs($no_runsheet, $id_courier, $date_from, $date_thru)
    {

        $no_runsheet = base64_decode(urldecode($no_runsheet));
        $id_courier = base64_decode(urldecode($id_courier));

        $get_cod_pod = $this->Pod_model->get_cod_pod($no_runsheet);
        $get_detail_cod = $this->Pod_model->get_detail_cod_by_no_runsheet($no_runsheet);

        $data['page_name'] = 'add_hrs';
        $user_role = $this->session->userdata('role');
        $data['title'] = "Add HRS";
        $data['id_user'] = $this->session->userdata('id_user');
        $data['role'] = $user_role;
        $data['get_cod_pod'] = $get_cod_pod;
        $data['no_runsheet'] = $no_runsheet;
        $data['id_courier'] = $id_courier;
        $data['get_detail_cod'] = $get_detail_cod;
        $data['mode'] = "add";

        $date_from = base64_decode(urldecode($date_from));
        $date_thru = base64_decode(urldecode($date_thru));
        $data['date_thru'] = $date_thru;
        $data['date_from'] = $date_from;
        $this->load->view('dashboard', $data);

    }
    public function detail_hrs($no_runsheet, $id_courier, $date_from, $date_thru)
    {

        $no_runsheet = base64_decode(urldecode($no_runsheet));
        $id_courier = base64_decode(urldecode($id_courier));

        $get_cod_pod = $this->Pod_model->get_cod_pod($no_runsheet);
        $get_detail_cod = $this->Pod_model->get_detail_cod_by_no_runsheet($no_runsheet);
        $list_hrs = $this->Hrs_model->get_detail_hrs($no_runsheet);

        $data['page_name'] = 'add_hrs';
        $user_role = $this->session->userdata('role');
        $data['title'] = "Add HRS";
        $data['id_user'] = $this->session->userdata('id_user');
        $data['role'] = $user_role;
        $data['get_cod_pod'] = $get_cod_pod;
        $data['no_runsheet'] = $no_runsheet;
        $data['id_courier'] = $id_courier;
        $data['list_hrs'] = $list_hrs;
        $data['get_detail_cod'] = $get_detail_cod;
        $data['mode'] = "detail";

        $date_from = base64_decode(urldecode($date_from));
        $date_thru = base64_decode(urldecode($date_thru));
        $data['date_thru'] = $date_thru;
        $data['date_from'] = $date_from;
        $this->load->view('dashboard', $data);

    }

    public function save_hrs()
    {
        $hrs_list = $this->input->post('hrs');
        $dri = $this->input->post('dri');
        $create_at = $this->session->userdata('id_user');
      
        $inserted = 0;
        //  SIMPAN FILTER (FLASHDATA)
        $this->session->set_flashdata('filter_pod', [
            'dateFrom' => $this->input->post('dateFrom'),
            'dateThru' => $this->input->post('dateThru'),
            'select_courier' => $this->input->post('select_courier')
        ]);

        if (!empty($hrs_list) && is_array($hrs_list)) {
            foreach ($hrs_list as $hrs) {
                if (trim($hrs) == '')
                    continue;

                $data = [
                    'hrs' => $hrs,
                    'create_at' => $create_at,
                    'dri' => $dri,
                    'create_date' => date('Y-m-d H:i:s')
                ];

                if ($this->db->insert('hrs', $data)) {
                    $inserted++;
                }
            }
        }

        if ($inserted > 0) {
            if ($this->Pod_model->update_status_dri($dri)) {

                $response = [
                    'status' => 'success',
                    'message' => 'Data HRS berhasil disimpan!',
                    'redirect' => base_url('pod/detail_pod')
                ];
            } else {
                $response = [
                    'status' => 'danger',
                    'message' => 'Data HRS gagal di update!',
                    'redirect' => base_url('pod/detail_pod')
                ];

            }            
        } else {
            $response = [
                'status' => 'danger',
                'message' => 'Data HRS gagal disimpan',
                'redirect' => base_url('pod/detail_pod')
            ];
        }

        echo json_encode($response);
    }
    public function edit_hrs()
    {
        $create_at = $this->session->userdata('id_user');
        $dri = $this->input->post('dri');

        // =========================
        // SIMPAN FLASHDATA FILTER
        // =========================
        $this->session->set_flashdata('filter_pod', [
            'dateFrom' => $this->input->post('dateFrom'),
            'dateThru' => $this->input->post('dateThru'),
            'select_courier' => $this->input->post('select_courier')
        ]);

        // =========================
        // INSERT (ADD HRS)
        // =========================
        $add_hrs = $this->input->post('add_hrs');
        $add_hrs = is_array($add_hrs) ? $add_hrs : [];

        $add_hrs = array_filter($add_hrs, function ($v) {
            return trim($v) !== '';
        });

        $inserted = 0;
        foreach ($add_hrs as $hrs) {
            $this->db->insert('hrs', [
                'hrs' => $hrs,
                'dri' => $dri,
                'create_at' => $create_at,
                'create_date' => date('Y-m-d H:i:s')
            ]);
            $inserted++;
        }

        // =========================
        // UPDATE (EDIT HRS)
        // =========================
        $hrs_edit = $this->input->post('hrs_edit');
        $id_hrs_edit = $this->input->post('id_hrs_edit');

        $hrs_edit = is_array($hrs_edit) ? $hrs_edit : [];
        $id_hrs_edit = is_array($id_hrs_edit) ? $id_hrs_edit : [];

        $updated = 0;
        foreach ($hrs_edit as $i => $hrs) {
            if (trim($hrs) === '')
                continue;

            if (!empty($id_hrs_edit[$i])) {
                $this->db->where('id_hrs', $id_hrs_edit[$i])
                    ->update('hrs', [
                        'hrs' => $hrs,
                        'create_at' => $create_at,
                        'create_date' => date('Y-m-d H:i:s')
                    ]);
                $updated++;
            }
        }

        // =========================
        // RESPONSE
        // =========================
        if (($inserted + $updated) > 0) {
            $this->Pod_model->update_status_dri($dri);

            $response = [
                'status' => 'success',
                'message' => 'Data HRS berhasil disimpan!',
                'redirect' => base_url('pod/detail_pod')
            ];
        } else {
            $response = [
                'status' => 'danger',
                'message' => 'Tidak ada data yang disimpan',
                'redirect' => base_url('pod/detail_pod')
            ];
        }

        echo json_encode($response);
    }

    public function delete_hrs()
    {
        $id_hrs = $this->input->post('id_hrs');

        if (empty($id_hrs)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'ID HRS tidak valid'
            ]);
            return;
        }

        // Panggil model
        $deleted = $this->Hrs_model->delete_hrs($id_hrs);

        if ($deleted) {
            echo json_encode([
                'status' => 'success',
                'message' => 'HRS berhasil dihapus'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal menghapus HRS'
            ]);
        }
    }




    public function get_total_poin_courier()
    {
        $no_runsheet = "BDO/DRI/15405122";
        $poin = $this->Leaderboard_model->get_total_poin_courier($no_runsheet);
        var_dump($poin);
    }

    public function getdatatables_hrs()
    {
        $date_from = $this->input->post('date_from');
        $date_thru = $this->input->post('date_thru');
        // $date_from = date('Y-m-d', strtotime('-1 day'));
        // $date_thru = date('Y-m-d', strtotime('-1 day'));

        $list = $this->Hrs_model->get_hrs();

        $data = array();
        $no = $this->input->post('start', true);
        foreach ($list as $item) {

            $no++;
            $row = array();
            $row[] = '<small style="font-size:12px">' . $no . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->no_runsheet) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->total_delivered) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->already_paid) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->payment_status) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->payment_status) . '</small>';


            if ($item->status_hrs == 1) {
                $row[] = '<span class="badge rounded-pill bg-success"> Sudah Ditambahkan </span>';
                $button = ' <div class="form-button-action"> ';
                $button .= '
                <a href="' . base_url('hrs/detail_hrs/' . urlencode(base64_encode($item->no_runsheet)) . '/' . urlencode(base64_encode($item->id_courier)) . '/' . urlencode(base64_encode($date_from)) . '/' . urlencode(base64_encode($date_thru))) . '"  
                class="btn btn-dark waves-effect waves-light btn-sm me-1" 
                title="Detail" data-plugin="tippy" data-tippy-placement="top">
                <i class="fa fa-info-circle"> Detail</i>
                </a>';

                $button .= '</div>';
                $row[] = $button;

            } else {
                $row[] = '<span class="badge rounded-pill bg-secondary"> Belum Ditambahkan </span>';

                $button = ' <div class="form-button-action"> ';
                $button .= '
                <a href="' . base_url('hrs/add_hrs/' . urlencode(base64_encode($item->no_runsheet)) . '/' . urlencode(base64_encode($item->id_courier)) . '/' . urlencode(base64_encode($date_from)) . '/' . urlencode(base64_encode($date_thru))) . '"  
                class="btn btn-dark waves-effect waves-light btn-sm me-1" 
                title="Detail" data-plugin="tippy" data-tippy-placement="top">
                <i class="fa fa-info-circle"> Add</i>
                </a>';

                $button .= '</div>';
                $row[] = $button;

            }

            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->Hrs_model->count_all_hrs(),
            "recordsFiltered" => $this->Hrs_model->count_filtered_hrs(),
            "data" => $data,
        );
        echo json_encode($output);
    }
}
?>