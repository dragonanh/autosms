<?php

/**
 * Created by JetBrains PhpStorm.
 * User: tiennx6
 * Date: 8/19/14
 * Time: 9:23 AM
 * To change this template use File | Settings | File Templates.
 */
class SendBccs
{
//  const WSDL = 'http://10.60.34.8:8066/BCCSGateway?wsdl';
//  const USER = 'bccs_im';
//  const PASS = 'bccs_im';
    const SMS_SENDER = '1316';
    const SEND_SMSFLASH = 2;
    const SEND_SMSINBOX = 0;

    // call bccs
    public static function callBccsWs($paramsRawData)
    {
        $encrypt = new vtEncryption;
        $strValue ="<![CDATA[<proc:doReceiveRevenueOnBCCS>
                      <username>bccs_im</username>
                      <password>bccs_im</password>
                      <createUserCode>".$paramsRawData['createUserCode']."</createUserCode>
                      <custName>".$paramsRawData['custName']."</custName>
                      <companyName>".$paramsRawData['companyName']."</companyName>
                      <address>".$paramsRawData['address']."</address>
                      <tin>".$paramsRawData['tin']."</tin>
                      <telNumber>".$paramsRawData['telNumber']."</telNumber>
                      <transCode>".$paramsRawData['transCode']."</transCode>
                      <saleTransDate>".$paramsRawData['saleTransDate']."</saleTransDate>
                      <revenueObjectCode>".$paramsRawData['revenueObjectCode']."</revenueObjectCode>
                      <transType>".$paramsRawData['transType']."</transType>
                      <saleTransType>".$paramsRawData['saleTransType']."</saleTransType>
                      <lstRevenueItem>
                         <amount>".$paramsRawData['amount']."</amount>
                         <discountAmount>".$paramsRawData['discountAmount']."</discountAmount>
                         <stockModelCode>".$paramsRawData['stockModelCode']."</stockModelCode>
                      </lstRevenueItem>
                    </proc:doReceiveRevenueOnBCCS>]]>";

        $params = array(
            'username' => $encrypt->decode(sfConfig::get('app_bccs_user')),
            'password' => $encrypt->decode(sfConfig::get('app_bccs_password')),
            'wscode' => sfConfig::get('app_bccs_wscode'),
            'rawData' => $strValue
        );
        VtHelper::writeLogValue('Begin call bccs | url= '.sfConfig::get('app_bccs_wsdl').' | username = ' . sfConfig::get('app_bccs_user'). ' | password = ' . sfConfig::get('app_bccs_password'). ' | wscode = ' . sfConfig::get('app_bccs_wscode') .' | params: '.json_encode($paramsRawData), 'bccs_call.log');

        try {
            $soapClient = new SoapClient(sfConfig::get('app_bccs_wsdl'));
            $res = $soapClient->__soapCall('gwOperation', array($params));
            VtHelper::writeLogValue('Call success | response = ' . $res->error.'|'.$res->description, 'bccs_call.log');
            return $res->error;
        } catch (Exception $e) {
            VtHelper::writeLogValue('Call bccs fail | error = ' . $e->getMessage(), 'bccs_call.log');
            return -1;
        }
    }

}