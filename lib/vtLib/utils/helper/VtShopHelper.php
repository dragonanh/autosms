<?php

/**
 * Created by PhpStorm.
 * User: anhbhv
 * Date: 04/05/2016
 * Time: 5:09 CH
 */
class VtShopHelper
{
  public static function generateOrderUrl($url, $pOrder, &$urlAsc, &$urlDesc, &$cOrderAsc, &$cOrderDesc){
    $cOrderAsc = $cOrderDesc = '';
    $urlAsc = $urlDesc = $url;
    if($pOrder){
      if($pOrder == 'asc'){
        $cOrderAsc = 'order-active';
        $urlDesc = (strpos($url,'?') !== false) ? $url.'&' : $url.'?';
        $urlDesc .= 'p_order=desc';
      }elseif($pOrder == 'desc'){
        $urlAsc = (strpos($url,'?') !== false) ? $url.'&' : $url.'?';
        $urlAsc .= 'p_order=asc';
        $cOrderDesc = 'order-active';
      }
    }else{
      $url = (strpos($url,'?') !== false) ? $url.'&' : $url.'?';
      $urlAsc = $url.'p_order=asc';
      $urlDesc = $url.'p_order=desc';
    }
  }

  public static function deleteImage($newImage, $oldImage, $oldPath){
    $oldFile = explode('.', $oldImage);
    $oldFilePathCache = sfConfig::get('sf_web_dir') . '/cache' . $oldFile[0];
    if ($newImage && $newImage != $oldImage) {
      if (file_exists($oldPath)) {
        unlink($oldPath);
        array_map('unlink', glob($oldFilePathCache . '*'));
      }
    }
  }

  /**
   * Ham phan loai sim
   * @author anhbhv
   * @param $phone
   * @param $price
   * @param $typeName
   * @param $typeId
   */
  public static function classifyPhoneNumber($phone,$price, &$typeName, &$typeId){
    $typeName = '';
    $typeId = 0;
    $last2Char = self::getLastCharInString($phone, 2);
    $last3Char = self::getLastCharInString($phone, 3);
    $last4Char = self::getLastCharInString($phone, 4);
    $last5Char = self::getLastCharInString($phone, 5);
    $last6Char = self::getLastCharInString($phone, 6);
    if(preg_match('/^(.)\1*$/', $last6Char)){
      $typeName = 'Sim lục quý';
      $typeId = SimTypeEnum::SIM_LUC_QUY;
    }elseif(preg_match('/^(.)\1*$/', $last5Char)){
      $typeName = 'Sim ngũ quý';
      $typeId = SimTypeEnum::SIM_NGU_QUY;
    }elseif(preg_match('/^(.)\1*$/', $last4Char)){
      $typeName = 'Sim tứ quý';
      $typeId = SimTypeEnum::SIM_TU_QUY;
    }elseif(preg_match('/^(.)\1*$/', $last3Char)){
      $typeName = 'Sim tam hoa';
      $typeId = SimTypeEnum::SIM_TAM_HOA;
    }elseif(strpos("123456789",$last3Char) !== false){
      $typeName = 'Sim số tiến';
      $typeId = SimTypeEnum::SIM_SO_TIEN;
    }elseif(in_array(substr($last4Char, 0,3), array('199','198','197'))){
      $typeName = 'Sim năm sinh';
      $typeId = SimTypeEnum::SIM_NAM_SINH;
    }elseif($last2Char == '68'){
      $typeName = 'Sim lộc phát';
      $typeId = SimTypeEnum::SIM_LOC_PHAT;
    }elseif($last2Char == '86'){
      $typeName = 'Sim phát lộc';
      $typeId = SimTypeEnum::SIM_PHAT_LOC;
    }elseif (in_array($last2Char, array('39', '79'))) {
      $typeName = 'Sim thần tài';
      $typeId = SimTypeEnum::SIM_THAN_TAI;
    } elseif (in_array($last2Char, array('78', '38'))) {
      $typeName = 'Sim ông địa';
      $typeId = SimTypeEnum::SIM_ONG_DIA;
    }elseif(substr($last6Char,0,3) == $last3Char) {
      $typeName = 'Sim taxi lặp 3';
      $typeId = SimTypeEnum::SIM_TAXI_3;
    }elseif(substr($last6Char,0,2) == substr($last6Char,2,2) && substr($last6Char,2,2) == $last2Char) {
      $typeName = 'Sim taxi lặp 2';
      $typeId = SimTypeEnum::SIM_TAXI_2;
    }elseif(preg_match('/(.)\1{1,1}/', $last2Char)){
      $typeName = 'Sim số kép';
      $typeId = SimTypeEnum::SIM_SO_KEP;
    }elseif(substr($last4Char,0,2) == $last2Char) {
      $typeName = 'Sim số lặp';
      $typeId = SimTypeEnum::SIM_SO_LAP;
    }elseif($last3Char[0] == $last3Char[2]) {
      $typeName = 'Sim số gánh';
      $typeId = SimTypeEnum::SIM_SO_GANH;
    }elseif(substr($last4Char,0,2) == strrev($last2Char)){
      $typeName = 'Sim số đảo';
      $typeId = SimTypeEnum::SIM_SO_DAO;
    }elseif($price < 250000){
      $typeName = 'Sim bình dân';
      $typeId = SimTypeEnum::SIM_BINH_DAN;
    }else{
      $typeName = 'Sim đặc biệt';
      $typeId = SimTypeEnum::SIM_DAC_BIET;
    }
  }

