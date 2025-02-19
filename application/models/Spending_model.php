<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Spending_model extends CI_Model
{
    var $customer_column_order = array(null, 'id_spending', 'name_spending', 'create_date', 'description','nominal_spending', null); //set column field database for datatable orderable
    var $customer_column_search = array('id_spending', 'name_spending', 'create_date', 'description','nominal_spending' ); //set column field database for datatable searchable
    var $customer_order = array('id_spending' => 'ASC');
    public function get_user_details($id)
    {
        $query = $this->db->get_where('users', array('id_user' => $id));

        return $query->row();
    }
    private function _getdatatables_spending()
    {
        $this->db->select('*');
        $this->db->from('spending');
        $dateFrom = $this->input->post('dateFrom', TRUE);
        $dateThru = $this->input->post('dateThru', TRUE);

        // Pastikan $dateFrom dan $dateThru memiliki nilai sebelum digunakan dalam kondisi
        if (!empty($dateFrom) && !empty($dateThru)) {
            $this->db->where('DATE(create_date) >=', $dateFrom);
            $this->db->where('DATE(create_date) <=', $dateThru);
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

    function getdatatables_spending()
    {
        $this->_getdatatables_spending();
        if (@$_POST['length'] != -1)
            $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered_spending()
    {
        $this->_getdatatables_spending();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_all_spending()
    {

        $this->db->select('*');
        $this->db->from('users');
        return $this->db->count_all_results();
    }

    public function reset_password($id, $password)
    {
        $newPassword = md5($password);
        $data = array('password' => $newPassword);
        $this->db->where('id_user', $id);
        $this->db->update('users', $data);
        return $this->db->affected_rows() > 0;
    }
    public function default_password($id_user)
    {
        $newPassword = md5('123456');
        $data = array('password' => $newPassword);
        $this->db->where('id_user', $id_user);
        $this->db->update('users', $data);
        return $this->db->affected_rows() > 0;
    }

    function _add_spending($user_data)
    {
     
        try {
            $this->db->insert('spending', $user_data);
            return TRUE;
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return FALSE;
        }       
    }


    function _delete_spending($id_spending)
    {
        $this->db->where('id_spending', $id_spending);
        $this->db->delete('spending');
        return $this->db->affected_rows() > 0;
    }


    function _edit_spending($user_data, $id_user)
    {

        $this->db->where('id_spending', $id_user);
        $this->db->update('spending', $user_data);
        return $this->db->affected_rows() > 0;
    }
}