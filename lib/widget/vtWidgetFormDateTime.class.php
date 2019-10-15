<?php

/**
 * vtWidgetFormDate represents a date widget.
 *
 * @package    mobiletv
 * @subpackage widget
 * @author     NamDT5@viettel.com.vn
 * @version    SVN: $Id: mtvWidgetFormDate.class.php 16259 2009-03-12 11:42:00Z fabien $
 */
class vtWidgetFormDateTime extends sfWidgetFormDateTime
{

  /**
   * @param  string $name        The element name
   * @param  string $value       The date displayed in this widget
   * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
   * @param  array  $errors      An array of errors for the field
   *
   * @return string An HTML tag string
   *
   * @see sfWidgetForm
   */


  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if(self::isTimestamp($value))
    {
      $time = strtotime($value);
      $formattedValue = date($this->getOption('format'), $time);    
    }
    else
    {
      $formattedValue = $value;
    }
    return $this->renderTag(
      'input',
      array(
        'name' => $name,
        'size' => 10,
        'readonly' => 'readonly',
        'id'   => (isset($attributes['id']))? $attributes['id'] : $this->generateId($name),
        'class' => 'text_cursor datetimepicker_me '. ((isset($attributes['class']))?$attributes['class']: ''),
        'value' => isset($attributes['value']) ? $attributes['value'] : $formattedValue
      )
    );
  }
  
  public function isTimestamp($string) {
    return (preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2})\s{1}([0-9]{2}):([0-9]{2})$/", $string) || preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2})\s{1}([0-9]{2}):([0-9]{2}):([0-9]{2})$/", $string));
  }

}