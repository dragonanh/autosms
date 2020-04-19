<?php

/**
 * vtCommon actions.
 *
 * @package    mobile_marketing
 * @subpackage vtCommon
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php_bak.bak 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class HomepageActions extends sfActions
{
  public function executeQrcode(sfWebRequest $request){
    $id = $request->getParameter('id');
    if(!$id) $this->forward404();
    $this->error = null;

    $autoSms = new AutosmsWS();
    $detail = $autoSms->detailSchedule($id);
    if($detail['errorCode'] == 0){
      $schedule = $detail['data'];
      $this->content = $schedule['content'];
      $this->startTime = date('d-m-Y H:i:s', strtotime($schedule['start_time']));
      $this->endTime = date('d-m-Y H:i:s', strtotime($schedule['end_time']));

      $content = $this->generateUrl('detailProgram', ['id' => $id], true);
      $qrcode = new ProcessQrCode($content);
      $this->qrCodeImg = $qrcode->writeDataUri();
      $this->id = $id;

      $urlShare = "";
      $user_agent     =   $_SERVER['HTTP_USER_AGENT'];
      $smsContent = sprintf('DV Tin nhan bao ban thong minh AutoSMS %s', $content);
      $detect = new Mobile_Detect();
      if($detect->is('AndroidOS')){
        $urlShare = "sms:?body=$smsContent";
      }elseif($detect->is('iOS') || preg_match('/macintosh|mac os x/i', $user_agent)){
        $urlShare = sprintf("sms://open?addresses=/&body=%s",$smsContent);
      }
      $this->urlShare = $urlShare;
    }else{
      $this->error= 'Hệ thống bận hoặc mã qrcode không tồn tại';
    }


  }
  public function executePolicy(sfWebRequest $request){

  }
  public function executeAbout(sfWebRequest $request){

  }
  public function executeGuide(sfWebRequest $request){

  }
  public function executeIndex(sfWebRequest $request){

  }

  public function executeCreate(sfWebRequest $request){
    $this->form = new CreateProgramForm();
  }

  public function executeDetail(sfWebRequest $request){
    $id = $request->getParameter('id');
    $logger = VtHelper::getLogger4Php('all');
    if(!$id){
      $this->forward404();
    }
    $logger->info('=============BEGIN DETAIL==============');

    $mobileInfo = [];
    $this->error = null;

    //todo: lay thong tin lich bao ban tu ws
    $autoSms = new AutosmsWS();
    $detail = $autoSms->detailSchedule($id);
    if($detail['errorCode'] == 0){
      $dataResponse = $request->getParameter('DATA');
      $sign = $request->getParameter('SIG');
      if($dataResponse && $sign){
        $mps = new MpsWS();
        $dataDecrypt = $mps->decryptData($dataResponse, $sign);
        $logger->info(sprintf("[executeMpsResult] DECRYPT SUCCESS|data: %s", json_encode($dataDecrypt)));
        $response = $dataDecrypt['RES'];
        $transId = $dataDecrypt['REQ'];
        $id = substr($transId,9,3);
        $msisdn = $dataDecrypt['MOBILE'];
        if(!empty($msisdn) && strtolower($msisdn) != 'null'){
          //luu thong tin nhan dien vao session
          $mobileInfo = [
            'msisdn' => $msisdn,
            'isSub' => $response == 205 ? true : false
          ];
        }else{
          $this->error = "Không nhận diện được thuê bao, Quý khách vui lòng thử lại sau";
        }

      }

      $logger->info(sprintf("[executeDetail] %s|GET DETAIL SUCCESS", $id));
//      $mobileInfo = $this->getUser()->getAttribute('autosms.detectMobile');
      if($request->isMethod('get') && empty($mobileInfo)){
        $logger->info(sprintf("[executeDetail] %s|NOT INFO IN SESSION", $id));
        //truong hop khong co thong tin so dien thoai trong session
        //thuc hien redirect sang mps de nhan dien
        $mps = new MpsWS();
        $transId = rand(100000000, 999999999);
        //luu id lich vao session
//        $this->getUser()->setAttribute(sprintf('autosms.detect.transId.%s', $transId), $id);
        $params = [
          'SUB' => 'AUTOSMS_DAILY', 'PRO' => 'GHD',
          'SER' => 'AutoSMS', 'REQ' => $transId . $id
        ];
        $urlRedirect = $mps->getMpsUrl($params, MpsWS::MOBILE);
        $logger->info(sprintf("[executeDetail] %s|URL MPS DETECT MOBILE: %s", $id, $urlRedirect));
        $this->redirect($urlRedirect);
      }

      $logger->info(sprintf("[executeDetail] %s|DETECT SUCCESS|%s", $id, json_encode($mobileInfo)));

      $schedule = $detail['data'];
      $schedule['start_time'] = date('d-m-Y H:i:s', strtotime($schedule['start_time']));
      $schedule['end_time'] = date('d-m-Y H:i:s', strtotime($schedule['end_time']));
      $this->form = new CreateProgramForm(null, ['schedule' => $schedule, 'page' => 'detail']);
      $this->id = $id;

      $isSub = $mobileInfo['isSub'];
      $this->isSub = $isSub;

      if ($request->isMethod('post') && !$isSub) {
        $token = $request->getParameter('token');
        if ($token == $this->form->getCSRFToken()) {
          $msisdn = $mobileInfo['msisdn'];
          $logger->info(sprintf("[executeDetail] %s|mobile: %s|isSub: %s", $id, $msisdn, $isSub));
          if ($isSub) {
            //truong hop la sub --> thuc hien dang ky luon
            $autoSms = new AutosmsWS();
            $result = $autoSms->applySchedule($id, $msisdn);
            $logger->info(sprintf("[executeDetail] %s|IS SUB - APPLY SCHEDULE|mobile: %s", $id, $msisdn));
            if ($result['errorCode'] == 0) {
              $message = 'Áp dụng lịch thành công';
              $this->getUser()->setFlash('success', $message);
            } else {
              $message = 'Áp dụng lịch thất bại';
              $this->getUser()->setFlash('error', $message);
            }
            $this->redirect('detailProgram', ['id' => $id]);
          } else {
            $transId = rand(100000000,999999999);
            //luu id lich vao session
//            $this->getUser()->setAttribute(sprintf('autosms.transId.%s', $transId), $id);

            //truong hop khong phai la sub se redirect sang mps de tru tien
            $mps = new MpsWS();
            $params = [
              'SUB' => 'AUTOSMS_DAILY', 'CATE' => '', 'ITEM' => 'qrcode',
              'SUB_CP' => 'ghd', 'CONT' => 'qrcode', 'PRICE' => 0,
              'PRO' => 'GHD', 'SER' => 'AutoSMS', 'REQ' => $transId.$id, 'MOBILE' => $msisdn
            ];
            $mpsUrl = $mps->getMpsUrl($params, MpsWS::CHARGE);
            $logger->info(sprintf("[executeDetail] %s|NOT SUB - REDIRECT MPS URL CHARGE: %s|mobile: %s", $id, $mpsUrl, $msisdn));
            $this->redirect($mpsUrl);
          }
        }else{
          $message = 'Dữ liệu không hợp lệ';
          $this->getUser()->setFlash('error', $message);
          $this->redirect('detailProgram', ['id' => $id]);
        }
      }
    }else{
      $this->error = $detail['message'];
    }


  }

  public function executeAjaxCreate(sfWebRequest $request){
    $this->getResponse()->setContentType('application/json; charset=utf-8');
    $form = new CreateProgramForm();
    $form->bind($request->getParameter($form->getName()));
    if($form->isValid()){
      $formValues = $form->getValues();
      //todo: goi ws tao lich
      $autoSms = new AutosmsWS();
      $startTime = date('YmdHis', strtotime($formValues['start_time']));
      $endTime = date('YmdHis', strtotime($formValues['end_time']));
      $content = removeSignClass::removeSignOnly($formValues['content']);
      $timeStr = str_replace(" ", " ngay ", date("H:i d/m/Y", strtotime($formValues['end_time'])));
      $timeStr = str_replace(":", "h", $timeStr);
      $content = str_replace("hh:mm dd/mm", $timeStr, $content);
      $result =$autoSms->createSchedule($content,$startTime, $endTime);
      if($result['errorCode'] == 0){
        $errorCode = 0;
        $message = 'Khởi tạo thành công';
        $id = $result['id'];

        //tao qrcode
        $contentQr = $this->generateUrl('detailProgram', ['id' => $id], true);
        $qrcode = new ProcessQrCode($contentQr);
        $qrCodeImg = $qrcode->writeDataUri();

        $template = $this->getPartial('Homepage/tempSuccess', [
          'form' => $form, 'qrCodeImg' => $qrCodeImg, 'id' => $id,
          'content' => $content,
          'startTime' => $formValues['start_time'],
          'endTime' => $formValues['end_time']
        ]);
      }else{
        $errorCode = 2;
        $message = 'Khởi tạo thất bại';
        $template = $this->getPartial('Homepage/tempCreate', ['form' => $form]);
      }
    }else{
      $errorCode = 1;
      $message = 'Dữ liệu không hợp lệ';
      $template = $this->getPartial('Homepage/tempCreate', ['form' => $form]);
    }

    return $this->renderText(json_encode([
      'errorCode' => $errorCode,
      'message' => $message,
      'template' => $template
    ]));
  }

  public function executeMpsResult(sfWebRequest $request){
    $logger = VtHelper::getLogger4Php('all');
    $dataResponse = $request->getParameter('DATA');
    $sign = $request->getParameter('SIG');
    if(!$dataResponse || !$sign){
      $this->redirect('homepage');
    }

    $logger->info(sprintf("=========== BEGIN MPS RESULT =============="));
    $logger->info('[executeMpsResult] request: '.json_encode($request->getGetParameters()));
    $mps = new MpsWS();
    $dataDecrypt = $mps->decryptData($dataResponse, $sign);
    $logger->info(sprintf("[executeMpsResult] DECRYPT SUCCESS|data: %s", json_encode($dataDecrypt)));
    $response = $dataDecrypt['RES'];
    $transId = $dataDecrypt['REQ'];
    $id = substr($transId,9,3);
    $cmd = $dataDecrypt['CMD'];
    $msisdn = $dataDecrypt['MOBILE'];
    $errorCode = 1;
    $this->schedule = null;

    if($cmd == 'DOWNLOAD') {
//      $id = $this->getUser()->getAttribute(sprintf('autosms.transId.%s',$transId));
      $logger->info(sprintf("[executeMpsResult] CMD=DOWNLOAD|GET ID SCHEDULE| data: %s|id: %s", json_encode($dataDecrypt), $id));
//      $this->getUser()->setAttribute(sprintf('autosms.transId.%s',$transId), null);
      if(!$id) {
        $logger->info(sprintf("[executeMpsResult] CMD=DOWNLOAD|CANNOT GET ID IN SESSION| data: %s", json_encode($dataDecrypt)));
        $this->redirect('homepage');
      }

      if ($response == 0) {
        if (!empty($msisdn) && strtolower($msisdn) != 'null') {
          $autoSms = new AutosmsWS();
          $result = $autoSms->applySchedule($id, $msisdn);
          $logger->info(sprintf("[executeMpsResult] CMD=DOWNLOAD|APPLY SCHEDULE| data: %s|id: %s|result: %s", json_encode($dataDecrypt), $id, json_encode($result)));
          if ($result['errorCode'] == 0) {
            $message = 'Áp dụng lịch thành công';
            $errorCode = 0;
            $detail = $autoSms->detailSchedule($id);
            if($detail['errorCode'] == 0){
              $this->schedule = $detail['data'];
            }
          } else {
            $message = 'Áp dụng lịch thất bại';
          }
        } else {
          $message = 'Không nhận diện được thuê bao, Quý khách vui lòng thử lại sau';
        }
      } else {
        $message = $mps->getMessageByErrorCode($response);
      }
    }else{
//      $id = $this->getUser()->getAttribute(sprintf('autosms.detect.transId.%s',$transId));
      $logger->info(sprintf("[executeMpsResult] CMD=MSISDN|GET ID SCHEDULE| data: %s|id: %s", json_encode($dataDecrypt), $id));
//      $this->getUser()->setAttribute(sprintf('autosms.detect.transId.%s',$transId), null);
//      if(!$id) {
//        $logger->info(sprintf("[executeMpsResult] CMD=MSISDN|CANNOT GET ID IN SESSION| data: %s", json_encode($dataDecrypt)));
//        $this->redirect('homepage');
//      }

      if (!empty($msisdn) && strtolower($msisdn) != 'null') {
        //truong hop nhan dien dươc thue bao
        //kiem tra thue bao co phai la sub không
//        $isSub = $response == 205 ? true : false;
//
//        //luu thong tin nhan dien vao session
//        $this->getUser()->setAttribute('autosms.detectMobile', [
//          'msisdn' => $msisdn,
//          'isSub' => $isSub
//        ]);
        $url = $this->generateUrl('detailProgram', ['id' => $id, 'SIG' => $sign, 'DATA' => $dataResponse]);
        $this->redirect($url);
      }else{
        $message = 'Không nhận diện được thuê bao, Quý khách vui lòng thử lại sau';
      }
    }

    $this->errorCode = $errorCode;
    $this->message = $message;
  }

  public function executeDownload(sfWebRequest $request){
    $id = $request->getParameter('id');
    $fileName = 'qrcode_'.uniqid().'.png';
    $filePath = sfConfig::get('sf_log_dir').'/'.$fileName;
    $content = $this->generateUrl('detailProgram', ['id' => $id], true);
    $qrcode = new ProcessQrCode($content);
    $qrcode->writeFile($filePath);

    if(!file_exists($filePath)){ // file does not exist
      die('file not found');
    } else {
      $detect = new Mobile_Detect();
      if($detect->isMobile() || $detect->isTablet()) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/force-download');
//    header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));
        ob_clean();
        flush();
        readfile($filePath);
        unlink($filePath);
        exit();
      }else{
        header("Content-type: image/png");
        header("Pragma: public");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header(sprintf('Content-Disposition: attachment; filename="%s"',$fileName));
        ob_end_clean();
        ob_start();
        readfile($filePath);
        $size = ob_get_length();
        header("Content-Length: $size");
        ob_end_flush();
        unlink($filePath);
        die;
      }
    }
  }
}
