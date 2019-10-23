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
    $params = [
      'SUB' => 'AUTOSMS_DAILY', 'CATE' => 'autosms', 'ITEM' => 'qrcode',
      'SUB_CP' => 'ghd', 'CONT' => 'qrcode', 'PRICE' => 0,
      'REQ' => '', 'PRO' => 'GHD', 'SER' => 'AutoSMS'
    ];
    $mps = new MpsWS();
    $mpsUrl = $mps->getMpsChargeUrl($params);
    var_dump($mpsUrl);die;
    $this->redirect($mpsUrl);
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
      $this->error = null;
      $schedule = $detail['data'];
      $schedule['start_time'] = date('d-m-Y H:i:s', strtotime($schedule['start_time']));
      $schedule['end_time'] = date('d-m-Y H:i:s', strtotime($schedule['end_time']));
      $this->form = new CreateProgramForm(null, ['schedule' => $schedule]);
      $this->id = $id;

      if($request->isMethod('post')){
        $token = $request->getParameter('token');
        if($token == $this->form->getCSRFToken()){
          $params = [
            'SUB' => 'AUTOSMS_DAILY', 'CATE' => 'autosms', 'ITEM' => 'qrcode',
            'SUB_CP' => 'ghd', 'CONT' => 'qrcode', 'PRICE' => 0,
            'REQ' => '', 'PRO' => 'GHD', 'SER' => 'AutoSMS'
          ];
          $mps = new MpsWS();
          $mpsUrl = $mps->getMpsChargeUrl($params);
          $this->redirect($mpsUrl);
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
      $result =$autoSms->createSchedule($formValues['content'],$startTime, $endTime);
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

  }
}
