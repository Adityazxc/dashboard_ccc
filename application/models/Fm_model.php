<?php

class Fm_model extends CI_Model
{
    var $fm_column_order = array(
        null,
        'cust_name',
        'pic_bdo',
        'tgl',
        'origin',
        'zone_code',
        'cust_type',
        'service',
        'shipment',
        'cnote_pay_type',
        'zone_delivery',
        'pod_code',
        'total_shipment',
        'total_amount',
        'total_weight',
        'delivered_count',
        'on_proses_count',
        'return_count',
        'cnote_cust_no',
        'return',
        'un_runsheet',
        'open_pod',
        'undelivered',
        'customers_request',
        'un_receiving',
        'un_manifest',
        'auto_close_irreg',
        'auto_close_system',
        'claim',
        'irregularity',
        'weight',
        'first_attemp'
    );
    var $fm_column_search = array(
        'cust_name',
        'cust_type',
        'pic_bdo',
        'origin',
        'zone_code',
        'service',
        'shipment',
        'cnote_cust_no'
    );
    var $fm_order = array('id' => 'DESC');

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->db_checker = $this->load->database('checker_pod', TRUE);
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
        $this->db->from('mv_shipment_fm');

        $this->applyFilterDashboard($post);

        return $this->db->get()->row_array(); // IMPORTANT
    }

    private function _getdatatables_fm()
    {
        $post = $this->input->post();
        $this->db->select('*');

        $this->db->from('mv_shipment_fm mv');
        // $this->db->where('cust_name !=', '-');

        $this->applyFilterDashboard($post);




        $i = 0;

        if (@$_POST['search']['value']) {
            foreach ($this->fm_column_search as $item) {
                if ($i === 0) {
                    $this->db->group_start()
                        ->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if (count($this->fm_column_search) - 1 == $i) {
                    $this->db->group_end();
                }
                $i++;
            }
        }

        if (isset($_POST['order'])) {
            $column_order_index = $_POST['order']['0']['column'];
            if ($this->fm_column_order[$column_order_index] != null) {
                $this->db->order_by($this->fm_column_order[$column_order_index], $_POST['order']['0']['dir']);
            }
        } elseif (isset($this->order)) {
            $fm_order = $this->order;
            $this->db->order_by(key($fm_order), $fm_order[key($fm_order)]);
        }

    }

    function getdatatables_fm()
    {
        $this->_getdatatables_fm();
        if (@$_POST['length'] != -1)
            $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered_fm()
    {
        $this->_getdatatables_fm();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function count_all_fm()
    {
        $post = $this->input->post();
        $this->db->select('*');

        $this->db->from('mv_shipment_fm mv');
        $this->db->where('cust_name !=', '-');
        $this->applyFilterDashboard($post);


        return $this->db->count_all_results();
    }
    var $fm_column_corp_order = array(
        null, // kolom nomor urut        
        'cust_name',
        'id_pic',
        'delivered_count',
        'open_pod',
        'return_count',
        'total_shipment',
    );

    var $fm_column_corp_search = array(
        'cust_name',
        'id_pic',
        'delivered_count',
        'open_pod',
        'return_count',
        'total_shipment',
    );
    var $fm_column_stat_order = array(
        null, // kolom nomor urut        
        'cust_name',
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

    var $fm_column_stat_search = array(
        'cust_name',
        'id_pic',
        'delivered_count',
        'open_pod',
        'return_count',
        'total_shipment',
    );
    private function _corp_stat_shp()
    {
        $post = $this->input->post();
        // $this->db->select('*');
        $this->db->select("
        cnote_cust_no,
        cust_name,
        cust_type,
        id_pic,
        SUM(delivered_count) as delivered_count,
        SUM(kategori_delivered) as kategori_delivered,
        SUM(un_status_pod) as un_status_pod,
        SUM(un_inbound) as un_inbound,
        SUM(on_proses_count) as on_proses_count,
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
        SUM(declare_missing) as declare_missing,
        SUM(destroy) as destroy,
        SUM(internal_problem) as internal_problem,
        SUM(other) as other,
        SUM(warehouse) as warehouse,
        SUM(kategori_return) as kategori_return,
        SUM(total_shipment) as total_shipment
    ");

        $this->db->from('mv_shipment_fm mv');
        $this->db->where('cust_name !=', '-');
        $this->db->group_by('cust_name, cust_type');
        // $this->db->order_by("CASE WHEN cust_name = '-' THEN 1 ELSE 0 END", "ASC");
        $this->applyFilterDashboard($post);

        $i = 0;

        if (@$_POST['search']['value']) {
            foreach ($this->fm_column_stat_search as $item) {
                if ($i === 0) {
                    $this->db->group_start()
                        ->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if (count($this->fm_column_stat_search) - 1 == $i) {
                    $this->db->group_end();
                }
                $i++;
            }
        }

        if (isset($_POST['order'])) {
            $column_order_index = $_POST['order']['0']['column'];
            if ($this->fm_column_stat_order[$column_order_index] != null) {
                $this->db->order_by($this->fm_column_stat_order[$column_order_index], $_POST['order']['0']['dir']);
            }
        } elseif (isset($this->order)) {
            $fm_order = $this->order;
            $this->db->order_by(key($fm_order), $fm_order[key($fm_order)]);
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
        // $this->db->select('*');
        $this->db->select("
        cnote_cust_no,
        cust_name,
        cust_type,
        id_pic,
        SUM(delivered_count) as delivered_count,
        SUM(kategori_delivered) as kategori_delivered,
        SUM(un_status_pod) as un_status_pod,
        SUM(un_inbound) as un_inbound,
        SUM(on_proses_count) as on_proses_count,
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
        SUM(declare_missing) as declare_missing,
        SUM(destroy) as destroy,
        SUM(internal_problem) as internal_problem,
        SUM(other) as other,
        SUM(warehouse) as warehouse,
        SUM(kategori_return) as kategori_return,
        SUM(total_shipment) as total_shipment
    ");

        $this->db->from('mv_shipment_fm mv');
        $this->db->where('cust_name !=', '-');
        $this->db->group_by('cust_name, cust_type');
        // $this->db->order_by("CASE WHEN cust_name = '-' THEN 1 ELSE 0 END", "ASC");
        $this->applyFilterDashboard($post);

        return $this->db->count_all_results();
    }

    public function _get_regional()
    {
        $this->db->select('code,regional');
        $this->db->from('letter_code');
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

    public function refresh_mv_shipment_fm()
    {
        $this->db->query("
        SET SESSION sql_mode = REPLACE(
        REPLACE(@@sql_mode,'NO_ZERO_DATE',''),
        'NO_ZERO_IN_DATE','')
        ");
        $this->db->query("TRUNCATE mv_shipment_fm");

        $sql = "
        INSERT INTO mv_shipment_fm (
    tgl, service, shipment, cnote_pay_type,zone_delivery,
    pic_bdo, pod_code,
    total_shipment, total_amount, total_weight,
    delivered_count, on_proses_count, return_count,
    cnote_cust_no,
    un_inbound, un_runsheet,
    open_pod, undelivered, customers_request,
    un_receiving, un_manifest,
    auto_close_irreg, auto_close_system,
    claim, irregularity,
    cust_name, sm_date,
    id_pic, cust_type,
    origin, zone_code,
    un_status_pod,
    declare_missing,
    destroy,
    internal_problem,
    other,
    kategori_return,
    warehouse,
    kategori_delivered
)

SELECT
    DATE(NULLIF(s.tgl,'0000-00-00')) AS tgl,

    COALESCE(NULLIF(s.service,''), '-') AS service,
    COALESCE(NULLIF(s.shipment,''), '-') AS shipment,
    COALESCE(NULLIF(s.cnote_pay_type,''), '-') AS cnote_pay_type,
    s.sla_delivery,

    COALESCE(s.pic  ,'-') AS pic_bdo,
    COALESCE(s.pod_code,'-') AS pod_code,

    COUNT(DISTINCT s.cnote_no) AS total_shipment,
    SUM(s.cnote_amount) AS total_amount,
    SUM(s.cnote_weight) AS total_weight,

    SUM(ps.filter='Delivered') AS delivered_count,
         SUM(
    CASE 
        WHEN ps.filter = 'On Proses'
          OR s.pod_code IS NULL
          OR TRIM(s.pod_code) = ''
        THEN 1
        ELSE 0
    END
),
    SUM(ps.filter='Return') AS return_count,

    COALESCE(NULLIF(s.cnote_cust_no,''), '-') AS cnote_cust_no,

    SUM(ps.pod_code='UN INBOUND') AS un_inbound,
    SUM(s.runsheet_date IS NULL) AS un_runsheet,

    SUM(ps.pod_kategori='OPEN POD') AS open_pod,
    SUM(ps.pod_kategori='UNDEL') AS undelivered,
    SUM(ps.pod_kategori='CUSTOMER REQUEST') AS customers_request,
    SUM(ps.pod_kategori='UN RECEIVING') AS un_receiving,
    SUM(ps.pod_kategori='un_manifest') AS un_manifest,
    SUM(ps.pod_kategori='AUTO CLOSE IRREG') AS auto_close_irreg,
    SUM(ps.pod_kategori='AUTO CLOSE SYSTEM') AS auto_close_system,
    SUM(ps.pod_kategori='CLAIM BREACH') AS claim,
    SUM(ps.pod_kategori='IRREGULARITY') AS irregularity,

    s.grouping_cust as cust_name,

    DATE(NULLIF(s.sm_date,'0000-00-00')) AS sm_date,

    s.pic AS pic,
    s.cust_type AS cust_type,
    s.province_name AS origin,
    s.city_name AS zone_code,
    SUM(CASE 
    WHEN
    trim(s.pod_code)=''
    OR s.pod_code IS NULL
    THEN 1
    ELSE 0
    END
    ),
    SUM(ps.pod_kategori='DECLARE MISSING') AS declare_missing,
    SUM(ps.pod_kategori='DESTROY') AS destroy,
    SUM(ps.pod_kategori='INTERNAL PROBLEM') AS internal_problem,
    SUM(ps.pod_kategori='OTHER') AS other,
    SUM(ps.pod_kategori='RETURN') AS kategori_return,
    SUM(ps.pod_kategori='WAREHOUSE') AS warehouse,
    SUM(ps.pod_kategori='DELIVERED') AS kategori_delivered
    

FROM shipment_fm s

LEFT JOIN pod_status ps 
    ON s.pod_code = ps.pod_code



   

WHERE 
    s.tgl IS NOT NULL
    AND s.tgl != '0000-00-00'

GROUP BY
    DATE(NULLIF(s.tgl,'0000-00-00')),
    s.service,
    s.cust_type,
    s.shipment,
    s.cnote_pay_type,
    s.sla_delivery,
    s.pic,
    s.pod_code,
    s.province_name,
    s.city_name,
    s.cnote_cust_no,
    s.grouping_cust,
    DATE(NULLIF(s.sm_date,'0000-00-00')),
    s.cnote_origin,
    s.cnote_destination;
        ";

        return $this->db->query($sql);
    }
    public function _get_cust_mp()
    {

        $this->db->select("
            CONCAT(
                COALESCE(NULLIF(cust_id, ''), 'null'),
                '/',
                cust_name
            ) AS cust_key,
            source
        ", FALSE);

        $this->db->from('cus_fm');

        $this->db->where_in('segmentasi', ['MARKETPLACE', 'RETAIL']);

        return $this->db->get()->result();
    }
    public function get_cust_mp()
    {
        $result = $this->_get_cust_mp();
        foreach ($result as $row) {
            echo "Customer: " . $row->cust_key . " - " . $row->source . "<br>";
        }

        // print_r($result);
    }


    public function getSourceService($post)
    {
        $this->db->select('service, COUNT(*) as total');
        $this->db->from('mv_shipment_fm');


        $this->db->group_by('service');
        $this->applyFilterDashboard($post);
        $query = $this->db->get();
        $result = $query->result();

        return $result;

    }
    public function getSourcePayTypeShipment($post)
    {
        $this->db->select('cnote_pay_type, COUNT(*) as total');
        $this->db->from('mv_shipment_fm');



        $this->applyFilterDashboard($post);
        $this->db->group_by('cnote_pay_type');

        $query = $this->db->get();
        $result = $query->result();

        return $result;

    }
    public function _get_origins()
    {
        $this->db->select('tariff_code, province_name, district_name,region_in_jne');
        $this->db->group_by('region_in_jne');
        $this->db->from('dest');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function _get_zone($origin)
    {
        $this->db->select('tariff_code,district_name,province_name,city_name');
        $this->db->from('dest');
        $this->db->group_by('city_name');
        $this->db->where('region_in_jne', $origin);

        $query = $this->db->get();
        return $query->result();
    }

    public function getTopCustomers($post)
    {

        $this->db->select('cl.segmentasi, SUM(m.total_shipment) as total_shipment');
        $this->db->from('cus_fm cl');
        $this->db->join('mv_shipment_fm m', 'cl.cust_id = m.cnote_cust_no', 'left');
        $this->db->where('m.cust_type !=', '-');
        $this->db->group_by('cl.segmentasi');
        ;


        $this->applyFilterDashboard($post);

        $query = $this->db->get();
        return $query->result();
    }


    public function applyFilterDashboard($post)
    {
        // if (!empty($post['dateFrom']) && !empty($post['dateThru'])) {
        //     $this->db->where('tgl >=', $post['dateFrom']);
        //     $this->db->where('tgl <=', $post['dateThru']);
        // }

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

        if (!empty($post['customer_fm'])) {
            $this->db->where('cust_name', trim($post['customer_fm']));
        }

        if (!empty($post['type_cust']) && is_array($post['type_cust'])) {
            $this->db->where_in('cust_type', $post['type_cust']);
        }
        if (!empty($post['cnote_cust_no']) && is_array($post['cnote_cust_no'])) {
            $this->db->where_in('cnote_cust_no', $post['cnote_cust_no']);
        }
    }



    public function _get_cnote_cust_no()
    {
        $this->db->select('cust_id,source');
        $this->db->from('cus_fm');
        $this->db->group_by('cust_id');
        $query = $this->db->get();
        return $query->result_array();

    }
    public function getDataNotApprove($post)
    {
        $this->db->select("
            s.*,
    
            
     
    
       
    
    
            DATEDIFF(s.pod_date, s.cnote_date) as total_days,
            (DATEDIFF(s.pod_date, s.cnote_date) - s.sla) as carrier,
        
        ");

        $this->db->from('shipment_fm s');

        // JOIN tetap, tapi pastikan kolom join ada index
    

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
            $this->db->where('s.province_name', $post['origin']);
        }

        if (!empty($post['zone'])) {
            $this->db->where('s.city_name', $post['zone']);
        }

        if (!empty($post['pic'])) {
            $this->db->where('s.pic', $post['pic']);
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

        // JOIN FILTER (pastikan ada index!)
        if (!empty($post['zone_delivery'])) {
            $this->db->where('s.sla_delivery', $post['zone_delivery']);
        }

        if (!empty($post['status_pod']) && is_array($post['status_pod']) && count($post['status_pod']) <= 10) {
            $this->db->where_in('s.pod_code', $post['status_pod']);
        }

        if (!empty($post['customer_fm'])) {
            $this->db->where('s.grouping_cust', $post['customer_fm']);
        }



        if (!empty($post['type_cust']) && is_array($post['type_cust'])) {
            $this->db->where_in('s.cust_type', $post['type_cust']);
        }
        if (!empty($post['customer_lm'])) {
            $this->db->where('s.cnote_cust_no', $post['customer_lm']);
        }
    }

    public function _get_type_cust()
    {
        $this->db->distinct();
        $this->db->select('segmentasi');
        $this->db->from('cus_fm');
        $query = $this->db->get();
        return $query->result_array();

    }
}
?>