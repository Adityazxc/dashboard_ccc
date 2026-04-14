<?php

class Checker_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();

        // Load DB checker_pod tanpa mengganti $this->db
        $this->db_checker = $this->load->database('checker_pod', TRUE);
    }
    var $checker_column_order = array(null, "id_courier", "courier_name", "qty_awb", "qty_sesuai", "qty_tidak_sesuai", "qty_revisi", null, "runsheet_date", "upload_by", "zone", "zone_name", "status_pod"); //set column field database for datatable orderable
    var $checker_column_search = array("id_courier", "courier_name", "zone", "status_pod"); //set column field database for datatable orderable

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
        $orderColumns = [
            "id_checker",        // index 0 -> untuk kolom "No"
            "id_courier",        // index 1
            "courier_name",      // index 2
            "qty_awb",           // index 3
            "qty_sesuai",        // index 4
            "qty_tidak_sesuai",  // index 5
            "qty_revisi",        // index 6
            "success_pod",       // index 7
            "runsheet_date",     // index 8
            "name",              // index 9  (atau upload_by)
            "zone",              // index 10
            "zone_name",         // index 11
            "status_pod",        // index 12
            null                 // index 13 (action button, tidak bisa sort)
        ];

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

        // if (!empty($role) && $role == "Koordinator") {
        //     $where .= " AND role = ?";
        //     $params[] = $role;
        // }

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
    // function get_zone_name($zone_code)
    // {
    //     $this->db->select('zone');
    //     $this->db->from('zone');
    //     $this->db->where('zone_code', $zone_code);
    //     $query = $this->db->get();

    //     if ($query->num_rows() > 0) {
    //         return $query->row()->zone;  // Mengembalikan langsung string zone-nya
    //     } else {
    //         return null;  // Atau bisa juga return ''; tergantung kebutuhan
    //     }
    // }
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

        if ($filter == "qty_revisi") {
            $_filter = "qty_revisi";
        } else {

            $_filter = "qty_tidak_sesuai";
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
            WHERE $where $searchClause AND $_filter >0
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
        $filter = $this->input->post('filter', TRUE);
        $zone = $this->input->post('zone', TRUE);
        $role = $this->input->post('role', TRUE);
        $search = $this->input->post('search')['value'];
        if ($filter == "qty_revisi") {
            $_filter = "qty_revisi";
        } else {

            $_filter = "qty_tidak_sesuai";
        }

        $searchColumns = $this->checker_column_search;

        // $where = "WHERE create_date BETWEEN ? AND ?" ;
        $where = "WHERE $_filter >0 
        AND create_date BETWEEN ? AND ?";
        $params = [$dateFrom . ' 00:00:00', $dateThru . ' 23:59:59'];

        if (!empty($origin)) {
            $where .= " AND origin_code = ?";
            $params[] = $origin;
        }

        if (!empty($zone)) {
            $where .= " AND zone = ?";
            $params[] = $zone;
        }

        // if (!empty($role) && $role == "Koordinator") {
        //     $where .= " AND role = ?";
        //     $params[] = $role;
        // }

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
        $filter = $this->input->post('filter', TRUE);

        if ($filter == "qty_revisi") {
            $_filter = "Revisi";
        } else {

            $_filter = "Tidak Sesuai";
        }


        // Hitung tabel 1
        $this->db->where('status_checker', $_filter);
        $count1 = $this->db->count_all_results('mv_checker_summary');

        // Reset builder
        $this->db->reset_query();

        // Hitung tabel 2
        $this->db->where('status_checker', $_filter);
        $count2 = $this->db->count_all_results('summary_checker');

        return $count1 + $count2;
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
    public function change_status_awb($runsheet_date, $id_courier)
    {
        try {
            $this->db->where('DATE(runsheet_date)', $runsheet_date); // Bisa menerima array
            $this->db->where('id_courier', $id_courier); // Bisa menerima array
            $this->db->update('checker', ['status_pod' => "Y"]);
            $this->refresh_mv_checker_summary();
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
    function _get_progress($id_courier, $runsheet_date)
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
        $this->db_checker->select('origin_code');
        $this->db_checker->where('zone_code', $zone);
        $this->db_checker->from('zone');
        $query = $this->db_checker->get();
        $result = $query->row();
        return $result;

    }



}
?>