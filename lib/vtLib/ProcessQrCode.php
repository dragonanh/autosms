<?php

use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\LabelAlignment;
use Endroid\QrCode\QrCode;
class ProcessQrCode
{
  public $qrCode = null;
  public function __construct($content)
  {
    $this->qrCode = new QrCode($content);
    $this->qrCode->setSize(300)
      ->setWriterByName('png')
      ->setMargin(10)
      ->setEncoding('UTF-8')
      ->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH)
      ->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0])
      ->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255]);
  }

  public function writeString(){
    header('Content-Type: '.$this->qrCode->getContentType());
    return $this->qrCode->writeString();
  }

  public function writeDataUri(){
    return $this->qrCode->writeDataUri();
  }

  public function writeFile($filePath){
    return $this->qrCode->writeFile($filePath);
  }


}