<?php
use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;
/**
 * Created by PhpStorm.
 * User: anhbhv
 * Date: 26/04/2016
 * Time: 9:05 SA
 */
class SpoutProcess
{
  public static function writer($filePath){
    if(is_file($filePath)){
      $writer = WriterFactory::create(Type::XLSX);
      $writer->openToFile($filePath);
    }
  }
}