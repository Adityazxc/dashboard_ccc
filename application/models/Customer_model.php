<?php

class Customer_model extends CI_Model
{
    public function getVoucherData($dateFrom = null, $dateThru = null)
    {

        $this->db->order_by('date', 'DESC');
        if ($dateFrom) {
            $this->db->where('date >=', $dateFrom);
        }
        if ($dateThru) {
            $this->db->where('date <=', $dateThru);
        }
        $query = $this->db->get('customers');
        return $query->result();
    }

    public function getImportData()
    {
        $this->db->order_by('date', 'DESC');
        $today = date('Y-m-d');
        $this->db->where('DATE(date)', $today);
        $query = $this->db->get('customers');
        return $query->result();
    }

    public function tambah($data)
    {      
        try {
            $this->db->insert_batch('destinations', $data);
            return TRUE;
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return FALSE;
        }
    }

    public function searchCustomer($keyword)
    {

        $this->db->where('voucher', $keyword);
        $query = $this->db->get('customers');

        if ($query->num_rows() > 0) {
            $result = $query->result();
        } else {
            $result = false;
        }

        return $result;
    }
    public function update_password($id_user, $new_password)
    {
        $data = array(
            'pass' => md5($new_password)
        );

        $this->db->where('id_user', $id_user);
        $this->db->update('users', $data);
        

        return ($this->db->affected_rows() > 0);
    }
    public function getCustomerById($id)
    {
        $this->db->select('*');
        $this->db->from('customers');
        $this->db->where('id', $id);
        $query = $this->db->get();

        return $query->row(); // Mengembalikan satu baris hasil sebagai objek
    }
    public function reedem_voucher()
    {
        $id_user = $this->session->userdata('id_user');
        // Menggunakan data dari input dan id_user untuk melakukan pembaruan
        // $otp = $this->generate_otp($this->input->post('id'));
        $data = array(
            'awbno_claim' => $this->input->post('resi'),
            'id_user' => $id_user,
            'status' => 'Y'
        );

        $this->db->where('id', $this->input->post('id'));
        $this->db->update('customers', $data);
        // Periksa apakah pembaruan berhasil
        return $this->db->affected_rows() > 0;
        // return true;
    }


    public function generate_otp($id)
    {
        $otp_length = 6;
        $otp = "";

        for ($i = 0; $i < $otp_length; $i++) {
            $otp .= rand(0, 9);
        }

        $data = array('otp' => $otp);
        $this->db->where('id', $id);
        $this->db->update('customers', $data);

        return $otp;
    }

    public function getUsedVoucher()
    {
        $this->db->where('status', 'Y');
        $query = $this->db->get('customers');
        return $query->result();
    }
    public function getNotUsedVoucher()
    {
        $this->db->where('status', 'N');
        $query = $this->db->get('customers');
        return $query->result();
    }

    public function getUsedVoucherCount()
    {
        // Assuming your 'customers' table has a column named 'status'
        $this->db->where('status', 'Y');
        return $this->db->count_all_results('customers');
    }
    public function getSendVoucherCount()
    {
        return $this->db->count_all_results('customers');
    }
    public function getNotUsedVoucherCount()
    {
        $this->db->where('status', 'N');
        return $this->db->count_all_results('customers');
    }

    public function isAwbNoExists($awb_no)
    {
        // Mencari data dengan nomor AWB yang sama  
        $this->db->where('awb_no', $awb_no);
        $query = $this->db->get('destinations'); // Ganti 'your_table_name' dengan nama tabel yang sesuai  

        // Mengembalikan true jika ada data yang ditemukan, false jika tidak ada  
        return $query->num_rows() > 0;
    }

}
?>