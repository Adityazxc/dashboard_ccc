<?php

class Checker_model extends CI_Model
{

    var $checker_column_order = array(null, "id_courier", "courier_name", "qty_sesuai", "qty_revisi", "qty_tidak_sesuai",null, "runsheet_date", "upload_by", "zone", "zone_name","status_pod"); //set column field database for datatable orderable
    var $checker_column_search = array("id_courier", "courier_name", "zone"); //set column field database for datatable orderable

    function getdatatables_checker()
    {
        $dateFrom = $this->input->post('dateFrom', TRUE);
        $dateThru = $this->input->post('dateThru', TRUE);
        $origin = $this->input->post('origin', TRUE);
        $zone = $this->input->post('zone', TRUE);
        $role = $this->input->post('role', TRUE);
        $search = $this->input->post('search')['value'];
        $orderColumnIndex = $_POST['order']['0']['column'];
        $orderDir = $_POST['order']['0']['dir'];
        $start = $_POST['start'];
        $length = $_POST['length'];

        // Kolom yang akan dipilih, harus sama di kedua tabel
        $select_columns = "
            id_courier,no_runsheet, id_checker, runsheet_date, create_date, upload_by,
            zone, status_checker, qty_awb, qty_sesuai, qty_revisi, qty_tidak_sesuai,
            courier_name, zone_name, origin_code, role, name, username, success_pod, in_progress_pod, amount,status_pod,
            'mv' AS source_table
        ";          


        // Kolom-kolom search
        $searchColumns = ["id_courier", "courier_name", "zone"];

        // Kolom urutan (harus cocok dengan index datatable)
        $orderColumns = array("id_courier", "courier_name", "qty_sesuai", "qty_revisi", "qty_tidak_sesuai", "runsheet_date", "upload_by", "zone", "zone_name","status_pod");
        $orderColumn = $orderColumns[$orderColumnIndex];

        // Build filter WHERE (tanpa search)
        $where = "create_date BETWEEN ? AND ?";
        $params = [$dateFrom . ' 00:00:00', $dateThru . ' 23:59:59'];

        if (!empty($origin)) {
            $where .= " AND origin_code = ?";
            $params[] = $origin;
        }

        if (!empty($zone)) {
            $where .= " AND zone = ?";
            $params[] = $zone;
        }

        // if (!empty($role) && $role === "Koordinator") {
        //     $where .= " AND role = ?";
        //     $params[] = $role;
        // }

        // Build search clause
        $searchClause = "";
        $searchParams = [];
        if (!empty($search)) {
            $searchClause .= " AND (";
            foreach ($searchColumns as $i => $col) {
                $searchClause .= "$col LIKE ?";
                $searchParams[] = "%$search%";
                if ($i < count($searchColumns) - 1) {
                    $searchClause .= " OR ";
                }
            }
            $searchClause .= ")";
        }

        $sql = "
        SELECT * FROM (
            SELECT $select_columns FROM mv_checker_summary
            WHERE $where $searchClause
    
            UNION ALL
    
            SELECT
                id_courier, no_runsheet,id_checker, runsheet_date, create_date, upload_by,
                zone, status_checker, qty_awb, qty_sesuai, qty_revisi, qty_tidak_sesuai,
                courier_name, zone_name, origin_code, role, name, username, success_pod, in_progress_pod,amount,status_pod,
                'summary' AS source_table
            FROM summary_checker
            WHERE $where $searchClause
        ) AS combined
        ORDER BY $orderColumn $orderDir
        LIMIT $start, $length
    ";
    

        // Param digandakan karena dipakai di kedua SELECT
        $finalParams = array_merge($params, $searchParams, $params, $searchParams);
        $query = $this->db->query($sql, $finalParams);

        return $query->result();
    }

