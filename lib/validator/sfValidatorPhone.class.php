<?php

/**
 * sfValidatorPhone validates a phone number.
 *
 * @author Brent Shaffer <bshafs@gmail.com>
 */
class sfValidatorPhone extends sfValidatorRegex {

    public function __construct($options = array(), $messages = array()) {        
        $i18n = sfContext::getInstance()->getI18N();
        $messageInvalid = $i18n->__("Bạn chỉ được nhập số điện thoại của Viettel");
        $messageRequired = $i18n->__('Số điện thoại không được để trống.');
        $myoption = array('pattern' => sfConfig::get('app_viettel_phone_expression'), 
                          'max_length' => sfConfig::get('app_viettel_phone_length'), 
                          'trim' => true);
        $mymessage = array('invalid' => $messageInvalid, 'max_length' => $messageInvalid, 'required' => $messageRequired);
        return parent::__construct(array_merge($myoption, $options), array_merge($mymessage, $messages));
    }

}
