<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Courier_model extends CI_Model
{
    var $customer_column_order = array(null, 'id_courier', 'courier_name', 'nik', 'tipe_courier', "location","area", null); //set column field database for datatable orderable
    var $customer_column_search = array('id_courier', 'courier_name', 'nik', 'tipe_courier', "location","area" ); //set column field database for datatable searchable
    var $customer_order = array('id' => 'DESC');
    public function get_user_details($id)
    {
        $query = $this->db->get_where('users', array('id_user' => $id));

        return $query->row();
    }
    private function _getdatatables_courier()
    {
        $this->db->select('*');
        $this->db->from('courier');

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

    function getdatatables_courier()
    {
        $this->_getdatatables_courier();
        if (@$_POST['length'] != -1)
            $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered_courier()
    {
        $this->_getdatatables_courier();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_all_courier()
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

    function add_courier($user_data)
    {
        // Cek apakah id kurir sudah ada
        $this->db->where('id_courier', $user_data['id_courier']);
        $query = $this->db->get('courier');

        if ($query->num_rows() > 0) {
            return false; // id courier sudah ada, tidak bisa insert
        }

        // Jika belum ada, tambahkan user
        $this->db->insert('courier', $user_data);
        return $this->db->affected_rows() > 0;
    }


    function delete_courier($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('courier');
        return $this->db->affected_rows() > 0;
    }


    function edit_courier($user_data, $id)
    {

        $this->db->where('id', $id);
        $this->db->update('courier', $user_data);
        return $this->db->affected_rows() > 0;
    }
}