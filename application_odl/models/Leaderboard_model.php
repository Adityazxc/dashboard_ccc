<?php

class Leaderboard_model extends CI_Model
{

    var $checker_column_order = array(null, "c.courier_name", "l.succes_rate","l.kpi",null, "l.hrs", "l.minus_poin", "l.total_poin", "z.zone",null); //set column field database for datatable orderable
    var $checker_column_search = array("c.id_courier", "c.courier_name", "c.zone"); //set column field database for datatable orderable

    
    // private function _get_datatables_top_courier()
    // {

    //     $dateFrom = $this->input->post('dateFrom');
    //     $origin = $this->input->post('origin');

    //     $bulan = date('m', strtotime($dateFrom));
    //     $tahun = date('Y', strtotime($dateFrom));

    //     $this->db->select('
    //         c.courier_name,
    //         c.id_courier,
    //         l.kpi,
    //         l.hrs,
    //         l.total_poin,
    //         l.succes_rate,
    //         l.minus_poin,
    //         m.qty_sesuai,
    //         m.qty_revisi,
    //         m.qty_awb,
    //         m.runsheet_date,            
    //         z.zone
    //     ');
    //     $this->db->from('mv_leaderboard_summary l');
    //     $this->db->join('courier c', 'c.id_courier = l.id_courier', 'left');
    //     $this->db->join('mv_checker_summary m', 'm.id_courier = l.id_courier ');
    //     $this->db->join('zone z', 'm.zone = z.zone_code', 'left');
    //     $this->db->order_by('l.total_poin', 'DESC');


    //     $this->db->where('m.leaderboard_month', $bulan);
    //     $this->db->where('m.leaderboard_year', $tahun);

    //     if (!empty($origin)) {
    //         $this->db->where('z.origin_code', $origin);
    //     }
        
    //     $this->db->where('c.id_courier IS NOT NULL');
    //     $this->db->where('c.id_courier !=', '');
        

    //     $i = 0;

    //     if (@$_POST['search']['value']) {
    //         foreach ($this->checker_column_search as $item) {
    //             if ($i === 0) {
    //                 $this->db->group_start()
    //                     ->like($item, $_POST['search']['value']);
    //             } else {
    //                 $this->db->or_like($item, $_POST['search']['value']);
    //             }
    //             if (count($this->checker_column_search) - 1 == $i) {
    //                 $this->db->group_end();
    //             }
    //             $i++;
    //         }
    //     }

    //     if (isset($_POST['order'])) {
    //         $this->db->order_by($this->checker_column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    //     } elseif (isset($this->order)) {
    //         $checker_order = $this->order;
    //         $this->db->order_by(key($checker_order), $checker_order[key($checker_order)]);
    //     }
    // }

    // function get_datatables_top_courier()
    // {
    //     $this->_get_datatables_top_courier();
    //     if (@$_POST['length'] != -1)
    //         $this->db->limit(@$_POST['length'], @$_POST['start']);
    //     $query = $this->db->get();
    //     return $query->result();
    // }

    // function count_filtered_top_courier()
    // {
    //     $this->_get_datatables_top_courier();
    //     $query = $this->db->get();
    //     return $query->num_rows();
    // }

    // function count_all_top_courier()
    // {

