<?php

class Ccc_model extends CI_Model
{

    var $customer_column_order = array(null, 'id', 'date', 'awb_no', 'customer_name', 'harga', 'email', 'no_hp', 'service', null); //set column field database for datatable orderable
    var $customer_column_search = array('id', 'date', 'awb_no', 'customer_name', 'harga', 'email', 'no_hp', 'service'); //set column field database for datatable searchable
    var $customer_order = array('id' => 'DESC'); // default order



    private function _getdatatables_customer()
    {
        $this->db->select('*');
        $this->db->where('type', 'customer');
        $this->db->order_by('date', 'desc');

        $status = $this->input->post('status',TRUE);
        $dateFrom = $this->input->post('dateFrom',TRUE);
        $dateThru = $this->input->post('dateThru',TRUE);

        switch ($status) {
            case 'status1':
                $this->db->where('status_email', 'Y');
                break;
            case 'status2':
                $this->db->where('status', 'Y');
                break;
            case 'status3':
                $this->db->where('status_email', 'Y')
                ->where('status', 'N');
                break;
            case 'emailDikirim':
                $this->db->where('status_email', 'Y');
                break;
            case 'belumDikirim':
                $this->db->where('status_email !=', 'Y');
                break;
            case 'hangus':
                $this->db->where('expired_date <', date('Y-m-d'))
                    ->where('DATE(date) >=', $dateFrom)
                    ->where('DATE(date) <=', $dateThru);
                break;
            case 'status4':
                // Handle status4 case if needed
                break;
        }

        $this->db->where('DATE(date) >=', $dateFrom)
            ->where('DATE(date) <=', $dateThru);

        $this->db->from('customers');

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
        $this->db->where('type', 'customer');
        if ($this->input->post('status') == 'status2') {
            $this->db->where('status', 'Y');
        } else if ($this->input->post('status') == 'status3') {
            $this->db->where('status', 'N');
        } else if ($this->input->post('status') == 'status4') {
        }
        $this->db->where('DATE(date) >=', $this->security->xss_clean($this->input->post('dateFrom')));
        $this->db->where('DATE(date) <=', $this->security->xss_clean($this->input->post('dateThru')));
        $this->db->from('customers');
        return $this->db->count_all_results();
    }
   
    function count_filtered_agen()
    { 
        $this->_getdatatables_agen();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_all_agen()
    { 
        $this->db->select('*');
        $this->db->where('type', 'customer');
        if ($this->input->post('status') == 'status2') {
            $this->db->where('status', 'Y');
        } else if ($this->input->post('status') == 'status3') {
            $this->db->where('status', 'N');
        } else if ($this->input->post('status') == 'status4') {
        }
        $this->db->where('DATE(date) >=', $this->security->xss_clean($this->input->post('dateFrom')));
        $this->db->where('DATE(date) <=', $this->security->xss_clean($this->input->post('dateThru')));
        $this->db->from('customers');
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
        $this->db->where('type', 'customer');
        $query = $this->db->get('customers');
        return $query->row();
    }


    private function _getdatatables_agen()
    {
        $id_user = $this->session->userdata('id_user');
        $this->db->select('*');
        $this->db->where('type', 'customer');
        if ($this->input->post('status') == 'status2') {
            $this->db->where('status', 'Y');
        } else if ($this->input->post('status') == 'status3') {
            $this->db->where('status', 'N');
        } else if ($this->input->post('status') == 'status4') {
        }
        $this->db->where('DATE(date) >=', $this->security->xss_clean($this->input->post('dateFrom')));
        $this->db->where('DATE(date) <=', $this->security->xss_clean($this->input->post('dateThru')));
        $this->db->where('id_user', $id_user);



        $this->db->from('customers');
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




}

?>