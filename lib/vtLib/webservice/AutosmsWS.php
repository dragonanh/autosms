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
  private $username;
  private $password;

  public function __construct()
  {
    $this->url = sfConfig::get('app_ws_autosms_url');
    $this->username = sfConfig::get('app_ws_autosms_username');
    $this->password = sfConfig::get('app_ws_autosms_password');
  }

  public function createSchedule($content, $startTime, $endTime){
    $data = sprintf('<?xml version="1.0"?>
<S:Envelope xmlns:S="http://schemas.xmlsoap.org/soap/envelope/">
<S:Body>
 <moRequest xmlns="http://mtws/xsd">
  <username>%s</username>
  <password>%s</password>
  <command>create_qr</command>
        <content>%s</content>
        <start_date>%s</start_date>
        <end_date>%s</end_date>
 </moRequest>
</S:Body>
</S:Envelope>', $this->username, $this->password, $content, $startTime, $endTime);
    $id = null;
    $response = $this->post_curl($data, 'createSchedule', 15);
    if($response){
      $doc = new DOMDocument;
      $doc->loadXML($response);
      $return = $doc->getElementsByTagName('return')->item(0)->nodeValue;
      if(!empty($return)){
        $returnArr = explode('|', $return);
        if($returnArr[0] == 0 && count($returnArr) >= 2){
          $errorCode = 0;
          $message = 'success';
          $id = $returnArr[1];
        }else{
          $errorCode = 1;
          $message = 'Hệ thống bận Quý khách vui lòng thử lại sau';
        }
      }else{
        $errorCode = 500;
        $message = 'Hệ thống bận Quý khách vui lòng thử lại sau';
      }
    }else{
      $errorCode = 500;
      $message = 'Hệ thống bận Quý khách vui lòng thử lại sau';
    }

    return ['errorCode' => $errorCode, 'message' => $message, 'id' => $id];
  }

  public function detailSchedule($id){
    $result = null;
    $data = sprintf('<?xml version="1.0"?>
<S:Envelope xmlns:S="http://schemas.xmlsoap.org/soap/envelope/">
<S:Body>
 <moRequest xmlns="http://mtws/xsd">
  <username>%s</username>
  <password>%s</password>
  <command>get_qr</command>
        <qr_id>%s</qr_id>
 </moRequest>
</S:Body>
</S:Envelope>', $this->username, $this->password, $id);

    $response = $this->post_curl($data, 'detailSchedule', 15);
    if($response){
      $doc = new DOMDocument;
      $doc->loadXML($response);
      $return = $doc->getElementsByTagName('return')->item(0)->nodeValue;
      if($return == 0){
          $errorCode = 0;
          $message = 'success';
          $result = [
            'content' => $doc->getElementsByTagName('content')->item(0)->nodeValue,
            'start_time' => $doc->getElementsByTagName('start_date')->item(0)->nodeValue,
            'end_time' => $doc->getElementsByTagName('end_date')->item(0)->nodeValue
          ];
      }else{
        $errorCode = 500;
        $message = 'Hệ thống bận Quý khách vui lòng thử lại sau';
      }
    }else{
      $errorCode = 500;
      $message = 'Hệ thống bận Quý khách vui lòng thử lại sau';
    }

    return ['errorCode' => $errorCode, 'message' => $message, 'data' => $result];
  }

  protected function post_curl($_data, $functionName, $timeoutSecond = 0, $method = 'POST')
  {
    $logger = VtHelper::getLogger4Php("all");
    try {
      $pst = curl_init();
      curl_setopt($pst, CURLOPT_URL, $this->url);
      curl_setopt($pst, CURLOPT_CUSTOMREQUEST, $method);
      curl_setopt($pst, CURLOPT_POST, count($_data));
      curl_setopt($pst, CURLOPT_POSTFIELDS, $_data);
      curl_setopt($pst, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
      curl_setopt($pst, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($pst, CURLOPT_CONNECTTIMEOUT, 15); //so giay timeout khi ket noi
      curl_setopt($pst, CURLOPT_PROXY, false);
      if ($timeoutSecond != 0) {
        curl_setopt($pst, CURLOPT_TIMEOUT, $timeoutSecond); //so giay timeout hoac su dung milisecond voi CURLOPT_TIMEOUT_MS
      }
      $res = curl_exec($pst);
      curl_close($pst);
      $logger->info(sprintf('%s|params: %s|response: %s', $functionName, json_encode($_data), json_encode($res)));
    } catch (Exception $ex) {
      $logger->info(sprintf('%s|params: %s|error: %s', $functionName, json_encode($_data), $ex->getMessage()));
      return null;
    }

    return $res;
  }
}