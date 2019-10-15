<?php
/**
 * Created by JetBrains PhpStorm.
 * User: vas_hoangl
 * Date: 7/20/12
 * Time: 5:52 PM
 * To change this template use File | Settings | File Templates.
 */
class VtBannerUtils
{
  public static function getCacheCustom($widget)
  {
    if ($widget->isCachable() && $cache = $widget->getCache()) {
      return $cache;
    }
    else
      return;
  }

  public static function setCacheCustom($widget, $html, $lifetime)
  {
    if ($widget->isCachable()) {
      return $widget->getService('cache_manager')->getCache($widget->getCacheName())->set($widget->generateCacheKey(), $html, $lifetime);
    }
    else
      return;
  }

  /**
   * Ham tra ve duong dan theo ngay thang nam
   * @param string $path
   */
  public static function generatePath($path = '')
  {
    if ($path)
      $dayFolder = '/medias/' . $path . '/' . date('Y') . '/' . date('m') . '/' . date('d') . "/";
    else
      $dayFolder = '/medias/' . date('Y') . '/' . date('m') . '/' . date('d') . "/";

    $fullDir = sfConfig::get('sf_web_dir') . $dayFolder;
    if (!is_dir($fullDir)) {
      @mkdir($fullDir, 0777, true);
    }
    return $dayFolder;
  }


  /**
   * Lay link anh thumbnail<br />
   * Vi du su dung:<br />
   * <img src="<?php VtHelper::getThumbUrl('/medias/huypv/2011/06/15/abc.jpg', 90, 60); ?>" />
   * @param string $source /medias/huypv/2011/06/15/abc.jpg (nam trong thu muc web!)
   * @author huypv
   * @param int $width
   * @param int $height
   * @return string /medias/huypv/2011/06/15/thumbs/abc_90_60.jpg
   */
  public static function getThumbUrl($source, $width = null, $height = null, $type = '')
  {
    switch ($type)
    {
      case 'app':
        $defaultImage = sfConfig::get('app_app_image_default');
        break;
      default:
        $defaultImage = sfConfig::get('app_image_default');
    }
    if ($width == null && $height == null)
      return (file_exists(sfConfig::get('sf_web_dir') . $source)) ? $source : $defaultImage;
    if (empty($source)) {
      return $defaultImage;
    }
    $mediasDir = sfConfig::get('sf_web_dir');
    $fullPath = $mediasDir . $source;
    $pos = strrpos($source, '/');
    if ($pos !== false) {
      $filename = substr($source, $pos + 1);

      $app = sfContext::getInstance()->getConfiguration()->getApplication();
      switch ($app)
      {
        case 'frontend':
          $dir = '/cache' . '/f_' . substr($source, 1, $pos);
          break;
        case 'backend':
          $dir = '/cache' . '/a_' . substr($source, 1, $pos);
          break;
      }
    } else {
      return $defaultImage;
    }
    $pos = strrpos($filename, '.');
    if ($pos !== false) {
      $basename = substr($filename, 0, $pos);
      $extension = substr($filename, $pos + 1);
    } else {
      return $defaultImage;
    }
    if ($width == null) {
      $thumbName = $basename . '_auto_' . $height . '.' . $extension;
    } else if ($height == null) {
      $thumbName = $basename . '_' . $width . '_auto.' . $extension;
    } else {
      $thumbName = $basename . '_' . $width . '_' . $height . '.' . $extension;
    }
    $fullThumbPath = $mediasDir . $dir . $thumbName;
    # Neu thumbnail da ton tai roi thi khong can generate
    if (file_exists($fullThumbPath)) {
      return $dir . $thumbName;
    }
    # Neu thumbnail chua ton tai thi su dung plugin de tao ra
    $scale = ($width != null && $height != null) ? false : true;
    $thumbnail = new sfThumbnail($width, $height, $scale, true, 100);
    if (!is_file($fullPath)) {
      return $defaultImage;
    }
    $thumbnail->loadFile($fullPath);
    if (!is_dir($mediasDir . $dir)) mkdir($mediasDir . $dir, 0777, true);
    $thumbnail->save($fullThumbPath, 'image/jpeg');
    return (file_exists(sfConfig::get('sf_web_dir') . $dir . $thumbName)) ? $dir . $thumbName : $defaultImage;
  }

