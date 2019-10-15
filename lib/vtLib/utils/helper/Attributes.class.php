<?php
/*
* Created on Sep 28, 2010
* Author: minhnd10@viettel.com.vn
* Copyright 2010 Viettel Telecom. All rights reserved.
* VIETTEL PROPRIETARY/CONFIDENTIAL. Use is subject to license terms.
*/

class Attributes
{
  
  /*
   * Get array of attributes
   */
  public static function getAll($attributes)
  {
      $yaml = new sfYamlParser();
      $attrArr = $yaml->parse(file_get_contents($ymlFile = sfConfig::get('sf_config_dir') . DIRECTORY_SEPARATOR . 'attributes.yml'));
      return $attrArr[$attributes];
  }

  /*
   * Get article attributes list.
   */
  public static function getAttributesList($attributes)
  {
      $attrs = self::getAll($attributes);
      if (empty($attrs)) return array();

      $result = array();
      foreach ($attrs as $attr)
      {
        $result[$attr['value']] = $attr['alias'];
      }
      return $result;
  }

  /*
   * Get article attributes list.
  */
  public static function getActualAttributesList($attributes)
  {
    $attrs = self::getAll($attributes);
    if (empty($attrs)) return array();
  
    $result = array();
    foreach ($attrs as $attr)
    {
      $result[$attr['value']] = $attr['alias'];
    }
    return $result;
  }  
  
  /**
   * Get Value of an attribute
   * @param $attrName
   */
  public static function getValueOfAttribute($attrName = '')
  {
    if ($attrName == '') return false;

    $attrs = self::getAll();
    if (isset($attrs[$attrName]['value']))
      return $attrs[$attrName]['value'];
    else
      return false;
  }

  /**
   * Get Alias of an attribute
   * @param $attrName
   */
  public static function getAliasOfAttribute($attrName = '')
  {
    if ($attrName == '') return false;

    $attrs = self::getAll();
    if (isset($attrs[$attrName]['alias']))
      return $attrs[$attrName]['alias'];
    else
      return false;
  }
}