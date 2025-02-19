<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Barcode extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Barcode');
    }

    public function generate($code = '123456789')
    {
        header('Content-Type: image/png');
        echo $this->barcode->generate($code);
    }
}
