<?php

class Admin_model extends CI_Model
{
    var $customer_column_order = array(null, 'id_user', 'account_name','employee_position','regional','branch','origin','zone','username','kpi', 'role',  null); //set column field database for datatable orderable
    var $customer_column_search = array('id_user', 'account_name','employee_position','regional','branch','origin','zone','username','kpi', 'role',  ); //set column field database for datatable searchable
    var $customer_order = array('id' => 'DESC');

    private function _getdatatables_customer()
    {
        //
        $this->db->select('*');
        // $this->db->order_by('id_user','DESC');
        // $this->db->where('username IS NOT NULL ');   
        $this->db->from('users');       

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

    }

    // public function _get()
    // {
    public function getSourceData($dateFrom, $dateThru,$origin,$zone)
    {
        $this->db->where('DATE(ch.create_date) >=', $dateFrom);
        $this->db->where('DATE(ch.create_date) <=', $dateThru);
        $this->db->select('ch.status_checker, COUNT(*) as count');
        $this->db->from('checker ch');
        $this->db->join('zone z', 'ch.zone = z.zone_code', 'left');
        $this->db->group_by('ch.status_checker');
        if (!empty($origin) && empty($zone)) {
            $this->db->where('z.origin_code', $origin);
        }
        if (!empty($origin) && !empty($zone)) {
            $this->db->where('ch.zone', $zone);
            $this->db->where('z.origin_code', $origin);
        }
        $query = $this->db->get();
        return $query->result_array();
    }
    public function getSourceDataMultiple($year,$origin,$zone)
    {
        $this->db->select('ch.status_checker, MONTH(ch.create_date) AS month, COUNT(*) AS count, z.*');        
        $this->db->from('checker ch');
        $this->db->join('zone z', 'ch.zone = z.zone_code', 'left');
        $this->db->where('YEAR(ch.create_date)', $year);
        $this->db->group_by(['ch.status_checker', 'MONTH(ch.create_date)']);
        $this->db->order_by('ch.status_checker', 'ASC');
        $this->db->order_by('month', 'ASC');       
        if (!empty($origin) && empty($zone)) {
            $this->db->where('z.origin_code', $origin);
        }
        if (!empty($origin) && !empty($zone)) {
            $this->db->where('ch.zone', $zone);
            $this->db->where('z.origin_code', $origin);
        }
        return $this->db->get()->result_array();
    }
  
    public function _get_origins()
    {
        $this->db->distinct();
        $this->db->select('*');
        $this->db->group_by('origin_name');
        $this->db->from('zone');
        $query = $this->db->get();
        $result = $query->result();        
        return $result; 
    }
    public function _get_zone($origin)
    {
        $this->db->distinct();
        $this->db->select('*');
        $this->db->from('zone');
        $this->db->where('origin_code',$origin);
        $query = $this->db->get();
        $result = $query->result();        
        return $result; 
    }
    public function _destination_code($zone_code)
    {
        $this->db->distinct();
        $this->db->select('*');
        $this->db->from('destination_code');
        $this->db->where('origin_code',$zone_code);
        $query = $this->db->get();
        $result = $query->result();        
        return $result; 
    }
    function getdatatables_customer()
    {
        //
        $this->_getdatatables_customer();
        if (@$_POST['length'] != -1)
            $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }


    private function _getdatatables_user()
    {
        $this->db->select('*');
        $this->db->from('users');                               
                                

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
            $column_order_index = $_POST['order']['0']['column'];
            if ($this->customer_column_order[$column_order_index] != null) {
                $this->db->order_by($this->customer_column_order[$column_order_index], $_POST['order']['0']['dir']);
            }
        } elseif (isset($this->order)) {
            $customer_order = $this->order;
            $this->db->order_by(key($customer_order), $customer_order[key($customer_order)]);
        }

    }

    function getdatatables_user()
    {
        $this->_getdatatables_user();
        if (@$_POST['length'] != -1)
            $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered_customer()
    {
        $this->_getdatatables_customer();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_all_customer()
    {
        $this->db->select('*');

        $this->db->from('users');
        return $this->db->count_all_results();
    }
    function count_filtered_user()
    {
        //
        $this->_getdatatables_user();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_all_user()
    {
        //
        $this->db->select('*');
        $this->db->from('users');
        return $this->db->count_all_results();
    }




}

?>