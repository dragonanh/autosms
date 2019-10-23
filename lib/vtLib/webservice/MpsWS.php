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

  public function __construct()
  {
    $z = 'abcdefghijuklmno0123456789012345';
    $aes = new AES($z);
    $encrypted = $aes->encrypt();
    $this->aeskey = bin2hex($encrypted);
  }

  public function encryptData($params){
    $key_path = sfConfig::get('sf_root_dir').DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'key';
    $pub_key = file_get_contents($key_path.DIRECTORY_SEPARATOR.'PublicKeyVT.pem');
    $pri_key_cp = file_get_contents($key_path.DIRECTORY_SEPARATOR.'PrivateKeyCP.pem');

    $pub_key = <<<EOD
-----BEGIN PUBLIC KEY-----
xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
xxxxxxxxxxxxxxxxxxxxxxxxx
xxxxxxxxxxxxxxxxxsample==
-----END PUBLIC KEY-----
EOD;


//key do viettel cung cap
    $pri_key_cp = <<<EOD
-----BEGIN RSA PRIVATE KEY-----
aaaaaaaaaaaaaaaaaaaaaaaaa
aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
aaaaaaaaaaaaaaaaaaaaaaa=
-----END RSA PRIVATE KEY-----
EOD;

    //CP thuc hienng h
    $transId = date('ymdHis').rand(10000,99999);
    $data = sprintf('SUB=%s&CATE=%s&ITEM=%s&SUB_CP=%s&CONT=%s&PRICE=%s&REQ=%s&SOURCE=WAP',
      $params['SUB'],$params['CATE'],$params['ITEM'],$params['SUB_CP'],$params['CONT'],$params['PRICE'],$transId);

    $data = 'SUB=MEDIASTACK&CATE=VIDEO&ITEM=REGISTER&SUB_CP=KLEII&CONT=Noi&PRICE=1&REQ=011131212032423000&MOBILE=&SOURCE=WAP';

    $data = $this->pkcs5_pad($data, 16);
    //B1. Ma hoa du lieu bang AES
    $value_encrypt_aes = $this->encrypt($data,$this->aeskey);

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

  public function getMpsChargeUrl($params){
    list($dataEncrypt, $signature) = $this->encryptData($params);
    $server = 'http://125.235.32.12/MPS/';
    return $server.'charge.html?PRO=IWEB&CMD=DOWNLOAD&SER=IWEB&SUB=MEDIASTACK&DATA='.urlencode( $dataEncrypt).'&SIG='.$signature;
    // điền các thông tin của dịch vụ
    return $server.'charge.html?PRO=GHD&CMD=DOWNLOAD&SER=AutoSMS&SUB=AUTOSMS_DAILY&DATA='.urlencode( $dataEncrypt).'&SIG='.$signature;
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
}