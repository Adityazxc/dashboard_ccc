<?php

class Upload_model extends CI_Model
{

    var $customer_column_order = array(null, 'id', 'destination_code', 'awb_no', null); //set column field database for datatable orderable
    var $customer_column_search = array('destination_code', 'awb_no');
    var $customer_order = array('id' => 'DESC'); // default order




    function delete_validasi($id_courier, $create_date)
    {
        $create_date = date('Y-m-d', strtotime($create_date));

        // Ambil data terlebih dahulu
        $this->db->where('id_courier', $id_courier);
        $this->db->where('DATE(runsheet_date)', $create_date);        
        $result = $this->db->get('checker')->result();

        foreach ($result as $row) {
            $paths = [$row->url_pod, $row->url_photo];
            foreach ($paths as $path) {
                // Hanya hapus file di dalam folder uploads
                if (!empty($path) && strpos($path, 'uploads/') === 0) {
                    $full_path = FCPATH . $path;
                    if (file_exists($full_path)) {
                        @unlink($full_path); // Gunakan @ untuk suppress warning jika gagal
                    }
                }
            }
        }

        // Hapus dari database
        $this->db->where('id_courier', $id_courier);
        $this->db->where('DATE(runsheet_date)', $create_date);
        $this->db->delete('checker');

        return $this->db->affected_rows() > 0;
    }

    public function get_image_paths($id_courier, $runsheet_date)
    {
        $date_only = date('Y-m-d', strtotime($runsheet_date));

        $results = $this->db->select('id_checker, url_photo, url_pod, url_revision')
            ->where('id_courier', $id_courier)
            ->where('DATE(runsheet_date)', $date_only)
            ->get('checker')
            ->result();

        // Normalisasi path untuk response
        foreach ($results as &$result) {
            $result->url_photo = $this->normalizePath($result->url_photo);
            $result->url_pod = $this->normalizePath($result->url_pod);
            $result->url_revision = $this->normalizePath($result->url_revision);
        }

        return $results;
    }

    private function normalizePath($path)
{
    if ($path === null) {
        return null;
    }
    // Konversi semua backslash ke forward slash
    return str_replace('\\', '/', $path);
}

    public function get_url_path()
    {

    }



}

?>