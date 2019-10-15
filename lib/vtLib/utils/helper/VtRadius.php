<?php

/**
 * Created by PhpStorm.
 * User: tuanbm2
 * Date: 27/10/2015
 * Time: 2:32 CH
 */
class VtRadius {


    public static function getIsdn() {
        $isdn = self::getMsisdn();
        if($isdn!="unknown"){
            return VtHelper::getMobileNumber($isdn,VtHelper::MOBILE_NOTPREFIX);
        }
        return "unknown";
    }

    /**
     * Tra ve so dien thoai thue bao (Su dung 3G Header va Radius)
     * @created on 10 08, 2012
     * @author
     * @return string|unknown
     */
    public static function getMsisdn() {
//        $ip = self::getAgentIp(); Loi qua haproxy
        $loggerAll = VtHelper::getLogger4Php("all");
        $loggerRadius = VtHelper::getLogger4Php("radius");
        $ip = self::getRealIpAddr();
        if (self::isV_wapIp($ip)) {
            $isdn =  self::getMsisdnFromIp($ip);
            $loggerAll->info("From IP:".$ip. "=> isdn=".$isdn);
            $loggerRadius = VtHelper::getLogger4Php("radius");
            $loggerRadius->info($ip."|From Radius:".$isdn);
            return $isdn;
        }
        $loggerAll->debug("ip is not in ip_range allow: ".$ip);
        return 'unknown';
    }

    public static function getMsisdnFromHeader(){
        $loggerAll = VtHelper::getLogger4Php("all");
        $ip = self::getRealIpAddr();
        if (self::isV_wapIp($ip)) {
            if (isset($_SERVER['MSISDN'])) {
                $loggerAll->info('IP='.$ip.'&SERVER["MSISDN"]='.$_SERVER['MSISDN']);
                return $_SERVER['MSISDN'];
            }
            if (isset($_SERVER['HTTP_MSISDN'])) {
                $loggerAll->info('ip='.$ip.'&SERVER["HTTP_MSISDN"]='.$_SERVER['HTTP_MSISDN']);
                return $_SERVER['HTTP_MSISDN'];
            }
            $loggerAll->debug("Header is not exist isdn");
            $loggerRadius = VtHelper::getLogger4Php("radius");
            $loggerRadius->info($ip."|Header is not exist isdn");
        }else{
            $loggerAll->debug("getMsisdnFromHeader:ip is not in ip_range allow: ".$ip);
        }
        return "unknown";
    }

    public static function getIsdnFromHeader(){
        $loggerAll = VtHelper::getLogger4Php("all");
        $ip = self::getRealIpAddr();
        if (self::isV_wapIp($ip)) {
            if (isset($_SERVER['MSISDN'])) {
                $loggerAll->info('IP='.$ip.'&SERVER["MSISDN"]='.$_SERVER['MSISDN']);
                return VtHelper::getMobileNumber($_SERVER['MSISDN'],VtHelper::MOBILE_NOTPREFIX);
            }
            if (isset($_SERVER['HTTP_MSISDN'])) {
                $loggerAll->info('ip='.$ip.'&SERVER["HTTP_MSISDN"]='.$_SERVER['HTTP_MSISDN']);
                return VtHelper::getMobileNumber($_SERVER['HTTP_MSISDN'],VtHelper::MOBILE_NOTPREFIX);
            }
            $loggerAll->debug("Header is not exist isdn");
            $loggerRadius = VtHelper::getLogger4Php("radius");
            $loggerRadius->info($ip."|Header is not exist isdn");
        }else{
            $loggerAll->debug("getIsdnFromHeader:ip is not in ip_range allow: ".$ip);
        }
        return "unknown";
    }

