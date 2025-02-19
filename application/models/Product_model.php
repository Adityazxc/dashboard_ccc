<?php

class Product_model extends CI_Model
{

    var $customer_column_order = array(null, 'name_product', 'barcode_product', 'size_printing', 'price_shirt', 'price_packing', 'price_printing', 'price_press', 'price_press', 'price_production', 'category'); //set column field database for datatable orderable
    var $customer_column_search = array('name_product', 'barcode_product', 'size_printing', 'price_shirt', 'price_packing', 'price_printing', 'price_press', 'price_press', 'price_production', 'category'); //set column field database for datatable searchable



    private function _getdatatables_product()
    {
        $this->db->select('p.*, pp.foto');
        $this->db->from('products p');
        $this->db->join('(SELECT id_product, MIN(id_photo) AS id_photo FROM photo_product GROUP BY id_product) pp_min', 'p.id_product = pp_min.id_product', 'left');
        $this->db->join('photo_product pp', 'pp.id_photo = pp_min.id_photo', 'left');

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
    public function insert_images($id_product, $files)
    {
        $data = [];
        foreach ($files as $file) {
            $data[] = [
                'id_product' => $id_product,
                'foto' => $file
            ];
        }
        return $this->db->insert_batch('photo_product', $data);
    }

    function getdatatables_product()
    {
        $this->_getdatatables_product();
        if (@$_POST['length'] != -1)
            $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered_product()
    {
        $this->_getdatatables_product();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_all_product()
    {

        $this->db->select('*');
        $this->db->from('products');
        return $this->db->count_all_results();
    }

    function _add_product($user_data)
    {
        $this->db->insert('products', $user_data);
        // var_dump($this->db->insert_id());
        $this->db->insert_id();
        return $this->db->insert_id();


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
    function _add_stock($id_product, $user_data)
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

    public function getSourceData($dateFrom, $dateThru)
    {
        $this->db->where('DATE(date_selling) >=', $dateFrom);
        $this->db->where('DATE(date_selling) <=', $dateThru);
        $this->db->select('source, sum(amount) as count');
        $this->db->from('selling');
        $this->db->group_by('source');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getSourceDataMultiple($dateFrom, $dateThru)
    {
        $this->db->select('source, SUM(amount) as count');
        $this->db->from('selling');
        $this->db->where('DATE(date_selling) >=', $dateFrom);
        $this->db->where('DATE(date_selling) <=', $dateThru);
        $this->db->group_by('source');
        $query = $this->db->get();

        return $query->result_array(); // Kembalikan hasil dalam bentuk array
    }



}

?>