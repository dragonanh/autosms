<?php

/**
 * VtRegisterLog form.
 *
 * @package    source
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CreateProgramForm extends sfForm
{
  public function configure()
  {
    $this->setWidgets([
      'content' => new sfWidgetFormInputText([], [
        'style' => 'width:100%',
        'placeholder' => 'VD: Tôi đang họp liên lạc với tôi sau 16h'
      ]),
      'start_time' => new sfWidgetFormInputText([], [
        'style' => 'width:100%',
        'placeholder' => 'Thời gian bắt đầu (VD: 31-07-2019 16:00:00)'
      ]),
      'end_time' => new sfWidgetFormInputText([], [
        'style' => 'width:100%;',
        'placeholder' => 'Thời gian kết thúc (VD: 31-07-2019 16:30:00)'
      ]),
    ]);

    $this->setValidators([
      'content' => new sfValidatorString([
        'required' => true,
        'max_length' => 255
      ], [
        'required' => 'Vui lòng nhập nội dung',
        'max_length' => 'Trường quà dài (Tối đa 255 ký tự)'
      ]),
      'start_time' => new sfValidatorString([
        'required' => true
      ], [
        'required' => 'Vui lòng nhập thời gian bắt đầu',
      ]),
      'end_time' => new sfValidatorString([
        'required' => true
      ], [
        'required' => 'Vui lòng nhập thời gian kết thúc',
      ]),
    ]);

    if(!empty($defaultValue = $this->getOption('schedule'))){
      $this->setDefaults($defaultValue);
    }

    $this->mergePostValidator(new sfValidatorCallback(array(
      'callback' => array($this, 'checkValue')
    )));

    $this->widgetSchema->setNameFormat('create_program[%s]');
  }

  public function checkValue($validator, $values)
  {
    $errorArr = array();
    if ($values['start_time'] != '' && $values['end_time'] != '') {
      if($this->validateDate($values['start_time']) && $this->validateDate($values['end_time'])){


        $errorArr['start_time'] = new sfValidatorError($validator, 'CMTND đã sử dụng quá số lượng sim được phép');
      }else{
        if($this->validateDate($values['start_time'])){
          $errorArr['start_time'] = new sfValidatorError($validator, 'Thời gian bắt đầu không hợp lệ');
        }

        if($this->validateDate($values['end_time'])){
          $errorArr['end_time'] = new sfValidatorError($validator, 'Thời gian kết thúc không hợp lệ');
        }
      }
    }

    if (count($errorArr))
      throw new sfValidatorErrorSchema($validator, $errorArr);

    return $values;
  }

  public function validateDate($date, $format = 'd-m-Y H:i:s')
  {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
  }
}
