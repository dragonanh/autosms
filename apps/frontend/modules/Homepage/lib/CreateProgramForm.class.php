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
        'style' => 'width:100%; height: 220px;',
        'placeholder' => 'VD: Tôi đang họp liên lạc với tôi sau 16h'
      ]),
      'start_time' => new sfWidgetFormInputText([], [
        'style' => 'width:100%',
        'placeholder' => 'Thời gian bắt đầu'
      ]),
      'end_time' => new sfWidgetFormInputText([], [
        'style' => 'width:100%;',
        'placeholder' => 'Thời gian kết thúc'
      ]),
    ]);

    $this->setValidators([
      'content' => new sfValidatorString([
        'required' => true,
        'max_length' => 255
      ]),
      'start_time' => new sfValidatorString([
        'required' => true
      ]),
      'end_time' => new sfValidatorString([
        'required' => true
      ]),
    ]);

    $this->widgetSchema->setNameFormat('create_program[%s]');
  }
}
