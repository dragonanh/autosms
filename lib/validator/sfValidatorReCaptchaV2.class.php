<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfValidatorReCaptcha validates a ReCaptcha.
 *
 * This validator uses ReCaptcha: http://recaptcha.net/
 *
 * The ReCaptcha API documentation can be found at http://recaptcha.net/apidocs/captcha/
 *
 * To be able to use this validator, you need an API key: http://recaptcha.net/api/getkey
 *
 * To create a captcha validator:
 *
 *    $captcha = new sfValidatorReCaptcha(array('private_key' => RECAPTCHA_PRIVATE_KEY));
 *
 * where RECAPTCHA_PRIVATE_KEY is the ReCaptcha private key.
 *
 * @package    symfony
 * @subpackage validator
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfValidatorReCaptcha.class.php 7903 2008-03-15 13:17:41Z fabien $
 */
class sfValidatorReCaptchaV2 extends sfValidatorBase
{
  /**
   * Configures the current validator.
   *
   * Available options:
   *
   *  * private_key:    The ReCaptcha private key (required)
   *
   * Available error codes:
   *
   *  * captcha
   *  * server_problem
   *
   * @see sfValidatorBase
   */
  protected function configure($options = array(), $messages = array())
  {
    $this->addRequiredOption('private_key');

    $this->addMessage('invalid', 'The captcha is not valid (%error%).');
    $this->addMessage('required', 'Required.');
  }

  /**
   * Cleans the input value.
   *
   * The input value must be an array with 2 required keys: recaptcha_challenge_field and recaptcha_response_field.
   *
   * It always returns null.
   *
   * @see sfValidatorBase
   */
  protected function doClean($value)
  {
    $reCaptchaRes = isset($value['g-recaptcha-response']) ? $value['g-recaptcha-response'] : null;
    $ip = isset($value['remote_ip']) ? $value['remote_ip'] : null;
    if (empty($reCaptchaRes) || empty($ip))
    {
      throw new sfValidatorError($this, 'required');
    }

    $reCaptcha = new \ReCaptcha\ReCaptcha($this->getOption('private_key'));
    $resp = $reCaptcha->verify($reCaptchaRes, $ip);
    if (!$resp->isSuccess())
    {
      throw new sfValidatorError($this, 'invalid', array('error' => 'invalid captcha'));
    }

    return $value;
  }
}
