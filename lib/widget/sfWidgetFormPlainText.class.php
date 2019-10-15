<?php

/**
 * Description of sfWidgetFormPlainText
 *
 * @author tuanbm2
 */
class sfWidgetFormPlainText extends sfWidgetForm {

  public function configure($options = array(), $attributes = array())
  {
    $this->addRequiredOption('value_data');
    $this->addOption('required', false);
    $this->addOption('add_empty', false);
    $this->addOption('encode', true);
    $this->addOption('array_status_text', null);   //array(0=>"Từ chối",1=>"Phê duyệt")
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
    $value_data =  $this->getOption("value_data");
    $arrayStatusText =  $this->getOption("array_status_text");
    $encode =  $this->getOption("encode");
    $output = "";
    if(is_null($arrayStatusText)==false){
      $output = $arrayStatusText[$value_data];
    }else{
      $output = $value_data;
    }
    if($encode==true){
      return  VtHelper::encodeOutput($output,true);
    }else{
      return  VtHelper::strip_html_default_tags($output);
    }
  }

}
?>