<?php

class Scan_Product_model extends CI_Model
{

    var $customer_column_order = array(null, 'id', 'date', 'awb_no', 'customer_name', 'harga', 'email', 'no_hp', 'service', null); //set column field database for datatable orderable
    var $customer_column_search = array('nik', 'nama', 'orion_id', 'sca_id', 'dashboard_id', 'apex_id', 'create_date', 'email', 'no_tlp'); //set column field database for datatable searchable
    var $customer_order = array('id' => 'DESC'); // default order

    // default order
    public function _getDetails($barcode)
    {
        $this->db->from('products');
        $this->db->where('barcode_product', $barcode);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }
   


    

}
?>