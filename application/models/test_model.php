<?php

class Admin_model extends CI_Model
{
    var $customer_column_order = array(null, 'id_user', 'account_name', 'employee_position', 'regional', 'branch', 'origin', 'zone', 'username', 'kpi', 'role', null); //set column field database for datatable orderable
    var $customer_column_search = array('id_user', 'account_name', 'employee_position', 'regional', 'branch', 'origin', 'zone', 'username', 'kpi', 'role', ); //set column field database for datatable searchable
    var $customer_order = array('id' => 'DESC');

    private function _getdatatables_customer()
    {
        //
        $this->db->select('*');
        // $this->db->order_by('id_user','DESC');
        // $this->db->where('username IS NOT NULL ');   
        $this->db->from('users');

        $i = 0;

        if (@$_POST['search']['value']) {
            foreach ($this->customer_column_search as $item) {
                if ($i === 0) {
                    $this->db->group_start()
                        ->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if (count($this->customer_column_search) - 1 == $i) {
                    $this->db->group_end();
                }
                $i++;
            }
        }

    }

    // public function _get()
    // {
        public function getSourceData($dateFrom, $dateThru, $origin, $zone)
        {
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
        
            $select_columns = "
                id_courier, id_checker, runsheet_date, create_date, upload_by,
                zone, qty_awb, qty_sesuai, qty_revisi, qty_tidak_sesuai,
                courier_name, zone_name, origin_code, role, name, username
            ";
        
            $sql = "
                SELECT 
                    SUM(qty_sesuai) AS approve,
                    SUM(qty_tidak_sesuai) AS notApprove,
                    SUM(qty_revisi) AS revision,
                    SUM(qty_awb) AS count
                FROM (
                    SELECT $select_columns FROM mv_checker_summary $where
                    UNION ALL
                    SELECT $select_columns FROM summary_checker $where
                ) AS combined
            ";
        
            $finalParams = array_merge($params, $params);
            $query = $this->db->query($sql, $finalParams);
            $result = $query->row_array();
        
            // Supaya tetap cocok dengan frontend (sourceLabels & sourceCounts)
            return [
                [
                    'status_checker' => 'Sesuai',
                    'count' => (int) $result['approve']
                ],
                [
                    'status_checker' => 'Tidak Sesuai',
                    'count' => (int) $result['notApprove']
                ],
                [
                    'status_checker' => 'Revisi',
                    'count' => (int) $result['revision']
                ]
            ];
        }
public function getSourceDataMultiple($year, $origin, $zone)
{
    $where = "WHERE YEAR(create_date) = ?";
    $params = [$year];

    if (!empty($origin)) {
        $where .= " AND origin_code = ?";
        $params[] = $origin;
    }

    if (!empty($zone)) {
        $where .= " AND zone = ?";
        $params[] = $zone;
    }

    $sql = "
        SELECT 
            MONTH(create_date) AS month,
            SUM(qty_awb) AS total_awb,
            SUM(qty_sesuai) AS approve,
            SUM(qty_tidak_sesuai) AS notApprove,
            SUM(qty_revisi) AS revision
        FROM (
            SELECT qty_awb, qty_sesuai, qty_tidak_sesuai, qty_revisi, create_date, origin_code, zone 
            FROM mv_checker_summary $where
            UNION ALL
            SELECT qty_awb, qty_sesuai, qty_tidak_sesuai, qty_revisi, create_date, origin_code, zone 
            FROM summary_checker $where
        ) AS combined
        GROUP BY MONTH(create_date)
        ORDER BY month ASC
    ";

    $finalParams = array_merge($params, $params);
    $query = $this->db->query($sql, $finalParams)->result_array();

    // Inisialisasi semua bulan dengan 0
    $result = [
        'Sesuai' => array_fill(1, 12, 0),
        'Tidak Sesuai' => array_fill(1, 12, 0),
        'Revisi' => array_fill(1, 12, 0),
    ];

    foreach ($query as $row) {
        $month = (int) $row['month'];
        $result['Sesuai'][$month] = (int) $row['approve'];
        $result['Tidak Sesuai'][$month] = (int) $row['notApprove'];
        $result['Revisi'][$month] = (int) $row['revision'];
    }

    // Format ulang sesuai kebutuhan frontend
    $final = [];
    foreach ($result as $status => $months) {
        foreach ($months as $month => $count) {
            $final[] = [
                'status_checker' => $status,
                'month' => $month,
                'count' => $count
            ];
        }
    }

    return $final;
}
        
        


    public function _get_origins()
    {
        $this->db->distinct();
        $this->db->select('*');
        $this->db->group_by('origin_name');
        $this->db->from('zone');
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
    public function _get_zone($origin)
    {
        $this->db->distinct();
        $this->db->select('*');
        $this->db->from('zone');
        $this->db->where('origin_code', $origin);
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
    public function _destination_code($zone_code)
    {
        $this->db->distinct();
        $this->db->select('*');
        $this->db->from('destination_code');
        $this->db->where('origin_code', $zone_code);
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
    function getdatatables_customer()
    {
        //
        $this->_getdatatables_customer();
        if (@$_POST['length'] != -1)
            $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }


    private function _getdatatables_user()
    {
        $this->db->select('*');
        $this->db->from('users');


        $i = 0;

        if (@$_POST['search']['value']) {
            foreach ($this->customer_column_search as $item) {
                if ($i === 0) {
                    $this->db->group_start()
                        ->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if (count($this->customer_column_search) - 1 == $i) {
                    $this->db->group_end();
                }
                $i++;
            }
        }

        if (isset($_POST['order'])) {
            $column_order_index = $_POST['order']['0']['column'];
            if ($this->customer_column_order[$column_order_index] != null) {
                $this->db->order_by($this->customer_column_order[$column_order_index], $_POST['order']['0']['dir']);
            }
        } elseif (isset($this->order)) {
            $customer_order = $this->order;
            $this->db->order_by(key($customer_order), $customer_order[key($customer_order)]);
        }

    }

    function getdatatables_user()
    {
        $this->_getdatatables_user();
        if (@$_POST['length'] != -1)
            $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered_customer()
    {
        $this->_getdatatables_customer();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_all_customer()
    {
        $this->db->select('*');

        $this->db->from('users');
        return $this->db->count_all_results();
    }
    function count_filtered_user()
    {
        //
        $this->_getdatatables_user();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_all_user()
    {
        //
        $this->db->select('*');
        $this->db->from('users');
        return $this->db->count_all_results();
    }




}

?>
