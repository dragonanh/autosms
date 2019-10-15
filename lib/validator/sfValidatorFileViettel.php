<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfValidatorFile validates an uploaded file.
 *
 * @package    symfony
 * @subpackage validator
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfValidatorFile.class.php 32836 2011-07-27 07:15:58Z fabien $
 */
class sfValidatorFileViettel extends sfValidatorFile
{
  /**
   * Configures the current validator.
   *
   * Available options:
   *
   *  * max_size:             The maximum file size in bytes (cannot exceed upload_max_filesize in php.ini)
   *  * mime_types:           Allowed mime types array or category (available categories: web_images)
   *  * mime_type_guessers:   An array of mime type guesser PHP callables (must return the mime type or null)
   *  * mime_categories:      An array of mime type categories (web_images is defined by default)
   *  * path:                 The path where to save the file - as used by the sfValidatedFile class (optional)
   *  * validated_file_class: Name of the class that manages the cleaned uploaded file (optional)
   *
   * There are 3 built-in mime type guessers:
   *
   *  * guessFromFileinfo:        Uses the finfo_open() function (from the Fileinfo PECL extension)
   *  * guessFromMimeContentType: Uses the mime_content_type() function (deprecated)
   *  * guessFromFileBinary:      Uses the file binary (only works on *nix system)
   *
   * Available error codes:
   *
   *  * max_size
   *  * mime_types
   *  * partial
   *  * no_tmp_dir
   *  * cant_write
   *  * extension
   *
   * @param array $options   An array of options
   * @param array $messages  An array of error messages
   *
   * @see sfValidatorBase
   */
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options,$messages);
    $this->addOption('upload_path');//tuanbm them truong upload_path (ko bat buoc),neu ko them vao se tu dong generate theo thu muc quy dinh
    $this->addOption('prefix_path');// tien to cho duong dan se luu file (ko bat buoc) vi du: /medias2/song/...
    $this->addRequiredOption('path');//bat buoc phai nhap path
    $this->addOption('validated_file_class', 'sfValidatedFileViettel');
    //$this->setOption('validated_file_class', 'sfValidatedFileViettel');
    $this->addOption('extensions');
    $this->addMessage('extensions', 'Invalid extension (%extension%).');
  }

  /**
   * This validator always returns a sfValidatedFile object.
   *
   * The input value must be an array with the following keys:
   *
   *  * tmp_name: The absolute temporary path to the file
   *  * name:     The original file name (optional)
   *  * type:     The file content type (optional)
   *  * error:    The error code (optional)
   *  * size:     The file size in bytes (optional)
   *
   * @see sfValidatorBase
   */
  protected function doClean($value)
  {
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

    $mimeType = $this->getMimeType((string)$value['tmp_name'], (string)$value['type']);

    // check mime type
    if ($this->hasOption('mime_types')) {
      $mimeTypes = is_array($this->getOption('mime_types')) ? $this->getOption('mime_types') : $this->getMimeTypesFromCategory($this->getOption('mime_types'));
      if (!in_array($mimeType, array_map('strtolower', $mimeTypes))) {
        throw new sfValidatorError($this, 'mime_types', array('mime_types' => $mimeTypes, 'mime_type' => $mimeType));
      }
    }

    $class = $this->getOption('validated_file_class');
    //23/07/2014, tuanbm2 code de luu them prefix vao DB
    return new $class($value['name'], $mimeType, $value['tmp_name'], $value['size'], $this->getOption('path'), $this->getOption('upload_path'),$this->getOption('prefix_path'));
  }
}
