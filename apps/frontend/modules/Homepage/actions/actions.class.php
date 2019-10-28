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
  public function executeIndex(sfWebRequest $request){

  }

  public function executeCreate(sfWebRequest $request){
    $this->form = new CreateProgramForm();
  }

  public function executeDetail(sfWebRequest $request){
    $id = $request->getParameter('id');

    if(!$id){
      $this->forward404();
    }

    //todo: lay thong tin lich bao ban tu ws
    $autoSms = new AutosmsWS();
    $detail = $autoSms->detailSchedule($id);
    if($detail['errorCode'] == 0){

      $transId = date('ymdHis').rand(10000,99999);
      $params = [
        'SUB' => 'AUTOSMS_DAILY', 'CATE' => 'autosms', 'ITEM' => 'qrcode',
        'SUB_CP' => 'ghd', 'CONT' => 'qrcode', 'PRICE' => 0,
        'PRO' => 'GHD', 'SER' => 'AutoSMS', 'REQ' => $transId
      ];

      $req = $request->getParameter('REQ');
      if(!$req) {
        //truong hop chua nhan dien so dien thoai
        //redirect sang mps de nhan dien so dien thoai
        $mps = new MpsWS();
        $urlRedirect = $mps->getMpsUrl($params, MpsWS::MOBILE);
        $this->redirect($urlRedirect);
      }

      $this->error = null;
      if(($msisdn = $request->getParameter('MOBILE'))) {
        $isSub = false;
        //kiem tra thue bao co phai la sub ko
        if( strpos( 'x', $msisdn ) === false) {
          $isSub = true;
        }

        $schedule = $detail['data'];
        $schedule['start_time'] = date('d-m-Y H:i:s', strtotime($schedule['start_time']));
        $schedule['end_time'] = date('d-m-Y H:i:s', strtotime($schedule['end_time']));
        $this->form = new CreateProgramForm(null, ['schedule' => $schedule]);
        $this->id = $id;

        if ($request->isMethod('post')) {
          $token = $request->getParameter('token');
          if ($token == $this->form->getCSRFToken()) {
            if($isSub){
              //truong hop la sub --> thuc hien dang ky luon
              $autoSms = new AutosmsWS();
              $result = $autoSms->applySchedule($id, $msisdn);

              if($result['errorCode'] == 0){
                $message = 'Áp dụng lịch thành công';
              }else{
                $message = 'Áp dụng lịch thất bại';
              }
            }else {
              //luu id lich vao session
              $this->getUser()->setAttribute(sprintf('autosms.transId.%s',$transId), $id);

              //truong hop khong phai la sub se redirect sang mps de tru tien
              $mpsUrl = $mps->getMpsUrl($params);
              $mpsUrl = $this->generateUrl('mpsResult', ['RES' => 0, 'MOBILE' => '0354926551', 'REQ' => $transId]);
              $this->redirect($mpsUrl);
            }
          }
        }
      }else{
        $this->error = 'Hệ thống không nhận diện được số điện thoại';
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

        $template = $this->getPartial('Homepage/tempSuccess', ['form' => $form, 'qrCodeImg' => $qrCodeImg]);
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
    $response = $request->getParameter('RES');
    $msisdn = $request->getParameter('MOBILE');
    $transId = $request->getParameter('REQ');
    $id = $this->getUser()->getAttribute(sprintf('autosms.transId.%s',$transId));
    $this->getUser()->setAttribute(sprintf('autosms.transId.%s',$transId), null);
    if(!$id) $this->redirect('homepage');

    if($response == 0){
      if($msisdn){
        $autoSms = new AutosmsWS();
        $result = $autoSms->applySchedule($id, $msisdn);

        if($result['errorCode'] == 0){
          $message = 'Áp dụng lịch thành công';
        }else{
          $message = 'Áp dụng lịch thất bại';
        }
      }else{
        $message = 'Không nhận diện được thuê bao, Quý khách vui lòng thử lại sau';
      }
    }else{
      $message = 'Thanh toán không thành công, Quý khách vui lòng thử lại sau';
    }

    $this->message = $message;
  }
}
