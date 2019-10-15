<?php

/**
 * Created by PhpStorm.
 * User: tuanbm2
 * Date: 27/10/2015
 * Time: 2:32 CH
 */
class VtRegisterAddonWS {

    public static function registerAddOn($msisdn, $packageName, $isPromotion = 0, $sendSms = 1) {
        $logger = VtHelper::getLogger4Php("all");
        $wsdl = sfConfig::get('sf_app_config_dir') . DIRECTORY_SEPARATOR . "register3g.wsdl";
        $method = sfConfig::get("app_addon_method", "registerAddOn");
        $options = array(
            'connect_timeout' => sfConfig::get('app_radius_connect_timeout', 5),
            'timeout' => sfConfig::get('app_radius_timeout', 5),
            'cache_wsdl' => WSDL_CACHE_NONE,
        );
        try {
            $params = array();
            $params['user'] = sfConfig::get('app_addon_username');
            $params['password'] = sfConfig::get('app_addon_password');
            $params['msisdn'] = $msisdn;
            $params['packageName'] = $packageName;
            $params['isPromotion'] = $isPromotion;
            $params['sendSms'] = $sendSms;
            $client = new TimeoutSoapClient($wsdl, $options);
            $response = $client->__soapCall($method, array($params));
            $stdClass = $response->return;
            $params['password'] = "******";
            $logger->info("[CALL_WEBSERVICE] method=[" . $method . "] params = [" . var_export($params, true) . "]| result=[" . var_export($stdClass, true) . "]");
            return $stdClass;
        } catch (Exception $e) {
            $logger->info("[CALL_WEBSERVICE][ERROR]  method=[" . $method . "] params = [" . var_export($params, true) . "]| exception=[" . var_export($e->getTraceAsString(), true) . "|message=" . $e->getMessage() . "]");
            return null;
        }
    }


    public static function checkPackage($msisdn) {
        $logger = VtHelper::getLogger4Php("all");
        $wsdl = sfConfig::get('sf_app_config_dir') . DIRECTORY_SEPARATOR . "register3g.wsdl";
        $method = "checkData3gV2";
        //sfConfig::get("app_addon_method", "registerAddOn");
        $options = array(
            'connect_timeout' => sfConfig::get('app_radius_connect_timeout', 5),
            'timeout' => sfConfig::get('app_radius_timeout', 5),
            'cache_wsdl' => WSDL_CACHE_NONE,
        );
        try {
            $params = array();
            $params['user'] = sfConfig::get('app_addon_username');
            $params['password'] = sfConfig::get('app_addon_password');
            $params['msisdn'] = $msisdn;
            $client = new TimeoutSoapClient($wsdl, $options);
            $response = $client->__soapCall($method, array($params));
            $stdClass = $response->return;
            $params['password'] = "******";
            $logger->info("[CALL_WEBSERVICE] method=[" . $method . "] params = [" . var_export($params, true) . "]| result=[" . var_export($stdClass, true) . "]");
            return $stdClass;
        } catch (Exception $e) {
            $logger->info("[CALL_WEBSERVICE][ERROR]  method=[" . $method . "] params = [" . var_export($params, true) . "]| exception=[" . var_export($e->getMessage(), true) . "|message=" . $e->getMessage() . "]");
            return null;
        }
    }

    public static function register3g($msisdn, $packageName, $sendSms = 1) {
        $logger = VtHelper::getLogger4Php("all");
        $wsdl = sfConfig::get('sf_app_config_dir') . DIRECTORY_SEPARATOR . "register3g.wsdl";
        $method = "registerData";
            //sfConfig::get("app_addon_method", "registerAddOn");
        $location = "http://192.168.176.213:8788/WebServices/DataWS";
        $options = array(
            'connect_timeout' => 20,
            'timeout' => 20,
            'cache_wsdl' => WSDL_CACHE_NONE,
            'location' => $location,
        );
        try {
            $params = array();
            $params['user'] = sfConfig::get('app_addon_username');
            $params['password'] = sfConfig::get('app_addon_password');
            $params['msisdn'] = $msisdn;
            $params['pkgName'] = $packageName;
            $params['sendMt'] = $sendSms;
            $params['requestId'] = uniqid();
            $client = new TimeoutSoapClient($wsdl, $options);
            $response = $client->__soapCall($method, array($params));
            $stdClass = $response->return;
            $params['password'] = "******";
            $logger->info("[CALL_WEBSERVICE] method=[" . $method . "] params = [" . var_export($params, true) . "]| result=[" . var_export($stdClass, true) . "]");
            return $stdClass;
        } catch (Exception $e) {
            $logger->info("[CALL_WEBSERVICE][ERROR]  method=[" . $method . "] params = [" . var_export($params, true) . "]| exception=[" . var_export($e->getMessage(), true) . "|message=" . $e->getMessage() . "]");
            return null;
        }
    }