    function count_filtered_checker()
    {
        $dateFrom = $this->input->post('dateFrom', TRUE);
        $dateThru = $this->input->post('dateThru', TRUE);
        $origin = $this->input->post('origin', TRUE);
        $zone = $this->input->post('zone', TRUE);
        $role = $this->input->post('role', TRUE);
        $search = $this->input->post('search')['value'];

        $searchColumns = $this->checker_column_search;
        $where = "WHERE create_date BETWEEN ? AND ?";
        $params = [$dateFrom . ' 00:00:00', $dateThru . ' 23:59:59'];

        if (!empty($origin)) {
            $where .= " AND origin_code = ?";
            $params[] = $origin;
        }

        if (!empty($zone)) {
            $where .= " AND zone = ?";
            $params[] = $zone;
        }

        if (!empty($role) && $role == "Koordinator") {
            $where .= " AND role = ?";
            $params[] = $role;
        }

        $searchClause = "";
        if (!empty($search)) {
            $searchClause .= " AND (";
            foreach ($searchColumns as $idx => $col) {
                $searchClause .= "$col LIKE ?";
                $params[] = "%$search%";
                if ($idx < count($searchColumns) - 1) {
                    $searchClause .= " OR ";
                }
            }
            $searchClause .= ")";
        }

        $sql = "
        SELECT COUNT(*) AS total FROM (
            SELECT id_checker FROM mv_checker_summary $where $searchClause
            UNION ALL
            SELECT id_checker FROM summary_checker $where $searchClause
        ) AS combined
    ";

        $query = $this->db->query($sql, array_merge($params, $params));
        return $query->row()->total;
    }


