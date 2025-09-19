<?php
require_once APPPATH . 'libraries/BarcodeGenerator/BarcodeGenerator.php';
require_once APPPATH . 'libraries/BarcodeGenerator/BarcodeGeneratorPNG.php';

class Barcode
{
    public function generate($code, $type = 'png')
    {
        $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
        return $generator->getBarcode($code, $generator::TYPE_CODE_128);
    }
}
