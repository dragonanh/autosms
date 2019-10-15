<?php

require_once dirname(__FILE__).'/../lib/sfGuardGroupGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/sfGuardGroupGeneratorHelper.class.php';

/**
 * sfGuardGroup actions.
 *
 * @package    sfGuardPlugin
 * @subpackage sfGuardGroup
 * @author     Fabien Potencier
 * @version    SVN: $Id: actions.class.php 23319 2009-10-25 12:22:23Z Kris.Wallsmith $
 */
class sfGuardGroupActions extends autosfGuardGroupActions
{
  public function preExecute()
  {
//    isicsBreadcrumbs::getInstance()->addItem('Nhóm người dùng hệ thống', '@sf_guard_group');
    switch ($this->getActionName())
    {
      case 'edit': 
//        isicsBreadcrumbs::getInstance()->addItem('Sửa nhóm', '');
        break;
      case 'new': 
//        isicsBreadcrumbs::getInstance()->addItem('Thêm mới nhóm', '');
        break;
    }
    parent::preExecute();
  }
}
