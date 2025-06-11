<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Admin_model');
        $this->load->model('Upload_model');
        // $this->load->model('User_model');        
        $this->load->model('Checker_model');
        $this->load->model('Leaderboard_model');
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
            )
        ) {
            $data['title'] = 'Dashboard Admin';
            $data['page_name'] = 'upload';
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
    public function detail($encrypted_id, $runsheet_date)
    {

        $user_role = $this->session->userdata('role');
        $id_courier = base64_decode(urldecode($encrypted_id));
        $runsheet_date = base64_decode(urldecode($runsheet_date));
        $checker_approve = $this->Checker_model->_get_data_approve($id_courier, $runsheet_date);
        $checker_not_approve = $this->Checker_model->_get_data_not_approve($id_courier, $runsheet_date);
        $checker_revision = $this->Checker_model->_get_data_revision($id_courier, $runsheet_date);

        $data['courier'] = $this->Checker_model->_get_data_courier($id_courier);
        $data['data_checkers_approve'] = $checker_approve['data'];
        $data['summary_checkers_approve'] = $checker_approve['num_rows'];
        $data['data_checkers_not_approve'] = $checker_not_approve['data'];
        $data['summary_checkers_not_approve'] = $checker_not_approve['num_rows'];
        $data['get_data_revision'] = $checker_revision['data'];
        $data['summary_revision'] = $checker_revision['num_rows'];
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
            // Refresh materialize
            $this->Checker_model->refresh_mv_checker_summary();

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
            // Refresh materialize
            $this->Checker_model->refresh_mv_checker_summary();
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
        $user_role = $this->session->userdata('role');
        $list = $this->Checker_model->getdatatables_checker();
        $data = array();
        $no = $this->input->post('start', true);
        foreach ($list as $item) {
            $zone = $item->zone;

            $no++;
            $row = array();
            $row[] = '<small style="font-size:12px">' . $no . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->id_courier) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->courier_name) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->qty_awb) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->qty_sesuai) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->qty_tidak_sesuai) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->qty_revisi) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->runsheet_date) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->name) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($zone) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($this->Checker_model->get_zone_name($item->zone)) . '</small>';
            $button = '
    <div class="form-button-action"> 
        <a href="' . base_url('admin/detail/' . urlencode(base64_encode($item->id_courier)) . '/' . urlencode(base64_encode($item->runsheet_date))) . '"  
            class="btn btn-dark waves-effect waves-light btn-sm me-1" 
            title="Detail" data-plugin="tippy" data-tippy-placement="top">
            <i class="fa fa-info-circle"> Detail</i>
        </a>';

            if ($user_role === "Super User") {
                $button .= '
        <button class="btn btn-link btn-danger btn-lg" onclick="deleteValidasi(
            \'' . addslashes(trim($item->id_courier)) . '\',
            \'' . addslashes(trim($item->id_checker)) . '\',
            \'' . addslashes(trim($item->create_date)) . '\',
            \'' . addslashes(trim($item->courier_name)) . '\')"
            data-bs-toggle="modal" data-bs-target="#deleteValidasi">
            <i class="fa fa-times"></i>
        </button>';
            }

            $button .= '</div>';

            $row[] = $button;


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

    public function summary_dashboard()
    {
        $dateFrom = $this->input->post('dateFrom');
        $dateThru = $this->input->post('dateThru');
        $origin = $this->input->post('origin', TRUE);
        $zone = $this->input->post('zone', TRUE);


        $this->db->select('
        COUNT(*) as totalAwb,          
        SUM(ch.status_checker = "Sesuai") as approve,
        SUM(ch.status_checker = "Tidak Sesuai") as notApprove,
        SUM(ch.status_checker = "Revisi") as revision
                          
        ');

        if (!empty($dateFrom) && !empty($dateThru)) {
            $this->db->where('DATE(ch.create_date) >=', $dateFrom);
            $this->db->where('DATE(ch.create_date) <=', $dateThru);
        }

        $this->db->from('checker ch');
        $this->db->join('zone z', 'ch.zone = z.zone_code', 'left');
        if (!empty($origin) && empty($zone)) {
            $this->db->where('z.origin_code', $origin);
        }
        if (!empty($origin) && !empty($zone)) {
            $this->db->where('ch.zone', $zone);
            $this->db->where('z.origin_code', $origin);
        }

        $query = $this->db->get();
        $result = $query->row();

        echo json_encode([
            'totalAwb' => htmlspecialchars($result->totalAwb),
            'approve' => htmlspecialchars($result->approve),
            'notApprove' => htmlspecialchars($result->notApprove),
            'revision' => htmlspecialchars($result->revision),


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
            $source_data = $this->Admin_model->getSourceData($dateFrom, $dateThru, $origin, $zone);
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

    public function getSourceDataMultiple()
    {
        $year = $this->input->post('year') ?? date('Y'); // Default tahun sekarang
        $origin = $this->input->post('origin');
        $zone = $this->input->post('zone');
        $source_data = $this->Admin_model->getSourceDataMultiple($year, $origin, $zone);

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

// bug di hapus url revision
    public function delete_validasi()
    {
        $id_courier = $this->input->post('id_courrier_delete');
        $create_date = $this->input->post('create_date_delete');

        if ($this->Upload_model->delete_validasi($id_courier, $create_date)) {

            // Cek dan hapus file jika bukan 'public/img/Image-not-found.png'
            $default_img = 'public/img/Image-not-found.png';
            $deleted_files = [];

            $data_list = $this->Upload_model->get_image_paths($id_courier, $create_date);

            foreach ($data_list as $data) {
                foreach (['url_photo', 'url_pod', 'url_revision'] as $field) {
                    if (!empty($data->$field)) {
                        $image_path = $data->$field;

                        // Skip default image
                        if ($image_path === $default_img) {
                            var_dump("Melewati default image: $image_path");
                            continue;
                        }

                        $full_path = FCPATH . $image_path;

                        var_dump('Coba hapus file: ' . $full_path);

                        if (file_exists($full_path) && is_file($full_path)) {
                            if (unlink($full_path)) {
                                $deleted_files[] = $image_path;
                                var_dump('Berhasil hapus file: ' . $full_path);
                            } else {
                                var_dump('Gagal hapus file: ' . $full_path);
                            }
                        } else {
                            var_dump('File tidak ditemukan: ' . $full_path);
                        }
                    }
                }
            }

            $this->session->set_flashdata('notify', [
                'message' => 'Validasi dan ' . count($deleted_files) . ' file berhasil dihapus!',
                'type' => 'success'
            ]);
        } else {
            $this->session->set_flashdata('notify', [
                'message' => 'Gagal hapus Validasi!',
                'type' => 'warning'
            ]);
        }

        // Refresh materialize
        $this->Checker_model->refresh_mv_checker_summary();
        redirect('admin');
    }

    public function testing_delete()
    {
        $path = "uploads/images_revision/revision_1749629088.jpg";
        if (file_exists($path)) {
            echo "Ada file. ";
            if (unlink($path)) {
                echo "Berhasil dihapus.";
            } else {
                echo "Gagal hapus.";
            }
        } else {
            echo "File tidak ditemukan.";
        }
    }


    public function get_path()
    {
        $id_courier = "BDO3476";
        $create_date = "2025-06-11 09:26:51";

        $results = $this->Upload_model->get_image_paths($id_courier, $create_date);

        // Format response lebih baik
        $response = [
            'status' => 'success',
            'count' => count($results),
            'data' => $results
        ];

        // Tampilkan response JSON dengan format rapi
        header('Content-Type: application/json');
        echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
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