  /**
   * Ham tinh diem sim
   * @author anhbhv
   * @param $phone
   * @return array
   */
  public static function calculationSimPoint($phone){
    $originalPhone = $phone;
    $phone = VtHelper::getMobileNumber($phone, VtHelper::MOBILE_SIMPLE);
    $last2Char = self::getLastCharInString($phone, 2);
    $last3Char = self::getLastCharInString($phone, 3);
    $last4Char = self::getLastCharInString($phone, 4);
    $last5Char = self::getLastCharInString($phone, 5);
    $last6Char = self::getLastCharInString($phone, 6);
    $last7Char = self::getLastCharInString($phone, 7);
    $result = array(
      'msisdn'=>0,'duoi'=>0,'kep4so'=>0,'kep3so'=>0,'kep2so'=>0,'kepduoi'=>0,'tamhoa'=>0,'tamhoadacbiet'=>0,'dao'=>0,'duoi4dep'=>0,'duoi3dep'=>0,'duoi2dep'=>0,
      'sotien'=>0,'lapcach'=>0,'lapcach2'=>0,'lapcach3'=>0,'lapcach4'=>0,'lap3dacbiet'=>0,'lapdoi'=>0,'lapdoidacbiet'=>0,'lap2duoi'=>0,'namsinh'=>0,'namsinh2'=>0,
      'abcdbc'=>0,'abbccd'=>0,'aabccd'=>0,'abcabd'=>0,'abccba'=>0,'ganh2dau'=>0,'lap1'=>0,'lap2'=>0,'khac'=>0,'aaabbb'=>0,'abbcbb'=>0,'baabaa'=>0,
      'aabaab'=>0,'abcabc'=>0,'a68a68'=>0,'98b98b'=>0,'tong'=>0
    );

    if(preg_match('/(.)\1{3,3}/', $phone))
      $result['kep4so'] = 1;
    if(preg_match('/(.)\1{2,2}/', $phone))
      $result['kep3so'] = 1;
    if(preg_match('/(.)\1{1,1}/', $phone))
      $result['kep2so'] = 1;
    if(preg_match('/(.)\1{1,1}/', $last2Char))
      $result['kepduoi'] = 1;
    if(preg_match('/^(.)\1*$/', $last3Char)){
      $result['tamhoa'] = 1;
      preg_match_all('/(.)(\1)(\1)?/', substr($last7Char,0,4), $match);
      if(count($match[0]) == 2)
        $result['tamhoadacbiet'] = 1;
    }
    if(substr($last4Char,0,2) == strrev($last2Char))
      $result['dao'] = 1;
    if(in_array($last4Char, array('6789','1234','2345','3456','5678','3579','2468')))
      $result['duoi4dep'] = 1;
    if(strpos("123456789",$last3Char) !== false || in_array($last3Char, array('368','168','102','579','246','357','468','389','568','188')))
      $result['duoi3dep'] = 1;
    if(in_array($last2Char, array('86','68','79','89','99','88')))
      $result['duoi2dep'] = 1;
    if(strpos("0123456789",substr($last6Char,0,5)) !== false)
      $result['sotien'] = 1;
    if($last6Char[0] == $last6Char[2] && $last6Char[2] == $last6Char[4]){
      if($last6Char[5] > $last6Char[3]) {
        $result['lapcach'] = 1;
        if($last6Char[3] > $last6Char[1])
          $result['lapcach2'] = 1;
      }
    }
    if($last6Char[1] == $last6Char[3] && $last6Char[3] == $last6Char[5]){
      if($last6Char[4] > $last6Char[2]) {
        $result['lapcach3'] = 1;
        if($last6Char[2] > $last6Char[0])
          $result['lapcach4'] = 1;
      }
    }
    if(substr($last6Char,0,2) == substr($last6Char,2,2) && substr($last6Char,2,2) == $last2Char)
      $result['lap3dacbiet'] = 1;
    if ($last6Char[0] == $last6Char[1] && $last6Char[2] == $last6Char[3] && $last6Char[4] == $last6Char[5]) {
      $result['lapdoi'] = 1;
      if($last6Char[0] == $last6Char[4])
        $result['lapdoidacbiet'] = 1;
    }
    if(($last4Char[0] == $last4Char[1] && $last4Char[2] == $last4Char[3]) || (substr($last4Char,0,2) == $last2Char))
      $result['lap2duoi'] = 1;
    if($last4Char > 1970 && $last4Char < date('Y'))
      $result['namsinh'] = 1;
    if((in_array($last6Char[0], array('0','1','2')) && $last6Char[2] == 0 && in_array($last6Char[4], array('6','7','8','9')))
      || (in_array($last6Char[0], array('0','1','2')) && in_array($last6Char[3], array('0','1','2')) && $last6Char[2] == 1 && in_array($last6Char[4], array('6','7','8','9'))))
      $result['namsinh2'] = 1;
    if($last6Char[1].$last6Char[2] == $last6Char[4].$last6Char[5]) {
      $result['abcdbc'] = 1;
      if($last6Char[1] == $last6Char[2])
        $result['abbcbb'] = 1;
    }
    if($last6Char[1] == $last6Char[2] && $last6Char[3] == $last6Char[4])
      $result['abbccd'] = 1;
    if($last6Char[0]==$last6Char[1] && $last6Char[3]==$last6Char[4])
      $result['aabccd'] = 1;
    if(substr($last6Char,0,2) == substr($last6Char,3,2))
      $result['abcabd'] = 1;
    if(substr($last6Char,0,3) == strrev($last3Char))
      $result['abccba'] = 1;
    if($last3Char[0]==$last3Char[2])
      $result['lap1'] = 1;
    if($last4Char[0]==$last4Char[2] && $last4Char[3]>$last4Char[1])
      $result['lap2'] = 1;
    if(preg_match('/(.)\1{4,4}/', substr($last7Char,0,5)) || preg_match('/(.)\1{4,4}/', substr($last7Char,1,5)))
      $result['khac'] = 1;
    if(preg_match('/^(.)\1*$/', substr($last6Char,0,3)) && preg_match('/^(.)\1*$/', $last3Char))
      $result['aaabbb'] = 1;
    if(substr($last6Char,0,3) == $last3Char) {
      $result['abcabc'] = 1;
      if($last6Char[1] == $last6Char[2])
        $result['baabaa'] = 1;
      if($last6Char[0] == $last6Char[1])
        $result['aabaab'] = 1;
      if($last2Char == '68')
        $result['a68a68'] = 1;
      if(substr($last3Char,0,1) == '98')
        $result['98b98b'] = 1;
    }

    if(in_array(substr($phone,0,3), array('098','096','086')) && (substr($phone,0,5) == $last5Char || (substr($phone,0,4) == $last4Char && $last6Char[0] == $last6Char[1])))
      $result['ganh2dau'] = 1;
    $result['tong'] = array_sum($result);
    $result['duoi'] = $last2Char;
    $result['msisdn'] = $originalPhone;

    return $result;
  }

