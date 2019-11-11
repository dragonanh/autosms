<?php
/**
 * Created by PhpStorm.
 * User: anhbhv
 * Date: 10/18/2019
 * Time: 9:19 AM
 */

class MpsWS
{
  private $aesKey;
  private $logger;

  const CHARGE = 1;
  const MOBILE = 2;

  public function __construct()
  {
    $z = 'abcdefghijuklmno0123456789012345';
    $aes = new AES($z);
    $encrypted = $aes->encrypt();
    $this->aesKey = bin2hex($encrypted);
    $this->logger = VtHelper::getLogger4Php('all');
  }

  public function encryptData($params, $type){
    $key_path = sfConfig::get('sf_root_dir').DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'key';
    $pub_key = file_get_contents($key_path.DIRECTORY_SEPARATOR.'PublicKeyVT.pem');
    $pri_key_cp = file_get_contents($key_path.DIRECTORY_SEPARATOR.'PrivateKeyCP.pem');

    switch ($type){
      case self::CHARGE:
        $data = sprintf('SUB=%s&CATE=%s&ITEM=%s&SUB_CP=%s&CONT=%s&PRICE=%s&REQ=%s&MOBILE=%s&SOURCE=WAP',
          $params['SUB'],$params['CATE'],$params['ITEM'],$params['SUB_CP'],$params['CONT'],$params['PRICE'],$params['REQ'],$params['MOBILE']);
        break;
      default:
        $data = sprintf('SUB=%s&SESS=%s&REQ=%s&SOURCE=WAP',
          $params['SUB'],session_id(),$params['REQ']);
    }
    $this->logger->info(sprintf('[encryptData] params before encrypt | data: %s', $data));
    $data = $this->pkcs5_pad($data, 16);
    //B1. Ma hoa du lieu bang AES
    $value_encrypt_aes = $this->encrypt($data,$this->aesKey);

    //B2. Input du lieu co gan key AES
    $value_with_key = 'value='.$value_encrypt_aes.'&key='.$this->aesKey;

    //B3. Ma hoa du lieu bang public key
    openssl_public_encrypt($value_with_key,$data_encrypted,$pub_key);
    $data_encrypted = base64_encode($data_encrypted);

    //B4. Ky ban tin
    $signature ='';
    openssl_sign($data_encrypted, $signature, $pri_key_cp, OPENSSL_ALGO_SHA1);

    // Base64 Encode
    $signature = base64_encode($signature);
    // URL Encode
    $signature = urlencode($signature);

    return [$data_encrypted, $signature];
  }

  public function decryptData($data_encrypted, $signature){
    $key_path = sfConfig::get('sf_root_dir').DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'key';
    $pub_key_cp = file_get_contents($key_path.DIRECTORY_SEPARATOR.'PublicKeyVT.pem');
    $pri_key_cp = file_get_contents($key_path.DIRECTORY_SEPARATOR.'PrivateKeyCP.pem');
    $result = [];
    $data_encrypted = str_replace(' ', '+', urldecode($data_encrypted));
    $verify = openssl_verify ( $data_encrypted, base64_decode(str_replace(' ', '+',urldecode($signature))) , $pub_key_cp, OPENSSL_ALGO_SHA1);

    $this->logger->info(sprintf('[decryptData] verify:%s|data_encrypted: %s|sig: %s',$verify, $data_encrypted, $signature));
    if($verify) {
      //B6. Giai ma du lieu bang private key
      openssl_private_decrypt(base64_decode($data_encrypted), $data_decrypted, $pri_key_cp);
      $arr = array();
      parse_str($data_decrypted, $arr);
      $this->logger->info(sprintf('[decryptData] open ssl decrypt: %s', $data_decrypted));

      //B7. Giai ma du lieu bang AES
      $value_decrypt = $this->decrypt(str_replace(' ', '+', $arr['VALUE']), $arr['KEY']);
      parse_str($value_decrypt, $result);
      $this->logger->info(sprintf('[decryptData] decrypt data: %s', $value_decrypt));
    }
    return $result;
  }

  public function getMpsUrl($params, $type){
    list($dataEncrypt, $signature) = $this->encryptData($params, $type);
    $server = sfConfig::get('app_mps_url');
    // điền các thông tin của dịch vụ
    switch ($type){
      case self::CHARGE:
        return $server.'charge.html?PRO=GHD&SER=AutoSMS&SUB=AUTOSMS_DAILY&CMD=DOWNLOAD&DATA='.urlencode( $dataEncrypt).'&SIG='.$signature;
      default:
        return $server.'mobile.html?PRO=GHD&SER=AutoSMS&SUB=AUTOSMS_DAILY&DATA='.urlencode( $dataEncrypt).'&SIG='.$signature;
    }

  }

  protected function encrypt($encrypt, $key){
    $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB), MCRYPT_RAND);
    $encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, pack("H*", $key), $encrypt, MCRYPT_MODE_ECB, $iv));
    return $encrypted;
  }

  protected function decrypt($decrypt, $key){
    $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB), MCRYPT_RAND);
    $decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_128,  pack("H*", $key), base64_decode($decrypt), MCRYPT_MODE_ECB, $iv);
    return $decrypted;
  }

  protected function pkcs5_pad ($text, $blocksize) {
    $pad = $blocksize - (strlen($text) % $blocksize);
    return $text . str_repeat(chr($pad), $pad);
  }

  public function getMessageByErrorCode($errorCode){
    switch ($errorCode){
      case 0:
        $message = 'Thanh toán thành công'; break;
      case 401:
        $message = 'Tài khoản không đủ thanh toán'; break;
      case 402:
        $message = 'Thuê bao chưa đăng ký thanh toán'; break;
      case 403:
        $message = 'Thuê bao không tồn tại'; break;
      case 404:
        $message = 'Số điện thoại không hợp lệ'; break;
      case 405:
        $message = 'Số điện thoại đã đổi chủ'; break;
      case 408:
        $message = 'Thuê bao đang sử dụng dịch vụ'; break;
      case 409:
        $message = 'Thuê bao bị khoá 2 chiều'; break;
      case 410:
        $message = 'Số điện thoại không phải là thuê bao Viettel'; break;
      default:
        $message = 'Thanh toán không thành công, Quý khách vui lòng thử lại sau';
    }
    return $message;
  }
}