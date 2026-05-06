<?php

class Customers_fm_model extends CI_Model
{

    var $fm_column_order = array(
        null,
        'cust_id',        
        'cust_name',
        'grouping_cust',
        'segmentasi',
        'name',
        'status',
        'address',
        'source'
    );
    
    var $fm_column_search = array(
        'cust_id',        
        'cust_name',
        'grouping_cust',
        'segmentasi',
        'name',
        'status',
        'address',
        'source'
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
    public function add_customers($data)
    {
        $this->db->insert('cus_fm', $data);
        return $this->db->affected_rows() > 0;
    }
    public function active_customers_lm($id_user,$status_customer)
    {
        $data = array('status' => $status_customer);
        $this->db->where('id', $id_user);
        $this->db->update('cus_fm',$data);        
        return $this->db->affected_rows() > 0;

    }

    public function edit_customers_fm($data, $id_user)
    {
        $this->db->where('id', $id_user);
        $this->db->update('cus_fm', $data);
        return $this->db->affected_rows() > 0;
    }
    private function _getdatatables_customers_fm()
    {
        $this->db->select('cus_fm.*,
        u.name');
        
        $this->db->from('cus_fm');
        $this->db->join('checker_pod.users u', 'u.username = cus_fm.pic', 'left');
        
        

        


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