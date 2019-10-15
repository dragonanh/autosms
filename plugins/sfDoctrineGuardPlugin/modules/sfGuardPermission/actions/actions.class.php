<?php

require_once dirname(__FILE__).'/../lib/sfGuardPermissionGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/sfGuardPermissionGeneratorHelper.class.php';

/**
 * sfGuardPermission actions.
 *
 * @package    sfGuardPlugin
 * @subpackage sfGuardPermission
 * @author     Fabien Potencier
 * @version    SVN: $Id: actions.class.php 23319 2009-10-25 12:22:23Z Kris.Wallsmith $
 */
class sfGuardPermissionActions extends autosfGuardPermissionActions
{
  public function preExecute()
  {
    isicsBreadcrumbs::getInstance()->addItem('Quyền người dùng hệ thống', '@sf_guard_permission');
    switch ($this->getActionName())
    {
      case 'edit': 
        isicsBreadcrumbs::getInstance()->addItem('Sửa quyền', '');
        break;
      case 'new': 
        isicsBreadcrumbs::getInstance()->addItem('Thêm mới quyền', '');
        break;
    }
    parent::preExecute();
  }
}