  public static function getExtension($fileName)
  {
    $pos = strrpos($fileName, '.');
    if ($pos !== false) {
      $ext = substr($fileName, $pos + 1);
    } else {
      $ext = 'bin';
    }
    return $ext;
  }


  /**
   * Tao ra 1 ten UUID (Universally Unique IDentifier)
   * @author huypv5
   * @created on 03 23, 2011
   * @param string $dir
   * @param int $timestamp
   * @param string $ext
   * @return string
   */
  public static function generateUniqueFileName($ext)
  {
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
      // 32 bits for "time_low"
      mt_rand(0, 0xffff), mt_rand(0, 0xffff),
      // 16 bits for "time_mid"
      mt_rand(0, 0xffff),
      // 16 bits for "time_hi_and_version",
      // four most significant bits holds version number 4
      mt_rand(0, 0x0fff) | 0x4000,
      // 16 bits, 8 bits for "clk_seq_hi_res",
      // 8 bits for "clk_seq_low",
      // two most significant bits holds zero and one for variant DCE1.1
      mt_rand(0, 0x3fff) | 0x8000,
      // 48 bits for "node"
      mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    ) . '.' . $ext;
  }


  /**
   * Tra ve duong dan toi file default
   * @author NamDT5
   * @created on Apr 21, 2011
   * @param string $type
   */
  public static function getDefault($w = null, $h = null, $type = 'media')
  {
    // 'http://'. sfConfig::get('app_domain'). '/'.
    $domain = $domain = 'http://' . $_SERVER['HTTP_HOST'] . '/';
    if (!$w && !$h) {
      // Neu khong truyen width height thi tra ve anh default goc
      return $domain . sfConfig::get('app_default_' . $type . '_path');
    }
    $thumbName = $type . '_' . (($w) ? $w : 'auto') . '_' . (($h) ? $h : 'auto') . '.jpg';
    $thumbPath = sfConfig::get('sf_web_dir') . '/medias/defaults/';
    switch ($type)
    {
      case 'media':
        $source = sfConfig::get('sf_web_dir') . '/' . sfConfig::get('app_default_media_path');
        break;
      case 'avatar':
        $source = sfConfig::get('sf_web_dir') . '/' . sfConfig::get('app_default_avatar_path');
        break;
      default:
    }
    $scale = ($w != null && $h != null) ? false : true;
    $thumbnail = new sfThumbnail($w, $h, $scale, true, 90);
    $thumbnail->loadFile($source);
    // Luu anh thumb
    $thumbnail->save($thumbPath . $thumbName, 'image/jpeg');
    return $domain . 'medias/defaults/' . $thumbName;
    die();
  }

  public static function genRandomString($length = 8)
  {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $string = '';
    for ($p = 0; $p < $length; $p++) {
      $string .= $characters[mt_rand(0, strlen($characters) - 1)];
    }
    return $string;
  }

  /**
   * Ham tra cat ky tu tieng viet
   * @param string $text
   * @param int $length
   * @param string $truncateString
   * @param string $truncateLastspace
   * @throws dmException
   */
  public static function truncate($text, $length = 30, $truncateString = '...', $truncateLastspace = false)
  {
    if (is_array($text)) {
      throw new dmException('Can not truncate an array: ' . implode(', ', $text));
    }
    $text = (string)$text;
    if (extension_loaded('mbstring')) {
      $strlen = 'mb_strlen';
      $substr = 'mb_substr';
    }
    else
    {
      $strlen = 'strlen';
      $substr = 'substr';
    }
    if ($strlen($text) > $length) {
      $text = $substr($text, 0, $length - $strlen($truncateString));

      if ($truncateLastspace) {
        $text = preg_replace('/\s+?(\S+)?$/', '', $text);
      }
      $text = $text . $truncateString;
    }
    return $text;
  }
}
