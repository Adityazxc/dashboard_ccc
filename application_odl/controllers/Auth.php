<?php
defined("BASEPATH") or exit("No direct script access allowed");

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->library('GoogleAuthenticator');
        // Jika tanpa composer:         


    }

    public function index()
    {
        $this->load->view('index');
    }
    public function setup_2fa_page()
    {

        $this->load->view('setup_2fa');
    }




    public function login()
    {
        $username = trim($this->input->post('username', TRUE));
        $password = trim($this->input->post('password', TRUE));

        // Debug input
        log_message('debug', 'Input Username: ' . $username);
        log_message('debug', 'Input Password Hash: ' . md5($password));

        if (empty($username) || empty($password)) {
            $this->session->set_flashdata('error_message', 'Username dan Password tidak boleh kosong!');
            redirect('auth');
            return;
        }

        $this->db->group_start();
        $this->db->where('username', $username);
        $this->db->group_end();
        $this->db->where('pass', md5($password));

        $query = $this->db->get('users');

        if ($query->num_rows() == 1) {
            $user = $query->row();

            $redirect_page = 'auth';
            if (md5($password) == md5("123456")) {
                $redirect_page = "reset_password/input_password";
            } else {
                switch ($user->role) {
                    case "Koordinator":
                    case "Admin":
                    case "BPS":
                    case "Kepala Cabang":
                    case "CS":
                    case "CCC":
                    case "HC":
                    case "Kepala Cabang BDO2":
                    case "Super User":
                    case "BBP":
                    case "PAO":
                        $redirect_page = "dashboard";
                        break;
                }
            }

            $this->session->set_userdata([
                'data_user' => [
                    'id_user' => $user->id_user,
                    'username' => $user->username,
                    'name' => $user->name,
                    'role' => $user->role,
                    'pass' => $user->pass,
                    'location' => $user->location
                ]
            ]);
            if (!empty($user->secret_2fa && $user->is_2fa_enabled == 1)) {

                redirect('auth/verify_otp');
                return;
            } else if (empty($user->secret_2fa) || $user->is_2fa_enabled == 0) {
                redirect('auth/setup_2fa');
            }
            var_dump("login berhasil");
            log_message('debug', 'Login successful. Redirecting to: ' . $redirect_page);
            redirect($redirect_page);
        } else {
            log_message('debug', 'Login failed for username: ' . $username);
            $this->session->set_flashdata('error_message', 'Username dan Password tidak sesuai!');
            redirect('auth');
        }

    }




    public function verify_otp()
    {
        $this->load->library('GoogleAuthenticator');
        $ga = new GoogleAuthenticator();

        $tempUser = $this->session->userdata('data_user');

        if (!$tempUser) {
            redirect('auth');
            return;
        }

        $user = $this->db->where('id_user', $tempUser['id_user'])->get('users')->row();

        // Jika belum memiliki secret, buatkan
        if (empty($user->secret_2fa)) {
            $secret = $ga->createSecret();
            $this->db->where('id_user', $user->id_user)->update('users', ['secret_2fa' => $secret]);
            $user->secret_2fa = $secret;
        }

        // QR Code URL tetap ditampilkan jika secret baru
        $qr_url = $ga->getQRCodeGoogleUrl('NamaApp (' . $tempUser['username'] . ')', $user->secret_2fa);

        // Proses OTP jika disubmit
        if ($this->input->post()) {
            $otp = trim($this->input->post('otp', TRUE));

            if ($ga->verifyCode($user->secret_2fa, $otp, 2)) {
                // Simpan session login penuh
                $this->session->set_userdata([
                    'id_user' => $user->id_user,
                    'username' => $user->username,
                    'account_name' => $user->name,
                    'role' => $user->role,
                    'password' => $user->pass,
                    'location' => $user->location,
                    'logged_in' => TRUE
                ]);
                $this->session->unset_userdata('data_user');

                // Tandai aktif 2FA
                $this->db->where('id_user', $user->id_user)->update('users', ['is_2fa_enabled' => 1]);

                redirect('dashboard');
            } else {
                $this->session->set_flashdata('error_message', 'Kode OTP salah!');
            }
        }

        // Tampilkan view dengan/atau tanpa QR code
        $this->load->view('verify_otp', [
            'qr_url' => !empty($qr_url) ? $qr_url : null,
            'has_secret' => !empty($user->secret_2fa),
        ]);
    }



    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth');
    }



    public function setup_2fa()
    {
        $this->load->library('GoogleAuthenticator');
        $ga = new GoogleAuthenticator();
        $secret = $ga->createSecret();
        $tempUser = $this->session->userdata('data_user');
        $user = $this->db->where('id_user', $tempUser['id_user'])->get('users')->row();

        // Simpan secret ke session sementara
        $this->session->set_userdata('setup_secret', $secret);

        $this->db->where('id_user', $user->id_user)->update('users', ['secret_2fa' => $secret]);

        $data_user = $this->session->userdata('data_user');
        $username = isset($data_user['username']) ? $data_user['username'] : 'user';
        $role = isset($data_user['role']) ? $data_user['role'] : 'User';

        $qrCodeUrl = $ga->getQRCodeGoogleUrl(
            'Validasi POD(' . $username . ')',
            $secret
        );


        $data['qr_url'] = $qrCodeUrl;
        $this->load->view('setup_2fa', $data);
    }



    public function verify_otp_setup()
    {
        $otp = trim($this->input->post('otp_code', TRUE));
        $secret = $this->session->userdata('setup_secret');
        $user_data = $this->session->userdata('data_user');

        if (!$secret || !$user_data) {
            $this->session->set_flashdata('error_message', 'Session tidak valid. Silakan login ulang.');
            redirect('auth');
            return;
        }
        // var_dump($secret);
        $this->load->library('GoogleAuthenticator');
        $ga = new GoogleAuthenticator();

        if ($ga->verifyCode($secret, $otp, 2)) {
            // Simpan secret ke database
            $this->db->where('id_user', $user_data['id_user'])
                ->update('users', ['secret_2fa' => $secret,'is_2fa_enabled' => 1])
                ;

            // Hapus setup_secret agar tidak bisa dipakai lagi
            $this->session->unset_userdata('setup_secret');

            // Set session login final
            $this->session->set_userdata(array_merge($user_data, ['logged_in' => TRUE]));

            redirect('dashboard');
        } else {
            $this->session->set_flashdata('error_message', 'Kode OTP salah, coba lagi.');
            redirect('auth/setup_2fa');
        }
    }



}
?>