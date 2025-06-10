<?php
defined('BASEPATH') or exit('No direct script access allowed');
class User_model extends CI_Model
{
    var $customer_column_order = array(null, 'id_user', 'username', 'name', 'location', 'role', "no_hp", null); //set column field database for datatable orderable
    var $customer_column_search = array('id_user', 'username', 'name', 'location', 'role', "no_hp", ); //set column field database for datatable searchable
    var $customer_order = array('id_user' => 'DESC');
    public function get_user_details($id)
    {
        $query = $this->db->get_where('users', array('id_user' => $id));

        return $query->row();
    }
    private function _getdatatables_users()
    {
        $this->db->select('u.*,z.*');
        $this->db->from('users u');
        $this->db->join('zone z', 'z.zone_code=u.location', 'left');

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
    public function _get_origins()
    {
        $this->db->distinct();
        $this->db->select('*');        
        $this->db->from('zone');
        $query = $this->db->get();
        $result = $query->result();        
        return $result; 
    }
    function getdatatables_users()
    {
        $this->_getdatatables_users();
        if (@$_POST['length'] != -1)
            $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered_users()
    {
        $this->_getdatatables_users();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_all_users()
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
        $data = array('pass' => $newPassword);
        $this->db->where('id_user', $id_user);
        $this->db->update('users', $data);
        return $this->db->affected_rows() > 0;
    }

    function add_user_model($user_data)
    {
        // Cek apakah username sudah ada
        $this->db->where('username', $user_data['username']);
        $query = $this->db->get('users');

        if ($query->num_rows() > 0) {
            return false; // Username sudah ada, tidak bisa insert
        }

        // Jika belum ada, tambahkan user
        $this->db->insert('users', $user_data);
        return $this->db->affected_rows() > 0;
    }


    function delete_users($id_user)
    {
        $this->db->where('id_user', $id_user);
        $this->db->delete('users');
        return $this->db->affected_rows() > 0;
    }


    function edit_users($user_data, $id_user)
    {

        $this->db->where('id_user', $id_user);
        $this->db->update('users', $user_data);
        return $this->db->affected_rows() > 0;
    }
}