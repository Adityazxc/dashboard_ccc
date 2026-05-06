<?php

class Customers_lm_model extends CI_Model
{

    var $lm_column_order = array(
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
    
    var $lm_column_search = array(
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
    
    var $lm_order = array('id_cus_lm' => 'DESC');
    
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->db_checker = $this->load->database('checker_pod', TRUE);
    }
  

    private function _getdatatables_customers_lm()
    {
        $this->db->select('cl.*,u.name');
        
        $this->db->from('cus_lm cl');
        $this->db->join('checker_pod.users u', 'u.username = cl.pic_bdo', 'left');
        
        

        


        $i = 0;

        if (@$_POST['search']['value']) {
            foreach ($this->lm_column_search as $item) {
                if ($i === 0) {
                    $this->db->group_start()
                        ->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if (count($this->lm_column_search) - 1 == $i) {
                    $this->db->group_end();
                }
                $i++;
            }
        }

        if (isset($_POST['order'])) {
            $column_order_index = $_POST['order']['0']['column'];
            if ($this->lm_column_order[$column_order_index] != null) {
                $this->db->order_by($this->lm_column_order[$column_order_index], $_POST['order']['0']['dir']);
            }
        } elseif (isset($this->order)) {
            $lm_order = $this->order;
            $this->db->order_by(key($lm_order), $lm_order[key($lm_order)]);
        }

    }

    function getdatatables_customers_lm()
    {
        $this->_getdatatables_customers_lm();
        if (@$_POST['length'] != -1)
            $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered_customers_lm()
    {
        $this->_getdatatables_customers_lm();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_all_customers_lm()
    {
        $this->db->select('*');

        $this->db->from('cus_lm');
        

        return $this->db->count_all_results();
    }


    public function _get_customer_name()
    {
        $this->db->select('account_number,cust_name');
        $this->db->from('cus_lm');
        $this->db->group_by('cust_name');
        $query = $this->db->get();
        return $query->result_array();

    }
    public function _get_grouping_customer()
    {
        $this->db->select('big_grouping_cust, account_number');
        $this->db->from('cus_lm');
        $this->db->group_by(['big_grouping_cust']);
        $query = $this->db->get();
        return $query->result_array();

    }
    public function deactive_customers_lm($id_user,$status_customer)
    {
        $data = array('status_customer' => $status_customer);
        $this->db->where('id_cus_lm', $id_user);
        $this->db->update('cus_lm',$data);        
        return $this->db->affected_rows() > 0;

    }

    public function add_Customers_lm_model($data)
    {
        $this->db->insert('cus_lm', $data);
        return $this->db->affected_rows() > 0;
    }
    public function active_customers_lm($id_user,$status_customer)
    {
        $data = array('status_customer' => $status_customer);
        $this->db->where('id_cus_lm', $id_user);
        $this->db->update('cus_lm',$data);        
        return $this->db->affected_rows() > 0;

    }

    public function edit_customers_lm($data, $id_user)
    {
        $this->db->where('id_cus_lm', $id_user);
        $this->db->update('cus_lm', $data);
        return $this->db->affected_rows() > 0;
    }
}

?>