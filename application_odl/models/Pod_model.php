<?php

class Pod_model extends CI_Model
{

    var $checker_column_order = array(null, "ch.id_courier", "courier_name", "qty_sesuai", "qty_revisi", "qty_tidak_sesuai", "ch.runsheet_date", "ch.upload_by", "ch.zone", "zone_name"); //set column field database for datatable orderable
    var $checker_column_search = array("ch.id_courier", "courier_name", "ch.zone"); //set column field database for datatable orderable

    private function _get_datatables_cod_pod()
    {
        $dateFrom = $this->input->post('dateFrom'); // "2025-08"        
        $dateThru = $this->input->post('dateThru'); // "2025-08"        
        $origin = $this->input->post('origin'); // "2025-08"     
        $zone = $this->input->post('zone'); // "2025-08"     



        $this->db->select('        
        ch.status_cod,
        ch.no_runsheet,
        ch.cod_paid,
        ch.total_paid_cod,
        ch.persentase_cod,
        ch.minus_cod,        
        ch.poin_hrs,
        ch.paid_off_date,
        ch.id_checker_notes,
        ch.create_hrs_by,
        ch.closing_hrs_by,
         u_create.name as create_name,
        u_close.name as closing_name,        
        c.courier_name,
        c.id_courier,
        m.runsheet_date,        
        c.location
        '

        );
        if (!empty($origin)) {
            $this->db->where('m.origin_code', $origin);
        }
        if (!empty($zone)) {
            $this->db->where('m.zone', $zone);
        }
        


        if (!empty($dateFrom)) {
            $this->db->where("Date(ch.paid_off_date)>=", $dateFrom);

        }
        if (!empty($dateThru)) {
            $this->db->where("Date(ch.paid_off_date)<=", $dateThru);

        }
        $this->db->from('checker_notes ch');
        $this->db->join('courier c', 'c.id_courier=ch.id_courier', 'left');        
        $this->db->join('users u_close', 'u_close.id_user = ch.closing_hrs_by', 'left');
        $this->db->join('users u_create', 'u_create.id_user = ch.create_hrs_by', 'left');
        $this->db->join('mv_checker_summary m', 'm.id_courier=ch.id_courier', 'left');
        


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
        if (@$_POST['length'] != -1)
            $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered_cod_pod()
    {
        $this->_get_datatables_cod_pod();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_all_cod_pod()
    {

        $this->db->select('*');
        $this->db->from('checker');
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
    public function _add_checker_pod($data_pod)
    {
        $this->db->insert('checker_notes', $data_pod);
        return $this->db->affected_rows() > 0;
    }
    public function _edit_checker_pod($id_checker_notes, $data_pod)
    {
        $this->db->where('id_checker_notes', $id_checker_notes);
        $this->db->update('checker_notes', $data_pod);
        return $this->db->affected_rows() > 0;
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

    public function _get_no_runsheet($no_runsheet)
    {
        $this->db->where('no_runsheet', $no_runsheet);
        $this->db->from('checker_notes');

        $query = $this->db->get();
        return $query->num_rows() > 0;
    }
  
    

    public function status_pod(){
        $this->db->select('
        SUM(IF(status_cod LIKE "D%", 1, 0)) AS delivered,
        SUM(IF(status_cod LIKE "U%", 1, 0)) AS undelivered
    ');

    $this->db->where('');
    $this->db->from('checker');
    
    }

    public function get_cod_pod($no_runsheet)
    {

        $this->db->select('ch.id_checker_notes,
    ch.minus_cod,
    ch.status_cod,
    ch.create_hrs_by,
    ch.closing_hrs_by,
    ch.no_runsheet,
    ch.persentase_cod,
    ch.hrs,
    ch.cod_paid,
    ch.paid_off_date,
    ch.id_courier,
    ch.total_paid_cod,
    ch.poin_hrs,
    ch.transfer,
    
    u.id_user,
    u.username,
    u.name,
    


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
        $this->db->where('ch.no_runsheet', $no_runsheet);
        $this->db->from('checker_notes ch');
        $this->db->join('courier c', 'c.id_courier=ch.id_courier', 'left');
        $this->db->join('users u', 'u.id_user=ch.closing_hrs_by', 'left');
        $this->db->join('mv_checker_summary m', 'm.id_courier=ch.id_courier', 'left');

        $query = $this->db->get();
        return $query->result();
    }
    // function get_detail_cod($no_runsheet)
    // {

    //      $this->db->select('*,
    //         SUM(amount) AS amount,
    //   SUM(CASE WHEN status_cod LIKE "U%" THEN amount ELSE 0 END) as cod_undelivered,
    //   MIN(status_pod) AS status_pod,
    //   COUNT(id_courier) AS qty_awb,              
        
    //     ');
    //     $this->db->from('checker');
    //     $this->db->where('no_runsheet', $no_runsheet);
    //     $query = $this->db->get();
    //     return $query->row();

    // }
    // function get_detail_cod($no_runsheet)
    // {

    //     $this->db->select('*');
    //     $this->db->from('mv_checker_summary');
    //     $this->db->where('no_runsheet', $no_runsheet);
    //     $query = $this->db->get();
    //     return $query->row();

    // }

    public function get_detail_cod_by_id($id_courier,$start_date,$end_date)
    {

         $this->db->select('*
        ');
        $this->db->from('mv_checker_summary');
        $this->db->where('Date(runsheet_date) >=', $start_date);
        $this->db->where('Date(runsheet_date) <=', $end_date);
        $this->db->where('id_courier', $id_courier);
   
        $query = $this->db->get();
        return $query->row();

    }

    public function _get_no_runsheet_by_id($id_courier, $start_date, $end_date)
    {
        $this->db->select('cn.*, ch.runsheet_date');
        $this->db->from('checker_notes cn');
        $this->db->join('mv_checker_summary ch', 'ch.id_courier = cn.id_courier', 'left');
        $this->db->where('cn.id_courier', $id_courier);
        $this->db->where('ch.runsheet_date >=', $start_date . ' 00:00:00');
        $this->db->where('ch.runsheet_date <=', $end_date . ' 23:59:59');
        
    
        $query = $this->db->get();
        return $query->num_rows() > 0;
        // return $query->result();
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
        $this->db->select("
        MONTH(c.paid_off_date) AS month,
        SUM(c.minus_cod) AS minus_cod,
        SUM(c.cod_paid) AS cod_paid,
        SUM(c.total_paid_cod) AS total_cod_paid,     
        SUM(c.transfer) AS transfer
    ");
        $this->db->from("checker_notes c");
        $this->db->join('mv_checker_summary m', 'c.no_runsheet=m.no_runsheet', 'left');
        $this->db->join('zone z', 'm.zone=z.zone_code', 'left');

        // Filter berdasarkan tahun
        if (!empty($year)) {
            $this->db->where('YEAR(paid_off_date)', $year);
        }

        // Filter origin
        if (!empty($origin)) {
            $this->db->where('z.origin_code', $origin);
        }

        // Filter zone
        if (!empty($zone)) {
            $this->db->where('z.zone', $zone);
        }

        // Grouping per bulan
        $this->db->group_by('MONTH(c.paid_off_date)');

        $query = $this->db->get()->result();

        // 🧠 Mapping data agar tetap menghasilkan struktur: [status_checker => [month => count]]
        $result = [];

        foreach ($query as $row) {
            $month = (int) $row->month;
            $result['Minus Cod'][$month] = (int) $row->minus_cod;
            $result['Cod'][$month] = (int) $row->cod_paid;
            $result['Total Cod'][$month] = (int) $row->total_cod_paid;
            $result['Transfer'][$month] = (int) $row->transfer;
        }

        // Lengkapi bulan kosong dengan 0
        foreach (['Minus Cod', 'Cod', 'Total Cod', 'Transfer'] as $status) {
            if (!isset($result[$status])) {
                $result[$status] = array_fill(1, 12, 0);
            } else {
                $result[$status] = array_replace(array_fill(1, 12, 0), $result[$status]);
            }
        }

        // Format ulang agar mirip dengan result_array()
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

    public function getSourceData($dateFrom, $dateThru, $origin, $zone)
    {
        $this->db->select("
        MONTH(c.paid_off_date) AS month,
        SUM(c.minus_cod) AS minus_cod,
        SUM(c.cod_paid) AS cod_paid,
        SUM(c.total_paid_cod) AS total_cod_paid,     
        SUM(c.transfer) AS transfer
    ");
        $this->db->from("checker_notes c");
        $this->db->join('mv_checker_summary m', 'c.no_runsheet=m.no_runsheet', 'left');
        $this->db->join('zone z', 'm.zone=z.zone_code', 'left');

        // Filter berdasarkan tahun
        if (!empty($year)) {
            $this->db->where('YEAR(paid_off_date)', $year);
        }

        // Filter origin
        if (!empty($origin)) {
            $this->db->where('z.origin_code', $origin);
        }

        // Filter zone
        if (!empty($zone)) {
            $this->db->where('z.zone', $zone);
        }

        // Grouping per bulan
        $this->db->group_by('MONTH(c.paid_off_date)');
       
        $query = $this->db->get();
        $result = $query->row_array();

        if (!$result) {
            return []; // Kembalikan array kosong jika tidak ada data
        }
        // Supaya tetap cocok dengan frontend (sourceLabels & sourceCounts)
        return [
            [
                'status_checker' => 'Minus Cod',
                'count' => (int) $result['minus_cod']
            ],
            [
                'status_checker' => 'Cod',
                'count' => (int) $result['cod_paid']
            ],
            [
                'status_checker' => 'Total Cod',
                'count' => (int) $result['total_cod_paid']
            ],
            [
                'status_checker' => 'Transfer',
                'count' => (int) $result['transfer']
            ]
        ];
    }

    public function get_status_pod($no_runsheet){
     $this->db->select("status_pod");   
     $this->db->where("no_runsheet",$no_runsheet);   
     $this->db->from("mv_checker_summary");   

     $query=$this->db->get(); 
     $result=$query->row();

     return $result;
    }
    public function get_status_pod_by_id($id_courier,$start_date,$end_date){
     $this->db->select("status_pod");   
     $this->db->where("id_courier",$id_courier);   
     $this->db->where('Date(runsheet_date) >=', $start_date);
     $this->db->where('Date(runsheet_date) <=', $end_date);
     $this->db->from("mv_checker_summary");   

     $query=$this->db->get(); 
     $result=$query->row();

     return $result;
    }


  

}


?>