    function count_all_checker()
    {

        return $this->db->count_all_results('mv_checker_summary') + $this->db->count_all_results('summary_checker');
    }
    function get_zone_name($zone_code)
    {
        $this->db->select('zone');
        $this->db->from('zone');
        $this->db->where('zone_code', $zone_code);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row()->zone;  // Mengembalikan langsung string zone-nya
        } else {
            return null;  // Atau bisa juga return ''; tergantung kebutuhan
        }
    }
    function getdatatable_not_approve()
    {
        $dateFrom = $this->input->post('dateFrom', TRUE);
        $dateThru = $this->input->post('dateThru', TRUE);
        $origin = $this->input->post('origin', TRUE);
        $zone = $this->input->post('zone', TRUE);
        $role = $this->input->post('role', TRUE);
        $filter = $this->input->post('filter', TRUE);
        $search = $this->input->post('search')['value'];
        $orderColumnIndex = $_POST['order']['0']['column'];
        $orderDir = $_POST['order']['0']['dir'];
        $start = $_POST['start'];
        $length = $_POST['length'];

        // Kolom yang akan dipilih, harus sama di kedua tabel
        $select_columns = "
            id_courier, id_checker, runsheet_date, create_date, upload_by,
            zone, status_checker, qty_awb, qty_sesuai, qty_revisi, qty_tidak_sesuai,
            courier_name, zone_name, origin_code, role, name, username, success_pod, in_progress_pod, amount,status_pod,
            'mv' AS source_table
        ";          


        // Kolom-kolom search
        $searchColumns = ["id_courier", "courier_name", "zone"];

        // Kolom urutan (harus cocok dengan index datatable)
        $orderColumns = array("id_courier", "courier_name", "qty_sesuai", "qty_revisi", "qty_tidak_sesuai", "runsheet_date", "upload_by", "zone", "zone_name");
        $orderColumn = $orderColumns[$orderColumnIndex];

        // Build filter WHERE (tanpa search)
        $where = "create_date BETWEEN ? AND ?";
        $params = [$dateFrom . ' 00:00:00', $dateThru . ' 23:59:59'];

        if (!empty($origin)) {
            $where .= " AND origin_code = ?";
            $params[] = $origin;
        }

        if (!empty($zone)) {
            $where .= " AND zone = ?";
            $params[] = $zone;
        }

        if (!empty($role) && $role === "Koordinator") {
            $where .= " AND role = ?";
            $params[] = $role;
        }

        // Build search clause
        $searchClause = "";
        $searchParams = [];
        if (!empty($search)) {
            $searchClause .= " AND (";
            foreach ($searchColumns as $i => $col) {
                $searchClause .= "$col LIKE ?";
                $searchParams[] = "%$search%";
                if ($i < count($searchColumns) - 1) {
                    $searchClause .= " OR ";
                }
            }
            $searchClause .= ")";
        }

        $sql = "
        SELECT * FROM (
            SELECT $select_columns FROM mv_checker_summary
            WHERE $where $searchClause AND $filter>0
    
            UNION ALL
    
            SELECT
                id_courier, id_checker, runsheet_date ,create_date, upload_by,
                zone, status_checker, qty_awb, qty_sesuai, qty_revisi, qty_tidak_sesuai,
                courier_name, zone_name, origin_code, role, name, username, success_pod, in_progress_pod,amount,status_pod,
                'summary' AS source_table
            FROM summary_checker
            WHERE $where $searchClause AND qty_tidak_sesuai >0
        ) AS combined
        ORDER BY $orderColumn $orderDir
        LIMIT $start, $length
    ";
    

        // Param digandakan karena dipakai di kedua SELECT
        $finalParams = array_merge($params, $searchParams, $params, $searchParams);
        $query = $this->db->query($sql, $finalParams);

        return $query->result();
    }

    function count_filtered_not_approve()
    {
        $dateFrom = $this->input->post('dateFrom', TRUE);
        $dateThru = $this->input->post('dateThru', TRUE);
        $origin = $this->input->post('origin', TRUE);
        $zone = $this->input->post('zone', TRUE);
        $role = $this->input->post('role', TRUE);
        $search = $this->input->post('search')['value'];

        $searchColumns = $this->checker_column_search;
        $where = "WHERE create_date BETWEEN ? AND ?" ;
        $params = [$dateFrom . ' 00:00:00', $dateThru . ' 23:59:59'];

        if (!empty($origin)) {
            $where .= " AND origin_code = ?";
            $params[] = $origin;
        }

        if (!empty($zone)) {
            $where .= " AND zone = ?";
            $params[] = $zone;
        }

        if (!empty($role) && $role == "Koordinator") {
            $where .= " AND role = ?";
            $params[] = $role;
        }

        $searchClause = "";
        if (!empty($search)) {
            $searchClause .= " AND (";
            foreach ($searchColumns as $idx => $col) {
                $searchClause .= "$col LIKE ?";
                $params[] = "%$search%";
                if ($idx < count($searchColumns) - 1) {
                    $searchClause .= " OR ";
                }
            }
            $searchClause .= ")";
        }

        $sql = "
        SELECT COUNT(*) AS total FROM (
            SELECT id_checker FROM mv_checker_summary $where $searchClause
            UNION ALL
            SELECT id_checker FROM summary_checker $where $searchClause
        ) AS combined
    ";

        $query = $this->db->query($sql, array_merge($params, $params));
        return $query->row()->total;
    }


    function count_all_not_approve()
    {

        return $this->db->count_all_results('mv_checker_summary') + $this->db->count_all_results('summary_checker');
    }
    

    public function _add_checker($data)
    {
        try {
            $this->db->insert_batch('checker', $data);
            return TRUE;
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return FALSE;
        }

        // return $data;
    }
    public function _add_status($data)
    {
        try {
            $this->db->insert_batch('checker', $data);
            return TRUE;
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return FALSE;
        }
    }
    public function _change_status($id_courier, $id_checker)
    {
        try {
            $this->db->where_in('id_checker', $id_checker); // Bisa menerima array
            $this->db->update('checker', ['status_checker' => "Tidak Sesuai"]);

            return $this->db->affected_rows() > 0; // Pastikan ada data yang terupdate
        } catch (Exception $e) {
            return false;
        }
    }
    public function change_status_awb($runsheet_date,$id_courier)
    {
        try {
            $this->db->where('DATE(runsheet_date)', $runsheet_date); // Bisa menerima array
            $this->db->where('id_courier', $id_courier); // Bisa menerima array
            $this->db->update('checker', ['status_pod' => "Y"]);

            return $this->db->affected_rows() > 0; // Pastikan ada data yang terupdate
        } catch (Exception $e) {
            return false;
        }
    }
    public function _revision_image($awb, $data)
    {
        try {
            $this->db->where('awb', $awb); // Bisa menerima array
            $this->db->update('checker', $data);

            return $this->db->affected_rows() > 0; // Pastikan ada data yang terupdate
        } catch (Exception $e) {
            return false;
        }
    }
    public function _change_status_approve($id_courier, $id_checker)
    {
        try {
            $this->db->where_in('id_checker', $id_checker); // Bisa menerima array
            $this->db->update('checker', ['status_checker' => "Sesuai"]);

            return $this->db->affected_rows() > 0; // Pastikan ada data yang terupdate
        } catch (Exception $e) {
            return false;
        }
    }


    function _get_data_approve($id_courier, $runsheet_date)
    {
        $this->db->select("
        id_checker,
        ch.url_pod,
        ch.runsheet_date,
        ch.no_runsheet,
        ch.awb,
        ch.status_cod,
        ch.id_courier,
        c.courier_name,
        c.no_tlp,
        ch.destination_code,
        ch.zone,
        ch.received_date,
        ch.receiver_name,
        ch.receiver_address,
        ch.paymeny_type,
        ch.amount,
        ch.link_maps,
        ch.status_via,
        s.pod_status,      
        ch.url_photo,    
        ch.remarks,
        b.big_grouping_cust,
    CASE 
        WHEN b.account_number IS NOT NULL THEN 1
        ELSE 0
    END AS is_banking", false); // ← ini penting, escape = false

        $this->db->from("checker ch");
        $this->db->join("courier c", 'c.id_courier=ch.id_courier', 'left');
        $this->db->join("status_pod s", 's.pod_code=ch.status_cod', 'left');
        $this->db->join("banking b", 'b.account_number=ch.id_customers', 'left');
        $this->db->where("ch.id_courier", $id_courier);
        $this->db->where("DATE(ch.runsheet_date)", date('Y-m-d', strtotime($runsheet_date)));
        $this->db->where("ch.status_checker", "Sesuai");
        $this->db->group_by([
            "ch.id_checker",
            "ch.url_pod",
            "ch.runsheet_date",
            "ch.no_runsheet",
            "ch.awb",
            "ch.status_cod",
            "ch.id_courier",
            "c.courier_name",
            "c.no_tlp",
            "ch.destination_code",
            "ch.zone",
            "ch.received_date",
            "ch.receiver_name",
            "ch.receiver_address",
            "ch.paymeny_type",
            "ch.amount",
            "ch.link_maps",            
            "ch.status_via",
            "s.pod_status",
            "ch.url_photo",
            "ch.remarks",
            "b.big_grouping_cust",
            "b.account_number"
        ]);
        


        // Urutan: photo not found → pod not found → yang lainnya
        $this->db->order_by("
        CASE
            WHEN b.account_number IS NOT NULL THEN 0
            WHEN ch.url_photo = 'public/img/Image-not-found.png' THEN 1
            WHEN ch.url_pod = 'public/img/Image-not-found.png' THEN 2
            ELSE 3
        END", '', false);

        // Tambahan: urut berdasarkan tanggal
        $this->db->order_by("ch.runsheet_date", "ASC");

        $query = $this->db->get();
        return [
            'data' => $query->result(),
            'num_rows' => $query->num_rows()
        ];
    }

    function _get_data_not_approve($id_courier, $runsheet_date)
    {
        $this->db->select("
        id_checker,
        ch.url_pod,
        ch.runsheet_date,
        ch.no_runsheet,
        ch.awb,
        ch.status_cod,
        ch.id_courier,
        c.courier_name,
        c.no_tlp,
        ch.destination_code,
        ch.zone,
        ch.received_date,
        ch.receiver_name,
        ch.receiver_address,
        ch.paymeny_type,
        ch.amount,
        ch.link_maps,
        ch.status_via,
        s.pod_status,      
        ch.url_photo,    
        ch.remarks,
        b.big_grouping_cust,
    CASE 
        WHEN b.account_number IS NOT NULL THEN 1
        ELSE 0
    END AS is_banking", false);
        $this->db->from("checker ch");
        $this->db->join("courier c", 'c.id_courier=ch.id_courier', 'left');
        $this->db->join("status_pod s", 's.pod_code=ch.status_cod', 'left');
        $this->db->join("banking b", 'b.account_number=ch.id_customers', 'left');
        $this->db->where("ch.id_courier", $id_courier);
        $this->db->where("DATE(ch.runsheet_date)", date('Y-m-d', strtotime($runsheet_date)));

        $this->db->where("ch.status_checker", "Tidak Sesuai");
        $this->db->group_by([
            "ch.id_checker",
            "ch.url_pod",
            "ch.runsheet_date",
            "ch.no_runsheet",
            "ch.awb",
            "ch.status_cod",
            "ch.id_courier",
            "c.courier_name",
            "c.no_tlp",
            "ch.destination_code",
            "ch.zone",
            "ch.received_date",
            "ch.receiver_name",
            "ch.receiver_address",
            "ch.paymeny_type",
            "ch.amount",
            "ch.link_maps",
            "ch.status_via",
            "s.pod_status",
            "ch.url_photo",
            "ch.remarks",
            "b.big_grouping_cust",
            "b.account_number"
        ]);
        $this->db->order_by("
        CASE
            WHEN b.account_number IS NOT NULL THEN 0
            WHEN ch.url_photo = 'public/img/Image-not-found.png' THEN 1
            WHEN ch.url_pod = 'public/img/Image-not-found.png' THEN 2
            ELSE 3
        END", '', false);
        // $this->db->where("runsheet_date",$runsheet_date);

        $query = $this->db->get();
        return [
            'data' => $query->result(),
            'num_rows' => $query->num_rows()
        ];

    }
    function _get_data_revision($id_courier, $runsheet_date)
    {
        $this->db->select("ch.*,c.*,s.*");
        $this->db->from("checker ch");
        $this->db->join("courier c", 'c.id_courier=ch.id_courier', 'left');
        $this->db->join("status_pod s", 's.pod_code=ch.status_cod', 'left');
        $this->db->where("ch.id_courier", $id_courier);
        $this->db->where("DATE(ch.runsheet_date)", date('Y-m-d', strtotime($runsheet_date)));

        $this->db->where("ch.status_checker", "Revisi");
        // $this->db->where("runsheet_date",$runsheet_date);

        $query = $this->db->get();
        return [
            'data' => $query->result(),
            'num_rows' => $query->num_rows()
        ];

    }

    function _get_data_courier($id_courier)
    {
        $this->db->select("*");
        $this->db->from("courier");
        $this->db->where("id_courier", $id_courier);
        $query = $this->db->get();
        return $query->row();
    }
    function _get_progress($id_courier,$runsheet_date)
    {
        $this->db->select("success_pod, in_progress_pod,qty_awb,qty_sesuai,qty_revisi");
        $this->db->from("mv_checker_summary");
        $this->db->where("id_courier", $id_courier);
        $this->db->where("runsheet_date", $runsheet_date);
        $query = $this->db->get();
        return $query->row();
    }
   


    function _duplicat_awb($awb)
    {
        $this->db->where('awb', $awb);
        $query = $this->db->get('checker'); // Ganti 'your_table_name' dengan nama tabel yang sesuai  
        // Mengembalikan true jika ada data yang ditemukan, false jika tidak ada  
        return $query->num_rows() > 0;
    }

    public function _get_origin($zone)
    {
        $this->db->select('origin_code');
        $this->db->where('zone_code', $zone);
        $this->db->from('zone');
        $query = $this->db->get();
        $result = $query->row();
        return $result;

    }
    public function refresh_mv_checker_summary()
    {
        // 1. Kosongkan tabel summary
        $this->db->query('TRUNCATE TABLE mv_checker_summary');
    
        // 2. Isi ulang dari tabel checker dan relasi lainnya
        $sql = "
           INSERT INTO mv_checker_summary 
( id_courier, id_checker, runsheet_date, create_date, upload_by, zone, status_checker, qty_awb, qty_sesuai, qty_revisi, qty_tidak_sesuai, courier_name, zone_name, origin_code, role, name, username, no_runsheet, success_pod, in_progress_pod, amount, cod_undelivered, status_pod,leaderboard_year,leaderboard_month ) 
SELECT 
    ch.id_courier,
    MIN(ch.id_checker) AS id_checker,
    DATE(ch.runsheet_date) AS runsheet_date,
    MIN(ch.create_date) AS create_date,
    MIN(ch.upload_by) AS upload_by,
    MIN(ch.zone) AS zone,
    MIN(ch.status_checker) AS status_checker,
    COUNT(ch.id_courier) AS qty_awb,
    SUM(CASE WHEN ch.status_checker = 'Sesuai' THEN 1 ELSE 0 END) AS qty_sesuai,
    SUM(CASE WHEN ch.status_checker = 'Revisi' THEN 1 ELSE 0 END) AS qty_revisi,
    SUM(CASE WHEN ch.status_checker = 'Tidak Sesuai' THEN 1 ELSE 0 END) AS qty_tidak_sesuai,
    MIN(c.courier_name) AS courier_name,
    MIN(z.zone) AS zone_name,
    MIN(z.origin_code) AS origin_code,
    MIN(u.role) AS role,
    MIN(u.name) AS name,
    MIN(u.username) AS username,
    MIN(ch.no_runsheet) AS no_runsheet,
    SUM(CASE WHEN ch.status_cod IS NOT NULL THEN 1 ELSE 0 END) AS success_pod,
    SUM(CASE WHEN ch.status_cod IS NULL THEN 1 ELSE 0 END) AS in_progress_pod,
    SUM(ch.amount) AS amount,
    SUM(CASE WHEN ch.status_cod LIKE 'u%' THEN ch.amount ELSE 0 END) AS cod_undelivered,
    MIN(ch.status_pod) AS status_pod,
    YEAR(MIN(ch.runsheet_date)) AS leaderboard_year,
    MONTH(MIN(ch.runsheet_date)) AS leaderboard_month
FROM checker ch
LEFT JOIN courier c ON c.id_courier = ch.id_courier
LEFT JOIN zone z ON z.zone_code = ch.zone
LEFT JOIN users u ON u.id_user = ch.upload_by
GROUP BY ch.id_courier, DATE(ch.runsheet_date);

        ";
    
        $this->db->query($sql);
    }

    public function backup_and_cleanup_checker()
    {
        $sql = "
        INSERT INTO summary_checker (
            id_courier, id_checker, runsheet_date, create_date, upload_by,
            zone, status_checker, qty_awb, qty_sesuai, qty_revisi, qty_tidak_sesuai,
            courier_name, zone_name, origin_code, role, name, username,no_runsheet, success_pod, in_progress_pod,amount,cod_undelivered,status_pod
        )
        SELECT
            ch.id_courier,
            ch.id_checker,
            DATE(ch.runsheet_date),
            ch.create_date,
            ch.upload_by,
            ch.zone,
            ch.status_checker,
            COUNT(ch.id_courier),
            SUM(CASE WHEN ch.status_checker = 'Sesuai' THEN 1 ELSE 0 END),
            SUM(CASE WHEN ch.status_checker = 'Revisi' THEN 1 ELSE 0 END),
            SUM(CASE WHEN ch.status_checker = 'Tidak Sesuai' THEN 1 ELSE 0 END),
            c.courier_name,
            z.zone,
            z.origin_code,
            u.role,
            u.name,
            u.username,
            ch.runsheet,
            SUM(CASE WHEN ch.status_cod IS NOT NULL then 1 ELSE 0 END) as  success_pod,
      SUM(CASE WHEN ch.status_cod IS  NULL then 1 ELSE 0 END) as  in_progress_pod,
      SUM(ch.amount) AS amount,
      SUM(CASE WHEN ch.status_cod LIKE 'U%' THEN amount ELSE 0 END) as cod_undelivered,
      MIN(ch.status_pod) AS status_pod
      
     
        FROM checker ch
        LEFT JOIN courier c ON c.id_courier = ch.id_courier
        LEFT JOIN zone z ON z.zone_code = ch.zone
        LEFT JOIN users u ON u.id_user = ch.upload_by
        WHERE ch.runsheet_date < CURDATE() - INTERVAL 14 DAY
        GROUP BY ch.id_courier, DATE(ch.runsheet_date)
    ";
        $this->db->query($sql);

        // Ambil data gambar
        $old_data = $this->db->where('runsheet_date <', date('Y-m-d', strtotime('-14 days')))
            ->get('checker')->result();

        foreach ($old_data as $row) {
            foreach (['url_photo', 'url_pod', 'url_revision'] as $field) {
                if (!empty($row->$field) && $row->$field !== 'public/img/Image-not-found.png') {
                    $full_path = FCPATH . $row->$field;
                    if (file_exists($full_path)) {
                        @unlink($full_path);
                    }
                }
            }
        }

        // Hapus data checker lebih dari 30 hari
        $this->db->where('runsheet_date <', date('Y-m-d', strtotime('-14 days')))
            ->delete('checker');
    }

    public function _select_runsheet($id_courier,$dateFrom,$dateThru)
    {        
        $this->db->select('no_runsheet'); // Sesuaikan nama kolom
        $this->db->from('checker');                 
        $this->db->where('id_courier', $id_courier);
        $this->db->where('runsheet_date >', $dateFrom);
        $this->db->where('runsheet_date <', $dateThru);
        $this->db->group_by('no_runsheet');
        $query = $this->db->get();
        
        return $query->result_array(); // Ambil hanya satu baris sebagai array
    }

    // public function _select_runsheet($id_courier, $dateFrom, $dateThru)
    // {
    //     $this->db->select("no_runsheet, 
    //         CASE 
    //             WHEN SUM(CASE WHEN status_pod IS NULL THEN 1 ELSE 0 END) > 0 THEN 'N'
    //             ELSE 'Y'
    //         END AS status_pod_flag", false);
        
    //     $this->db->from('checker');
    
    //     if ($id_courier != "ALL") {
    //         $this->db->where('id_courier', $id_courier);
    //     }
    
    //     // Kalau mau filter tanggal, bisa aktifkan:
    //     // $this->db->where('runsheet_date >=', $dateFrom);
    //     // $this->db->where('runsheet_date <=', $dateThru);
    
    //     $this->db->group_by('no_runsheet');
    
    //     $query = $this->db->get();
    //     return $query->result_array();
    // }
    
public function get_sub_case_by_case($case_type)
{
    $this->db->select('sub_tipe, id');
    $this->db->from('sla');
    $this->db->where('tipe_case', $case_type);
    $query = $this->db->get();

    return $query->result_array(); // Mengembalikan array, bukan satu objek
}



}
?>
