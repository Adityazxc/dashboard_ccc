<?php

class Stock_model extends CI_Model
{

    var $customer_column_order = array(null,'name_product','barcode_product','size_printing','price_shirt','price_packing','price_printing','price_press','price_press'); //set column field database for datatable orderable
    var $customer_column_search = array('name_product','barcode_product','size_printing','price_shirt','price_packing','price_printing','price_press','price_press'); //set column field database for datatable searchable



    private function _getdatatables_stock()
    {
        $this->db->select('*');
        $this->db->from('products');
        $this->db->order_by('stock','ASC');
        



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
            $this->db->order_by($this->customer_column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } elseif (isset($this->order)) {
            $customer_order = $this->order;
            $this->db->order_by(key($customer_order), $customer_order[key($customer_order)]);
        }
    }

    function getdatatables_stock()
    {
        $this->_getdatatables_stock();
        if (@$_POST['length'] != -1)
            $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered_stock()
    {
        $this->_getdatatables_stock();
        $query = $this->db->get();
        return $query->num_rows();
    }
  
    function count_all_stock()
    {
    
        $this->db->select('*');
        $this->db->from('products');
        return $this->db->count_all_results();
    }

  

    function _delete_stock($id_product){
        try{            
            $this->db->where('id_product', $id_product);
            $this->db->delete('products');
            return TRUE;
        }catch (Exception $e) {
        echo 'Error' . $e->getMessage();
        return FALSE;
        }
        
    }
    function _edit_stock($id_product,$user_data){
        try{            
            $this->db->where('id_product', $id_product);
            $this->db->update('products', $user_data);
            return TRUE;
        }catch (Exception $e) {
        echo 'Error' . $e->getMessage();
        return FALSE;
        }        
    }
    
    function _add_stock($id_product,$user_data){
        try{            
            $this->db->where('id_product', $id_product);
            $this->db->update('products', $user_data);
            
            return TRUE;
        }catch (Exception $e) {
        echo 'Error' . $e->getMessage();
        return FALSE;
        }
        
    }
    

}

?>