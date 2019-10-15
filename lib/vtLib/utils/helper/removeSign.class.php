<?php
/*
* Created on Jun 30, 2010
* Author: BaoNV3@viettel.com.vn
* Copyright 2010 Viettel Telecom. All rights reserved.
* VIETTEL PROPRIETARY/CONFIDENTIAL. Use is subject to license terms.
*/
class removeSignClass
{
	private static  $hasSign = array(
            "à","á","ạ","ả","ã","â","ầ","ấ","ậ","ẩ","ẫ","ă","ằ","ắ","ặ","ẳ","ẵ",
            "è","é","ẹ","ẻ","ẽ","ê","ề","ế","ệ","ể","ễ",
            "ì","í","ị","ỉ","ĩ",
            "ò","ó","ọ","ỏ","õ","ô","ồ","ố","ộ","ổ","ỗ","ơ","ờ","ớ","ợ","ở","ỡ",
            "ù","ú","ụ","ủ","ũ","ư","ừ","ứ","ự","ử","ữ",
            "ỳ","ý","ỵ","ỷ","ỹ",
            "đ",
            "À","Á","Ạ","Ả","Ã","Â","Ầ","Ấ","Ậ","Ẩ","Ẫ","Ă","Ằ","Ắ","Ặ","Ẳ","Ẵ",
            "È","É","Ẹ","Ẻ","Ẽ","Ê","Ề","Ế","Ệ","Ể","Ễ",
            "Ì","Í","Ị","Ỉ","Ĩ",
            "Ò","Ó","Ọ","Ỏ","Õ","Ô","Ồ","Ố","Ộ","Ổ","Ỗ","Ơ","Ờ","Ớ","Ợ","Ở","Ỡ",
            "Ù","Ú","Ụ","Ủ","Ũ","Ư","Ừ","Ứ","Ự","Ử","Ữ",
            "Ỳ","Ý","Ỵ","Ỷ","Ỹ",
            "Đ",
            );
            
	private static $noSign = array(
            "a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","a",
            "e","e","e","e","e","e","e","e","e","e","e",
            "i","i","i","i","i",
            "o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o",
            "u","u","u","u","u","u","u","u","u","u","u",
            "y","y","y","y","y",
            "d",
            "a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","a",
            "e","e","e","e","e","e","e","e","e","e","e",
            "i","i","i","i","i",
            "o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o",
            "u","u","u","u","u","u","u","u","u","u","u",
            "y","y","y","y","y",
            "d");
	
	 private static $noSignOnly = array(
            "a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","a",
            "e","e","e","e","e","e","e","e","e","e","e",
            "i","i","i","i","i",
            "o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o",
            "u","u","u","u","u","u","u","u","u","u","u",
            "y","y","y","y","y",
            "d",
            "A","A","A","A","A","A","A","A","A","A","A","A","A","A","A","A","A",
            "E","E","E","E","E","E","E","E","E","E","E",
            "I","I","I","I","I",
            "O","O","O","O","O","O","O","O","O","O","O","O","O","O","O","O","O",
            "U","U","U","U","U","U","U","U","U","U","U",
            "Y","Y","Y","Y","Y",
            "D");

  public static function removeSignOnly($str)
  {
    //Sign
    $str = str_replace(self::$hasSign, self::$noSignOnly, $str);
    $str = trim($str);
    return $str;
  }
  	 
  public static function removeSign($str)
  {
    //Sign
    $str = str_replace(self::$hasSign, self::$noSign, $str);
    //Special string
    $spcStr = "/[^A-Za-z0-9]+/";
    $str = preg_replace($spcStr, ' ', $str);
    $str = trim($str);
		//Space
    $str = preg_replace("/( )+/", '-', $str);
    return strtolower($str);
  }

  public static function removeSignKeepSpace($str)
  {
    //Sign
    $str = str_replace(self::$hasSign, self::$noSign, $str);
    //Special string
    $spcStr = "/[^A-Za-z0-9]+/";
    $str = preg_replace($spcStr, ' ', $str);
    $str = trim($str);
    return strtolower($str);
  }
}