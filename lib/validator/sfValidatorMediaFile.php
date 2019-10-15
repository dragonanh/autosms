<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of sfValidatorMediaFile
 *
 * @author khanhnq16
 */
class sfValidatorMediaFile extends sfValidatorFile{
 protected function configure($options = array(), $messages = array())
  {
    parent::configure($options,$messages);
    $this->addOption('upload_path');//tuanbm them truong upload_path (ko bat buoc),neu ko them vao se tu dong generate theo thu muc quy dinh
    $this->addOption('prefix_path');// tien to cho duong dan se luu file (ko bat buoc) vi du: /medias2/song/...
    $this->addRequiredOption('path');//bat buoc phai nhap path
    $this->addOption('validated_file_class', 'sfValidatedFileViettel');
    $this->addOption('extensions');
    $this->addMessage('extensions', 'Invalid extension (%extension%).');
    //$this->setOption('validated_file_class', 'sfValidatedFileViettel');
  }
  
  
 protected function doClean($value)
  {
//    echo 'abc';
//    var_dump($value['tmp_name']);die;
    if (!is_array($value) || !isset($value['tmp_name'])) {
      throw new sfValidatorError($this, 'invalid', array('value' => (string)$value));
    }

    if (!isset($value['name'])) {
      $value['name'] = '';
    }


    if (!isset($value['error'])) {
      $value['error'] = UPLOAD_ERR_OK;
    }

    if (!isset($value['size'])) {
      $value['size'] = filesize($value['tmp_name']);
    }

    if (!isset($value['type'])) {
      $value['type'] = 'application/octet-stream';
    }
    
    //validate file by getID3
    try{    
        if ( copy( $value['tmp_name'], sfConfig::get('app_upload_file_temp_dir') . $value['name'] ) ) {
            $file = sfConfig::get('app_upload_file_temp_dir'). $value['name'];
            $getID3 = new getID3;
            $fileInfo = $getID3->analyze($file);
            if($fileInfo && $fileInfo['fileformat'] && $fileInfo['fileformat'] == sfConfig::get('app_audio_format_type')){
                $value['type'] = 'audio/mpeg';
            }
//            var_dump($value);die; 
//            unlink($file);
//            echo "success";die;
        } 
    }catch(Exception $e){
        
    }
    
    
    switch ($value['error'])
    {
      case UPLOAD_ERR_INI_SIZE:
        $max = ini_get('upload_max_filesize');
        if ($this->getOption('max_size')) {
          $max = min($max, $this->getOption('max_size'));
        }
        throw new sfValidatorError($this, 'max_size', array('max_size' => $max, 'size' => (int)$value['size']));
      case UPLOAD_ERR_FORM_SIZE:
        throw new sfValidatorError($this, 'max_size', array('max_size' => 0, 'size' => (int)$value['size']));
      case UPLOAD_ERR_PARTIAL:
        throw new sfValidatorError($this, 'partial');
      case UPLOAD_ERR_NO_TMP_DIR:
        throw new sfValidatorError($this, 'no_tmp_dir');
      case UPLOAD_ERR_CANT_WRITE:
        throw new sfValidatorError($this, 'cant_write');
      case UPLOAD_ERR_EXTENSION:
        throw new sfValidatorError($this, 'extension');
    }

    // check file size
    if ($this->hasOption('max_size') && $this->getOption('max_size') < (int)$value['size']) {
      throw new sfValidatorError($this, 'max_size', array('max_size' => $this->getOption('max_size'), 'size' => (int)$value['size']));
    }
   //anhbhv - check extension
   if ($this->hasOption('extensions'))
   {
     $allowExt = $this->getOption('extensions');
     $upExt = '';
     if (false !== $pos = strrpos(basename($value['name']), '.'))
     {
       $upExt = substr(basename($value['name']), $pos + 1);
     }
     if (!in_array(strtolower($upExt), array_map('strtolower', $allowExt)))
     {
       throw new sfValidatorError($this, 'extensions', array('extension' => $upExt));
     }
   }

    $mimeType = $value['type'];//$this->getMimeType((string)$value['tmp_name'], (string)$value['type']);

    // check mime type
    if ($this->hasOption('mime_types')) {
      $mimeTypes = is_array($this->getOption('mime_types')) ? $this->getOption('mime_types') : $this->getMimeTypesFromCategory($this->getOption('mime_types'));
      if (!in_array($mimeType, array_map('strtolower', $mimeTypes))) {
        throw new sfValidatorError($this, 'mime_types', array('mime_types' => $mimeTypes, 'mime_type' => $mimeType));
      }
    }

    $class = $this->getOption('validated_file_class');
    return new $class($value['name'], $mimeType, $value['tmp_name'], $value['size'], $this->getOption('path'), $this->getOption('upload_path'), $this->getOption('prefix_path'));
  }
}

?>
