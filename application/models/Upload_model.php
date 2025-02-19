<?php

class Upload_model extends CI_Model
{

    var $customer_column_order = array(null, 'id', 'destination_code', 'awb_no', null); //set column field database for datatable orderable
    var $customer_column_search = array('destination_code', 'awb_no');
    var $customer_order = array('id' => 'DESC'); // default order



    private function _getdatatables_upload_data()
    {
        $this->db->select('*');
        $this->db->from('destinations');
        $dateFrom = $this->input->post('dateFrom', TRUE);
        $dateThru = $this->input->post('dateThru', TRUE);
        $this->db->where('DATE(create_date) >=', $dateFrom)
            ->where('DATE(create_date) <=', $dateThru);


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
    function save_sortir_number($sortirNumber, $qty, $id_user, $createDateSortir)
    {
        // Data untuk tabel history
        $history = array(
            'start_date' => $createDateSortir,
            'qty' => $qty,
            'id_user' => $id_user,
            'end_date' => date('Y-m-d H:i:s'),
        );

        // Data untuk tabel destinations
        $destinations = array(
            'date_approve' => date('Y-m-d H:i:s'),
        );

        // // Update tabel history

        $this->db->where('sortir_number', $sortirNumber); // atau sesuaikan dengan kondisi yang sesuai untuk tabel destinations
        $this->db->update('history', $history);

        // Update tabel destinations, tambahkan kondisi yang sesuai
        $this->db->where('sortir_number', $sortirNumber); // atau sesuaikan dengan kondisi yang sesuai untuk tabel destinations
        $this->db->update('destinations', $destinations);

        // Mengembalikan nilai true jika update berhasil
        return $this->db->affected_rows() > 0;
        // return json_decode(var_dump($destinations,$history));
    }

    function getdatatables_upload_data()
    {
        $this->_getdatatables_upload_data();
        if (@$_POST['length'] != -1)
            $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered_upload_data()
    {
        $this->_getdatatables_upload_data();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_all_upload_data()
    {
        $this->db->select('*');
        $this->db->where('DATE(create_date) >=', $this->security->xss_clean($this->input->post('dateFrom')));
        $this->db->where('DATE(create_date) <=', $this->security->xss_clean($this->input->post('dateThru')));
        $this->db->from('destinations');
        return $this->db->count_all_results();
    }



    public function scan_form($id_user, $sortirNumber, $createDate, $awb)
    {
        $date = date('Y-m-d H:i:s');

        // Ambil nilai qty dari tabel history
        $this->db->select('qty');
        $this->db->where('sortir_number', $sortirNumber);
        $this->db->where('id_user', $id_user);
        $qtyRow = $this->db->get('history')->row();

        // Tetapkan nilai awal qty jika NULL
        $qty = $qtyRow ? (int) $qtyRow->qty : 0;

        // Update tabel destinations
        $data = array(
            'approve_by' => $id_user,
            'sortir_number' => $sortirNumber,
            'start_date' => $createDate,
            'approve' => 'Y',
            'scan_date' => $date
        );
        $this->db->where('awb_no', $awb);
        $this->db->update('destinations', $data);

        // Jika update berhasil, tambahkan qty
        if ($this->db->affected_rows() > 0) {
            $qty++; // Tambah 1 ke qty
            $this->db->where('sortir_number', $sortirNumber);
            $this->db->where('id_user', $id_user);
            $this->db->update('history', ['qty' => $qty]);

            return [
                'status' => 'success',
                'new_qty' => $qty // Kirim qty terbaru
            ];
        }

        return [
            'status' => 'error',
            'message' => 'Gagal menambahkan data.'
        ];
    }



    function summary_customer()
    {
        $this->db->select('SUM(CASE WHEN status = "status1" THEN harga ELSE 0 END) as sum_status1');
        // $this->db->where('type', 'customer');
        $query = $this->db->get('destinations');
        return $query->row();
    }



}

?>