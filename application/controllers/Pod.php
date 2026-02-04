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
                || $user_role == "POD"
                || $user_role == "Admin BDO2"
                || $user_role == "Koordinator BDO2"
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


    public function summary_dashboard_pod()
    {
        $dateFrom = $this->input->post('dateFrom');
        $dateThru = $this->input->post('dateThru');
        $origin = $this->input->post('origin', TRUE);
        $zone = $this->input->post('zone', TRUE);

        // ---------------------------
        // 1. Checker summary gabungan + join zone
        // ---------------------------
        $select_columns = "
            s.amount, 
            s.cod_undelivered, 
            s.qty_tidak_sesuai, 
            s.qty_revisi,
            'mv' AS source_table
        ";

        $sql = "
            SELECT 
                SUM(amount) AS amount,
                SUM(cod_undelivered) AS cod_undelivered,
                SUM(amount) - SUM(cod_undelivered) AS total_paid_cod               
            FROM (
                SELECT $select_columns
                FROM mv_checker_summary s
                LEFT JOIN zone z ON s.zone = z.zone_code
                WHERE s.runsheet_date BETWEEN ? AND ?
                  AND (? = '' OR z.origin_code = ?)
                  AND (? = '' OR z.zone_code = ?)
                UNION ALL
                SELECT $select_columns
                FROM summary_checker s
                LEFT JOIN zone z ON s.zone = z.zone_code
                WHERE s.runsheet_date BETWEEN ? AND ?
                  AND (? = '' OR z.origin_code = ?)
                  AND (? = '' OR z.zone_code = ?)
            ) AS combined
        ";

        $params = [
            $dateFrom . ' 00:00:00',
            $dateThru . ' 23:59:59',
            $origin,
            $origin,
            $zone,
            $zone,
            $dateFrom . ' 00:00:00',
            $dateThru . ' 23:59:59',
            $origin,
            $origin,
            $zone,
            $zone
        ];

        $query = $this->db->query($sql, $params);
        $result = $query->row();

        // ---------------------------
        // 2. runsheet_payment + join courier -> zone -> filter origin/zone + group_by id_courier
        // ---------------------------
        $sqlPayment = "
            SELECT SUM(tot_transfer) AS total_transfer,
                   SUM(tot_cod) AS total_cash,
                   SUM(tot_transfer + tot_cod) AS total_called_paid
            FROM (
                SELECT p.id_courier,
                       SUM(p.transfer) AS tot_transfer,
                       SUM(p.cod_paid) AS tot_cod
                FROM runsheet_payment p
                JOIN (
                    SELECT DISTINCT s.id_courier, z.zone_code, z.origin_code
                    FROM mv_checker_summary s
                    JOIN zone z ON s.zone = z.zone_code
                ) AS courier_zone ON p.id_courier = courier_zone.id_courier
                WHERE p.payment_date BETWEEN ? AND ?
                  AND (? = '' OR courier_zone.origin_code = ?)
                  AND (? = '' OR courier_zone.zone_code = ?)
                GROUP BY p.id_courier
            ) AS grouped_payment
        ";

        $paramsPayment = [
            $dateFrom . ' 00:00:00',
            $dateThru . ' 23:59:59',
            $origin,
            $origin,
            $zone,
            $zone
        ];

        $queryPayment = $this->db->query($sqlPayment, $paramsPayment);
        $paymentResult = $queryPayment->row();

        // ---------------------------
        // 3. courier_overpaid + join courier -> zone -> filter origin/zone + group_by id_courier
        // ---------------------------
        $sqlOverpaid = "
            SELECT SUM(tot_overpaid) AS total_overpaid
            FROM (
                SELECT p.id_courier, SUM(p.amount) AS tot_overpaid
                FROM courier_overpaid p
                JOIN (
                    SELECT DISTINCT s.id_courier, z.zone_code, z.origin_code
                    FROM mv_checker_summary s
                    JOIN zone z ON s.zone = z.zone_code
                ) AS courier_zone ON p.id_courier = courier_zone.id_courier
                WHERE p.payment_date BETWEEN ? AND ?
                  AND (? = '' OR courier_zone.origin_code = ?)
                  AND (? = '' OR courier_zone.zone_code = ?)
                GROUP BY p.id_courier
            ) AS grouped_overpaid
        ";

        $paramsOverpaid = [
            $dateFrom . ' 00:00:00',
            $dateThru . ' 23:59:59',
            $origin,
            $origin,
            $zone,
            $zone
        ];

        $queryOverpaid = $this->db->query($sqlOverpaid, $paramsOverpaid);
        $overpaidResult = $queryOverpaid->row();
        $total_overpaid = (int) $overpaidResult->total_overpaid;

        // ---------------------------
        // 4. plus_minus = total_called_paid + total_overpaid - total_paid_cod
        // ---------------------------
        $plus_minus = (int) $paymentResult->total_called_paid + $total_overpaid - (int) $result->total_paid_cod;

        // ---------------------------
        // 5. JSON Output
        // ---------------------------
        echo json_encode([
            'undel' => (int) $result->cod_undelivered,
            'amount' => (int) $result->amount,
            'difference_pod' => (int) $plus_minus,
            'total_paid_cod' => (int) $result->total_paid_cod,
            'transfer' => (int) $paymentResult->total_transfer,
            'cash' => (int) $paymentResult->total_cash,
            'overpaid' => $total_overpaid,
            'total_called_paid' => (int) $paymentResult->total_called_paid,
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
                || $user_role == "POD"
                || $user_role == "Admin BDO2"
                || $user_role == "Koordinator BDO2"
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

    public function detail_pod()
    {
        $data['page_name'] = 'detail_pod';
        $user_role = $this->session->userdata('role');
        $data['title'] = "Detail POD";
        $data['id_user'] = $this->session->userdata('id_user');
        $data['role'] = $user_role;
        $data['mode'] = "readonly";
        $filter = $this->session->flashdata('filter_pod');
        $data['dateFrom'] = $filter['dateFrom'] ?? date('Y-m-d');
        $data['dateThru'] = $filter['dateThru'] ?? date('Y-m-d');
        $data['select_courier'] = $filter['select_courier'] ?? '';

        // Data untuk dropdown (form awal)
        $data_courier = $this->Courier_model->_get_data_courier();
        $data['data_courier'] = $data_courier;
        // Jika bukan AJAX request, load view normal
        $this->load->view('dashboard', $data);
    }

    public function getSourceDataMultiple()
    {
        $year = $this->input->post('year') ?? date('Y');
        $origin = $this->input->post('origin');
        $zone = $this->input->post('zone');

        // ambil data dari MODEL (uang)
        $source_data = $this->Pod_model->getSourceDataMultiple($year, $origin, $zone);

        $dataBySource = [];

        foreach ($source_data as $row) {
            $status = $row['status_checker']; // Minus Cod, Cod, Total Cod, Transfer
            $month = (int) $row['month'];
            $count = (int) $row['count'];

            // init 12 bulan
            if (!isset($dataBySource[$status])) {
                $dataBySource[$status] = array_fill(1, 12, 0);
            }

            $dataBySource[$status][$month] = $count;
        }


        echo json_encode([
            'success' => true,
            'dataBySource' => $dataBySource,
            'months' => [
                "Jan",
                "Feb",
                "Mar",
                "Apr",
                "May",
                "Jun",
                "Jul",
                "Aug",
                "Sep",
                "Oct",
                "Nov",
                "Dec"
            ]
        ]);
    }


    public function getSourceData()
    {
        $dateFrom = $this->input->post('dateFrom');
        $dateThru = $this->input->post('dateThru');
        $origin = $this->input->post('origin');
        $zone = $this->input->post('zone');

        $source_data = [];

        if ($dateFrom && $dateThru) {
            $source_data = $this->Pod_model->getSourceData(
                $dateFrom,
                $dateThru,
                $origin,
                $zone
            );
        }

        $sourceLabels = [];
        $sourceCounts = [];

        foreach ($source_data as $data) {
            // langsung pakai label dari model
            $sourceLabels[] = $data['status_checker'];
            $sourceCounts[] = (int) $data['count'];
        }

        echo json_encode([
            'success' => true,
            'sourceLabels' => $sourceLabels,
            'sourceCounts' => $sourceCounts
        ]);
    }

    // let value = r.minus || 0; // nilai yang ingin ditampilkan
    // let sign = value < 0 ? '-' : '+';

    // // Ganti teks dengan format rupiah dan tanda
    // $('.minus_cod').text(sign + formatRupiah(Math.abs(value)));

    // // Ganti warna sesuai positif / negatif
    // $('.minus_cod').css('color', value < 0 ? 'red' : 'green');
    public function getdatatables_cod_pod()
    {
        $user_role = $this->session->userdata('role');
        $list = $this->Pod_model->get_datatables_cod_pod();
        $data = array();
        $no = $this->input->post('start', true);
        foreach ($list as $item) {
            // $closing_name = $item->closing_name;
            // var_dump($item);
            $no++;
            $row = array();
            $total_called_cod = $item->amount - $item->cod_undelivered;
            $plus_minus = $item->cod_called;
            $selisih = $item->cod_called - $total_called_cod;
            $persentase_cod = $total_called_cod != 0 ? ($item->cod_called / $total_called_cod) * 100 : 0;





            $row[] = '<small style="font-size:12px">' . $no . '</small>';

            $row[] = '<small style="font-size:12px"><b>' . htmlspecialchars($item->courier_name) . '</b><br>
            ' . htmlspecialchars($item->id_courier) . '
            </small>';
            $row[] = '<small style="font-size:12px"><b>' . htmlspecialchars($item->no_runsheet) . '</b>';
            $row[] = '<small style="font-size:12px">' . date('Y-m-d', strtotime($item->runsheet_date)) . '</small>';
            $row[] = '<small style="font-size:12px">' . date('Y-m-d', strtotime($item->created_at)) . '</small>';
            $row[] = '<small style="font-size:12px"><b>' . htmlspecialchars($item->created_by_name) . '</b>';
            $row[] = '<small style="font-size:12px"><b>' . htmlspecialchars($item->closed_by_name) . '</b>';
            $row[] = '<b style="font-size:12px"> Rp ' . number_format($item->cod_paid) . '/ Rp ' . number_format($total_called_cod) . '</b>
                <br>
                <div class="progress">
                    <div class="progress-bar progress-bar-striped" style="width:' . $persentase_cod . '%">' . number_format($persentase_cod, 1) . '%</div>
                </div>
                ';



            if ($total_called_cod <= $item->cod_called) {
                $row[] = '<span class="badge rounded-pill bg-success"> Lunas </span>';
                $button = ' <div class="form-button-action"> ';

                $button .= '</div>';


            } else {
                $row[] = '<span class="badge rounded-pill bg-warning"> Belum Lunas </span>';

                $button = ' <div class="form-button-action"> ';

                $button .= '</div>';


            }
            ;

            if ($selisih > 0) {
                $row[] = '<b class="text-success" style="font-size:12px">
                            ▲ Rp ' . number_format($selisih) . '
                          </b>';
            } elseif ($selisih < 0) {

                $row[] = '<b class="text-danger" style="font-size:12px">
                            ▼ Rp ' . number_format(abs($selisih)) . '
                          </b>';
            } else {
                $row[] = '<b class="text-muted" style="font-size:12px">
                 Rp ' . number_format(abs($selisih)) . '
              </b>';
            }

            $button .= '
                    <a href="' . base_url('pod/read_detail_pod/' . urlencode(base64_encode($item->id_courier)) . '/' . urlencode(base64_encode($item->runsheet_date))) . '"  
                        class="btn btn-dark waves-effect waves-light btn-sm me-1" 
                        title="Detail" data-plugin="tippy" data-tippy-placement="top">
                        <i class="fa fa-info-circle"> Detail</i>
                    </a>';


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



    public function read_detail_pod($id_courier, $runsheet_date)
    {
        $id_courier = base64_decode(urldecode($id_courier));
        $runsheet_date = base64_decode(urldecode($runsheet_date));
        $runsheet_date = date('Y-m-d', strtotime($runsheet_date));

        $get_cod_pod = $this->Pod_model->get_cod_pod($runsheet_date);

        $data['page_name'] = 'detail_pod';
        $data['title'] = "Detail POD";
        $data['id_user'] = $this->session->userdata('id_user');
        $data['role'] = $this->session->userdata('role');

        $data['mode'] = 'edit';
        $data['dateFrom'] = $runsheet_date;
        $data['dateThru'] = $runsheet_date;

        // 🔥 INI KUNCINYA
        $data['select_courier'] = $id_courier;

        // Data dropdown (WAJIB)
        $data['data_courier'] = $this->get_data_courier();

        $data['get_cod_pod'] = $get_cod_pod;

        $this->load->view('dashboard', $data);
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
    public function get_detail_cod_by_id()
    {
        $start_date = "2025-09-20";
        $end_date = "2025-10-20";
        $id_courier = "BDO1007";

        $this->db->select('amount, cod_undelivered, qty_awb, runsheet_date');
        $this->db->from('mv_checker_summary');
        $this->db->where('Date(runsheet_date) >=', $start_date);
        $this->db->where('Date(runsheet_date) <=', $end_date);
        $this->db->where('id_courier', $id_courier);

        $query = $this->db->get();
        $results = $query->result();

        // Inisialisasi total
        $total_amount = 0;
        $total_cod_undelivered = 0;
        $total_qty_awb = 0;
        $runsheet_dates = [];

        foreach ($results as $row) {
            $total_amount += $row->amount;
            $total_cod_undelivered += $row->cod_undelivered;
            $total_qty_awb += $row->qty_awb;

            $formatted_date = date('Y-m-d', strtotime($row->runsheet_date));
            $runsheet_dates[] = $formatted_date;
        }

        // Hilangkan duplikat tanggal & gabung jadi string
        $unique_dates = array_unique($runsheet_dates);
        $combined_dates = implode(', ', $unique_dates);

        $cod_data = [
            'cod_display' => $total_amount,
            'display_undelivered' => $total_cod_undelivered,
            'total_awb' => $total_qty_awb,
            'runsheet_date' => $combined_dates,
        ];

        print_r($results);
        print_r($cod_data);
    }


    public function get_courier_data()
    {
        $courier_id = $this->input->post('courier_id');
        $date_from = $this->input->post('date_from');
        $date_thru = $this->input->post('date_thru');

        // Get courier info
        $courier = $this->Courier_model->get_by_id($courier_id);

        if (!$courier) {
            echo json_encode([
                'success' => false,
                'message' => 'Kurir tidak ditemukan'
            ]);
            return;
        }

        // Get runsheet data
        $this->db->select('
            no_runsheet,
            DATE(create_date) as create_date,
            COUNT(awb) as total_awb,
            MIN(status_pod) as status_pod,
            SUM(CASE WHEN status_cod IS NOT NULL THEN 1 ELSE 0 END) as success_count,
            COUNT(awb) as total_count
        ');
        $this->db->from('checker');
        $this->db->where('id_courier', $courier_id);
        $this->db->where('DATE(create_date) >=', $date_from);
        $this->db->where('DATE(create_date) <=', $date_thru);
        $this->db->group_by('no_runsheet');
        $this->db->order_by('create_date', 'DESC');

        $runsheets = $this->db->get()->result_array();

        // Count runsheet yang bisa disetorkan
        $runsheet_ready = 0;
        foreach ($runsheets as &$rs) {
            // Logic: bisa disetorkan kalau semua AWB sudah ada status_cod
            $rs['can_submit'] = ($rs['success_count'] == $rs['total_count']);
            if ($rs['can_submit']) {
                $runsheet_ready++;
            }
        }

        // Prepare courier data
        $courier_data = [
            'id_courier' => $courier->id_courier,
            'courier_name' => $courier->courier_name,
            'no_tlp' => $courier->no_tlp,
            'photo' => $courier->id_courier . '.jpg', // Sesuaikan dengan nama file foto
            'runsheet_ready' => $runsheet_ready,
            'runsheet_total' => count($runsheets)
        ];

        echo json_encode([
            'success' => true,
            'courier' => $courier_data,
            'runsheets' => $runsheets
        ]);
    }

    public function get_courier_info()
    {
        $courier_id = $this->input->post('courier_id');
        $date_from = $this->input->post('date_from');
        $date_thru = $this->input->post('date_thru');



        // Get courier data
        $courier = $this->db->get_where('courier', ['id_courier' => $courier_id])->row();

        if (!$courier) {
            echo json_encode([
                'success' => false,
                'message' => 'Kurir tidak ditemukan'
            ]);
            return;
        }

        // Count runsheet total dan ready

        $runsheets = $this->Pod_model->get_runsheet_paid_pod($courier_id, $date_from, $date_thru);
        $runsheet_total = count($runsheets);
        $runsheet_ready = 0;

        foreach ($runsheets as $rs) {
            // Ready kalau semua AWB sudah completed
            if ($rs['total_awb'] == $rs['completed_awb']) {
                $runsheet_ready++;
            }
        }

        $nominal = $this->Pod_model->get_nominal_paid_pod($courier_id, $date_from, $date_thru);


        // print_r($nominal);
        // var_dump("heh");



        echo json_encode([
            'success' => true,
            'courier' => [
                'id_courier' => $courier->id_courier,
                'courier_name' => $courier->courier_name,
                'no_tlp' => $courier->no_tlp,
                'photo' => $courier->id_courier . '.jpg',
                'runsheet_ready' => $runsheet_ready,
                'runsheet_total' => $runsheet_total,
                'nominal' => $nominal->amount ?? 0,
                'delivered' => $nominal->delivered ?? 0,
                'nominalUndel' => $nominal->total_undelivered ?? 0,
                'dateFrom' => $date_from,
                'dateThru' => $date_thru,

            ]
        ]);
    }
    public function get_runsheet($id_courier, $date_from, $date_thru, $create_date)
    {
        $result = [];
        $create_date_only = date('Y-m-d', strtotime($create_date));

        $start = new DateTime($date_from);
        $end = new DateTime($date_thru);
        $end->modify('+1 day');

        for ($date = clone $start; $date < $end; $date->modify('+1 day')) {

            $tanggal = $date->format('Y-m-d');

            if ($tanggal > $create_date_only)
                continue;

            $rs = $this->Checker_model->get_runsheet($id_courier, $tanggal);
            if (!$rs)
                continue;

            $result[] = [
                'tanggal' => $tanggal,
                'no_runsheet' => $rs->no_runsheet,

            ];
        }

        return $result;
    }

    public function poin_hrs($create_date, $runsheet_date, $no_runsheet)
    {
        $create_date_only = date('Y-m-d', strtotime($create_date));

        $poin_obj = $this->Leaderboard_model->get_total_poin_courier($no_runsheet);
        $total_poin = isset($poin_obj->total_poin) ? (int) $poin_obj->total_poin : 0;

        $poin = 0;
        $minus = 0;

        if ($runsheet_date == $create_date_only) {
            // 🔥 HARI INI
            $poin = 30;

        } elseif ($runsheet_date == date('Y-m-d', strtotime('-1 day', strtotime($create_date_only)))) {
            // 🔥 KEMARIN
            $minus = $total_poin;
        }

        return [
            'tanggal' => $runsheet_date,
            'poin' => $poin,
            'minus' => $minus,
            'id_leaderboard' => $poin_obj->id_leaderboard ?? null
        ];
    }





    public function payment_cod()
    {
        $courier_id = $this->input->post('courier_id');
        $date_from = $this->input->post('date_from');
        $date_thru = $this->input->post('date_thru');
        $payment_method = $this->input->post('payment_method'); // cod / transfer
        $cod = (int) $this->input->post('cod');
        $transfer = (int) $this->input->post('transfer');
        $create_date = date('Y-m-d H:i:s');

       
        $runsheet_list = $this->get_runsheet(
            $courier_id,
            $date_from,
            $date_thru,
            $create_date
        );

        $final = [];

        foreach ($runsheet_list as $row) {

            $detail = $this->poin_hrs(
                $create_date,
                $row['tanggal'],
                $row['no_runsheet']
            );

            $final[] = array_merge(
                ['no_runsheet' => $row['no_runsheet']],
                $detail
            );
        }
        

        $data_poin = []; // harus array of arrays

        foreach ($final as $row) {
            if (!isset($row['id_leaderboard']))
                continue; // aman

            $data_poin[] = [
                'id_courier' => $courier_id,
                'id_leaderboard' => $row['id_leaderboard'],
                'no_runsheet' => $row['no_runsheet'],
                'create_date' => $create_date,
                'hrs' => $row['poin'],
                'minus_poin' => $row['minus']
            ];
        }


        if (!empty($data_poin)) {
            $rows_updated = $this->db->update_batch('leaderboard', $data_poin, 'id_leaderboard');
            $runsheets = array_unique(array_column($final, 'no_runsheet'));
            foreach ($runsheets as $no_runsheet) {
                $this->Leaderboard_model->refresh_total_poin($no_runsheet);
            }
            $this->Leaderboard_model->refresh_total_poin_all();
            $this->Leaderboard_model->refresh_mv_leaderboard_summary();

            if ($rows_updated) {                
            } else {                
                return $this->response_notify(
                    'danger',
                    'Gagal update, cek data atau key id_leaderboard.'
                );
            }
        } else {
            
            return $this->response_notify(
                'danger',
                'Tidak ada poin data yang bisa di-update.'
            );
        }
        // ===============================
        // VALIDASI DASAR
        // ===============================
        $paid_amount = ($payment_method === 'cod') ? $cod : $transfer;

        if ($paid_amount <= 0) {
            return $this->response_notify(
                'danger',
                'Nominal pembayaran tidak valid'
            );
        }

        if (!in_array($payment_method, ['cod', 'transfer'])) {
            return $this->response_notify(
                'danger',
                'Metode pembayaran tidak valid'
            );
        }

        // ===============================
        // CEK OVERPAID (HARD STOP)
        // ===============================
        if (
            $this->Pod_model->has_overpaid_runsheet(
                $courier_id,
                $date_from,
                $date_thru
            )
        ) {
            return $this->response_notify(
                'danger',
                'Terdapat runsheet yang sudah overpaid'
            );
        }

        // ===============================
        // AMBIL RUNSHEET YANG MASIH PERLU DIBAYAR
        // ===============================
        $runsheets = $this->Pod_model->get_unpaid_runsheets(
            $courier_id,
            $date_from,
            $date_thru
        );

        if (empty($runsheets)) {
            return $this->response_notify(
                'info',
                'Tidak ada runsheet yang perlu dibayar'
            );
        }

        // ===============================
        // PROSES PEMBAYARAN (TRANSACTION)
        // ===============================
        $this->db->trans_begin();

        $sisa_uang = $paid_amount;
        $used = 0;

        $next_sequence=$this->Pod_model->get_sequence($courier_id);
        foreach ($runsheets as $rs) {
             // Hitung sequence terakhir untuk courier hari ini

            if ($sisa_uang <= 0)
                break;

            $sisa_tagihan = $rs->total_cod - $rs->already_paid;
            if ($sisa_tagihan <= 0)
                continue;

            $bayar = min($sisa_uang, $sisa_tagihan);

            $this->db->insert('runsheet_payment', [
                'no_runsheet' => $rs->no_runsheet,
                'id_courier' => $courier_id,
                'payment_date' => date('Y-m-d'),
                'cod_paid' => ($payment_method === 'cod') ? $bayar : 0,
                'transfer' => ($payment_method === 'transfer') ? $bayar : 0,
                'status' => 'DRAFT',
                'created_by' => $this->session->userdata('id_user'),
                'created_at' => date('Y-m-d H:i:s'),
                'sequence_hrs' => $next_sequence,

            ]);
            $next_sequence++; 
            // 🔥 AUTO CLOSE CHECK
            $summary = $this->Pod_model
                ->get_runsheet_summary($rs->no_runsheet);

            if ($summary && $summary->total_paid >= $summary->total_cod) {
                $this->Pod_model->auto_close_runsheet(
                    $rs->no_runsheet,
                    $this->session->userdata('id_user')
                );
            }

            $sisa_uang -= $bayar;
            $used += $bayar;
        }


        // ===============================
        // OVERPAID → SIMPAN TERPISAH
        // ===============================
        if ($sisa_uang > 0) {
            $this->Pod_model->insert_courier_overpaid($courier_id,$sisa_uang);           
        }

        // ===============================
        // COMMIT / ROLLBACK
        // ===============================
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();

            return $this->response_notify(
                'danger',
                'Gagal menyimpan pembayaran'
            );
        }

        $this->db->trans_commit();

        // ===============================
        // RESPONSE KE FE
        // ===============================
        return $this->response_notify(
            'success',
            'Pembayaran berhasil disimpan',
            [
                'paid' => $paid_amount,
                'used' => $used,
                'overpaid' => $sisa_uang
            ],
            base_url('pod/detail_pod')
        );
    }
    public function testing(){
         // ===============================
         return $this->response_notify(
            'success',
            'Pembayaran berhasil disimpan',
            [
                'paid' => "hehe",
                
            ],
            base_url('pod/detail_pod')
        );
    }
    private function response_notify(
        $status,
        $message,
        $data = [],
        $redirect = null
    ) {
        echo json_encode([
            'status' => $status,
            'message' => $message,
            'data' => $data,
            'redirect' => $redirect
        ]);
        exit;
    }






}
