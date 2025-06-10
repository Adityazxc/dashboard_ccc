<?php
defined('BASEPATH') or exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\IOFactory;

class Upload extends CI_Controller
{
    var $customer_column_order = array(null, 'id', 'destination_code', 'awb_no', 'kpi', null); //set column field database for datatable orderable
    var $customer_column_search = array('destination_code', 'awb_no');
    var $customer_order = array('id' => 'DESC');

    public function __construct()
    {
        parent::__construct();
        // $this->load->model('Customer_model');
        // $this->load->model('Upload_model');
        $this->load->model('Checker_model');
        $this->load->library('session');
        $this->session->set_userdata('pages', 'ccc_role');
        $this->load->helper('url');

    }



    public function saveDataCTC()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        // Debugging: Log the received data
        error_log(print_r($data, true));

        if (is_array($data)) {
            // Simpan data ke database
            foreach ($data as $row) {
                $this->Customer_model->tambah($row);
            }

            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'Invalid data format']);
        }

        exit;
    }


    public function import_data()
    {
        $upload_by = $this->session->userdata('id_user');
        // return var_dump($id_user);
        $this->load->library('upload');
        $this->load->model('Checker_model');

        $this->upload->initialize([
            'upload_path' => './uploads/excel', // Sesuaikan dengan path
            'allowed_types' => 'xlsx|xls|csv',
            'encrypt_name' => TRUE,
        ]);

        $batch_data = []; // Inisialisasi array batch data
        $duplicate_awb = []; // Array untuk menyimpan AWB yang duplikat

        if ($this->upload->do_upload('excel_file')) {
            $data = $this->upload->data();
            $file_path = $data['full_path'];

            // Menggunakan library PhpSpreadsheet
            $spreadsheet = IOFactory::load($file_path);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            unset($sheetData[1]); // Hapus header

            // Pastikan folder penyimpanan gambar ada
            $upload_path = FCPATH . 'uploads/images/';
            $upload_pod_path = FCPATH . 'uploads/images_pod/';
            if (!is_dir($upload_path))
                mkdir($upload_path, 0777, true);
            if (!is_dir($upload_pod_path))
                mkdir($upload_pod_path, 0777, true);


            foreach ($sheetData as $row) {
                $address = $row['J'] . ' ' . $row['K'];
                $lat = $row['Z'];
                $lon = $row['AA'];
                $awb = $row['B'];
                $link_maps = 'https://www.google.com/maps?q=' . $lat . ',' . $lon;
                $photo_url = $row['AB'];
                $photo_pod_url = $row['AD'];
                $id_courier = $row['E'];


                //cek awb duplikat
                if ($this->Checker_model->_duplicat_awb($awb)) {
                    $duplicate_awb[] = $awb;
                    continue;
                }
                if (empty($id_courier)) {
                    continue;
                }

                if (empty($photo_pod_url) && empty($photo_url)) {
                    $saved_photo_path = 'public/img/Image-not-found.png';
                    $saved_photo_pod_path = 'public/img/Image-not-found.png';
                } else if (empty($photo_pod_url) && !empty($photo_url)) {
                    $saved_photo_pod_path = 'public/img/Image-not-found.png';

                    $photo_extension = pathinfo(parse_url($photo_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
                    $photo_name = uniqid() . '.' . $photo_extension;
                    $photo_path = $upload_path . $photo_name;

                    try {
                        if ($photo_content = @file_get_contents($photo_url)) {
                            file_put_contents($photo_path, $photo_content);
                        }

                    } catch (Exception $e) {
                        continue;
                    }

                    $saved_photo_path = 'uploads/images/' . $photo_name;

                } else if (empty($photo_url) && !empty($photo_pod_url)) {
                    $saved_photo_path = 'public/img/Image-not-found.png';

                    $photo_pod_extension = pathinfo(parse_url($photo_pod_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
                    $photo_pod_name = uniqid() . '.' . $photo_pod_extension;
                    $photo_pod_path = $upload_pod_path . $photo_pod_name;
                    try {

                        if ($photo_pod_content = @file_get_contents($photo_pod_url)) {
                            file_put_contents($photo_pod_path, $photo_pod_content);
                        }
                    } catch (Exception $e) {
                        continue;
                    }
                    $saved_photo_pod_path = 'uploads/images_pod/' . $photo_pod_name;
                } else {


                    // Unduh gambar dan simpan secara lokal
                    $photo_extension = pathinfo(parse_url($photo_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
                    $photo_pod_extension = pathinfo(parse_url($photo_pod_url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';

                    $photo_name = uniqid() . '.' . $photo_extension;
                    $photo_pod_name = uniqid() . '.' . $photo_pod_extension;

                    $photo_path = $upload_path . $photo_name;
                    $photo_pod_path = $upload_pod_path . $photo_pod_name;
                    try {
                        if ($photo_content = @file_get_contents($photo_url)) {
                            file_put_contents($photo_path, $photo_content);
                        }
                        if ($photo_pod_content = @file_get_contents($photo_pod_url)) {
                            file_put_contents($photo_pod_path, $photo_pod_content);
                        }
                    } catch (Exception $e) {
                        continue;
                    }

                    $saved_photo_path = 'uploads/images/' . $photo_name;
                    $saved_photo_pod_path = 'uploads/images_pod/' . $photo_pod_name;
                }





                // Tambahkan data ke batch array
                $batch_data[] = [
                    'awb' => $awb,
                    'no_runsheet' => $row['C'],
                    'id_courier' => $id_courier,
                    'destination_code' => $row['G'],
                    'receiver_name' => $row['I'],
                    'receiver_address' => $address,
                    'receiver_city' => $row['L'],
                    'qty' => $row['M'],
                    'weight' => $row['N'],
                    'service' => $row['O'],
                    'paymeny_type' => $row['Q'],
                    'amount' => $row['R'],
                    'runsheet_date' => $row['T'],
                    'received_date' => $row['U'],
                    'status_cod' => $row['V'],
                    'remarks' => $row['W'],
                    'link_maps' => $link_maps,
                    'url_photo' => $saved_photo_path,
                    'url_pod' => $saved_photo_pod_path,
                    'zone' => $row['AH'],
                    'no_hrs' => $row['AI'],
                    'hrs_date' => $row['AJ'],
                    'status_checker' => "Sesuai",
                    'create_date' => date('Y-m-d H:i:s'),
                    'upload_by' => $upload_by,
                    'status_via' => $row['Y'],
                ];
            }
            // Set flashdata untuk error duplikat
            if (!empty($duplicate_awb)) {
                $this->session->set_flashdata('notify', [
                    'message' => 'Terdapat awb yang duplikat',
                    'type' => 'danger'
                ]);
                $this->session->set_flashdata('error_duplicate_awb', 'Terdapat awb yang duplikat ');
            }

            // Jika ada data, lakukan batch insert ke database
            if (!empty($batch_data)) {
                $this->Checker_model->_add_checker($batch_data);
                $this->session->set_flashdata('notify', [
                    'message' => 'File berhasil diunggah dan data berhasil ditambahkan.',
                    'type' => 'success'
                ]);
                // $testing=$this->Checker_model->_add_checker($batch_data);
                // var_dump($testing);
            } else {
                // $testing=$this->Checker_model->_add_checker($batch_data);
                // var_dump($testing);
                $this->session->set_flashdata('notify', [
                    'message' => 'Tidak ada data yang valid untuk diimport.',
                    'type' => 'warning'
                ]);
            }

            redirect('admin');
            // echo '<pre>';
            // print_r($batch_data); // Menampilkan array dalam format yang lebih terbaca
            // echo '</pre>';
        } else {
            $this->session->set_flashdata('notify', [
                'message' => 'File gagal diunggah, file harus berformat excel!',
                'type' => 'warning'
            ]);
            redirect('admin');
        }
    }

    public function revision()
{
    $awb = $this->input->post('awb');
    $reason = $this->input->post('reason');

    $file = $_FILES['revision_img'];

    if ($file['name']) {
        $config['upload_path'] = './uploads/images_revision/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['max_size'] = 10048; // dalam KB (10MB)
        $config['file_name'] = 'revision_' . time();

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('revision_img')) {
            echo "Upload Error: " . $this->upload->display_errors();
            return;
        }

        $uploadData = $this->upload->data();
        $imgPath = 'uploads/images_revision/' . $uploadData['file_name'];

        //  CEK UKURAN FILE: jika >= 500 KB, baru kompres
        $fileSizeBytes = filesize($uploadData['full_path']); // dalam byte
        if ($fileSizeBytes >= 512000) { // 500 KB = 512000 byte
            // === COMPRESS IMAGE ===
            $compress_config['image_library'] = 'gd2';
            $compress_config['source_image'] = $uploadData['full_path'];
            $compress_config['create_thumb'] = FALSE;
            $compress_config['maintain_ratio'] = TRUE;
            $compress_config['quality'] = '60';
            $compress_config['width'] = 1280;
            $compress_config['height'] = 1280;
            $compress_config['overwrite'] = TRUE;

            $this->load->library('image_lib', $compress_config);
            $this->image_lib->initialize($compress_config);

            if (!$this->image_lib->resize()) {
                echo "Compress Error: " . $this->image_lib->display_errors();
                return;
            }
        }

        // Simpan ke database
        $data = [
            'reason_revision' => $reason,
            'url_revision' => $imgPath,
            'status_checker' => "Revisi",
        ];

        $this->Checker_model->_revision_image($awb, $data);

        $this->session->set_flashdata('notify', [
            'message' => 'Image berhasil direvisi.',
            'type' => 'success'
        ]);
    } else {
        $this->session->set_flashdata('notify', [
            'message' => 'Image gagal berhasil direvisi!',
            'type' => 'warning'
        ]);
    }
    redirect($_SERVER['HTTP_REFERER']);
}



    public function getdatatables_upload_data()
    {
        //
        // echo $this->input->post('dateFrom');
        $list = $this->Upload_model->getdatatables_upload_data();
        $data = array();
        $no = $this->input->post('start', true);
        foreach ($list as $item) {
            $no++;

            $row = array();
            $row[] = '<small style="font-size:12px">' . $no . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->destination_code) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->awb_no) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->create_date) . '</small>';


            $data[] = $row;
        }


        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->Upload_model->count_all_upload_data(),
            "recordsFiltered" => $this->Upload_model->count_filtered_upload_data(),
            "data" => $data,
        );
        // output to json format
        echo json_encode($output);
    }

    public function getdatatables_detail_history()
    {
        $id_user = $this->input->post('id_user');
        $list = $this->History_model->getdatatables_detail_history($id_user);
        $data = array();
        $no = $this->input->post('start', true);
        foreach ($list as $item) {
            $no++;

            $row = array();
            $row[] = '<small style="font-size:12px">' . $no . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->awb_no) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->username) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->account_name) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->employee_position) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->start_date) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->destination_code) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->kecamatan) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->hub) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->sortir_number) . '</small>';

            $data[] = $row;
        }


        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->History_model->count_all_detail_history(),
            "recordsFiltered" => $this->History_model->count_filtered_detail_history($id_user),
            "data" => $data,
        );
        // output to json format
        echo json_encode($output);
    }

    public function summary_customer()
    {

        $dateFrom = $this->security->xss_clean($this->input->post('dateFrom'));
        $dateThru = $this->security->xss_clean($this->input->post('dateThru'));
        $id_user = $this->input->post('id_user');


        // $this->db->where('DATE(create_date) >=', $dateFrom);
        // $this->db->where('DATE(create_date) <=', $dateThru);
        $customers_status1 = $this->db->get('history')->num_rows();
        $this->db->where('id_user', $id_user);
        $this->db->where('DATE(end_date) >=', $dateFrom);
        $this->db->where('DATE(end_date) <=', $dateThru);

        // $this->db->where('status', 'Y');
        $this->db->where('id_user', $id_user);
        $this->db->where('DATE(end_date) >=', $dateFrom);
        $this->db->where('DATE(end_date) <=', $dateThru);
        $this->db->select_sum('qty');
        $query = $this->db->get('history');
        $total_qty = $query->row()->qty;
        if ($total_qty == null) {
            $total_qty = 0;
        }


        echo json_encode([
            'sum_status1' => htmlspecialchars($customers_status1),
            'sum_status2' => htmlspecialchars($total_qty),
        ]);
    }


    public function get_csrf()
    {
        //
        echo $this->security->get_csrf_hash();
    }

    public function get_csrf_json()
    {
        //
        $data['status'] = "Success";
        $data['get_csrf_hash'] = $this->security->get_csrf_hash();
        echo json_encode($data);
    }

}