    public static function register3g2($msisdn, $packageName, $sendSms = 1)
    {
        $loggerAll = VtHelper::getLogger4Php("all");
        $link_ws = "http://192.168.176.213:8788/WebServices/DataWS";
        $username = sfConfig::get('app_addon_username');
        $password = sfConfig::get('app_addon_password');
        $wsdl = "http://192.168.176.213:8788/WebServices/DataWS?wsdl";
        $location = "http://192.168.176.213:8788/WebServices/DataWS";

        try {
//            $params = array();
//            $params['user'] = sfConfig::get('app_addon_username');
//            $params['password'] = sfConfig::get('app_addon_password');
//            $params['msisdn'] = $msisdn;
//            $params['pkgName'] = $packageName;
////            $params['sendMt'] = $sendSms;
//            $params['requestId'] = uniqid();
//            $soap = new SoapClient($wsdl, array(
//                'location' => $location
//            ));
//            $res = $soap->__call("registerData", array($params));
//            var_dump($res);

            $client = new nusoap_client($link_ws, $wsdl);
            $client->operations = array("registerData");
            $xml = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ws="http://ws.dvgt.viettel.com/">'
                . '<soapenv:Header/>'
                . '<soapenv:Body>'
                . '<ws:registerData>'
                . '<user>TTVAS_OP</user>'
                . '<password>RD53@5datgfsgeopera</password>'
                . '<msisdn></msisdn>'
                . '<pkgName>Mimax1.5</pkgName>'
                . '<sendMt>1</sendMt>'
                . '<requestId>abcasdfsafdsa</requestId>'
                . '</ws:registerData>'
                . '</soapenv:Body>'
                . '</soapenv:Envelope>';
            $msg = $client->serializeEnvelope($xml);
            $result= $client->send($msg, $link_ws);
//
//            //$loggerAll->debug("[Radius_ws] wsdl=" . var_export($link_ws, true) . "|result=" . var_export($result, true));
              echo '<h2>Request</h2><pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
              echo '<h2>Response</h2><pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';
////            var_dump($result);
            die();
//
//            if ($result == false) {
//                return array('errorCode' => -1, 'message' => "");
//            }
        } catch (Exception $ex) {
            $loggerAll->error("[Radius_ws] wsdl=" .$ex->getMessage());
            return array('errorCode' => -1, 'message' => "Loi " . $ex->getMessage());
        }

    }


        //http://10.58.52.9/smsApi.php/registerYoutubePackage
        //params :
        //-	u : sms
        //-	p : SmS#@!456
        //-	phone : SĐT
        //-	syntax :
        //o	      T70
        //o	      T120
        //o	      T200
        //o	      T300
        //o	      MIMAX15
        //o	      T0
        //
    public static function register3g3($msisdn, $packageName, $source = 0, $sendSms = 1){
        $url = "http://10.58.52.9/smsApi.php/registerYoutubePackage";
        if($packageName=="Mimax1.5" || $packageName=="MIMAX1.5"){
            $packageName = "MIMAX15";
        }
        $data = array(
            "u"=>"sms",
            "p"=>"SmS#@!456",
            "phone"=>$msisdn,
            "syntax"=>$packageName,
            "channel"=>0,
            "source"=>$source,
        );
        $loggerAll = VtHelper::getLogger4Php("all");
        $loggerAll->info(var_export($data,true));
        $value = self::post_curl($url,$data);
        return $value;
    }

    static function post_curl($_url, $_data) {
        try{
            $mfields = '';
            foreach($_data as $key => $val) {
                $mfields .= $key . '=' . $val . '&';
            }
            rtrim($mfields, '&');
            $pst = curl_init();
            curl_setopt($pst, CURLOPT_URL, $_url);
            curl_setopt($pst, CURLOPT_POST, count($_data));
            curl_setopt($pst, CURLOPT_POSTFIELDS, $mfields);
            curl_setopt($pst, CURLOPT_RETURNTRANSFER, 1);
            $res = curl_exec($pst);
            curl_close($pst);
        }catch (Exception $ex){
            return json_encode(array("errorCode"=>-1,"message"=> "Loi goi WS"));
        }
        return $res;
    }

}
