<?php

require_once dirname(__FILE__).'/../lib/sfGuardUserGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/sfGuardUserGeneratorHelper.class.php';

/**
 * sfGuardUser actions.
 *
 * @package    sfGuardPlugin
 * @subpackage sfGuardUser
 * @author     Fabien Potencier
 * @version    SVN: $Id: actions.class.php 23319 2009-10-25 12:22:23Z Kris.Wallsmith $
 */
class sfGuardUserActions extends autoSfGuardUserActions
{
  public function preExecute()
  {
//    isicsBreadcrumbs::getInstance()->addItem('Tài khoản hệ thống', '@sf_guard_user');
//    switch ($this->getActionName())
//    {
//      case 'edit': 
//        isicsBreadcrumbs::getInstance()->addItem('Sửa tài khoản', '');
//        break;
//      case 'new': 
//        isicsBreadcrumbs::getInstance()->addItem('Thêm mới tài khoản', '');
//        break;
//    }
    parent::preExecute();
  }
}
