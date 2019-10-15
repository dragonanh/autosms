<?php
/**
 * Created by JetBrains PhpStorm.
 * User: HoangL
 * Date: 3/2/12
 * Time: 7:27 PM
 * To change this template use File | Settings | File Templates.
 */
class vtSecurity
{
  public static function encodeOutput($string)
  {
    $string = VtHelper::replaceSpecialCharsFromWord($string);
    if (sfConfig::get('sf_escaping_method') == 'ESC_SPECIALCHARS')
      return htmlspecialchars($string, ENT_QUOTES, sfConfig::get('sf_charset'));
    return htmlentities($string, ENT_QUOTES, sfConfig::get('sf_charset'));

  }

  public static function decodeInput($string)
  {
    $string = VtHelper::replaceSpecialCharsFromWord($string);
    if (sfConfig::get('sf_escaping_method') == 'ESC_SPECIALCHARS')
      return htmlspecialchars_decode($string, ENT_QUOTES);
    return html_entity_decode($string, ENT_QUOTES, sfConfig::get('sf_charset'));
  }

  public static function checkCSRFToken($request)
  {
    $form = new BaseForm();
    $form->bind($form->isCSRFProtected() ? array($form->getCSRFFieldName() => $request->getParameter($form->getCSRFFieldName())) : array());
    if (!$form->isValid()) {
      return false;
    }
    return true;
  }
}
