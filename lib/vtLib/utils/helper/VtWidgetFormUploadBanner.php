<?php

/**
 * @author NamDT5
 *
 */
if (!class_exists('VtWidgetFormUploadBanner'))
{
  class VtWidgetFormUploadBanner extends sfWidgetFormInputFile
  {
    protected function configure($options = array(), $attributes = array())
    {
      parent::configure($options, $attributes);

      $this->setOption('type', 'file');
      $this->setOption('needs_multipart', true);

      $this->addRequiredOption('file_src');
      $this->addRequiredOption('file_dir');
      $this->addOption('is_image', false);
      $this->addOption('edit_mode', true);
      $this->addOption('style', '');
      $template = '
	      <table width="">
	        <tr>
	          <td align="left" style="overflow: hidden;">%file%</td>
	        </tr>
	        <tr>
	          <td>%input%</td>
	        </tr>
	      </table>
	    ';
      $this->addOption('template', $template);
    }

    public function render($name, $value = null, $attributes = array(), $errors = array())
    {
      $input = parent::render($name, $value, $attributes, $errors);

      return strtr($this->getOption('template'), array('%input%' => $input, '%file%' => $this->getFileAsTag($attributes)));
    }

    protected function getFileAsTag($attributes)
    {
      if ($this->getOption('is_image') && $this->getOption('file_src') && $this->getOption('file_dir'))
      {
        $mimeType = mime_content_type($this->getOption('file_dir'));

        if ($mimeType != 'application/x-shockwave-flash')
        {
          $params = array(
            'src' => $this->getOption('file_src'),
            'width' => 150,
            'height' => 150,
          );
          if($this->getOption('style'))
            $params['style'] = $this->getOption('style');
          return false !== $this->getOption('file_src') ? $this->renderTag('img', array_merge($params, $attributes)) : '';
        } else{
          return '
	          <object height="150"
	                classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" 
	                codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0">
	                  <param value="link='.$this->getOption('file_src').'" name="flashvars">
	                  <param name="movie" value="'. $this->getOption('file_src').'">
	                  <param name="wmode" value="opaque">
	                  
	                  <embed height="150" type="application/x-shockwave-flash"
	                    pluginspage="http://www.macromedia.com/go/getflashplayer" 
	                    src="'. $this->getOption('file_src').'" wmode="opaque" />
	              
	          </object>
	        ';
        }
      }
      else
      {
        return $this->getOption('file_src');
      }
    }
  }
}