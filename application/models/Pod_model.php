<?php

class Pod_model extends CI_Model
{

    var $checker_column_order = array(null, "ch.id_courier", "courier_name", "qty_sesuai", "qty_revisi", "qty_tidak_sesuai", "ch.runsheet_date", "ch.upload_by", "ch.zone", "zone_name"); //set column field database for datatable orderable
    var $checker_column_search = array("ch.no_runsheet", "c.id_courier", "m.runsheet_date", "ch.closing_hrs_by", "ch.create_hrs_by", "c.courier_name"); //set column field database for datatable orderable

    private function _get_datatables_cod_pod()
    {
        $dateFrom = $this->input->post('dateFrom'); // "2025-08"        
        $dateThru = $this->input->post('dateThru'); // "2025-08"        
        $origin = $this->input->post('origin'); // "2025-08"     
        $zone = $this->input->post('zone'); // "2025-08"     
        $role = $this->input->post('role', TRUE);



        $rp_subquery = "
        (
            SELECT
                rp.id_courier,
                DATE(rp.created_at) AS created_date,
                GROUP_CONCAT(DISTINCT rp.no_runsheet ORDER BY rp.no_runsheet SEPARATOR ', ') AS no_runsheet,
                MIN(rp.created_at) AS created_at,
                MIN(rp.created_by) AS created_by,
                MAX(rp.closed_by) AS closed_by,
                SUM(rp.cod_paid) AS cod_paid,
                SUM(rp.transfer) AS transfer,
                MAX(rp.status) AS status
            FROM runsheet_payment rp
            GROUP BY rp.id_courier, DATE(rp.created_at)
        ) rp
        ";

        $overpaid_subquery = "
        (
            SELECT
                id_courier,
                DATE(payment_date) AS payment_date,
                SUM(amount) AS overpaid_amount
            FROM courier_overpaid
            GROUP BY id_courier, DATE(payment_date)
        ) cop
        ";

        $checker_subquery = "
        (
            SELECT
                id_courier,
                DATE(runsheet_date) AS runsheet_date,
                SUM(amount) AS amount,
                SUM(cod_undelivered) AS cod_undelivered
            FROM (
                SELECT id_courier, runsheet_date, amount, cod_undelivered FROM mv_checker_summary
                UNION ALL
                SELECT id_courier, runsheet_date, amount, cod_undelivered FROM summary_checker
            ) combined
            GROUP BY id_courier, DATE(runsheet_date)
        ) cd
        ";

        $this->db->select("
            rp.id_courier,
            cd.runsheet_date,
            rp.no_runsheet,
            rp.created_at,
            COALESCE(uc.name, 'Unknown') AS created_by_name,
            COALESCE(ucl.name, 'Unknown') AS closed_by_name,
            cd.amount,
            cd.cod_undelivered,
            rp.cod_paid,
            rp.transfer,
            COALESCE(cop.overpaid_amount, 0) AS overpaid,
            (CAST(rp.cod_paid AS UNSIGNED) + CAST(rp.transfer AS UNSIGNED) + COALESCE(CAST(cop.overpaid_amount AS UNSIGNED), 0)) AS cod_called,
            rp.status,
            c.courier_name
        ", FALSE);

        $this->db->from($rp_subquery);
        $this->db->join($overpaid_subquery, 'rp.id_courier = cop.id_courier AND rp.created_date = cop.payment_date', 'left');
        $this->db->join($checker_subquery, 'rp.id_courier = cd.id_courier AND rp.created_date = cd.runsheet_date', 'left');
        $this->db->join('courier c', 'c.id_courier = rp.id_courier', 'left');
        $this->db->join('users uc', 'rp.created_by = uc.id_user', 'left');
        $this->db->join('users ucl', 'rp.closed_by = ucl.id_user', 'left');

        $this->db->group_by(['rp.id_courier', 'rp.created_date']);
        // // Filter berdasarkan date range
        // if (!empty($dateFrom) && !empty($dateThru)) {
        //     $this->db->where('DATE(checker_data.runsheet_date) >=', $dateFrom);
        //     $this->db->where('DATE(checker_data.runsheet_date) <=', $dateThru);
        // }

        // // Filter berdasarkan zone
        // if (!empty($zone)) {
        //     $this->db->where('checker_data.zone', $zone);
        // }

        // // Filter berdasarkan origin (jika diperlukan join dengan zone)
        // if (!empty($origin)) {
        //     $this->db->where('z.origin_code', $origin);
        // }



        $i = 0;

        if (@$_POST['search']['value']) {
            foreach ($this->checker_column_search as $item) {
                if ($i === 0) {
                    $this->db->group_start()
                        ->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if (count($this->checker_column_search) - 1 == $i) {
                    $this->db->group_end();
                }
                $i++;
            }
        }

        if (isset($_POST['order'])) {
            $this->db->order_by($this->checker_column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } elseif (isset($this->order)) {
            $checker_order = $this->order;
            $this->db->order_by(key($checker_order), $checker_order[key($checker_order)]);
        }
    }

    function get_datatables_cod_pod()
    {
        $this->_get_datatables_cod_pod();
        if (@$_POST['length'] != 1)
            $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    // Function untuk count total records (diperlukan untuk pagination DataTables)
    function count_filtered_cod_pod()
    {
        $this->_get_datatables_cod_pod();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_all_cod_pod()
    {
        $dateFrom = $this->input->post('dateFrom');
        $dateThru = $this->input->post('dateThru');
        $origin = $this->input->post('origin');
        $zone = $this->input->post('zone');

        $union_subquery = "
            (
                SELECT 
                    mv.no_runsheet,
                    mv.amount,
                    mv.zone,
                    mv.runsheet_date
                FROM mv_checker_summary mv
                
                UNION ALL
                
                SELECT 
                    sc.no_runsheet,
                    sc.amount,
                    sc.zone,
                    sc.runsheet_date
                FROM summary_checker sc
            ) AS checker_data
        ";

        // ✅ TAMBAHAN WAJIB (INI KUNCINYA)
        $this->db->select('rp.no_runsheet');

        $this->db->from('runsheet_payment rp');
        $this->db->join($union_subquery, 'rp.no_runsheet = checker_data.no_runsheet', 'left');

        if (!empty($dateFrom) && !empty($dateThru)) {
            $this->db->where('DATE(checker_data.runsheet_date) >=', $dateFrom);
            $this->db->where('DATE(checker_data.runsheet_date) <=', $dateThru);
        }

        if (!empty($zone)) {
            $this->db->where('checker_data.zone', $zone);
        }

        $this->db->group_by('rp.no_runsheet');

        return $this->db->count_all_results();
    }


    public function refresh_total_poin()
    {
        $bulan = date('n'); // 1-12
        $tahun = date('Y'); // 4-digit year

        $this->db->where('leaderboard_month', $bulan);
        $this->db->where('leaderboard_year', $tahun);
        $this->db->set('total_poin', 'succes_rate + kpi  + photo_pod + hrs', false);
        $this->db->update('leaderboard');
    }
    public function add_poin_photo_pod($id_courier)
    {
        $bulan = date('n'); // 1-12
        $tahun = date('Y'); // 4-digit year

        $this->db->where('leaderboard_month', $bulan);
        $this->db->where('leaderboard_year', $tahun);
        $this->db->set('photo_pod', '');
        $this->db->update('leaderboard');
    }
    public function get_poin_photo_pod($id_courier)
    {
        $start_date = date('Y-m-01 00:00:00');
        $end_date = date('Y-m-t 23:59:59');

        $this->db->select('qty_awb,qty_sesuai,qty_revisi,qty_tidak_sesuai');
        $this->db->where('create_date >=', $start_date);
        $this->db->where('create_date <=', $end_date);
        $this->db->from('mv_checker_summary');

        $query = $this->db->get();
        return $query->result();
    }



    public function status_pod()
    {
        $this->db->select('
        SUM(IF(status_cod LIKE "D%", 1, 0)) AS delivered,
        SUM(IF(status_cod LIKE "U%", 1, 0)) AS undelivered
    ');

        $this->db->where('');
        $this->db->from('checker');

    }

    public function get_cod_pod($no_runsheet)
    {

        $this->db->select('
    
    r.status,    
    r.no_runsheet,        
    r.cod_paid,
    r.payment_date,
    r.created_at,
    r.id_courier,    
    r.transfer,
    
    
    


    c.id,
    c.courier_name,
    c.nik,
    c.tipe_courier,
    c.area,
    c.zone,
    c.no_tlp,

    m.success_pod,
    m.in_progress_pod,
    m.amount,
    m.cod_undelivered,
    
    m.qty_awb
    
    ');
        $this->db->where('r.no_runsheet', $no_runsheet);
        $this->db->from('runsheet_payment r');
        $this->db->join('courier c', 'c.id_courier=r.id_courier', 'left');
        // $this->db->join('users u', 'u.id_user=r.closing_hrs_by', 'left');
        $this->db->join('mv_checker_summary m', 'm.id_courier=r.id_courier', 'left');

        $query = $this->db->get();
        return $query->result();
    }  

    public function get_detail_cod_by_id($id_courier, $start_date, $end_date)
    {

        $this->db->select('amount, cod_undelivered, qty_awb, runsheet_date');
        $this->db->from('mv_checker_summary');
        $this->db->where('Date(runsheet_date) >=', $start_date);
        $this->db->where('Date(runsheet_date) <=', $end_date);
        $this->db->where('id_courier', $id_courier);

        $query = $this->db->get();
        return $query->result();


    }

    function get_status_awb($no_runsheet)
    {
        $this->db->select('
        SUM(IF(status_cod LIKE "D%", 1, 0)) AS delivered,
        SUM(IF(status_cod LIKE "U%", 1, 0)) AS undelivered
    ');
        $this->db->from('checker');
        $this->db->where('no_runsheet', $no_runsheet);
        $query = $this->db->get();
        return $query->row();

    }
    function get_status_awb_by_id($id_courier, $start_date, $end_date)
    {
        $this->db->select('
            SUM(IF(status_cod LIKE "D%", 1, 0)) AS delivered,
            SUM(IF(status_cod LIKE "U%", 1, 0)) AS undelivered,
            SUM(IF(status_cod NOT LIKE "D%" AND status_cod NOT LIKE "U%", 1, 0)) AS other
        ');
        $this->db->from('checker');
        $this->db->where('id_courier', $id_courier);
        $this->db->where('DATE(runsheet_date) >=', $start_date);
        $this->db->where('DATE(runsheet_date) <=', $end_date);
        $query = $this->db->get();
        return $query->row();
    }


    // Jika hanya ingin refresh satu kurir per bulan

    public function getSourceDataMultiple($year, $origin, $zone)
    {
        $result = [];

        for ($m = 1; $m <= 12; $m++) {

            // ===== CHECKER SUMMARY =====
            $sqlChecker = "
    SELECT 
        SUM(s.amount) AS amount,
        SUM(s.cod_undelivered) AS undel
    FROM (
        SELECT runsheet_date, zone, amount, cod_undelivered
        FROM mv_checker_summary
        UNION ALL
        SELECT runsheet_date, zone, amount, cod_undelivered
        FROM summary_checker
    ) s
    LEFT JOIN zone z ON s.zone = z.zone_code
    WHERE YEAR(s.runsheet_date) = ?
      AND MONTH(s.runsheet_date) = ?
      AND (? = '' OR z.origin_code = ?)
      AND (? = '' OR z.zone_code = ?)
";


            $checker = $this->db->query(
                $sqlChecker,
                [$year, $m, $origin, $origin, $zone, $zone]
            )->row();

            $total_paid_cod = (int) $checker->amount - (int) $checker->undel;

            // ===== PAYMENT =====
            $sqlPayment = "
            SELECT 
                SUM(p.cod_paid) AS cash,
                SUM(p.transfer) AS transfer
            FROM runsheet_payment p
            JOIN (
                SELECT DISTINCT s.id_courier, z.origin_code, z.zone_code
                FROM mv_checker_summary s
                JOIN zone z ON s.zone = z.zone_code
            ) cz ON p.id_courier = cz.id_courier
            WHERE YEAR(p.payment_date) = ?
              AND MONTH(p.payment_date) = ?
              AND (? = '' OR cz.origin_code = ?)
              AND (? = '' OR cz.zone_code = ?)
        ";

            $payment = $this->db->query(
                $sqlPayment,
                [$year, $m, $origin, $origin, $zone, $zone]
            )->row();

            // ===== OVERPAID =====
            $sqlOver = "
            SELECT SUM(p.amount) AS overpaid
            FROM courier_overpaid p
            JOIN (
                SELECT DISTINCT s.id_courier, z.origin_code, z.zone_code
                FROM mv_checker_summary s
                JOIN zone z ON s.zone = z.zone_code
            ) cz ON p.id_courier = cz.id_courier
            WHERE YEAR(p.payment_date) = ?
              AND MONTH(p.payment_date) = ?
              AND (? = '' OR cz.origin_code = ?)
              AND (? = '' OR cz.zone_code = ?)
        ";

            $over = $this->db->query(
                $sqlOver,
                [$year, $m, $origin, $origin, $zone, $zone]
            )->row();

            $called_paid = (int) $payment->cash + (int) $payment->transfer;
            $minus = $called_paid + (int) $over->overpaid - $total_paid_cod;

            // ===== FORMAT SESUAI CONTROLLER =====
            $result[] = ['status_checker' => 'Minus Cod', 'month' => $m, 'count' => $minus];
            $result[] = ['status_checker' => 'Cod', 'month' => $m, 'count' => (int) $payment->cash];
            $result[] = ['status_checker' => 'Total Cod', 'month' => $m, 'count' => $total_paid_cod];
            $result[] = ['status_checker' => 'Transfer', 'month' => $m, 'count' => (int) $payment->transfer];
        }

        return $result;
    }



    public function getSourceData($dateFrom, $dateThru, $origin, $zone)
    {
        // ===== CHECKER SUMMARY (Total COD & Undelivered) =====
        $sqlChecker = "
            SELECT 
                SUM(s.amount) AS amount,
                SUM(s.cod_undelivered) AS undel
            FROM (
                SELECT runsheet_date, zone, amount, cod_undelivered
                FROM mv_checker_summary
                UNION ALL
                SELECT runsheet_date, zone, amount, cod_undelivered
                FROM summary_checker
            ) s
            JOIN zone z ON s.zone = z.zone_code
            WHERE s.runsheet_date BETWEEN ? AND ?
              AND (? = '' OR z.origin_code = ?)
              AND (? = '' OR z.zone_code = ?)
        ";

        $checker = $this->db->query(
            $sqlChecker,
            [$dateFrom, $dateThru, $origin, $origin, $zone, $zone]
        )->row();

        $total_paid_cod = (int) $checker->amount - (int) $checker->undel;

        // ===== PAYMENT (Cash & Transfer) =====
        $sqlPayment = "
            SELECT 
                SUM(p.cod_paid) AS cash,
                SUM(p.transfer) AS transfer
            FROM runsheet_payment p
            JOIN (
                SELECT DISTINCT s.id_courier, z.origin_code, z.zone_code
                FROM mv_checker_summary s
                JOIN zone z ON s.zone = z.zone_code
            ) cz ON p.id_courier = cz.id_courier
            WHERE p.payment_date BETWEEN ? AND ?
              AND (? = '' OR cz.origin_code = ?)
              AND (? = '' OR cz.zone_code = ?)
        ";

        $payment = $this->db->query(
            $sqlPayment,
            [$dateFrom, $dateThru, $origin, $origin, $zone, $zone]
        )->row();

        // ===== OVERPAID =====
        $sqlOver = "
            SELECT SUM(p.amount) AS overpaid
            FROM courier_overpaid p
            JOIN (
                SELECT DISTINCT s.id_courier, z.origin_code, z.zone_code
                FROM mv_checker_summary s
                JOIN zone z ON s.zone = z.zone_code
            ) cz ON p.id_courier = cz.id_courier
            WHERE p.payment_date BETWEEN ? AND ?
              AND (? = '' OR cz.origin_code = ?)
              AND (? = '' OR cz.zone_code = ?)
        ";

        $over = $this->db->query(
            $sqlOver,
            [$dateFrom, $dateThru, $origin, $origin, $zone, $zone]
        )->row();

        // ===== HITUNGAN FINAL =====
        $called_paid = (int) $payment->cash + (int) $payment->transfer;
        $minus_cod = $called_paid + (int) $over->overpaid - $total_paid_cod;

        // ===== FORMAT KHUSUS PIE CHART =====
        return [
            // [
            //     'status_checker' => 'Minus Cod',
            //     'count' => $minus_cod
            // ],
            [
                'status_checker' => 'Cod',
                'count' => (int) $payment->cash
            ],
            // [
            //     'status_checker' => 'Total Cod',
            //     'count' => $total_paid_cod
            // ],
            [
                'status_checker' => 'Transfer',
                'count' => (int) $payment->transfer
            ]
        ];
    }


    public function get_status_pod($no_runsheet)
    {
        $this->db->select("status_pod");
        $this->db->where("no_runsheet", $no_runsheet);
        $this->db->from("mv_checker_summary");

        $query = $this->db->get();
        $result = $query->row();

        return $result;
    }
    public function get_status_pod_by_id($id_courier, $start_date, $end_date)
    {
        $this->db->select("status_pod");
        $this->db->where("id_courier", $id_courier);
        $this->db->where('Date(runsheet_date) >=', $start_date);
        $this->db->where('Date(runsheet_date) <=', $end_date);
        $this->db->from("mv_checker_summary");

        $query = $this->db->get();
        $result = $query->row();

        return $result;
    }



    // Fungsi untuk cari data kurir
    public function get_courier_with_depositable_runsheet($id_courier = null, $date_from = null, $date_thru = null)
    {
        $this->db->select('c.*'); // Semua field dari tabel courier
        $this->db->from('courier c');
        $this->db->join('checker ch', 'ch.id_courier = c.id_courier', 'inner');

        // KONDISI BISNIS: Sesuaikan dengan kebutuhanmu
        // Contoh: cari kurir yang punya runsheet dengan status tertentu
        $this->db->where('ch.status_cod IS NOT NULL', null, false);
        $this->db->where('ch.status_checker', 'Sesuai');

        if ($id_courier && $id_courier !== 'ALL') {
            $this->db->where('c.id_courier', $id_courier);
        }

        if ($date_from && $date_thru) {
            $this->db->where('ch.runsheet_date >=', $date_from);
            $this->db->where('ch.runsheet_date <=', $date_thru);
        }

        $this->db->group_by('c.id_courier');
        $this->db->limit(1); // Ambil 1 saja untuk card

        $query = $this->db->get();
        return $query->row_array();
    }


    public function calculate_runsheet_stats($id_courier, $date_from = null, $date_thru = null)
    {
        // Hitung total runsheet yang bisa disetorkan
        $this->db->select('COUNT(DISTINCT no_runsheet) as total_depositable');
        $this->db->from('checker');
        $this->db->where('id_courier', $id_courier);
        $this->db->where('status_cod IS NOT NULL', null, false);
        $this->db->where('status_checker', 'Sesuai');

        if ($date_from && $date_thru) {
            $this->db->where('runsheet_date >=', $date_from);
            $this->db->where('runsheet_date <=', $date_thru);
        }

        $query1 = $this->db->get();
        $depositable = $query1->row()->total_depositable;

        // Hitung total semua runsheet (sesuai kondisi bisnis)
        $this->db->select('COUNT(DISTINCT no_runsheet) as total_all');
        $this->db->from('checker');
        $this->db->where('id_courier', $id_courier);

        if ($date_from && $date_thru) {
            $this->db->where('runsheet_date >=', $date_from);
            $this->db->where('runsheet_date <=', $date_thru);
        }

        $query2 = $this->db->get();
        $total = $query2->row()->total_all;

        return [
            'depositable' => $depositable ?: 0,
            'total' => $total ?: 0
        ];
    }

    public function get_detail_cod_by_no_runsheet($no_runsheet)
    {
        $this->db->select("
        SUM(IF(status_cod LIKE 'u%', 1, 0)) AS undeliverd,
        SUM(IF(status_cod LIKE 'd%', 1, 0)) AS delivered,
        count(awb) as total_awb,
    ");
        $this->db->from('checker');
        $this->db->where('no_runsheet', $no_runsheet);
        $query = $this->db->get();

        return $query->row(); // lebih tepat karena hasilnya satu baris
    }


    public function update_status_dri($dri)
    {
        try {
            $this->db->where('no_runsheet', $dri); // Bisa menerima array
            $this->db->update('checker', ['status_hrs' => 1]);
            return $this->db->affected_rows() > 0; // Pastikan ada data yang terupdate
        } catch (Exception $e) {
            return false;
        }
    }


    public function get_runsheet_paid_pod($courier_id, $date_from, $date_thru)
    {
        $this->db->select('
        no_runsheet,
        COUNT(awb) as total_awb,
        SUM(CASE WHEN status_pod IS NOT NULL THEN 1 ELSE 0 END) as completed_awb           
    ');

        $this->db->from('checker');
        $this->db->where('id_courier', $courier_id);
        $this->db->where('DATE(runsheet_date) >=', $date_from);
        $this->db->where('DATE(runsheet_date) <=', $date_thru);
        $this->db->group_by('no_runsheet');
        $runsheets = $this->db->get()->result_array();

        return $runsheets;
    }

    public function get_nominal_paid_pod($courier_id, $date_from, $date_thru)
    {
        return $this->db
            ->select("
                SUM(IF(status_cod LIKE 'd%' , amount, 0)) AS total_delivered,
                SUM(IF(status_cod LIKE 'u%', amount, 0)) AS total_undelivered,
                SUM(amount) AS amount
            ")
            ->where('id_courier', $courier_id)
            ->where('runsheet_date >=', $date_from . ' 00:00:00')
            ->where('runsheet_date <=', $date_thru . ' 23:59:59')
            ->get('checker')
            ->row();
    }

    public function get_amount_by_runsheet($courier_id, $date_from, $date_thru)
    {
        $this->db->select("
        no_runsheet,
       sum(if(status_cod like 'D%',amount,0 )) as total_delivered,
       sum(if(status_cod like 'U%',amount,0 )) as total_undelivered,
       sum(amount) as amount
       
       ");
        $this->db->from("checker");
        $this->db->where('id_courier', $courier_id);
        $this->db->where('DATE(runsheet_date) >=', $date_from);
        $this->db->where('DATE(runsheet_date) <=', $date_thru);
        $this->db->group_by('no_runsheet');
        $query = $this->db->get();



        return $query->result();
    }




    public function get_runsheet_with_payment($courier_id, $date_from, $date_thru)
    {
        $sql = "
            SELECT 
                c.no_runsheet,
                SUM(IF(c.status_cod LIKE 'D%', c.amount, 0)) AS total_delivered,
                IFNULL(SUM(p.cod_paid + p.transfer),0) AS already_paid
            FROM checker c
            LEFT JOIN runsheet_payment p
                ON p.no_runsheet = c.no_runsheet
            WHERE c.id_courier = ?
            AND DATE(c.runsheet_date) BETWEEN ? AND ?
            GROUP BY c.no_runsheet
            HAVING already_paid < total_delivered
            ORDER BY c.runsheet_date ASC
        ";

        return $this->db->query(
            $sql,
            [$courier_id, $date_from, $date_thru]
        )->result();
    }


    public function has_overpaid_or_closed_runsheet($courier_id, $date_from, $date_thru)
    {
        $sql = "
        SELECT 1
        FROM (
            SELECT 
                c.no_runsheet,
                SUM(IF(c.status_cod LIKE 'D%', c.amount, 0)) AS total_cod,
                IFNULL(SUM(p.cod_paid + p.transfer),0) AS total_paid
            FROM checker c
            LEFT JOIN runsheet_payment p
                ON p.no_runsheet = c.no_runsheet
            WHERE c.id_courier = ?
            AND DATE(c.runsheet_date) BETWEEN ? AND ?
            GROUP BY c.no_runsheet
        ) x
        WHERE x.total_cod > 0
        AND x.total_paid >= x.total_cod
        LIMIT 1
    ";

        return $this->db->query(
            $sql,
            [$courier_id, $date_from, $date_thru]
        )->num_rows() > 0;
    }


    public function get_total_cod_by_runsheet(
        $courier_id,
        $date_from,
        $date_thru
    ) {
        $sql = "
            SELECT 
                no_runsheet,
                SUM(IF(status_cod LIKE 'D%', amount, 0)) AS total_cod,
                DATE(runsheet_date) AS runsheet_date
            FROM checker
            WHERE id_courier = ?
            AND DATE(runsheet_date) BETWEEN ? AND ?
            GROUP BY no_runsheet
        ";

        return $this->db->query(
            $sql,
            [$courier_id, $date_from, $date_thru]
        )->result();
    }


    public function get_total_payment_by_runsheet()
    {
        $sql = "
            SELECT 
                no_runsheet,
                SUM(cod_paid + transfer) AS total_paid
            FROM runsheet_payment
            GROUP BY no_runsheet
        ";

        $rows = $this->db->query($sql)->result();
        $map = [];

        foreach ($rows as $r) {
            $map[$r->no_runsheet] = (int) $r->total_paid;
        }

        return $map;
    }



    public function get_unpaid_runsheets(
        $courier_id,
        $date_from,
        $date_thru
    ) {
        $runsheets = $this->get_total_cod_by_runsheet(
            $courier_id,
            $date_from,
            $date_thru
        );

        $paid_map = $this->get_total_payment_by_runsheet();

        $result = [];

        foreach ($runsheets as $rs) {

            $already_paid = $paid_map[$rs->no_runsheet] ?? 0;

            if ($already_paid < $rs->total_cod) {
                $rs->already_paid = $already_paid;
                $rs->sisa_tagihan = $rs->total_cod - $already_paid;
                $result[] = $rs;
            }
        }

        return $result;
    }

    public function has_overpaid_runsheet(
        $courier_id,
        $date_from,
        $date_thru
    ) {
        $runsheets = $this->get_total_cod_by_runsheet(
            $courier_id,
            $date_from,
            $date_thru
        );

        $paid_map = $this->get_total_payment_by_runsheet();

        foreach ($runsheets as $rs) {
            $paid = $paid_map[$rs->no_runsheet] ?? 0;

            if ($paid > $rs->total_cod) {
                return true;
            }
        }

        return false;
    }

    public function get_runsheet_summary($no_runsheet)
    {
        $sql = "
            SELECT 
                SUM(IF(c.status_cod LIKE 'D%', c.amount, 0)) AS total_cod,
                IFNULL(SUM(p.cod_paid + p.transfer),0) AS total_paid
            FROM checker c
            LEFT JOIN runsheet_payment p
                ON p.no_runsheet = c.no_runsheet
            WHERE c.no_runsheet = ?
        ";

        return $this->db->query($sql, [$no_runsheet])->row();
    }
    public function auto_close_runsheet($no_runsheet, $user_id)
    {
        $this->db->where('no_runsheet', $no_runsheet)
            ->where('status', 'DRAFT')
            ->update('runsheet_payment', [
                'status' => 'CLOSED',
                'closed_by' => $user_id,
                'created_at' => date('Y-m-d H:i:s')
            ]);
    }


    public function get_sequence($courier_id)
    {
        $last_sequence =$this->db
        ->select_max('sequence_hrs')
        ->from('runsheet_payment')
        ->where('id_courier', $courier_id)
        ->where('DATE(created_at)', date('Y-m-d'))
        ->get()
        ->row();
    
    $next_sequence = $last_sequence ? ((int)$last_sequence->sequence_hrs + 1) : 1;

        return $next_sequence;

    }

    public function insert_courier_overpaid($courier_id,$sisa_uang)
    {
        $this->db->insert('courier_overpaid', [
            'id_courier' => $courier_id,
            'payment_date' => date('Y-m-d'),
            'amount' => $sisa_uang,
            'note' => 'Overpaid otomatis dari pembayaran COD/Transfer',
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}





?>