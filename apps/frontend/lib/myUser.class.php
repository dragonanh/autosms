<?php

class myUser extends sfBasicSecurityUser
{
  //tuanbm2 set mac dinh language tieng viet:
  public function initialize(sfEventDispatcher $dispatcher, sfStorage $storage, $options = array())
  {
    parent::initialize($dispatcher, $storage, $options);
    $this->setCulture('vi');
  }

  public function setVtUser($vtUser) {
    //thuc hien thao tac logout tat ca cac account truoc khi set moi
    $this->logoutAll();
    if ($vtUser != false) {//de phong truy van ko ra ket qua van set du lieu
      $this->setAttribute('vt_user', $vtUser);
    }
  }

  public function getVtUser() {
    return $this->getAttribute('vt_user', null);
  }

  public function getUsername() {
    return $this->getVtUser() ? $this->getVtUser()->getUsername() : '';
  }

  public function getPhoneNumber() {
    return $this->getVtUser() ? $this->getVtUser()->getPhone() : '';
  }

  //trang thai login
  public function getIsLogin() {
    return $this->getVtUser() ? true : false;
  }

  public function setIpAddress($ip){
    $this->setAttribute('vtshop_ip_address', $ip);
  }

  public function getIpAddress(){
    return $this->getAttribute('vtshop_ip_address');
  }

  public function setUserAgent($userAgent){
    $this->setAttribute('vtshop_useragent', $userAgent);
  }

  public function getUserAgent(){
    return $this->getAttribute('vtshop_useragent');
  }

  public function logoutAll() {
    $this->getAttributeHolder()->clear();
  }
}
