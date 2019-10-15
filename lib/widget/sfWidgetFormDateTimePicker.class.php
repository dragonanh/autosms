<?php

class sfWidgetFormDateTimePicker extends sfWidgetFormDateTime{

    protected function configure($options = array(), $attributes = array()) {
        parent::configure($options, $attributes);
    }

    /**
     * @param  string $name        The element name
     * @param  string $value       The value selected in this widget
     * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
     * @param  array  $errors      An array of errors for the field
     *
     * @return string An HTML tag string
     *
     * @see sfWidgetForm
     */
    public function render($name, $value = null, $attributes = array(), $errors = array()) {
        
        //$html = '<div style="display:none">'.parent::render($name, $value, $attributes, $errors).'</div>';
        return /*$html.*/$this->renderDateTimePicker($name, $value, $attributes, $errors);
        
    }
    
    protected function renderDateTimePicker($name, $value, $attributes, $errors) {
       
        $widget = new sfWidgetFormInput();
        ($value === "") ? $value = "" : $value = date('d-m-Y H:i:s', strtotime($value));
        return $widget->render($name, $value, array_merge($attributes, array("class"=>"datetimepicker", "readonly"=>"readonly", "style"=>"width:150px;")), $errors);
    }

    public function getJavaScripts() {
        $javascripts = array(
            'lib.ui-datepicker',
            'lib.ui-i18n',
            'admin',
            'lib.ui-core',
            'lib.ui-widget',
            'lib.ui-mouse',
            '/dmDateTimePickerPlugin/js/datetimepicker.js',
            '/dmDateTimePickerPlugin/js/launcher.js'
            );
         return array_merge(parent::getJavascripts(), $javascripts);
    }

}