  public static function classifyBeautySim($phone){
    $originalPhone = $phone;
    $phone = VtHelper::getMobileNumber($phone, VtHelper::MOBILE_SIMPLE);
    $last1Char = self::getLastCharInString($phone, 1);
    $last2Char = self::getLastCharInString($phone, 2);
    $last3Char = self::getLastCharInString($phone, 3);
    $last4Char = self::getLastCharInString($phone, 4);
    $last5Char = self::getLastCharInString($phone, 5);
    $last6Char = self::getLastCharInString($phone, 6);
    $last7Char = self::getLastCharInString($phone, 7);
    if(strpos("0123456789",$last5Char) !== false)
      $type = 'Tiến 5 số đuôi';
    elseif(preg_match('/^(.)\1*$/', $last4Char)){
      $type = sprintf('Tứ quý %s', $last1Char);
      if(preg_match('/^([6-9])\1*$/', $last4Char) && ($phone[5]==$phone[6] || substr($phone, 2,4) == '8888' || preg_match('/^(.)\1*$/', substr($phone,3,3))))
        $type = sprintf('Tứ quý %s (Dạng đặc biệt)', $last1Char);
      elseif(preg_match('/^([0-5])\1*$/', $last4Char) && (preg_match('/^(.)\1*$/', substr($phone,2,4))))
        $type = sprintf('Tứ quý %s (Dạng đặc biệt)', $last1Char);
    }elseif(in_array($last4Char, array('6688','6868'))){
      $type = sprintf('Đuôi %s', $last4Char);
      if($phone[5]==$phone[6] || substr($phone, 2,4) == '8888')
        $type = sprintf('Đuôi %s (Dạng đặc biệt)', $last4Char);
    }elseif(strpos("23456789",$last4Char) !== false || in_array($last4Char, array('3579','2468'))){
      $type = sprintf('Tiến 4 số đuôi %s', $last4Char);
      if($last4Char == '6789' && preg_match('/^(.)\1*$/', substr($phone,3,3)))
        $type = sprintf('Tiến 4 số đuôi %s (Dạng đặc biệt)', $last4Char);
    }elseif(in_array($last4Char, array('7979','7799')))
      $type = sprintf('Số đuôi %s', $last4Char);
    elseif(preg_match('/^(.)\1*$/', substr($last6Char,0,3)) && preg_match('/^(.)\1*$/', $last3Char)){
      $type = 'Chập 3 theo từng đôi';
      if(in_array($last1Char, array('8','9')))
        $type = 'Chập 3 theo từng đôi (Dạng đặc biệt)';
    }elseif(substr($last6Char,0,3) == $last3Char) {
      $type = 'Chập 3 theo từng đôi';
      if(in_array($last3Char, array('168','889','899','668','688')) || strpos("456789",$last3Char) !== false || $last3Char[0] == $last3Char[1] || $last3Char[1] == $last3Char[2] || substr($last3Char,0,2) == '98')
        $type = 'Chập 3 theo từng đôi (Dạng đặc biệt)';
    }elseif(substr($last6Char,0,2) == substr($last6Char,2,2) && substr($last6Char,2,2) == $last2Char) {
      $type = 'Lặp từng đôi giống nhau 3 cặp';
      if(in_array($last2Char, array('68','79','39','69')))
        $type = 'Lặp từng đôi giống nhau 3 cặp (Dạng đặc biệt)';
    }elseif($last6Char[0] == $last6Char[1] && $last6Char[2] == $last6Char[3] && $last6Char[4] == $last6Char[5]){
      $type = 'Lặp từng đôi không giống nhau 3 cặp';
      if(strpos("456789",$last6Char[0].$last6Char[2].$last6Char[4]) !== false){

      }
    }
  }

