<?php
class Hrs_model extends CI_Model
{


    var $hrs_column_order = array(null, "no_runsheet", ); //set column field database for datatable orderable
    var $hrs_column_search = array("no_runsheet"); //set column field database for datatable orderable


    private function _get_hrs()
    {
        //     $this->db->select("
        //     no_runsheet,
        //     id_courier,
        //     MIN(runsheet_date) AS runsheet_date,
        //     MAX(status_pod) AS status_pod,
        //     MAX(status_hrs) AS status_hrs,         
        //    sum(if(status_cod like 'D%',amount,0 )) as total_delivered,
        //    sum(if(status_cod like 'U%',amount,0 )) as total_undelivered,
        //    sum(amount) as amount,
        //        CASE
        //     WHEN MAX(status_paid) = 1
        //     THEN 1
        //     ELSE 0
        // END AS status_paid
        // ", false);
        $id_courier = $this->input->post("courier_id");
        $date_from = $this->input->post('date_from');
        $date_thru = $this->input->post('date_thru');
        $this->db->select("
    c.no_runsheet,
    SUM(IF(c.status_cod LIKE 'D%', c.amount, 0)) AS total_delivered,
    IFNULL(paid.total_paid, 0) AS already_paid,
    (SUM(IF(c.status_cod LIKE 'D%', c.amount, 0)) - IFNULL(paid.total_paid, 0)) AS remaining,    
    status_pod,
            status_hrs,
            id_courier,
    CASE
        WHEN IFNULL(paid.total_paid,0) = 0 THEN 'UNPAID'
        WHEN IFNULL(paid.total_paid,0) < SUM(IF(c.status_cod LIKE 'D%', c.amount, 0)) THEN 'PARTIAL'
        ELSE 'PAID'
    END AS payment_status
");

        $this->db->from('checker c');

        $this->db->join("
    (
        SELECT 
            no_runsheet,
            
            SUM(cod_paid + transfer) AS total_paid
            
        FROM runsheet_payment
        GROUP BY no_runsheet
    ) paid
", 'paid.no_runsheet = c.no_runsheet', 'left');

        $this->db->group_by('c.no_runsheet');
        $this->db->where('c.id_courier', $id_courier);
        $this->db->where('DATE(c.runsheet_date) >=', $date_from);
        $this->db->where('DATE(c.runsheet_date) <=', $date_thru);





        if (@$_POST['search']['value']) {
            foreach ($this->hrs_column_search as $item) {
                if ($i === 0) {
                    $this->db->group_start()
                        ->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if (count($this->hrs_column_search) - 1 == $i) {
                    $this->db->group_end();
                }
                $i++;
            }
        }

        if (isset($_POST['order'])) {
            $this->db->order_by($this->hrs_column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } elseif (isset($this->order)) {
            $checker_order = $this->order;
            $this->db->order_by(key($checker_order), $checker_order[key($checker_order)]);
        }
    }

    function get_hrs()
    {
        $this->_get_hrs();
        if (@$_POST['length'] != -1)
            $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered_hrs()
    {
        $this->_get_hrs();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_all_hrs()
    {
        // $id_courier = $this->input->post("courier_id");
        $this->db->select("*");
        $this->db->from("checker");
        // $this->db->where("id_courier", $id_courier);
        return $this->db->count_all_results();
    }
    function get_detail_hrs($hrs)
    {
        $this->db->select("*");
        $this->db->from("hrs");
        $this->db->where("dri", $hrs);
        $query = $this->db->get();

        return $query->result();
    }



    public function delete_hrs($id_hrs)
    {
        $this->db->where('id_hrs', $id_hrs);
        $this->db->delete('hrs'); // ganti nama tabel sesuai DB

        return $this->db->affected_rows() > 0;
    }
}




?>