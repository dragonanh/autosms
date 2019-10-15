<?php

/**
 * BasesfGuardFormSignin
 *
 * @package    sfDoctrineGuardPlugin
 * @subpackage form
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @author     Jonathan H. Wage <jonwage@gmail.com>
 * @version    SVN: $Id: BasesfGuardFormSignin.class.php 25546 2009-12-17 23:27:55Z Jonathan.Wage $
 */
class BasesfGuardFormSignin extends BaseForm
{
  /**
   * @see sfForm
   */
  public function setup()
  {
    $this->setWidgets(array(
      'username' => new sfWidgetFormInputText(),
      'password' => new sfWidgetFormInputPassword(array('type' => 'password')),
      'remember' => new sfWidgetFormInputCheckbox(),
      'captcha' => new sfWidgetFormReCaptchaV2(array(
        'sitekey' => '6Le8KhsUAAAAAFu3uO8__5ZODgOy6OZYdfT4NJ47'
      )),
//      'captcha' => new sfWidgetCaptchaGD(),
    ));

    $this->setValidators(array(
      'username' => new sfValidatorString(),
      'password' => new sfValidatorString(),
      'remember' => new sfValidatorBoolean(),
//      'captcha' => new sfValidatorReCaptchaV2(array(
//        'private_key' => '6Le8KhsUAAAAABKGUImuU3sMCbc5jFiPPEq_3tqu'
//      )),
      'captcha' => new sfValidatorPass(),
    ));

    if (sfConfig::get('app_sf_guard_plugin_allow_login_with_email', true))
    {
      $this->widgetSchema['username']->setLabel('Username or E-Mail');
    }

    $this->validatorSchema->setPostValidator(new sfCaptchaValidatorUser());

    $this->widgetSchema->setNameFormat('signin[%s]');
  }

}

class sfCaptchaValidatorUser extends sfGuardValidatorUser {
  public function doClean($values) {
    // Neu captcha khong dung thi khong kiem tra thong tin dang nhap
    if (is_null($values['captcha'])) return $values;
    return parent::doClean($values);
  }
}