  /**
   * Ham lay ky tu cuoi chuoi ($numCharGet: so luong ky tu can lay)
   * @author anhbhv
   * @param $string
   * @param $numCharGet
   * @return string
   */
  public static function getLastCharInString($string, $numCharGet){
    return strlen($string) > $numCharGet ? substr($string, -$numCharGet) : $string;
  }

  public static function sendMailOrder($orderInfo){
    $mailTo = VtHelper::getSystemSetting('email');
    $filePath = sfConfig::get('app_email_order_path');
    $myFile = "$filePath";
    $fh = fopen($myFile, 'r');
    $theData = fread($fh, filesize($myFile));
    fclose($fh);
    $body_email = VtHelper::strip_html_default_tags($theData);
    $listSimTemp = '';
    $totalMoney = $stt =0;
    foreach ($orderInfo['list_sim'] as $key => $sim){
      $listSimTemp .= sprintf('<tr><td style="padding: 5px 15px;border: 1px solid #ddd;">%s</td><td style="padding: 5px 15px;border: 1px solid #ddd;">%s</td><td style="padding: 5px 15px;border: 1px solid #ddd;">%s</td></tr>',++$stt,$sim['sim_number'],$sim['price']);
      $totalMoney += $sim['price'];
    }
    $tempSim = '<table style="border-collapse: collapse;border-spacing: 0;border: 1px solid #ddd;">
                    <thead>
                      <tr>
                          <th style="padding: 5px 15px;border: 1px solid #ddd;">STT</th>
                          <th style="padding: 5px 15px;border: 1px solid #ddd;">Số sim</th>
                          <th style="padding: 5px 15px;border: 1px solid #ddd;">Giá tiền</th>
                      </tr>
                    </thead>
                    <tbody>'.$listSimTemp.'</tbody>
                    <tfoot><tr><td style="padding: 5px 15px;border: 1px solid #ddd;" colspan="2">Tổng tiền</td><td colspan="2" style="padding: 5px 15px;border: 1px solid #ddd;">'.$totalMoney.'</td></tr></tfoot>
                </table>';
    $body_email=str_replace('{simOrder}',$tempSim,$body_email);
    $body_email=str_replace('{customerName}',$orderInfo['full_name'],$body_email);
    $body_email=str_replace('{phone}',$orderInfo['phone_number'],$body_email);
    $cityArr = CityTable::getListCityArray();
    $body_email=str_replace('{city}',$cityArr[$orderInfo['city_id']],$body_email);
    $body_email=str_replace('{address}',$orderInfo['address'],$body_email);
    $body_email=str_replace('{note}',$orderInfo['note'],$body_email);
    return VtHelper::SendEmail($mailTo,'Thông báo: Có đơn đặt hàng trên website',$body_email);
  }
}