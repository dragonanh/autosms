<?php
/**
 * Created by JetBrains PhpStorm.
 * User: vas_hoangl
 * Date: 12/18/12
 * Time: 10:59 AM
 * To change this template use File | Settings | File Templates.
 */

class vtValidateUploadWidget extends sfWidgetFormInput
{
    /**
     * Configures the current widget.
     *
     * @param array $options     An array of options
     * @param array $attributes  An array of default HTML attributes
     *
     * @see sfWidgetFormInput
     */
    protected function configure($options = array(), $attributes = array())
    {
        parent::configure($options, $attributes);

        $this->setOption('type', 'file');
        $this->addRequiredOption('max_size');
        $this->setOption('needs_multipart', true);
    }

    public function render($name, $value = null, $attributes = array(), $errors = array())
    {
        $attributes['max_size'] = $this->getOption('max_size');
        return $this->renderTag('input', array_merge(array('type' => $this->getOption('type'), 'name' => $name, 'value' => $value), $attributes));
    }

    public function getJavaScripts() {
        return array(
          '/js/vtValidateUploadWidget.js'
        );
    }
}
