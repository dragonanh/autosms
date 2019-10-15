<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once(dirname(__FILE__).'/../lib/BasesfGuardAuthActions.class.php');

/**
 *
 * @package    symfony
 * @subpackage plugin
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: actions.class.php 23319 2009-10-25 12:22:23Z Kris.Wallsmith $
 */
class sfGuardAuthActions extends BasesfGuardAuthActions
{
  /**
   * custom lai action signout
   * @author Huynt74
   * @modified on 31/12/2013
   * @param $request
   */
  public function executeSignout($request) {
    $this->getUser()->signOut();
    session_unset();
    session_destroy();
    $this->redirectLogin($request);
  }

  private function redirectLogin($request){
    // code them 1 doan de dam bao luon login theo dung duong dan:  http://localhost/admin.php/login
    $domain = $request->getUriPrefix();
    $linkLogin =$domain.sfcontext::getinstance()->getRouting()->generate("sf_guard_signin");
    $this->redirect($linkLogin);
  }
}