    //     $this->db->select('*');
    //     $this->db->from('checker');
    //     return $this->db->count_all_results();
    // }
    function get_datatables_courier($type = 'top')
{
    $this->_get_datatables_courier($type);
    if (@$_POST['length'] != -1)
        $this->db->limit(@$_POST['length'], @$_POST['start']);
    $query = $this->db->get();
    return $query->result();
}

private function _get_datatables_courier($type = 'top')
{
    $dateFrom = $this->input->post('dateFrom');
    $origin = $this->input->post('origin');

    $bulan = date('m', strtotime($dateFrom));
    $tahun = date('Y', strtotime($dateFrom));

    $this->db->select('
        c.courier_name,
        c.id_courier,
        l.kpi,
        l.hrs,
        l.total_poin,
        l.succes_rate,
        l.minus_poin,
        m.qty_sesuai,
        m.qty_revisi,
        m.qty_awb,
        m.runsheet_date,            
        z.zone
    ');
    $this->db->from('mv_leaderboard_summary l');
    $this->db->join('courier c', 'c.id_courier = l.id_courier', 'left');
    $this->db->join('mv_checker_summary m', 'm.id_courier = l.id_courier');
    $this->db->join('zone z', 'm.zone = z.zone_code', 'left');

    $this->db->where('m.leaderboard_month', $bulan);
    $this->db->where('m.leaderboard_year', $tahun);
    $this->db->where('c.id_courier IS NOT NULL');
    $this->db->where('c.id_courier !=', '');

    if (!empty($origin)) {
        $this->db->where('z.origin_code', $origin);
    }

    // Sorting top/bottom
    $order = ($type === 'bottom') ? 'ASC' : 'DESC';
    $this->db->order_by('l.total_poin', $order);

    // Search filter
    $i = 0;
    if (@$_POST['search']['value']) {
        foreach ($this->checker_column_search as $item) {
            if ($i === 0) {
                $this->db->group_start()->like($item, $_POST['search']['value']);
            } else {
                $this->db->or_like($item, $_POST['search']['value']);
            }
            if (count($this->checker_column_search) - 1 == $i) {
                $this->db->group_end();
            }
            $i++;
        }
    }
}

function count_filtered_courier($type = 'top')
{
    $this->_get_datatables_courier($type);
    $query = $this->db->get();
    return $query->num_rows();
}

function count_all_courier()
{
    $this->db->select('*');
    $this->db->from('mv_leaderboard_summary'); // Pastikan ini benar
    return $this->db->count_all_results();
}


    public function refresh_total_poin($runsheet)
    {

        $this->db->where('no_runsheet', $runsheet);
        $this->db->set('total_poin', 'succes_rate + kpi  + photo_pod + hrs - minus_poin', false);
        $this->db->update('leaderboard');
    }
    public function refresh_total_poin_all()
    {
        
        $this->db->set('total_poin', 'succes_rate + kpi  + photo_pod + hrs - minus_poin', false);
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
    public function update_poin_photo_pod($no_runsheet, $point_photo_pod)
    {

        // $this->db->select('*');         
        $this->db->set('photo_pod', $point_photo_pod);
        $this->db->where('no_runsheet', $no_runsheet);
        // $this->db->from('leaderboard');         

        // $query = $this->db->get();
        // return $query->result();

        $update = $this->db->update('leaderboard');

        if ($update) {
            // Berhasil update (meskipun mungkin tidak ada row yang berubah)
            return true;
        } else {


            return false;
        }
    }

    public function get_total_poin_courier($no_runsheet){
        $this->db->select('total_poin');
        $this->db->where('no_runsheet',$no_runsheet);
        $this->db->from('leaderboard');
        $query = $this->db->get();
        $result = $query->row();
        return $result;

    }


    public function get_status_photo_pod($id_courier, $runsheet_date)
    {
        $this->db->select('qty_awb, qty_sesuai, qty_tidak_sesuai, qty_revisi');
        $this->db->from('mv_checker_summary');
        $this->db->where('runsheet_date', $runsheet_date);
        $this->db->where('id_courier', $id_courier);

        $query = $this->db->get();
        $result = $query->row();

        if ($result) {
            $qty_awb = (int) $result->qty_awb;
            $qty_sesuai = (int) $result->qty_sesuai;
            $qty_tidak_sesuai = (int) $result->qty_tidak_sesuai;
            $qty_revisi = (int) $result->qty_revisi;

            // Hindari pembagian dengan nol
            if ($qty_awb > 0) {
                $persen_sesuai = (($qty_sesuai + $qty_revisi) / $qty_awb) * 100;
                $persen_sesuai = number_format($persen_sesuai, 1);

                //point 
                if ($persen_sesuai == 100) {
                    $point_photo_pod = 20;
                } else if ($persen_sesuai >= 90) {
                    $point_photo_pod = 10;
                } else {
                    $point_photo_pod = 0;
                }

                return $point_photo_pod;
            } else {
                return $point_photo_pod = 0;
                ;
            }
        } else {
            // Data tidak ditemukan
            return $point_photo_pod = 0;
            ;
        }
    }



    public function get_quality_foto_pod($id_courier)
    {
        $start_date = date('Y-m-01 00:00:00');
        $end_date = date('Y-m-t 23:59:59');

        $this->db->select('
            SUM(qty_awb) AS total_awb,
            SUM(qty_sesuai) AS total_valid,
            SUM(qty_revisi) AS total_revision, 
            SUM(qty_tidak_sesuai) AS total_invalid
        ');
        $this->db->where('create_date >=', $start_date);
        $this->db->where('create_date <=', $end_date);
        if (!empty($id_courier)) {
            $this->db->where('id_courier', $id_courier);
        }

        $this->db->from('mv_checker_summary');
        $query = $this->db->get();
        $result = $query->row(); // ambil satu baris

        // Convert ke array sesuai format yang dibutuhkan controller
        return [
            ['status_cod' => 'total_valid', 'count' => (int) $result->total_valid],
            ['status_cod' => 'total_revision', 'count' => (int) $result->total_revision],
            ['status_cod' => 'total_invalid', 'count' => (int) $result->total_invalid],
        ];
    }


    // Jika hanya ingin refresh satu kurir per bulan


    public function _get_success_cod($id_courier)
    {
        // Ambil tanggal pertama dan terakhir bulan ini
        $dateFrom = date('Y-m-01 00:00:00');
        $dateThru = date('Y-m-t 23:59:59');

        $where = "WHERE create_date BETWEEN ? AND ?";
        $params = [$dateFrom, $dateThru];

        // Query langsung count berdasarkan status_cod
        $sql = "
            SELECT 
                SUM(CASE WHEN status_cod = 'D01' THEN 1 ELSE 0 END) AS D01,
                SUM(CASE WHEN status_cod = 'D04' THEN 1 ELSE 0 END) AS D04,
                SUM(CASE WHEN status_cod = 'D07' THEN 1 ELSE 0 END) AS D07,
                SUM(CASE WHEN status_cod = 'D09' THEN 1 ELSE 0 END) AS D09
            FROM checker
            $where
        ";

        $query = $this->db->query($sql, $params);
        $result = $query->row_array();

        // Return biar tetap konsisten dengan FE (label + count)
        return [
            [
                'status_cod' => 'D01',
                'count' => (int) $result['D01']
            ],
            [
                'status_cod' => 'D04',
                'count' => (int) $result['D04']
            ],
            [
                'status_cod' => 'D07',
                'count' => (int) $result['D07']
            ],
            [
                'status_cod' => 'D09',
                'count' => (int) $result['D09']
            ]
        ];
    }

    public function _get_failed_cod($id_courier)
    {
        $dateFrom = date('Y-m-01 00:00:00');
        $dateThru = date('Y-m-t 23:59:59');

        $where = "WHERE create_date BETWEEN ? AND ?";
        $params = [$dateFrom, $dateThru];

        $sql = "
        SELECT 
            SUM(CASE WHEN status_cod = 'U05' THEN 1 ELSE 0 END) AS F05,
            SUM(CASE WHEN status_cod = 'U09' THEN 1 ELSE 0 END) AS F09,
            SUM(CASE WHEN status_cod = 'U12' THEN 1 ELSE 0 END) AS F12,
            SUM(CASE WHEN status_cod LIKE 'U%' AND status_cod NOT IN ('U05', 'U09', 'U12') THEN 1 ELSE 0 END) AS Other
        FROM checker
        $where
    ";

        $query = $this->db->query($sql, $params);
        $result = $query->row_array();

        return [
            [
                'status_cod' => 'F05',
                'count' => (int) $result['F05']
            ],
            [
                'status_cod' => 'F09',
                'count' => (int) $result['F09']
            ],
            [
                'status_cod' => 'F12',
                'count' => (int) $result['F12']
            ],
            [
                'status_cod' => 'Other',
                'count' => (int) $result['Other']
            ]
        ];
    }

    public function _get_data_courier($id_courier)
    {
        $this->db->select('*');
        $this->db->from('courier');
        $this->db->where('id_courier', $id_courier);

        $query = $this->db->get();

        return $query->result();
    }
    public function _get_data_awb($id_courier)
    {
        // Dapatkan tanggal dari awal hingga akhir bulan ini (dinamis)
        $dateFrom = date('Y-m-01 00:00:00'); // contoh: 2025-08-01 00:00:00
        $dateThru = date('Y-m-t 23:59:59'); // contoh: 2025-08-31 23:59:59

        $this->db->select('*, SUM(qty_awb) AS sum_qty_awb');
        $this->db->from('mv_checker_summary');
        $this->db->where('id_courier', $id_courier);

        // Tambahkan filter tanggal dinamis
        $this->db->where('runsheet_date >=', $dateFrom);
        $this->db->where('runsheet_date <=', $dateThru);

        $query = $this->db->get();
        return $query->result();
    }

    public function _get_awb_status_summary($id_courier)
    {
        $dateFrom = date('Y-m-01 00:00:00');
        $dateThru = date('Y-m-t 23:59:59');

        $this->db->select("
            COUNT(CASE 
                WHEN status_cod LIKE 'D%' THEN 1 
                ELSE NULL 
            END) AS total_success,
    
            COUNT(CASE 
                WHEN status_cod LIKE 'U%' THEN 1 
                ELSE NULL 
            END) AS total_failed,
    
            COUNT(CASE 
                WHEN status_cod IS NULL OR status_cod = '' THEN 1 
                ELSE NULL 
            END) AS total_inprogress
        ");

        $this->db->from('checker');
        $this->db->where('id_courier', $id_courier);
        $this->db->where('runsheet_date >=', $dateFrom);
        $this->db->where('runsheet_date <=', $dateThru);

        $query = $this->db->get();
        return $query->row(); // hasil: 1 baris, 3 kolom
    }


    public function update_poin_hrs($poin, $no_runsheet)
    {
        $this->db->where('no_runsheet', $no_runsheet);
        $this->db->set('hrs', $poin);
        $this->db->update('leaderboard');
    }


    public function refresh_mv_leaderboard_summary()
    {
        // 1. Kosongkan tabel summary
        $this->db->query('TRUNCATE TABLE mv_leaderboard_summary');

        // 2. Isi ulang dari tabel checker dan relasi lainnya
        $sql = "
            INSERT INTO mv_leaderboard_summary (
            id_courier,succes_rate,
            kpi, hrs,photo_pod, minus_poin, total_poin, no_runsheet
        )
        SELECT 
            id_courier,            
            SUM(kpi) AS kpi,
            SUM(succes_rate) AS succes_rate,
            SUM(hrs) AS hrs,
            SUM(photo_pod) AS photo_pod,
            SUM(minus_poin) AS minus_poin,
            SUM(total_poin) AS total_poin,
            COUNT(DISTINCT no_runsheet) AS total_runsheet
        FROM leaderboard
        GROUP BY id_courier, YEAR(create_date), MONTH(create_date)
      

        ";

        $this->db->query($sql);
    }

    public function delete_leaderboard($no_runsheet){
        $this->db->where('no_runsheet', $no_runsheet);
        $this->db->delete('leaderboard');
        return $this->db->affected_rows() > 0;
    }
    public function delete_checker_notes($no_runsheet){
        $this->db->where('no_runsheet', $no_runsheet);
        $this->db->delete('checker_notes');
        return $this->db->affected_rows() > 0;
    }


}
?>