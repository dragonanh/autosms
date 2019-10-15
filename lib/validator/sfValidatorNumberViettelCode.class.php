<?php
/**
 * Created by JetBrains PhpStorm.
 * User: tuanbm
 * Date: 9/15/12
 * Time: 1:07 PM
 * To change this template use File | Settings | File Templates.
 */
class sfValidatorNumberViettelCode extends sfValidatorNumber
{

  protected function doClean($value)
  {
    if (!is_numeric($value))
    {
      throw new sfValidatorError($this, 'invalid', array('value' => $value));
    }

//    $clean = floatval($value);tuanbm return

    $length = function_exists('mb_strlen') ? mb_strlen($value, $this->getCharset()) : strlen($value);

    if ($this->hasOption('max') && $length > $this->getOption('max'))
    {
      throw new sfValidatorError($this, 'max', array('value' => $value, 'max' => $this->getOption('max')));
    }

//    if ($this->hasOption('max') && $clean > $this->getOption('max'))
//    {
//      throw new sfValidatorError($this, 'max', array('value' => $value, 'max' => $this->getOption('max')));
//    }
//
//    if ($this->hasOption('min') && $clean < $this->getOption('min'))
//    {
//      throw new sfValidatorError($this, 'min', array('value' => $value, 'min' => $this->getOption('min')));
//    }

    return $value;
  }
}