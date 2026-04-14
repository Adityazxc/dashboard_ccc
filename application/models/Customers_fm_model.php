<?php

class Customers_fm_model extends CI_Model
{

    var $fm_column_order = array(
        null,
        'account_number',
        'cust_branch',
        'cust_name',
        'cust_name2',
        'payment_metode',
        'big_grouping_cust',
        'cust_industry',
        'status_customer',
        'cek',
        'pic_bdo'
    );
    
    var $fm_column_search = array(
        'account_number',
        'cust_branch',
        'cust_name',
        'cust_name2',
        'payment_metode',
        'big_grouping_cust',
        'cust_industry',
        'status_customer',
        'cek',
        'pic_bdo'
    );
    
    var $lm_order = array('id_cus_fm' => 'DESC');
    
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->db_checker = $this->load->database('checker_pod', TRUE);
    }
    public function _get_grouping_customer()
    {
        $this->db->select('grouping_cust,cust_id');
        $this->db->group_by('grouping_cust');        
        $this->db->from('cus_fm');
        $query = $this->db->get();
        return $query->result_array();

    }

    private function _getdatatables_customers_fm()
    {
        $this->db->select('*');
        
        $this->db->from('cus_fm');
        
        

        


        $i = 0;

        if (@$_POST['search']['value']) {
            foreach ($this->fm_column_search as $item) {
                if ($i === 0) {
                    $this->db->group_start()
                        ->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if (count($this->fm_column_search) - 1 == $i) {
                    $this->db->group_end();
                }
                $i++;
            }
        }

        if (isset($_POST['order'])) {
            $column_order_index = $_POST['order']['0']['column'];
            if ($this->fm_column_order[$column_order_index] != null) {
                $this->db->order_by($this->fm_column_order[$column_order_index], $_POST['order']['0']['dir']);
            }
        } elseif (isset($this->order)) {
            $lm_order = $this->order;
            $this->db->order_by(key($lm_order), $lm_order[key($lm_order)]);
        }

    }

    function getdatatables_customers_fm()
    {
        $this->_getdatatables_customers_fm();
        if (@$_POST['length'] != -1)
            $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered_customers_fm()
    {
        $this->_getdatatables_customers_fm();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_all_customers_fm()
    {
        $this->db->select('*');

        $this->db->from('cus_fm');
        

        return $this->db->count_all_results();
    }


    public function _get_customer_name()
    {
        $this->db->select('account_number,cust_name');
        $this->db->from('cus_fm');
        $query = $this->db->get();
        return $query->result_array();

    }
}

?>