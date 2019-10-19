<?php
/**
 * Created by PhpStorm.
 * User: anhbhv
 * Date: 10/19/2019
 * Time: 5:22 PM
 */

class AutosmsWS
{
  private $url;
  
  public function __construct()
  {
    
  }

  public function createSchedule($content, $startTime, $endTime){
    $data = '<?xml version="1.0"?>
<S:Envelope xmlns:S="http://schemas.xmlsoap.org/soap/envelope/">
<S:Body>
 <moRequest xmlns="http://mtws/xsd">
  <username>viettel</username>
  <password>jsKk432Sw</password>
  <command>create_qr</command>
        <content>hello</content>
        <start_date>20191015000001</start_date>
        <end_date>20191115000001</end_date>
 </moRequest>
</S:Body>
</S:Envelope>';

    $this->post_curl();
  }

  protected function post_curl($_url, $_data, $functionName, $timeoutSecond = 0, $method = 'POST')
  {
    $logger = VtHelper::getLogger4Php("all");
    try {
      $pst = curl_init();
      curl_setopt($pst, CURLOPT_URL, $_url);
      curl_setopt($pst, CURLOPT_CUSTOMREQUEST, $method);
      curl_setopt($pst, CURLOPT_POST, count($_data));
      curl_setopt($pst, CURLOPT_POSTFIELDS, $_data);
      curl_setopt($pst, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
      curl_setopt($pst, CURLOPT_RETURNTRANSFER, 1);
      //curl_setopt($pst, CURLOPT_CONNECTTIMEOUT, 2); //so giay timeout khi ket noi
      curl_setopt($pst, CURLOPT_PROXY, false);
      if ($timeoutSecond != 0) {
        curl_setopt($pst, CURLOPT_TIMEOUT, $timeoutSecond); //so giay timeout hoac su dung milisecond voi CURLOPT_TIMEOUT_MS
      }
      $res = curl_exec($pst);
      curl_close($pst);
    } catch (Exception $ex) {
      return null;
    }

    return $res;
  }
}