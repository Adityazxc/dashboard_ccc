<?php

class Marketing_model extends CI_Model
{

    var $customer_column_order = array(null, 'id', 'date', 'awb_no', 'customer_name', 'harga', 'email', 'no_hp', 'service', null); //set column field database for datatable orderable
    var $customer_column_search = array('id', 'date', 'awb_no', 'customer_name', 'harga', 'email', 'no_hp', 'service'); //set column field database for datatable searchable
    var $customer_order = array('id' => 'DESC'); // default order


    public function getdatatables_email_null()
    {
        $this->db->select('*');
        $this->db->where('status_email', null);
        $this->db->where('DATE(date) >=', $this->input->post('dateFrom'));
        $this->db->where('DATE(date) <=', $this->input->post('dateThru'));
        $this->db->from('customers');

        $i = 0;

        if (@$_POST['search']['value']) {
            foreach ($this->customer_column_search as $item) {
                if ($i === 0) {
                    $this->db->group_start()->like($item, $_POST['search']['value']);
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

    function getdatatables_email_null_count()
    {
        $this->db->select('*');
        $this->db->where('status_email', null);
        $this->db->where('DATE(date) >=', $this->input->post('dateFrom'));
        $this->db->where('DATE(date) <=', $this->input->post('dateThru'));
        $this->db->from('customers');

        $i = 0;

        if (@$_POST['search']['value']) {
            foreach ($this->customer_column_search as $item) {
                if ($i === 0) {
                    $this->db->group_start()->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if (count($this->customer_column_search) - 1 == $i) {
                    $this->db->group_end();
                }
                $i++;
            }
        }

        return $this->db->count_all_results();
    }



    public function update_email_model($customerId, $newEmail)
    {
        $data = array('email' => $newEmail);

        $this->db->where('id', $customerId);
        $this->db->update('customers', $data);

        return $this->db->affected_rows() > 0;
    }
    private function _getdatatables_marketing()
    {
        $this->db->select('*');      
        $this->db->where('type', 'customer'); 
        $this->db->order_by('date', 'desc'); 
         

        $status = $this->input->post('status');
        $dateFrom = $this->input->post('dateFrom');
        $dateThru = $this->input->post('dateThru');

        switch ($status) {
            case 'status1':
                $this->db->where('status_email', 'Y');
                break;
            case 'status2':
                $this->db->where('status', 'Y');
                break;
            case 'belumDigunakan':
                $this->db->where('status_email', 'Y')
                ->where('status', 'N');
                break;
            case 'emailDikirim':
                $this->db->where('status_email', 'Y');
                break;
            case 'belumDikirim':
                $this->db->where('status_email', 'N');
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

    function getdatatables_marketing()
    {
        $this->_getdatatables_marketing();
        if (@$_POST['length'] != -1)
            $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }


    function count_filtered_customer()
    { 
        $this->_getdatatables_marketing();
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
        $this->db->where('DATE(date) >=', $this->input->post('dateFrom'));
        $this->db->where('DATE(date) <=', $this->input->post('dateThru'));
        $this->db->from('customers');
        return $this->db->count_all_results();
    }
    private function _getdatatables_send_email()
    {
        $this->db->select('*');      
        $this->db->where('type', 'customer');  
        $this->db->where('status_email', 'N'); 
        $this->db->where('email IS NOT NULL', null, false);

        $status = $this->input->post('status');
        $dateFrom = $this->input->post('dateFrom');
        $dateThru = $this->input->post('dateThru');

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

    function getdatatables_send_email()
    {
        $this->_getdatatables_send_email();
        if (@$_POST['length'] != -1)
            $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }


    function count_filtered_send_email()
    { 
        $this->_getdatatables_send_email();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_all_send_email()
    { 
        $this->db->select('*');
        $this->db->where('type', 'customer');
        if ($this->input->post('status') == 'status2') {
            $this->db->where('status', 'Y');
        } else if ($this->input->post('status') == 'status3') {
            $this->db->where('status', 'N');
        } else if ($this->input->post('status') == 'status4') {
        }
        $this->db->where('DATE(date) >=', $this->input->post('dateFrom'));
        $this->db->where('DATE(date) <=', $this->input->post('dateThru'));
        $this->db->from('customers');
        return $this->db->count_all_results();
    }

}
?>