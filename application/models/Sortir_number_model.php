<?php

class Sortir_number_model extends CI_Model
{
    var $customer_column_order = array(null, 'id_user', 'account_name', 'employee_position', 'regional', 'branch', 'origin', 'zone', 'username', 'kpi', 'role', null); //set column field database for datatable orderable
    var $customer_column_search = array('id_user', 'account_name', 'employee_position', 'regional', 'branch', 'origin', 'zone', 'username', 'kpi', 'role', ); //set column field database for datatable searchable
    var $customer_order = array('id' => 'DESC');

    public function getSortirNumberById($id)
    {
        $this->db->select('sortir_number, qty');
        $this->db->from('history'); // Ganti dengan nama tabel Anda
        $this->db->where('id_user', $id);
        $this->db->where('end_date IS NULL');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row(); // Mengembalikan sortir_number
        } else {
            return null; // Mengembalikan null jika tidak ditemukan
        }
    }
    public function createSortirNumberById($latter_code)
    {
        $this->db->select('*')
            ->from('history')
            ->like('sortir_number', "SRT/{$latter_code}/", 'after')
            ->order_by('sortir_number', 'DESC')
            ->limit(1);

            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                return $query->row(); // Kembalikan baris terakhir
            }
        
            return null;
    }





}

?>