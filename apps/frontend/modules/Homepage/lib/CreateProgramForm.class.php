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
    $page = $this->getOption('page');
    $this->setWidgets([
      'content' => new sfWidgetFormInputText([], [
        'class' => 'form-control border-success',
        'style' => 'border-color: #CED4DA !important; height: 90px;',
        'readonly' => $page == 'detail' ? true : false,
        'list' => 'suggestions',
        'placeholder' => 'Nhập nội dung báo bận',
        'data-placeholder' => 'Nhập nội dung báo bận',
        'autocomplete' => 'off'
      ]),
      'start_time' => new sfWidgetFormInputText([], [
        'class' => 'form-control',
        'placeholder' => 'Thời gian bắt đầu',
        'data-placeholder' => 'Thời gian bắt đầu',
        'data-field' => $page == 'detail' ? "" : "datetime",
//        'type' => $page == 'detail' ? "" : "hidden",
        'readonly' => true
      ]),
      'end_time' => new sfWidgetFormInputText([], [
        'class' => 'form-control',
        'placeholder' => 'Thời gian kết thúc',
        'data-placeholder' => 'Thời gian kết thúc',
        'data-field' => $page == 'detail' ? "" : "datetime",
//        'type' => $page == 'detail' ? "text" : "hidden",
        'readonly' => true
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
      $startTime = str_replace('T', ' ', $values['start_time']);
      $endTime = str_replace('T', ' ', $values['end_time']);
      if($this->validateDate($startTime) && $this->validateDate($endTime)){
        $isLTNow = $this->compareTwoDate($endTime, date('d-m-Y H:i:s'));

        if($isLTNow){
          $errorArr['end_time'] = new sfValidatorError($validator, 'Thời gian kết thúc phải lớn hơn thời gian hiện tại');
        }elseif($this->compareTwoDate($endTime, $startTime)){
          $errorArr['start_time'] = new sfValidatorError($validator, 'Thời gian bắt đầu phải nhỏ hơn thời gian kết thúc');
        }else{
          $dt = new DateTime($startTime);
          $temp = $dt->modify('+ 8 hour')->format('d-m-Y H:i:s');
          if(!$this->compareTwoDate($endTime,$temp)){
            $errorArr['start_time'] = new sfValidatorError($validator, 'Thời gian báo bận không quá 08h kể từ thời gian bắt đầu');
          }
        }

      }else{
        if(!$this->validateDate($startTime)){
          $errorArr['start_time'] = new sfValidatorError($validator, 'Thời gian bắt đầu không hợp lệ');
        }

        if(!$this->validateDate($endTime)){
          $errorArr['end_time'] = new sfValidatorError($validator, 'Thời gian kết thúc không hợp lệ');
        }
      }
    }

    if (count($errorArr))
      throw new sfValidatorErrorSchema($validator, $errorArr);

    return $values;
  }

  public function compareTwoDate($d1, $d2, $format = 'd-m-Y H:i:s'){
    $date1 = DateTime::createFromFormat($format, $d1);
    $date2 = DateTime::createFromFormat($format, $d2);

    return strtotime($date1->format($format)) < strtotime($date2->format($format));
  }

  public function validateDate($date, $format = 'd-m-Y H:i:s')
  {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
  }
}
