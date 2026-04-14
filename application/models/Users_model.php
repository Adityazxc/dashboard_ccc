<?php

class Users_model extends CI_Model
{
    var $customer_column_order = array(null, 'id_user', 'username', 'name', 'location', 'role', "no_hp", null); //set column field database for datatable orderable
    var $customer_column_search = array('id_user', 'username', 'name', 'location', 'role', "no_hp", ); //set column field database for datatable searchable
    var $customer_order = array('id_user' => 'DESC');

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->db_checker = $this->load->database('checker_pod', TRUE);
    }
    public function _get_user_()
    {
        $this->db_checker->select('id_user,name,username');
        $this->db_checker->from('users');
        $this->db_checker->where('role', 'CCC');        

        return $this->db_checker->get()->result_array();
    }

    public function _get_origins()
    {
        $this->db_checker->distinct();
        $this->db_checker->select('*');        
        $this->db_checker->from('zone');
        $query = $this->db_checker->get();
        $result = $query->result();        
        return $result; 
    }
    private function _getdatatables_users()
    {
        $this->db_checker->select('u.*,z.*');
        $this->db_checker->from('users u');
        $this->db_checker->where('role', 'CCC');  
        $this->db_checker->join('zone z', 'z.zone_code=u.location', 'left');

        $i = 0;

        if (@$_POST['search']['value']) {
            foreach ($this->customer_column_search as $item) {
                if ($i === 0) {
                    $this->db_checker->group_start()
                        ->like($item, $_POST['search']['value']);
                } else {
                    $this->db_checker->or_like($item, $_POST['search']['value']);
                }
                if (count($this->customer_column_search) - 1 == $i) {
                    $this->db_checker->group_end();
                }
                $i++;
            }
        }

        if (isset($_POST['order'])) {
            $this->db_checker->order_by($this->customer_column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } elseif (isset($this->order)) {
            $customer_order = $this->order;
            $this->db_checker->order_by(key($customer_order), $customer_order[key($customer_order)]);
        }
    }  
   
    function getdatatables_users()
    {
        $this->_getdatatables_users();
        if (@$_POST['length'] != -1)
            $this->db_checker->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db_checker->get();
        return $query->result();
    }

    function count_filtered_users()
    {
        $this->_getdatatables_users();
        $query = $this->db_checker->get();
        return $query->num_rows();
    }

    function count_all_users()
    {

        $this->db_checker->select('*');
        $this->db_checker->from('users');
        return $this->db_checker->count_all_results();
    }

    public function reset_password($id, $password)
    {
        $newPassword = md5($password);
        $data = array('password' => $newPassword);
        $this->db_checker->where('id_user', $id);
        $this->db_checker->update('users', $data);
        return $this->db_checker->affected_rows() > 0;
    }
    public function default_password($id_user)
    {
        $newPassword = md5('123456');
        $data = array('pass' => $newPassword);
        $this->db_checker->where('id_user', $id_user);
        $this->db_checker->update('users', $data);
        return $this->db_checker->affected_rows() > 0;
    }

    function add_user_model($user_data)
    {
        // Cek apakah username sudah ada
        $this->db_checker->where('username', $user_data['username']);
        $query = $this->db_checker->get('users');

        if ($query->num_rows() > 0) {
            return false; // Username sudah ada, tidak bisa insert
        }

        // Jika belum ada, tambahkan user
        $this->db_checker->insert('users', $user_data);
        return $this->db_checker->affected_rows() > 0;
    }


    function delete_users($id_user)
    {
        $this->db_checker->where('id_user', $id_user);
        $this->db_checker->delete('users');
        return $this->db_checker->affected_rows() > 0;
    }
    function locked_users($user_data,$id_user)
    {
        $this->db_checker->where('id_user', $id_user);
        $this->db_checker->update('users', $user_data);
        return $this->db_checker->affected_rows() > 0;
    }


    function edit_users($user_data, $id_user)
    {

        $this->db_checker->where('id_user', $id_user);
        $this->db_checker->update('users', $user_data);
        return $this->db_checker->affected_rows() > 0;
    }
}


?>