<?php

class Lm_model extends CI_Model
{
    var $lm_column_order = array(null, 'account_number', 'cust_name', 'total_shipment', 'total_shipment', 'total_amount', 'delivered_count', 'total_amount', 'delivered_percent'); //set column field database for datatable orderable
    var $lm_column_search = array('account_number', 'cust_name', 'total_shipment', 'total_shipment', 'total_amount', 'delivered_count', 'total_amount', 'delivered_percent');
    var $lm_order = array('id' => 'DESC');

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->db_checker = $this->load->database('checker_pod', TRUE);
        $this->db->query("SET SESSION sql_mode = (SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''))");
    }

    private function _getdatatables_lm()
    {
        $post = $this->input->post();
        $this->db->select('*');

        $this->db->from('mv_shipment_lm mv');
        $this->applyFilterDashboard($post);





        $i = 0;

        if (@$_POST['search']['value']) {
            foreach ($this->lm_column_search as $item) {
                if ($i === 0) {
                    $this->db->group_start()
                        ->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if (count($this->lm_column_search) - 1 == $i) {
                    $this->db->group_end();
                }
                $i++;
            }
        }

        if (isset($_POST['order'])) {
            $column_order_index = $_POST['order']['0']['column'];
            if ($this->lm_column_order[$column_order_index] != null) {
                $this->db->order_by($this->lm_column_order[$column_order_index], $_POST['order']['0']['dir']);
            }
        } elseif (isset($this->order)) {
            $lm_order = $this->order;
            $this->db->order_by(key($lm_order), $lm_order[key($lm_order)]);
        }

    }

    function getdatatables_lm()
    {
        $this->_getdatatables_lm();
        if (@$_POST['length'] != -1)
            $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered_lm()
    {
        $this->_getdatatables_lm();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_all_lm()
    {

        $post = $this->input->post();
        $this->db->select('*');

        $this->db->from('mv_shipment_lm');
        $this->applyFilterDashboard($post);


        return $this->db->count_all_results();
    }
    var $lm_column_corp_order = array(
        null, // nomor urut
        'customer_name',
        'cust_type',
        'delivered_count',
        'un_inbound',
        'un_runsheet',
        'open_pod',
        'undelivered',
        'customers_request',
        'irregularity',
        'return_count',
        'un_receiving',
        'un_manifest',
        'auto_close_irreg',
        'auto_close_system',
        'claim',
        'total_shipment',
    );

    var $lm_column_corp_search = array(
        'customer_name',
        'pic_bdo',
        'first_attemp',
        'on_time_sla',
        'over_sla',
        'sla',
        'total_amount',
    );
    private function _corp_stat_shp()
    {

        $post = $this->input->post();

        $this->db->select("
        cnote_cust_no,
        customer_name,
        cust_type,
        pic_bdo,
        SUM(delivered_count) as delivered_count,
        SUM(un_inbound) as un_inbound,
        SUM(un_runsheet) as un_runsheet,
        SUM(open_pod) as open_pod,
        SUM(undelivered) as undelivered,
        SUM(customers_request) as customers_request,
        SUM(irregularity) as irregularity,
        SUM(return_count) as return_count,
        SUM(un_receiving) as un_receiving,
        SUM(un_manifest) as un_manifest,
        SUM(auto_close_irreg) as auto_close_irreg,
        SUM(auto_close_system) as auto_close_system,
        SUM(claim) as claim,
        SUM(total_shipment) as total_shipment
    ");

        $this->db->from('mv_shipment_lm');

        $this->db->group_by('customer_name, cust_type');

        $this->applyFilterDashboard($post);

        // ini bikin cust_name "-" selalu di bawah
        $this->db->order_by("CASE WHEN customer_name = '-' THEN 1 ELSE 0 END", "ASC");
        $i = 0;

        if (@$_POST['search']['value']) {
            foreach ($this->lm_column_corp_search as $item) {
                if ($i === 0) {
                    $this->db->group_start()
                        ->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if (count($this->lm_column_corp_search) - 1 == $i) {
                    $this->db->group_end();
                }
                $i++;
            }
        }

        if (isset($_POST['order'])) {
            $column_order_index = $_POST['order']['0']['column'];
            if ($this->lm_column_corp_order[$column_order_index] != null) {
                $this->db->order_by($this->lm_column_corp_order[$column_order_index], $_POST['order']['0']['dir']);
            }
        } elseif (isset($this->order)) {
            $lm_order = $this->order;
            $this->db->order_by(key($lm_order), $lm_order[key($lm_order)]);
        }

    }

    function corp_stat_shp()
    {
        $this->_corp_stat_shp();
        if (@$_POST['length'] != -1)
            $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_all_corp_stat_shp()
    {
        $this->_corp_stat_shp();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_filtered_corp_stat_shp()
    {

        $post = $this->input->post();

        $this->db->select("
        cnote_cust_no,
        customer_name,
        cust_type,
        pic_bdo,
        SUM(delivered_count) as delivered_count,
        SUM(un_inbound) as un_inbound,
        SUM(un_runsheet) as un_runsheet,
        SUM(open_pod) as open_pod,
        SUM(undelivered) as undelivered,
        SUM(customers_request) as customers_request,
        SUM(irregularity) as irregularity,
        SUM(return_count) as return_count,
        SUM(un_receiving) as un_receiving,
        SUM(un_manifest) as un_manifest,
        SUM(auto_close_irreg) as auto_close_irreg,
        SUM(auto_close_system) as auto_close_system,
        SUM(claim) as claim,
        SUM(total_shipment) as total_shipment
    ");

        $this->db->from('mv_shipment_lm');

        $this->db->group_by('customer_name, cust_type');

        $this->applyFilterDashboard($post);

        // ini bikin cust_name "-" selalu di bawah
        $this->db->order_by("CASE WHEN customer_name = '-' THEN 1 ELSE 0 END", "ASC");


        return $this->db->count_all_results();
    }

    public function _get_regional()
    {
        $this->db->select('code,regional');
        $this->db->from('letter_code');
        $query = $this->db->get();
        return $query->result_array();

    }

    public function _get_type_cust()
    {
        $this->db->distinct();
        $this->db->select('cust_industry');
        $this->db->from('cus_lm');
        $query = $this->db->get();
        return $query->result_array();

    }
    public function _get_service()
    {
        $this->db->select('service_code,service_name');
        $this->db->from('service');
        $query = $this->db->get();
        return $query->result_array();

    }
    public function _get_status_pod()
    {
        $this->db->select('pod_code,pod_status');
        $this->db->from('pod_status');
        $query = $this->db->get();
        return $query->result_array();

    }



    public function refresh_mv_shipment_lm()
    {

        $this->db->query("
        SET SESSION sql_mode = REPLACE(
        REPLACE(@@sql_mode,'NO_ZERO_DATE',''),
        'NO_ZERO_IN_DATE','')
        ");
        $this->db->query("TRUNCATE mv_shipment_lm");

        $sql = "
                
        INSERT INTO mv_shipment_lm (
            tgl,
            origin,
            zone_code,
            service,
            shipment,
            cnote_pay_type,
            zone_delivery,
            pic_bdo,
            pod_code,

            total_shipment,
            total_amount,
            total_weight,
            delivered_count,
            on_proses_count,
            return_count,

            cnote_cust_no,
            un_inbound,
            un_runsheet,
                open_pod,
            undelivered,
            customers_request,
            irregularity,
            un_receiving,
            un_manifest,
            auto_close_irreg,
            auto_close_system,
            claim,
            customer_name,
            weight,
            first_attemp,
            on_time_sla,
            over_sla,
            sla,
            id_pic,
            id_cust,
            cust_type
        )

       
        SELECT 
            DATE(s.tgl),
            s.origin,
            s.zone_code,
            s.service,
            s.shipment,
            s.cnote_pay_type,
            s.zona_delivery,
            s.pic,             
            s.pod_code,

            COUNT(*),
            SUM(s.cnote_amount),
            SUM(s.cnote_weight),

            SUM(CASE WHEN ps.Filter='Delivered' THEN 1 ELSE 0 END),
            SUM(CASE WHEN ps.filter='On Proses' THEN 1 ELSE 0 END),
            SUM(CASE WHEN ps.filter='Return' THEN 1 ELSE 0 END),

            MAX(s.cnote_cust_no),  -- ⬅️ FIX
            SUM(CASE WHEN ps.pod_kategori='UN INBOUND' THEN 1 ELSE 0 END),
                                             
            SUM(CASE WHEN s.runsheet_date IS NULL THEN 1 ELSE 0 END),
            SUM(CASE WHEN ps.pod_kategori='OPEN POD' THEN 1 ELSE 0 END),
            SUM(CASE WHEN ps.pod_kategori='UNDEL' THEN 1 ELSE 0 END),
            SUM(CASE WHEN ps.pod_kategori='CUSTOMER REQUEST' THEN 1 ELSE 0 END),
            SUM(CASE WHEN ps.pod_kategori='IRREGULARITY' THEN 1 ELSE 0 END),
            SUM(CASE WHEN ps.pod_kategori='UN RECEIVING' THEN 1 ELSE 0 END),
            SUM(CASE 
            WHEN s.runsheet_date IS NULL 
            AND s.lm_date IS NULL 
            AND s.sla_due_date IS NULL 
            THEN 1 ELSE 0 
            END),
            SUM(CASE WHEN ps.pod_kategori='AUTO CLOSE IRREG' THEN 1 ELSE 0 END),
            SUM(CASE WHEN ps.pod_kategori='AUTO CLOSE SYSTEM' THEN 1 ELSE 0 END),
            SUM(CASE WHEN ps.pod_kategori='CLAIM BREACH' THEN 1 ELSE 0 END),


            MAX(s.big_grouping_cust),
            SUM(s.cnote_weight),   -- weight
            COUNT(CASE WHEN s.sm_date IS NOT NULL THEN 1 END),

            SUM(CASE WHEN DATEDIFF(s.pod_date, s.cnot_date) <= s.sla THEN 1 ELSE 0 END),
            SUM(CASE WHEN DATEDIFF(s.pod_date, s.cnot_date) > s.sla THEN 1 ELSE 0 END),

            MAX(DATEDIFF(s.pod_date, s.cnot_date)),
            s.id_pic,            -- id_pic
            s.cnote_cust_no,          -- id_cust
            s.cust_industry

        FROM shipment_lm s        
     
        LEFT JOIN pod_status ps ON s.pod_code = ps.pod_code
        
        

        WHERE s.tgl IS NOT NULL
        AND s.tgl != '0000-00-00'

        GROUP BY 
            DATE(s.tgl),
            s.origin,
            s.zone_code,
            s.service,
            s.shipment,
            s.cnote_pay_type,
            s.zona_delivery,
            s.pod_code,            
            s.id_pic,
            s.cnote_cust_no,
            s.cust_name,
            s.cust_industry;
        ";


        return $this->db->query($sql);
    }
    public function getSourceService($post)
    {
        $this->db->select('service, COUNT(*) as total');
        $this->db->from('mv_shipment_lm');


        $this->db->group_by('service');
        $this->applyFilterDashboard($post);
        $query = $this->db->get();
        $result = $query->result();

        return $result;

    }
    public function getSourcePayTypeShipment($post)
    {
        $this->db->select('cnote_pay_type, COUNT(*) as total');
        $this->db->from('mv_shipment_lm');



        $this->applyFilterDashboard($post);
        $this->db->group_by('cnote_pay_type');

        $query = $this->db->get();
        $result = $query->result();

        return $result;

    }
    public function getDeliveryComparisonChart($post)
    {
        $this->db->select('
                       SUM(delivered_count) as delivered_total,
                       SUM(on_proses_count) as on_proses_total');
        $this->db->from('mv_shipment_lm');




        $this->applyFilterDashboard($post);
        $query = $this->db->get();
        $result = $query->result();

        return $result;

    }

    public function getTopCustomers($post)
    {
        $this->db->select('cl.cust_industry, SUM(m.total_shipment) as total_shipment');
        $this->db->from('cus_lm cl');
        $this->db->join('mv_shipment_lm m', 'cl.account_number = m.id_cust', 'left');
        $this->db->group_by('cl.cust_industry');
        $this->applyFilterDashboard($post);

        $query = $this->db->get();
        return $query->result();
    }
    private function applyFilterDashboard($post)
    {
        if (!empty($post['dateFrom']) && !empty($post['dateThru'])) {
            $this->db->where('tgl >=', $post['dateFrom']);
            $this->db->where('tgl <=', $post['dateThru']);
        }

        if (!empty($post['origin'])) {
            $this->db->where('origin', $post['origin']);
        }

        if (!empty($post['zone'])) {
            $this->db->where('zone_code', $post['zone']);
        }

        if (!empty($post['pic'])) {
            $this->db->where('id_pic', $post['pic']);
        }

        if (!empty($post['category_shipment'])) {
            $this->db->where('shipment', $post['category_shipment']);
        }

        if (!empty($post['service'])) {
            $this->db->where('service', $post['service']);
        }

        if (!empty($post['cod_flag'])) {
            $this->db->where('cnote_pay_type', $post['cod_flag']);
        }

        if (!empty($post['zone_delivery'])) {
            $this->db->where('zone_delivery', $post['zone_delivery']);
        }

        if (!empty($post['status_pod']) && is_array($post['status_pod'])) {
            $this->db->where_in('pod_code', $post['status_pod']);
        }

        if (!empty($post['customer_lm'])) {
            $this->db->where('customer_name', $post['customer_lm']);
        }

        if (!empty($post['type_cust']) && is_array($post['type_cust'])) {
            $this->db->where_in('cust_type', $post['type_cust']);
        }
    }

    public function summary_dashboard($post)
    {
        $this->db->select('
        SUM(total_shipment) as total_shipment,
        SUM(delivered_count) as delivered_count,
        SUM(on_proses_count) as on_proses_count,
        SUM(return_count) as return_count,
        SUM(total_weight) as total_weight,
        SUM(total_amount) as total_amount
    ');
        $this->db->from('mv_shipment_lm');

        $this->applyFilterDashboard($post);

        return $this->db->get()->row_array(); // IMPORTANT
    }
    public function getDataNotApprove($post)
    {
        $this->db->select("
            s.*,
    
            DATEDIFF(s.pod_date, s.cnot_date) as total_days,
            (DATEDIFF(s.pod_date, s.cnot_date) - s.sla) as carrier,            
        ");

        $this->db->from('shipment_lm s');


        $this->applyFilterDownload($post);

        return $this->db->get()->result_array();
    }

    private function applyFilterDownload($post)
    {
        // // WAJIB ADA FILTER TANGGAL
        if (!empty($post['dateFrom']) && !empty($post['dateThru'])) {
            $this->db->where('s.tgl >=', $post['dateFrom']);
            $this->db->where('s.tgl <=', $post['dateThru']);
        } else {
            // safety biar ga ambil semua data
            $this->db->limit(5000);
        }

        if (!empty($post['origin'])) {
            $this->db->where('s.origin', $post['origin']);
        }

        if (!empty($post['zone'])) {
            $this->db->where('zone_code', $post['zone']);
        }

        if (!empty($post['category_shipment'])) {
            $this->db->where('s.shipment', $post['category_shipment']);
        }

        if (!empty($post['service'])) {
            $this->db->where('s.service', $post['service']);
        }

        if (!empty($post['cod_flag'])) {
            $this->db->where('s.cnote_pay_type', $post['cod_flag']);
        }

        if (!empty($post['status_pod']) && is_array($post['status_pod']) && count($post['status_pod']) <= 10) {
            $this->db->where_in('s.pod_code', $post['status_pod']);
        }

        if (!empty($post['customer_lm'])) {
            $this->db->where('s.big_grouping_cust', $post['customer_lm']);
        }

        // JOIN FILTER (pastikan ada index!)
        if (!empty($post['zone_delivery'])) {
            $this->db->where('s.zona_delivery', $post['zone_delivery']);
        }

        if (!empty($post['pic'])) {
            $this->db->where('s.id_pic', $post['pic']);
        }

        if (!empty($post['type_cust']) && is_array($post['type_cust'])) {
            $this->db->where_in('s.cust_industry', $post['type_cust']);
        }
    }
}

?>