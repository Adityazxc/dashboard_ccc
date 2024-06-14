<?php

class Finance_model extends CI_Model
{

    // PCRF REGISTRATION
    var $customer_column_order = array(null, 'id', 'date', 'awb_no', 'customer_name', 'harga', 'email', 'no_hp', 'service', 'account_name', null); //set column field database for datatable orderable
    var $customer_column_search = array('id', 'date', 'awb_no', 'customer_name', 'harga', 'email', 'no_hp', 'service', 'account_name'); //set column field database for datatable searchable
    var $customer_order = array('id' => 'DESC'); // default order


    private function _getdatatables_finance()
    {
        $this->db->select('customers.id,customers.date,customers.awbno_claim,customers.customer_name,customers.harga,customers.email,customers.no_hp,customers.service,customers.voucher,
        customers.value_voucher,customers.expired_date,customers.status,customers.status_email,customers.otp,users.id_user,users.account_name,customers.type,users.account_number');
        $this->db->from('customers');
        $this->db->join('users', 'customers.id_user = users.id_user', 'left');
        $this->db->where('customers.type', 'customer');
        $dateFrom = $this->input->post('dateFrom');
        $dateThru = $this->input->post('dateThru');
        // Logika filter untuk account_name
        $accountNameFilter = $this->input->post('account_name');
        if (!empty($accountNameFilter)) {
            $this->db->like('users.account_name', $accountNameFilter);
            $this->db->or_like('customers.customer_name', $accountNameFilter);
            $this->db->or_like('customers.awbno_claim', $accountNameFilter);
            $this->db->or_like('customers.voucher', $accountNameFilter);

        }

        if ($this->input->post('status') == 'status2') {
            $this->db->where('customers.status', 'Y');
        } else if ($this->input->post('status') == 'status3') {
            $this->db->where('customers.status_email', 'Y')
            ->where('customers.status', 'N');
        } else if ($this->input->post('status') == 'hangus') {            
            $this->db->where('expired_date <', date('Y-m-d'))
                ->where('DATE(customers.date) >=', $dateFrom)
                ->where('DATE(customers.date) <=', $dateThru);
        } else if ($this->input->post('status') == 'status4') {
            // Tidak perlu menambahkan kondisi khusus jika status4, biarkan kosong
        }

        $this->db->where('DATE(customers.date) >=', $this->input->post('dateFrom'));
        $this->db->where('DATE(customers.customer_name) <=', $this->input->post('dateThru'));


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
    function count_filtered_customer()
    {
        $this->_getdatatables_finance();
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
}

?>