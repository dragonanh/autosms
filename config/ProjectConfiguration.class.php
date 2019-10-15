<?php
require_once dirname(__FILE__).'/../lib/vendor/apache-log4php-2.3.0/src/main/php/Logger.php';
require_once dirname(__FILE__).'/../lib/vendor/symfony/lib/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();
require_once dirname(__FILE__).'/../lib/vendor/predis-1.0/autoload.php';
require_once dirname(__FILE__).'/../lib/recaptcha-master/src/autoload.php';
require_once dirname(__FILE__).'/../plugins/htmlpurifier-4.8.0/library/HTMLPurifier/Bootstrap.php';
HTMLPurifier_Bootstrap::registerAutoload();
class ProjectConfiguration extends sfProjectConfiguration
{
  public function setup()
  {
    $this->enablePlugins (
      array(
      'sfDoctrinePlugin',
      'sfCaptchaGDPlugin',
      'sfRedisPlugin',
      'tmcTwitterBootstrapPlugin',
      'sfDoctrineGuardPlugin',
      'sfCKEditorPlugin',
      'sfFormExtraPlugin',
      'sfThumbnailPlugin',
    ));
    date_default_timezone_set('Asia/Ho_Chi_Minh');
  }
}
