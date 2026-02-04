<?php

class Export_model extends CI_Model
{



    public function getDataNotApprove($filters = [])
    {
        // Pilih kolom yang ingin ditampilkan
        $this->db->select('
            h.awb,
            h.id_courier,
            c.courier_name,
            z.zone,
            h.url_photo,
            h.url_revision

            
            
        ');
        $this->db->from('checker h');        
        $this->db->join('courier c', 'c.id_courier = h.id_courier', 'left');
        $this->db->join('zone z', 'z.zone_code = h.zone', 'left');
        if($filters['action']=="revision"){            
            $this->db->where('status_checker',"Revisi");        
        }else{
            $this->db->where('status_checker',"Tidak Sesuai");        
        }
        
        

        // 🧩 Filter opsional
        if (!empty($filters['dateFrom']) && !empty($filters['dateThru'])) {
            $this->db->where('DATE(h.create_date) >=', $filters['dateFrom']);
            $this->db->where('DATE(h.create_date) <=', $filters['dateThru']);
        }

        

        $this->db->order_by('h.create_date', 'DESC');

        return $this->db->get()->result_array();
    }


}

?>