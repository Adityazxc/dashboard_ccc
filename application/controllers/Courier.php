<?php
use PHPStan\PhpDocParser\Ast\PhpDoc\TypeAliasImportTagValueNode;

defined('BASEPATH') or exit('No direct script access allowed');
// require APPPATH . 'third_party/PHPExcel/PHPExcel.php';

class Courier extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->model('Courier_model');
        $this->load->model('User_model');
        $this->session->set_userdata('pages', 'courier_page');
        $this->load->library('session');
        $this->load->helper('url');
        // $this->load->library('email');
        $this->load->library('encryption');

    }

    public function index()
    {
        $user_role = $this->session->userdata('role');
        $password = $this->session->userdata('password');
        $get_origins = json_encode($this->User_model->_get_origins());
        if ($password == "e10adc3949ba59abbe56e057f20f883e") {
            redirect('reset_password/input_password');
        } else if (
            $this->session->userdata('logged_in') && (
                $user_role == "Super User"
                || $user_role == "HC"

            )
        ) {
            $data['title'] = 'Dashboard User';
            $data['page_name'] = 'list_courier';
            $data['get_origins'] = $get_origins;
            $data['role'] = $user_role;
            $this->load->view('dashboard', $data);
        } else {
            redirect('auth');
        }

    }
    public function view_courier()
    {
        $list = $this->Courier_model->getdatatables_courier();

        $data = array();
        $no = $this->input->post('start', true);
        foreach ($list as $item) {
            // $img_path = base_url('uploads/images_courier/' . $item->id_courier . '.JPG');
            $img_path = base_url('uploads/image_courier/' . $item->id_courier . '.jpg');

            // fallback kalau foto tidak ada
            $img_html = '
            <img src="' . $img_path . '"
                onerror="this.src=\'' . base_url('uploads/image_courier/courier.png') . '\'"
                class="courier-img"
                onclick="showCourierImage(\'' . $img_path . '\')"
                alt="Foto Kurir">';



            $no++;
            $row = array();
            $row[] = '<small style="font-size:12px">' . $no . '</small>';
            $row[] = $img_html;
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->id_courier) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->courier_name) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->nik) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->tipe_courier) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->location) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->area) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->zone) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->no_tlp) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->work_zone) . '</small>';
            $row[] = '<div class="form-button-action"> 
            <button class="btn btn-link btn-simple-primary btn-lg" onclick="editCourier(
                ' . htmlspecialchars($item->id) . ',         
                \'' . htmlspecialchars($item->id_courier) . '\', 
                \'' . htmlspecialchars($item->work_zone) . '\', 
                \'' . htmlspecialchars($item->courier_name) . '\', 
                \'' . htmlspecialchars($item->nik) . '\', 
                \'' . htmlspecialchars($item->tipe_courier) . '\', 
                \'' . htmlspecialchars($item->location) . '\', 
                \'' . htmlspecialchars($item->area) . '\', 
                \'' . htmlspecialchars($item->zone) . '\',                 
                 \'' . htmlspecialchars($item->no_tlp) . '\' )"
                data-bs-toggle="modal" data-bs-target="#ModalEditCourier">
                <i class="fa fa-edit"></i>
                </button>
                
                <button class="btn btn-link btn-danger btn-lg" onclick="deletecourier(
                    ' . htmlspecialchars($item->id) . ',
                     \'' . htmlspecialchars($item->courier_name) . '\',
                     \'' . htmlspecialchars($item->id_courier) . '\')"
                    data-bs-toggle="modal" data-bs-target="#deletecourier">
                    <i class="fa fa-times"></i>
                    </button>
                    </div>'


            ;
            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->Courier_model->count_all_courier(),
            "recordsFiltered" => $this->Courier_model->count_filtered_courier(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    
    public function add_courier()
    {
        $no_hp = $this->input->post('no_tlp');
        if (substr($no_hp, 0, 1) == '0') {
            $no_hp = '62' . substr($no_hp, 1);
        }

        $id_courier = trim($this->input->post('idCourier'));

        $user_data = [
            'id_courier' => $id_courier,
            'courier_name' => $this->input->post('courierName'),
            'nik' => $this->input->post('nik'),
            'tipe_courier' => $this->input->post('type_courier'),
            'location' => $this->input->post('location'),
            'area' => $this->input->post('area'),
            'zone' => $this->input->post('zone'),
            'work_zone' => $this->input->post('work_zone'),
            'no_tlp' => $no_hp
        ];


        $insert = $this->Courier_model->add_courier($user_data);

        if ($insert) {

            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0) {

                $path = FCPATH . 'uploads/image_courier/';
                if (!is_dir($path))
                    mkdir($path, 0777, true);

                $file_path = $path . $id_courier . '.jpg';
                move_uploaded_file($_FILES['avatar']['tmp_name'], $file_path);
                chmod($file_path, 0644);
            }

            $this->session->set_flashdata('notify', [
                'message' => 'Data kurir berhasil ditambahkan!',
                'type' => 'success'
            ]);
        } else {
            $this->session->set_flashdata('notify', [
                'message' => 'ID courier sudah terdaftar!',
                'type' => 'danger'
            ]);
        }

        redirect('courier');
    }
    public function edit_courier()
    {
        $id_edit    = $this->input->post('idEdit');
        $id_courier = trim($this->input->post('idCourierEdit'));
        $no_hp      = $this->input->post('no_tlpEdit');
    
        if (substr($no_hp, 0, 1) === '0') {
            $no_hp = '62' . substr($no_hp, 1);
        }
    
        $user_data = [
            'id_courier'   => $id_courier,
            'courier_name' => $this->input->post('courierNameEdit'),
            'nik'          => $this->input->post('nikEdit'),
            'tipe_courier' => $this->input->post('type_courierEdit'),
            'location'     => $this->input->post('locationEdit'),
            'area'         => $this->input->post('areaEdit'),
            'zone'         => $this->input->post('zoneEdit'),
            'no_tlp'       => $no_hp,
            'work_zone'    => $this->input->post('work_zone_edit'),
        ];
    
        $dbUpdated   = false;
        $photoUpload = false;
    
        /* =========================
           UPDATE FOTO (OPTIONAL)
        ========================== */
        if (!empty($_FILES['avatarEdit']['name'])) {
    
            $config['upload_path']   = FCPATH . 'uploads/image_courier/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['file_name']     = $id_courier;
            $config['overwrite']     = true;
            $config['max_size']      = 2048;
    
            $this->load->library('upload', $config);
    
            if (!$this->upload->do_upload('avatarEdit')) {
                $this->session->set_flashdata('notify', [
                    'message' => $this->upload->display_errors('', ''),
                    'type'    => 'danger'
                ]);
                redirect('courier');
                return;
            }
    
            // ✔ kalau sampai sini, foto PASTI berhasil
            $photoUpload = true;
        }
    
        /* =========================
           UPDATE DATABASE
        ========================== */
        $result = $this->Courier_model->edit_courier($user_data, $id_edit);
    
        if (is_array($result) && $result['status']) {
            $dbUpdated = true;
        }
    
        /* =========================
           LOGIKA SUCCESS FINAL 🔥
        ========================== */
        if ($dbUpdated || $photoUpload) {
    
            $this->session->set_flashdata('notify', [
                'message' => 'Data kurir berhasil diperbarui!',
                'type'    => 'success'
            ]);
    
        } else {
    
            $this->session->set_flashdata('notify', [
                'message' => 'Tidak ada perubahan data',
                'type'    => 'warning'
            ]);
        }
    
        redirect('courier');
    }
    




    public function edit_cofurier()
    {

        $id_edit = $this->input->post('idEdit');
        $no_hp = $this->input->post('no_tlpEdit');
        $id_courier = trim($this->input->post('idCourierEdit'));

        var_dump($id_edit, $id_courier, $no_hp);
        // if (substr($no_hp, 0, 1) == '0') {
        //     // Ganti angka 0 pertama dengan 62
        //     $no_hp = '62' . substr($no_hp, 1);
        // }
        // $user_data = array(
        //     'id_courier' => $id_courier,
        //     'courier_name' => $this->input->post('courierNameEdit'),
        //     'nik' => $this->input->post('nikEdit'),
        //     'tipe_courier' => $this->input->post('type_courierEdit'),
        //     'location' => $this->input->post('locationEdit'),
        //     'area' => $this->input->post('areaEdit'),
        //     'zone' => $this->input->post('zoneEdit'),
        //     'no_tlp' => $no_hp,

        // );
        // if ($this->Courier_model->edit_courier($user_data, $id_edit)) {
        //     // Jika berhasil, set flashdata untuk notifikasi sukses
        //     $this->session->set_flashdata('notify', [
        //         'message' => 'Data kurir berhasil dirubah!',
        //         'type' => 'success'
        //     ]);
        // } else {
        //     // Jika gagal, set flashdata untuk notifikasi error
        //     $this->session->set_flashdata('notify', [
        //         'message' => 'Data kurir gagal dirubah!',
        //         'type' => 'danger'
        //     ]);
        // }        
        // redirect('courier');
    }

    public function delete_courier()
    {
        $id_courier = $this->input->post('idCourierDelete');
        $courier_id = $this->input->post('courier_id_delete');


        // path file image
        $img_path = FCPATH . 'uploads/image_courier/' . $courier_id . '.jpg';

        // hapus data di database
        if ($this->Courier_model->delete_courier($id_courier)) {

            // 🔥 hapus file image kalau ada
            if (file_exists($img_path)) {
                unlink($img_path);
            }

            $this->session->set_flashdata('notify', [
                'message' => 'Kurir berhasil dihapus!',
                'type' => 'success'
            ]);

        } else {

            $this->session->set_flashdata('notify', [
                'message' => 'Gagal menghapus kurir!',
                'type' => 'error'
            ]);
        }

        redirect('courier');
    }

    public function default_password()
    {
        $id_courier = $this->input->post('idUserReset');

        if ($this->Courier_model->default_password($id_courier)) {
            // Jika berhasil, set flashdata untuk notifikasi sukses
            $this->session->set_flashdata('notify', [
                'message' => 'Produk berhasil ditambahkan!',
                'type' => 'success'
            ]);
        } else {
            // Jika gagal, set flashdata untuk notifikasi error
            $this->session->set_flashdata('notify', [
                'message' => 'Gagal menambahkan produk!',
                'type' => 'success'
            ]);
        }
        redirect('courier');
    }

    public function get_csrf()
    {
        echo $this->security->get_csrf_hash();
    }

    public function get_csrf_json()
    {
        $data['status'] = "Success";
        $data['get_csrf_hash'] = $this->security->get_csrf_hash();
        echo json_encode($data);
    }




}

