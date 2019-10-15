<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of sfCaptchaGuardValidatorUser
 *
 * @author vas_tungtd2
 */
class sfCaptchaGuardValidatorUser extends sfGuardValidatorUser{
  public function doClean($values) {
    if(is_null($values['captcha'])) return $values;
    return parent::doClean($values);
  }
  //put your code here
}

?>
