<?php

/**
 * Description of sfWidgetFormPlainText
 *
 * @author anhbhv
 */
class sfWidgetFormReCaptchaV2 extends sfWidgetForm {

  public function configure($options = array(), $attributes = array())
  {
    $this->addRequiredOption('sitekey');
  }

  /**
   * @param  string $name        The element name
   * @param  string $value       The value displayed in this widget
   * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
   * @param  array  $errors      An array of errors for the field
   *
   * @return string An HTML tag string
   *
   * @see sfWidgetForm
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    return sprintf('
    <script src="https://www.google.com/recaptcha/api.js?hl=vi" async defer></script>
    <div class="g-recaptcha" data-sitekey="%s"></div>
    ', $this->getOption('sitekey'));
  }

}
?>