<?php

class Ccc_model_corp extends CI_Model
{

    // PCRF REGISTRATION
    var $customer_column_order = array(null, 'id', 'date', 'awb_no','id_customer', 'customer_name', 'consignee', 'qty', 'weight', 'harga', 'create_at', null); //set column field database for datatable orderable
    var $customer_column_search = array('id', 'date', 'awb_no','id_customer', 'customer_name', 'consignee', 'qty', 'weight', 'harga', 'create_at'); //set column field database for datatable searchable
    var $customer_order = array('id' => 'DESC'); // default order



    private function _getdatatables_customer()
{
    $this->db->select('*');            
    $dateFrom= $this->security->xss_clean($this->input->post('dateFrom'));
    $dateThru= $this->security->xss_clean($this->input->post('dateThru'));
        
    $this->db->where('DATE(date) >=', $dateFrom)
             ->where('DATE(date) <=', $dateThru);
    
    $this->db->from('corporate');
    
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

    function getdatatables_customer()
    {
        $this->_getdatatables_customer();
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
        $this->db->where('DATE(date) >=', $this->security->xss_clean($this->input->post('dateFrom')));
        $this->db->where('DATE(date) <=', $this->security->xss_clean($this->input->post('dateThru')));
        $this->db->from('corporate');
        return $this->db->count_all_results();
    }
    

    function tambah($data)
    {
        $this->db->insert('customers', $data);
        return TRUE;
    }

    function summary_customer()
    {
        $this->db->select('SUM(CASE WHEN status = "status1" THEN harga ELSE 0 END) as sum_status1');        
        $query = $this->db->get('corporate');
        return $query->row();
    }


    private function _getdatatables_agen()
    {
        $id_user = $this->session->userdata('id_user');
        $this->db->select('*');      
        $this->db->where('DATE(date) >=', $this->security->xss_clean($this->input->post('dateFrom')));
        $this->db->where('DATE(date) <=', $this->security->xss_clean($this->input->post('dateThru')));
        $this->db->where('id_user', $id_user);



        $this->db->from('corporate');
        $i = 0;
        foreach ($this->customer_column_search as $item) { // loop column
            if (@$_POST['search']['value']) { // if datatable send POST for search
                if ($i === 0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if (count($this->customer_column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) { // here order processing
            $this->db->order_by($this->customer_column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $customer_order = $this->order;
            $this->db->order_by(key($customer_order), $customer_order[key($customer_order)]);
        }
    }

    function getdatatables_agen()
    {
        $this->_getdatatables_agen();
        if (@$_POST['length'] != -1)
            $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }


    private function _getdatatables_finance()
    {
        $this->db->select('*');
        $this->db->from('corporate');     
        $this->db->order_by('date', 'desc');                     

        $this->db->where('DATE(date) >=', $this->security->xss_clean($this->input->post('dateFrom')));
        $this->db->where('DATE(date) <=', $this->security->xss_clean($this->input->post('dateThru')));


        $i = 0;
        foreach ($this->customer_column_search as $item) { // loop column
            if (@$_POST['search']['value']) { // if datatable send POST for search
                if ($i === 0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if (count($this->customer_column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) { // here order processing
            $this->db->order_by($this->customer_column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $customer_order = $this->order;
            $this->db->order_by(key($customer_order), $customer_order[key($customer_order)]);
        }
    }


    function getdatatables_finance()
    {
        $this->_getdatatables_finance();
        if (@$_POST['length'] != -1)
            $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
    private function _getdatatables_marketing()
    {
        $this->db->select('*');              
        
        $status = $this->input->post('status');                  
        $dateFrom= $this->security->xss_clean($this->input->post('dateFrom'));
        $dateThru= $this->security->xss_clean($this->input->post('dateThru'));

        $this->db->where('DATE(date) >=', $dateFrom)
                 ->where('DATE(date) <=', $dateThru);
        
        $this->db->from('corporate');
        
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
    
        function getdatatables_marketing()
        {
            $this->_getdatatables_marketing();
            if (@$_POST['length'] != -1)
                $this->db->limit(@$_POST['length'], @$_POST['start']);
            $query = $this->db->get();
            return $query->result();
        }    
   
}

?>