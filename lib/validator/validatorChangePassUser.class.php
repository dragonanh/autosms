<?php
/**
 * Created by JetBrains PhpStorm.
 * User: os_huynt74
 * Date: 1/17/13
 * Time: 4:18 PM
 * To change this template use File | Settings | File Templates.
 */
class validatorChangePassUser extends sfValidatorBase
{
  public function configure($options = array(), $messages = array())
  {
    $i18n = sfContext::getInstance()->getI18N();
    $this->addOption('username_field', 'username');
    $this->addOption('password_field', 'password');
    $this->addOption('throw_global_error', false);

    $this->setMessage('invalid', $i18n->__('Mật khẩu không chính xác.', null, 'sf_guard'));
  }

  protected function doClean($values)
  {
    $username = isset($values[$this->getOption('username_field')]) ? $values[$this->getOption('username_field')] : '';
    $password = isset($values[$this->getOption('password_field')]) ? $values[$this->getOption('password_field')] : '';

    // don't allow to sign in with an empty username
    if ($username)
    {
      if ($callable = sfConfig::get('app_sf_guard_plugin_retrieve_by_username_callable'))
      {
        $user = call_user_func_array($callable, array($username));
      } else {
        $user = $this->getTable()->retrieveByUsername($username);
      }
      // user exists?
      if($user)
      {
        // password is ok?
        if ($user->getIsActive() && $user->checkPassword($password))
        {
          return array_merge($values, array('user' => $user));
        }
      }
    }

    if ($this->getOption('throw_global_error'))
    {
      throw new sfValidatorError($this, 'invalid');
    }

    throw new sfValidatorErrorSchema($this, array($this->getOption('password_field') => new sfValidatorError($this, 'invalid')));
  }

  protected function getTable()
  {
    return Doctrine::getTable('sfGuardUser');
  }
}
