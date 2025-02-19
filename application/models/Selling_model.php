<?php

class Selling_model extends CI_Model
{

    var $customer_column_order = array(null, 'name_product', 'barcode_product', 'size_printing', 'price_shirt', 'price_packing', 'price_printing', 'price_press', 'price_press'); //set column field database for datatable orderable
    var $customer_column_search = array('name_product', 'barcode_product', 'size_printing', 'price_shirt', 'price_packing', 'price_printing', 'price_press', 'price_press'); //set column field database for datatable searchable



    private function _getdatatables_selling()
    {
        $this->db->from('selling s');
        $this->db->join('products p', 's.id_product = p.id_product', 'left');

        $dateFrom = $this->input->post('dateFrom', TRUE);
        $dateThru = $this->input->post('dateThru', TRUE);

        // Pastikan $dateFrom dan $dateThru memiliki nilai sebelum digunakan dalam kondisi
        if (!empty($dateFrom) && !empty($dateThru)) {
            $this->db->where('DATE(s.date_selling) >=', $dateFrom);
            $this->db->where('DATE(s.date_selling) <=', $dateThru);
        }


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

    function getdatatables_selling()
    {
        $this->_getdatatables_selling();
        if (@$_POST['length'] != -1)
            $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered_selling()
    {
        $this->_getdatatables_selling();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_all_selling()
    {

        $this->db->select('*');
        $this->db->from('selling');
        return $this->db->count_all_results();
    }

    function _add_selling($user_data,$barcode)
    {
        $add_stock = $user_data['amount'];        
        $stock = $this->_get_stock($barcode);
        $update_stock = $stock - $add_stock;
        $this->_update_stock($update_stock,$barcode);
        
        try {
            $this->db->insert('selling', $user_data);
            return TRUE;
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return FALSE;
        }
    
    }
    function _update_stock($update_stock,$id_product){
        $this->db->set('stock',$update_stock);
        $this->db->where('barcode_product', $id_product);
        $this->db->update('products');
    }

    function _get_stock($id_product)
    {
        $this->db->select('*');
        $this->db->from('products');
        $this->db->where('barcode_product', $id_product);
        $query = $this->db->get();
        $result = $query->result();
        $stok = $result[0]->stock;
        return $stok;
    }

    function _delete_product($id_product)
    {
        try {
            $this->db->where('id_product', $id_product);
            $this->db->delete('products');
            return TRUE;
        } catch (Exception $e) {
            echo 'Error' . $e->getMessage();
            return FALSE;
        }

    }
    function _edit_product($id_product, $user_data)
    {
        try {
            $this->db->where('id_product', $id_product);
            $this->db->update('products', $user_data);
            return TRUE;
        } catch (Exception $e) {
            echo 'Error' . $e->getMessage();
            return FALSE;
        }

    }


}

?>