<?php
use PHPStan\PhpDocParser\Ast\PhpDoc\TypeAliasImportTagValueNode;

defined('BASEPATH') or exit('No direct script access allowed');
// require APPPATH . 'third_party/PHPExcel/PHPExcel.php';

class Cs extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Customer_model');
        $this->load->model('Cs_model');
        $this->load->model('Marketing_model');
        $this->session->set_userdata('pages', 'cs_role');
        $this->load->library('session');
        $this->load->helper('url');
        // $this->load->library('email');
        $this->load->library('encryption');

    }

    public function index()
    {
        $user_role = $this->session->userdata('role');
        if ($this->session->userdata('logged_in') && ($user_role == 'CS' || $user_role == 'Admin')) {
            $data['title'] = 'Dashboard CS';
            $data['page_name'] = 'dashboard_cs';
            if ($user_role == 'CS') {
                $data['role'] = 'CS';
            } else {
                $data['role'] = 'Admin';
            }
            $data['voucher_data'] = $this->Customer_model->getVoucherData();
            $this->load->view('dashboard', $data);
        } else {
            redirect('auth');
        }
    }
    public function send_email()
    {
        $user_role = $this->session->userdata('role');
        if ($this->session->userdata('logged_in') && ($user_role == 'CS' || $user_role == 'Admin')) {
            $data['title'] = 'Send Email CS';
            $data['page_name'] = 'send_email';
            if ($user_role == 'CS') {
                $data['role'] = 'CS';
            } else {
                $data['role'] = 'Admin';
            }
            $data['voucher_data'] = $this->Customer_model->getVoucherData();
            $this->load->view('dashboard', $data);
        } else {
            echo "404";
        }
    }
    public function report()
    {
        $user_role = $this->session->userdata('role');
        if ($this->session->userdata('logged_in') && ($user_role == 'CS' || $user_role == 'Admin')) {
            $data['title'] = 'Report CS';
            $data['page_name'] = 'report';
            if ($user_role == 'CS') {
                $data['role'] = 'CS';
            } else {
                $data['role'] = 'Admin';
            }
            $data['voucher_data'] = $this->Customer_model->getVoucherData();
            $this->load->view('dashboard', $data);
        } else {
            echo "404";
        }
    }
    public function search_customer()
    {
        $this->load->model('Customer_model');
        $keyword = $this->input->post('search_keyword', TRUE);
        $search_result = $this->Customer_model->searchCustomer($keyword);
        $data['search_result'] = $search_result;
        $data['search_keyword'] = $keyword;
        $this->redeem($data);
    }


    public function session()
    {
        $session_data = $this->session->all_userdata();

        echo '<pre>';
        print_r($session_data);
    }


    public function getdatatables_send_email()
    {
        $list = $this->Cs_model->getdatatables_marketing();

        $data = array();
        $no = $this->input->post('start', true);
        foreach ($list as $item) {
            if ($item->status == 'N') {
                $status = 'Belum Dipakai';
            } else {
                $status = 'Telah dipakai';
            }
            if ($item->status_email == 'N') {
                $status_email = 'Belum dikirim';
            } elseif ($item->status_email == 'Y') {
                $status_email = 'Sudah terkirim';
            }
            $no++;
            $row = array();
            $row[] = '<small style="font-size:12px">' . $no . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->customer_name) . '</small>';
            if ($item->email == null) {
                // $row[] = '<button type="button" class="btn btn-sm btn-warning" onclick="editEmail(' . $item->id . ', \'' . $item->email . '\')"> <i class="bi bi-pencil "></i> Edit</button>';                
                $row[] = '<button type="button" id="modal_csrf_token" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#ModalEditEmail" onclick="editEmail(' . htmlspecialchars($item->id) . ',\'' . htmlspecialchars($item->customer_name) . '\')"> <i class="bi bi-pencil "></i> Edit</button>';
            } else {
                $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->email) . '</small>';
            }
            $formattedNumber = $item->no_hp;
            if ($item->no_hp[0] === '0') {
                $formattedNumber = '62' . substr($item->no_hp, 1);
            }
            $resi = $item->awb_no;
            $sendWhatsapp = $this->openWhatsapp($formattedNumber, $resi);
            $whatsappLink = '<a href="' . $sendWhatsapp . '" target="_blank">+' . $formattedNumber . '</a>';

            $row[] = '<small style="font-size:12px">' . $whatsappLink . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->harga) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->awb_no) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($status) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->service) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->voucher) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($status_email) . '</small>';
            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->Cs_model->count_all_marketing(),
            "recordsFiltered" => $this->Cs_model->count_filtered_marketing(),
            "data" => $data,
        );
        echo json_encode($output);
    }
    public function getdatatables_report()
    {
        $list = $this->Cs_model->getdatatables_report();

        $data = array();
        $no = $this->input->post('start', true);
        foreach ($list as $item) {
            if ($item->status == 'N') {
                $status = 'Belum Dipakai';
            } else {
                $status = 'Telah dipakai';
            }
            if ($item->status_email == 'N') {
                $status_email = 'Belum dikirim';
            } elseif ($item->status_email == 'Y') {
                $status_email = 'Sudah terkirim';
            }
            $no++;
            $row = array();
            $row[] = '<small style="font-size:12px">' . $no . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->customer_name) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->email) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->no_hp) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->harga) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->awb_no) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($status) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->service) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->voucher) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($status_email) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->account_name) . '</small>';
            $row[] = '<small style="font-size:12px">' . htmlspecialchars($item->date_send_email) . '</small>';
            $data[] = $row;
        }
        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->Cs_model->count_all_customer(),
            "recordsFiltered" => $this->Cs_model->count_filtered_customer(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    private function openWhatsapp($phoneNumber, $resi)
    {
        $message = "Halo kak, \n\n" .
            "Selamat ya, Kamu dapat E-Voucher Ongkir JNE untuk kiriman kamu dengan no resi " . $resi . "\n" .
            "E-Voucher akan dikirmkan melalui e-mail, segera kirimkan alamat e-mail mu dengan membalas pesan ini ya Kak.\n" .
            "Jika ingin informasi lebih lanjut, jangan ragu menghubungi Call Center kami di 022-86023222.\n\n" .
            "Salam hangat dan selamat beraktivitas ";

        $encodedPhoneNumber = urlencode($phoneNumber);
        $encodedMessage = urlencode($message);
        $whatsappUrl = "https://wa.me/" . $encodedPhoneNumber . "?text=" . $encodedMessage;

        return $whatsappUrl;

    }
    public function send_emails()
    {
        $selectedEmails = $this->input->post('selectedEmails', TRUE);
        $customerName = $this->input->post('customerName', TRUE); // Get the customer's name
        $voucherInfo = $this->input->post('voucherInfo', TRUE);

        if (!empty($selectedEmails)) {
            foreach ($selectedEmails as $email) {
                if (!empty($email)) {
                    $this->kirim_email($email, $customerName, $voucherInfo);
                    $this->db->where('email', $email);
                    $this->db->update('customers', array('status_email' => 'Y'));
                }
            }
            echo 'Email berhasil dikirim';
        } else {
            echo 'Tidak ada email yang dipilih atau email null.';
        }
    }
    public function update_email()
    {
        $customerId = $this->input->post('customerID', TRUE);
        $email = $this->input->post('newEmail', TRUE);
        $user_create=$this->session->userdata('id_user');
        if ($this->Cs_model->update_email_model($customerId, $email)) {
            $customers = $this->db->get_where('customers', ['id' => $customerId]);
            // Data yang ingin diupdate
            $updateData = [
                'status_email' => 'Y',
                'date_send_email' => date('Y-m-d H:i:s')                
            ];

            // Tambahkan 30 hari ke tanggal saat ini
            $newExpiredDate = date('Y-m-d', strtotime('+30 days'));
            $updateData['expired_date'] = $newExpiredDate;
            $updateData['id_send_email'] = $user_create;

            // Set kolom expired_date dengan nilai baru dan update kolom lainnya
            $this->db->where('id', $customerId);
            $this->db->update('customers', $updateData);

            $harga = $customers->row()->harga;
            $resi = $customers->row()->awb_no;
            $harga_voucher = $this->format_rupiah($harga);
            $expired = strftime('%e %B %Y', strtotime($newExpiredDate));

            $this->kirim_email($customers->row()->email, $customers->row()->customer_name, $customers->row()->voucher, $harga_voucher, $resi, $expired);
            echo "Email berhasil diperbarui!";
        } else {
            echo "Gagal memperbarui email.";
        }

        redirect("cs/send_email");

    }

    public function getdatatables_email_null()
    {
        $list = $this->ccc_model->getdatatables_email_null();
        $data = array();
        $no = @$_POST['start'];

        foreach ($list as $item) {
            // Add your logic for each row if needed
            $no++;
            $row = array();
            $row[] = '<input type="checkbox" name="id_customer[]" value="' . htmlspecialchars(@$item->id) . '" class="form-check-input ml-2 data-check" id="id_customer">';
            $row[] = '<small style="font-size:12px">' . $no . '</small>';
            // Add other columns based on your table structure
            $data[] = $row;
        }

        $output = array(
            "draw" => @$_POST['draw'],
            "recordsTotal" => $this->ccc_model->getdatatables_email_null_count(),
            "recordsFiltered" => $this->ccc_model->getdatatables_email_null_count(),
            "data" => $data,
        );

        echo json_encode($output);
    }

    public function kirim_email($recipientEmail, $nama, $voucherCode, $harga, $resi, $expired_date)
    {
        $output = '<!DOCTYPE html>
        <html lang="id">
        
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Promosi Voucher</title>
            
        </head>
        <body>
            <div class="container">
                <style>
                    .content,            
                    .footer {                        
                        margin: 0;
                        padding: 0;
                        text-align: center;
                    }
                    .ketentuan,.header {                
                        margin: 0;
                        padding: 0;
                        
                    }
        
                    .container {
                        max-width: 600px;
                        margin: 0 auto;
                        background-color: #ffffff;
                        padding: 20px;
                        border-radius: 8px;
                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                    }
        
                    .header img {
                        width: 100%;
                        max-width: 300px;
                        height: auto;
                        margin-bottom: 20px;
                    }
        
                    .promo-text {
                        color: #333;
                        font-size: 18px;
                        line-height: 1.6;
                        margin-bottom: 20px;
                    }
        
                    .cta-button {
                        display: inline-block;
                        background-color: #3498db;
                        color: #fff;
                        text-decoration: none;
                        padding: 10px 20px;
                        font-size: 16px;
                        border-radius: 5px;
                    }
                </style>
        
                <div class="content">
                <h2>Halo ' . htmlspecialchars($nama) . '</h2>
                    <p class="promo-text">Selamat Anda mendapatkan E-Voucher Ongkir sebesar ' . htmlspecialchars($harga) . ' dari Program "GARANSI
                        ONGKIR KEMBALI" JNE Cabang Utama Bandung dari Transaksi pengiriman dengan No. Resi/Airwaybill ' . htmlspecialchars($resi) . ' berikut syarat & ketentuannya :</p>
                </div>
                <div class="ketentuan">
                    <ol>
                        <li>E-Voucher Ongkir berlaku hingga ' . htmlspecialchars($expired_date) . '</li>                        
                        <li>E-Voucher Ongkir hanya bisa digunakan untuk 1 (satu) kali transaksi</li>
                        <li>Tidak ada Pengembalian Uang jika E-Voucher melebihi Harga Ongkos Kirim</li>
                        <li>Jika pada saat melakukan Transaksi Pengiriman Total Ongkos Kirim melebihi dari nilai E-Voucher maka
                            customer Wajib menambah Ongkos Kirim sesuai dengan kekurangannya.</li>
                        <li>Penggunaan E-Voucher Ongkir tidak dapat digabung dengan program/promo lainnya.</li>
                    </ol>
                </div>
        
                <div class="footer">
                    <p>
                    Segera gunakan E-Voucher ongkir dengaan kode <b style="font-size: larger;background-color: yellow;">' . htmlspecialchars($voucherCode) . '</b> di seluruh Sales Counter JNE Kantor Cabang Utama Bandung.</p>
        
                    Tim Promotion E-Voucher JNE Bandung
                </div>
            </div>
        </body>
        
        </html>
        ';

        $conn = [
            'protocol' => 'smtp',
            'smtp_host' => 'smtp.office365.com',
            'smtp_user' => 'bdo.promo@jne.co.id',
            'smtp_pass' => 'prob0220',
            'smtp_port' => '587',
            'smtp_crypto' => 'tls',
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'newline' => "\r\n",
            'crlf' => "\r\n"
        ];

        $this->load->library('email', $conn);
        $this->email->from('bdo.promo@jne.co.id', 'JNE BANDUNG');
        $this->email->to($recipientEmail);
        $this->email->subject('Selamat Anda mendapatkan E-Voucher Ongkir dari JNE Bandung');
        $this->email->message($output);
        if ($this->email->send()) {
            echo 'Email berhasil dikirim';
        } else {
            echo 'Email gagal dikirim';
            echo $this->email->print_debugger(); // Jika terjadi kesalahan, tampilkan pesan debug
        }

    }


    function format_rupiah($harga)
    {
        return 'Rp ' . number_format($harga, 0, ',', '.');
    }



}