    /**
     * Ham kiem tra Ip co nam trong dai ip opera cua viettel khong
     * @author dungld5
     * @created on 17/01/2013
     * @param $ip
     * @return bool
     */
    public static function isV_opreraIp($ip) {
        $vInternetRange = sfConfig::get('app_ip_opera_mini_v_ip');
        if (!empty($vInternetRange)) {
            foreach ($vInternetRange as $range) {
                $netArr = explode("/", $range);
                if (self::ipInNetwork($ip, $netArr[0], $netArr[1])) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @author: dungld5
     * @created at : 10/02/2015
     * Kiem tra thue bao truy cap bang duong 3G hay wifi
     * @return boolean
     */
    public static function checkDCN() {
        if (sfConfig::get('app_check_3g') == 1) { // Kiem tra dai dcn
            $ip = self::getAgentIp();
            if (self::isV_wapIp($ip)) { // nam trong dai 3G
                return true;
            } else {
                if (self::isV_opreraIp($ip)) {
                    return true;
                } else {
                    return false; // Khong nam trong dai ip pool
                }
            }
        } else {
            return false; // khong check dai ip pool
        }
    }

    public static function getRealIpAddr()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) //to check ip passed from proxy
        {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    /**
     * Tra ve IP cua thue bao
     * @author ducda2@viettel.com.vn
     * @return IP
     */
    public static function getAgentIp() {
        // $ipString = @getenv("HTTP_X_FORWARDED_FOR");
        $ipString = @getenv("HTTP_X_REAL_IP");
        if (!empty($ipString)) {
            $addr = explode(",", $ipString);
            return $addr[0];
        } else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }

    /**
     * Ham kiem tra Ip co nam trong dai V-internet cua viettel khong
     * @author NamDT5
     * @created on 17/01/2013
     * @param $ip
     * @return bool
     */
    public static function isV_wapIp($ip) {
        $vInternetRange = sfConfig::get('app_ip_pool_v_wap');
        if (!empty($vInternetRange)) {
            foreach ($vInternetRange as $range) {
                $netArr = explode("/", $range);
                if (self::ipInNetwork($ip, $netArr[0], $netArr[1])) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Ham kiem tra IP co nam trong dai IP cho phep khong
     * Tham khao: http://php.net/manual/en/function.ip2long.php
     * @author NamDT5
     * @created on 17/01/2013
     * @param $ip
     * @param $netAddr
     * @param $netMask
     * @return bool
     */
    public static function ipInNetwork($ip, $netAddr, $netMask) {
        if ($netMask <= 0) {
            return false;
        }
        $ipBinaryString = sprintf("%032b", ip2long($ip));
        $netBinaryString = sprintf("%032b", ip2long($netAddr));
        return (substr_compare($ipBinaryString, $netBinaryString, 0, $netMask) === 0);
    }

    public static function getDeviceIp() {
        $ipString = @getenv("HTTP_X_FORWARDED_FOR");

        if (!empty($ipString)) {
            $addr = explode(",", $ipString);
            return $addr[0];
        } else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }


    public static function getMsisdnFromIp($ip)
    {
        $loggerAll = VtHelper::getLogger4Php("all");

//        $ip = self::getDeviceIp();
        if (!$ip)
        {
            return 'unknown';
        }else
        {
            $link_ws = sfConfig::get('app_radious_webservice');
            $username = sfConfig::get('app_radious_username');
            $password = sfConfig::get('app_radious_password');
            $options = array('connection_timeout' => 10);
            try
            {
                $client = new nusoap_client($link_ws, true);
                $client->operations = array("getMSISDN");

                $xml = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://viettel.com/xsd">'
                    .'<soapenv:Header/>'
                    .'<soapenv:Body>'
                    .'<getMSISDN>'
                    .'<username>'.$username.'</username>'
                    .'<password>'.$password.'</password>'
                    .'<ip>'.$ip.'</ip>'
                    .'</getMSISDN>'
                    .'</soapenv:Body>'
                    .'</soapenv:Envelope>';
                $msg = $client->serializeEnvelope($xml);
                $result=$client->send($msg, $link_ws);
                $loggerAll = VtHelper::getLogger4Php("all");
                $loggerAll->debug("[Radius_ws] wsdl=" . var_export($link_ws, true) . "|result=" . var_export($result, true));
//                echo '<h2>Request</h2><pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
//                echo '<h2>Response</h2><pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';
//                die();
                if($result==false){
                    $workload = array("ip"=>$ip,"result:"=>"GOI WS ERROR");
                    return 'unknown';
                }
                if($result["return"]["code"]==0){
                    $phonenumber = $result["return"]["desc"];
                    $workload = array("ip"=>$ip,"returnCode"=>$result["return"]["code"],"result:"=>$phonenumber);
//                    $phonenumber = self::removeCountryCodeOfPhoneNumber($phonenumber);
                    return $phonenumber;
                }else{
                    $error = $result["return"]["desc"];
                    $workload = array("ip"=>$ip,"result:"=>$error);
                    return 'unknown';
                };
            }
            catch (Exception $e)
            {
                // die("getMsisdn:" . $e->getMessage());
                if (sfConfig::get('sf_environment') == 'dev')
                {
                    echo 'Error ' . $e->getCode() . ': ' . $e->getMessage();
                }
                return 'unknown';
            }
        }
    }

    public static function current_millis() {
        list($usec, $sec) = explode(" ", microtime());
        return round(((float) $usec + (float) $sec) * 1000);
    }

}
