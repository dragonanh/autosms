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

    $content = $this->generateUrl('detailProgram', ['id' => $id], true);
    $qrcode = new ProcessQrCode($content);
    $this->qrCodeImg = $qrcode->writeDataUri();
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
    //todo: lay thong tin lich bao ban tu ws
    $autoSms = new AutosmsWS();
    $detail = $autoSms->detailSchedule($id);
    if($detail['errorCode'] == 0){
      $logger->info(sprintf("[executeDetail] %s|GET DETAIL SUCCESS", $id));
      $mobileInfo = $this->getUser()->getAttribute('autosms.detectMobile');
      if(empty($mobileInfo) && (!$this->getUser()->hasFlash('error') && !$this->getUser()->hasFlash('success'))){
        $logger->info(sprintf("[executeDetail] %s|NOT INFO IN SESSION", $id));
        //truong hop khong co thong tin so dien thoai trong session
        //thuc hien redirect sang mps de nhan dien
        $mps = new MpsWS();
        $transId = date('ymdHis').rand(10000,99999);
        //luu id lich vao session
        $this->getUser()->setAttribute(sprintf('autosms.detect.transId.%s', $transId), $id);
        $params = [
          'SUB' => 'AUTOSMS_DAILY', 'PRO' => 'GHD',
          'SER' => 'AutoSMS', 'REQ' => $transId
        ];
        $urlRedirect = $mps->getMpsUrl($params, MpsWS::MOBILE);
        $logger->info(sprintf("[executeDetail] %s|URL MPS DETECT MOBILE: %s", $id, $urlRedirect));
        $this->redirect($urlRedirect);
      }

      $logger->info(sprintf("[executeDetail] %s|DETECT SUCCESS|%s", $id, json_encode($mobileInfo)));

      $this->error = null;

      $schedule = $detail['data'];
      $schedule['start_time'] = date('d-m-Y H:i:s', strtotime($schedule['start_time']));
      $schedule['end_time'] = date('d-m-Y H:i:s', strtotime($schedule['end_time']));
      $this->form = new CreateProgramForm(null, ['schedule' => $schedule, 'page' => 'detail']);
      $this->id = $id;

      if ($request->isMethod('post')) {
        $token = $request->getParameter('token');
        if ($token == $this->form->getCSRFToken()) {
          $msisdn = $mobileInfo['msisdn'];
          $isSub = $mobileInfo['isSub'];
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
            $transId = date('ymdHis').rand(10000,99999);
            //luu id lich vao session
            $this->getUser()->setAttribute(sprintf('autosms.transId.%s', $transId), $id);

            //truong hop khong phai la sub se redirect sang mps de tru tien
            $mps = new MpsWS();
            $params = [
              'SUB' => 'AUTOSMS_DAILY', 'CATE' => '', 'ITEM' => 'qrcode',
              'SUB_CP' => 'ghd', 'CONT' => 'qrcode', 'PRICE' => 0,
              'PRO' => 'GHD', 'SER' => 'AutoSMS', 'REQ' => $transId, 'MOBILE' => $msisdn
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
      $result =$autoSms->createSchedule(removeSignClass::removeSignOnly($formValues['content']),$startTime, $endTime);
      if($result['errorCode'] == 0){
        $errorCode = 0;
        $message = 'Khởi tạo thành công';
        $id = $result['id'];

        //tao qrcode
        $content = $this->generateUrl('detailProgram', ['id' => $id], true);
        $qrcode = new ProcessQrCode($content);
        $qrCodeImg = $qrcode->writeDataUri();

        $template = $this->getPartial('Homepage/tempSuccess', ['form' => $form, 'qrCodeImg' => $qrCodeImg, 'id' => $id]);
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
    $cmd = $dataDecrypt['CMD'];
    $msisdn = $dataDecrypt['MOBILE'];
    $errorCode = 1;
    $this->schedule = null;

    if($cmd == 'DOWNLOAD') {
      $id = $this->getUser()->getAttribute(sprintf('autosms.transId.%s',$transId));
      $logger->info(sprintf("[executeMpsResult] CMD=DOWNLOAD|GET ID SCHEDULE| data: %s|id: %s", json_encode($dataDecrypt), $id));
      $this->getUser()->setAttribute(sprintf('autosms.transId.%s',$transId), null);
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
      $id = $this->getUser()->getAttribute(sprintf('autosms.detect.transId.%s',$transId));
      $logger->info(sprintf("[executeMpsResult] CMD=MSISDN|GET ID SCHEDULE| data: %s|id: %s", json_encode($dataDecrypt), $id));
      $this->getUser()->setAttribute(sprintf('autosms.detect.transId.%s',$transId), null);
      if(!$id) {
        $logger->info(sprintf("[executeMpsResult] CMD=MSISDN|CANNOT GET ID IN SESSION| data: %s", json_encode($dataDecrypt)));
        $this->redirect('homepage');
      }

      if (!empty($msisdn) && strtolower($msisdn) != 'null') {
        //truong hop nhan dien dươc thue bao
        //kiem tra thue bao co phai la sub không
        $isSub = $response == 205 ? true : false;

        //luu thong tin nhan dien vao session
        $this->getUser()->setAttribute('autosms.detectMobile', [
          'msisdn' => $msisdn,
          'isSub' => $isSub
        ]);
        $url = $this->generateUrl('detailProgram', ['id' => $id]);
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
    $filePath = sfConfig::get('sf_log_dir').'/qrcode.png';
    $content = $this->generateUrl('detailProgram', ['id' => $id], true);
    $qrcode = new ProcessQrCode($content);
    $qrcode->writeFile($filePath);

    if(!file_exists($filePath)){ // file does not exist
      die('file not found');
    } else {
      header('Content-Description: File Transfer');
      header('Content-Type:  application/octet-stream');
      header('Content-Disposition: attachment; filename=qrcode.png');
      header('Content-Transfer-Encoding: binary');
      header('Expires: 0');
      header('Cache-Control: must-revalidate');
      header('Pragma: public');
      header('Content-Length: ' . filesize($filePath));
      ob_clean();
      flush();
      readfile($filePath);

      unlink($filePath);
      exit();
    }
  }
}
