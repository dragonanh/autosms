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
    $transId = date('ymdHis').rand(10000,99999);
    $params = [
      'SUB' => 'AUTOSMS_DAILY', 'CATE' => 'autosms', 'ITEM' => 'qrcode',
      'SUB_CP' => 'ghd', 'CONT' => 'qrcode', 'PRICE' => 0,
      'PRO' => 'GHD', 'SER' => 'AutoSMS', 'REQ' => $transId
    ];
    $mps = new MpsWS();
//    $mpsUrl = $mps->getMpsUrl($params, MpsWS::MOBILE);
    $data = 'Jsf5isu9JkH1WBvHdZUrXMQtLBVHraXfaqDigLS9QBzCgNypOr2XFT0sbgj05YYYcsnZsk7gntMQ97eYJvj+d77FkxoFEl2CsNUXzkCMhLBMeU2fyVXlt4ERBkzwvUVB4dndQDtuKzS3LbNgBWVsYys5l13/Vi5XphS7mktYPqqx150ZonFQY1q+h61jZwWeaIyHr0DVB2H9t1Zm64FC0J3VsiH8RTCfKG6jtxHOGToV8eKlu2JmyxfxJgMv5TfxOG59MqziBhB8GGkhJUtK3qINyg5REsbmxXBRq/Y/9ejj37XYEiBZbJaeBYaoqVncbu7WJEPwrXajl7mjbgEWTzQaQ2fdiERRTLGyvXk8aXRwyq+Sqqx/gt2CsK27VlneMM3hE+yi54QayVYlwaDuccpgre/boV1bpPrYPqOfoxbJlJSeAhcuAjtCqp1xfzFV8YbCRiSYh1Wi7VuAj9qn7kFL7ZI1gaGeEKrgf2fM0vl5LYK6+Xc1J1h7nAWtCgXXjt5TUyGaH/T/FW1erOkX1P8ehJEF5emyvaqRkX7QPMTzk6+4atYysp6IhX3ue/61g7tWB/pV55XgYotsfJs8Q0cHrBtBPFHXqVEtJTgVDKlE7D0crZro4zu7nuFrvB9zeJmVLxbjR0O4PBjReRzZeTUD+7cWeoeLmBmUiYLGeIE=';
    $sign = 'JZ5fTh5n15PQEoC/a/N64VLANAx4IAq1gL4Jgso2U2EZQ3ijcqP59nFfPjWv+D2GIC/tL8Wgn4oZZr3U63qNsf3lUOxDsgv+bktrR8T7LsgHkOgd86tgdjiqHmBUPMNFTawZCQKWeVDO7FOWyCrMddm4fc3/Od+qgnHsAh4EPpc3HSiq/ZJ1WFwLoFprBBtwAxauWzVJcXhQ+LIusQDdh5UclRTC/hnszPvU7EPao9bh7Pmo3ZTixiJVQp7k0ARlIXVXGrEwNOM2+LLaB+RyKZyQRTKes4MzQs1EYUEAScBRFkSZc43AOD+7DipCvVWfzYT70+OmPUIhQKSg+pc4aXzwNupNXi9SqD9lDM2jIDFOxo7Ug3argZMXDLVeT5PrxMHTrUklkLJRtud+wmXe6EsWhOPkl7oyqMBOyL+beR42huhIkjNm2LlFnt+0EBLMsVHskWTXiSuzrSuU0Nq0O6mAKclZK8cPWqL8uSHtiqGq6zWvoxikPRvvDGWEBIZodM5wvDGdYvlJk+n+2XmyCxtjvsJmoU/qsjazixNKYTQw0y7tfZtm/smenS+WjtNiXn1AgjAD9qdOxfI0c587lsLOIVMDjP7B1z1VyBc8NhdFqTmznBU69W7SXRrgwSUZIiSvpciL89WtLKiBV9d+MgFTIaWMmVnoLNPoQN9VJfc=' ;
    $mps->decryptData($data, $sign);
//    var_dump($mpsUrl);
    die;
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
          $transId = date('ymdHis').rand(10000,99999);
          //luu id lich vao session
          $this->getUser()->setAttribute(sprintf('autosms.transId.%s',$transId), $id);
          $params = [
            'SUB' => 'AUTOSMS_DAILY', 'CATE' => 'autosms', 'ITEM' => 'qrcode',
            'SUB_CP' => 'ghd', 'CONT' => 'qrcode', 'PRICE' => 0,
            'PRO' => 'GHD', 'SER' => 'AutoSMS', 'REQ' => $transId
          ];
          $mps = new MpsWS();
          $mpsUrl = $mps->getMpsUrl($params, MpsWS::CHARGE);
          $mpsUrl = $this->generateUrl('mpsResult', ['RES' => 0, 'MOBILE' => '0354926551', 'REQ' => $transId]);
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
        $isdn = VtHelper::getMobileNumber($msisdn, VtHelper::MOBILE_NOTPREFIX);
        $autoSms = new AutosmsWS();
        $result = $autoSms->applySchedule($id, $isdn);

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
