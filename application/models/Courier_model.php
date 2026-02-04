<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Courier_model extends CI_Model
{
    var $customer_column_order = array(null, null,'id_courier', 'courier_name', 'nik', 'tipe_courier', "location", "area", null,null,"work_zone"); //set column field database for datatable orderable
    var $customer_column_search = array('id_courier', 'courier_name', 'nik', 'tipe_courier', "location", "area","work_zone"); //set column field database for datatable searchable
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
    
        // ❌ query error
        if ($this->db->error()['code'] != 0) {
            return [
                'status' => false,
                'error'  => $this->db->error()['message']
            ];
        }
    
        // ⚠️ tidak ada data berubah
        if ($this->db->affected_rows() == 0) {
            return [
                'status' => false,
                'error'  => 'Tidak ada data yang berubah'
            ];
        }
    
        // ✅ sukses
        return [
            'status' => true
        ];
    }
    
    function search_courier($id_courier)
    {
        $this->db->from('courier');
        $this->db->where('id_courier', $id_courier);
        $query = $this->db->get();
        return $query->row();

    }
    function get_detail_cod($no_runsheet)
    {

        $this->db->select('*');
        $this->db->from('checker_notes');
        $this->db->where('no_runsheet', $no_runsheet);
        $query = $this->db->get();
        return $query->row();

    }

    public function _get_data_courier()
    {
        $this->db->select('id_courier, courier_name');
        $this->db->from('courier');
        $query = $this->db->get();

        $result = $query->result(); // Ambil hasil query dalam bentuk array objek

        // Ubah array objek menjadi array biasa yang berisi nilai 'kategori'
        $data_courier = array_map(function ($item) {
            return [
                'id_courier' => $item->id_courier,
                'courier_name' => $item->courier_name,

            ];  // Pastikan key nya 'kategori'
        }, $result);

        return json_encode($data_courier); // Encode ke JSON string
    }


    public function get_by_id($id_courier)
    {
        return $this->db->get_where('courier', ['id_courier' => $id_courier])->row();
    }
    
    public function get_all()
    {
        return $this->db->get('courier')->result_array();
    }
}