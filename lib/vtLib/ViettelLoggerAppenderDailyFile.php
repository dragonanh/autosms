<?php
/**
 * Created by PhpStorm.
 * User: tuanbm2
 * Date: 02/11/2016
 * Time: 8:39 SA
 */

class ViettelLoggerAppenderDailyFile extends LoggerAppenderDailyFile {
  public function setFile($file) {
    $path = sfConfig::get('sf_log_dir').DIRECTORY_SEPARATOR.$file;
    $this->file = $path;
  }
} 