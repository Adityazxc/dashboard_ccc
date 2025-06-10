<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Materialize extends CI_Controller {
    public function refresh_checker_summary()
    {
        // 1. Kosongkan data lama
        $this->db->truncate('mv_checker_summary');

        // 2. Insert ulang dari query sumber
        $sql = "
        INSERT INTO mv_checker_summary
        SELECT
            ch.id_courier,
            ch.id_checker,
            ch.runsheet_date,
            ch.create_date,
            ch.upload_by,
            ch.zone,
            ch.status_checker,
            COUNT(ch.id_courier) as qty_awb,
            SUM(CASE WHEN ch.status_checker = 'Sesuai' THEN 1 ELSE 0 END) as qty_sesuai,
            SUM(CASE WHEN ch.status_checker = 'Revisi' THEN 1 ELSE 0 END) as qty_revisi,
            SUM(CASE WHEN ch.status_checker = 'Tidak Sesuai' THEN 1 ELSE 0 END) as qty_tidak_sesuai,
            (SELECT courier_name FROM courier WHERE courier.id_courier = ch.id_courier) as courier_name,
            (SELECT zone FROM zone WHERE zone.zone_code = ch.zone) as zone_name,
            (SELECT origin_code FROM zone WHERE zone.zone_code = ch.zone) as origin_code,
            (SELECT role FROM users WHERE users.id_user = ch.upload_by) as role,
            (SELECT name FROM users WHERE users.id_user = ch.upload_by) as name,
            (SELECT username FROM users WHERE users.id_user = ch.upload_by) as username
        FROM
            checker ch
        GROUP BY
            ch.id_courier,
            DATE(ch.runsheet_date)";
        
        $this->db->query($sql);

        echo "Materialized view refreshed.";
    }
}
