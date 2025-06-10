<?php

class Leaderboard_model extends CI_Model
{

    var $checker_column_order = array(null, "ch.id_courier", "courier_name",  "qty_sesuai","qty_revisi", "qty_tidak_sesuai", "ch.runsheet_date", "ch.upload_by", "ch.zone","zone_name"); //set column field database for datatable orderable
    var $checker_column_search = array("ch.id_courier", "courier_name",  "ch.zone"); //set column field database for datatable orderable

    private function _get_datatables_top_courier()
    {       

        $dateFrom = $this->input->post('dateFrom', TRUE);
        $dateThru = $this->input->post('dateThru', TRUE);
        $origin = $this->input->post('origin', TRUE);
        $zone = $this->input->post('zone', TRUE);
        $role = $this->input->post('role', TRUE);
        $this->db->select('*, (ch.qty_sesuai / ch.qty_awb * 100) as persentase');
        $this->db->order_by('ch.qty_awb', 'DESC');
        $this->db->order_by('persentase', 'DESC');
        $this->db->from('validasi_pod_view ch');
        $this->db->where('ch.create_date >=', $dateFrom . ' 00:00:00');
        $this->db->where('ch.create_date <=', $dateThru . ' 23:59:59');
        if (!empty($origin) && empty($zone)) {
            $this->db->where('ch.origin_code', $origin);
        }
        if (!empty($origin) && !empty($zone)) {
            $this->db->where('ch.zone', $zone);
            $this->db->where('ch.origin_code', $origin);
        }
        if(!empty($role)&& $role=="Koordinator"){
            $this->db->where('ch.role', $role);
        }

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

    function get_datatables_top_courier()
    {
        $this->_get_datatables_top_courier();
        if (@$_POST['length'] != -1)
            $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered_top_courier()
    {
        $this->_get_datatables_top_courier();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_all_top_courier()
    {

        $this->db->select('*');
        $this->db->from('checker');
        return $this->db->count_all_results();
    }
    function get_zone_name($zone_code){
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
        $this->db->select("ch.*, c.*, s.*");
        $this->db->from("checker ch");
        $this->db->join("courier c", 'c.id_courier=ch.id_courier', 'left');
        $this->db->join("status_pod s", 's.pod_code=ch.status_cod', 'left');
        $this->db->where("ch.id_courier", $id_courier);
        $this->db->where("DATE(ch.runsheet_date)", date('Y-m-d', strtotime($runsheet_date)));
        $this->db->where("ch.status_checker", "Sesuai");

        // Urutan: photo not found → pod not found → yang lainnya
        $this->db->order_by("
        CASE
            WHEN ch.url_photo = 'public/img/Image-not-found.png' THEN 0
            WHEN ch.url_pod = 'public/img/Image-not-found.png' THEN 1
            ELSE 2
        END", 'ASC', false);

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
        $this->db->select("ch.*,c.*,s.*");
        $this->db->from("checker ch");
        $this->db->join("courier c", 'c.id_courier=ch.id_courier', 'left');
        $this->db->join("status_pod s", 's.pod_code=ch.status_cod', 'left');
        $this->db->where("ch.id_courier", $id_courier);
        $this->db->where("DATE(ch.runsheet_date)", date('Y-m-d', strtotime($runsheet_date)));

        $this->db->where("ch.status_checker", "Tidak Sesuai");
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
}
?>