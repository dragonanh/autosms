<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of VtHelper
 * @author vas_tungtd2
 */
class VtHelper {

    const MOBILE_SIMPLE = '09x';
    const MOBILE_GLOBAL = '849x';
    const MOBILE_NOTPREFIX = '9x';
    const VT_MSISDN_PATTERN = '/^8486\d{7}$|^8496\d{7}$|^0?96\d{7}$|^8497\d{7}$|^0?97\d{7}$|^8498\d{7}$|^0?98\d{7}$|^8416\d{8}$|^0?16\d{8}$/'; // So dien thoai viettel
    const SALT = 'whateveryouwant';
    const MY_PBKDF2_SALT = "\x2d\xb7\x68\x1a\x28\x15\xbe\x06\x33\xa0\x7e\x0e\x8f\x79\xd5\xdf";
    public static function getMobileNumber($msisdn, $type, $trim = true) {
        if (empty($type)) {
            $type = self::MOBILE_SIMPLE;
        }

        if ($trim) {
            $msisdn = trim($msisdn);
        }
        //loai bo so + dau tien doi voi dinh dang +84
        if ($msisdn[0] == '+') {
            $msisdn = substr($msisdn, 1);
        }
        switch ($type) {
            case self::MOBILE_GLOBAL:
                if ($msisdn[0] == '0') {
                    return '84' . substr($msisdn, 1);
                } else if ($msisdn[0] . $msisdn[1] != '84') {
                    return '84' . $msisdn;
                } else {
                    return $msisdn;
                }
                break;
            case self::MOBILE_SIMPLE:
                if ($msisdn[0] . $msisdn[1] == '84')
                    return '0' . substr($msisdn, 2);
                else if ($msisdn[0] != '0')
                    return '0' . $msisdn;
                else
                    return $msisdn;
                break;
            case self::MOBILE_NOTPREFIX:
                if ($msisdn[0] == '0') {
                    return substr($msisdn, 1);
                } elseif (strlen($msisdn) >=2 && $msisdn[0] . $msisdn[1] == '84') {
                    return substr($msisdn, 2);
                } else {
                    return $msisdn;
                }
                break;
        }
    }

    public static function truncate($text, $length = 30, $truncateString = '...', $truncateLastspace = true, $escSpecialChars = false) {
        if (sfConfig::get('sf_escaping_method') == 'ESC_SPECIALCHARS') {
            $text = htmlspecialchars_decode($text, ENT_QUOTES);
        }

        $text = (string) $text;

        if (extension_loaded('mbstring')) {
            $strlen = 'mb_strlen';
            $substr = 'mb_substr';
//hatt12 them dong nay de dem ky tu tieng viet
            $countStr = $strlen($text, 'utf-8');
            if ($countStr > $length) {
                $text = $substr($text, 0, $length, 'utf-8');

                if ($truncateLastspace) {
                    $text = preg_replace('/\s+?(\S+)?$/', '', $text);
                }

                $text = $text . $truncateString;
            }
        } else {
            $strlen = 'strlen';
            $substr = 'substr';
            $countStr = $strlen($text);
            if ($countStr > $length) {
                $text = $substr($text, 0, $length);
                if ($truncateLastspace) {
                    $text = preg_replace('/\s+?(\S+)?$/', '', $text);
                }

                $text = $text . $truncateString;
            }
        }
        if ($escSpecialChars) {
            return $text;
        } else {
            return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
        }
    }

    /**
     * preProcess query search backsplash (\) tat ca doan code search like trong PHP ma can tim ky tu dac biet deu phai goi ham nay
     * @author tuanbm
     * @date 2012/06/12
     * @return string
     */
    public static function preProcessForSearchLike($param) {
        return addslashes($param);
    }

    /**
     * @author ChuyenNV2
     * get a string with number character input
     * @static
     * @param $strInput
     * @param $maxString
     * @return string
     */
    public static function getStringMaxLength($strInput, $maxString) {
        //tuanbm su dung 1 ham duy nhat, fix loi tren ham getLimitString
        return self::getLimitString($strInput, $maxString);
    }

    public static function subString($str, $length = 22, $truncateString = '...', $truncateLastspace = true) {
        $str = self::replaceSpecialCharsFromWord($str);
        $str = (string) $str;
        if (extension_loaded('mbstring')) {
            $strlen = 'mb_strlen';
            $substr = 'mb_substr';
        } else {
            $strlen = 'strlen';
            $substr = 'substr';
        }

        if ($strlen($str) > $length) {
            if ($substr == 'mb_substr') {
                $str = $substr($str, 0, $length - $strlen($truncateString), 'UTF-8');
            } else {
                $str = $substr($str, 0, $length - $strlen($truncateString));
            }
            if ($truncateLastspace) {
                $str = preg_replace('/\s+?(\S+)?$/', '', $str);
            }
            $str = $str . $truncateString;
        }
        return $str;
    }

    public static function replaceSpecialCharsFromWord($strInput) {
        $strInput = str_replace('“', '"', $strInput);
        $strInput = str_replace('�?', '"', $strInput);
        return $strInput;
    }

    public static function getLimitString($strInput, $limit = 10) {
        $strInput = self::replaceSpecialCharsFromWord($strInput);
        //chuyennv2
        if ($strInput == '')
            return '';
        //tuanbm2 them decode truoc khi substring
        $str = vtSecurity::encodeOutput(VtHelper::subString(vtSecurity::decodeInput($strInput), $limit, '...', true));

        return $str;
    }

    //truong hop $strInput ko bi encode boi symfony, return tu dong encode anti XSS
    public static function getLimitStringWithoutEncode($strInput, $limit = 10) {
        $strInput = self::replaceSpecialCharsFromWord($strInput);

        $resultReturn = vtSecurity::encodeOutput(VtHelper::subString($strInput, $limit, '...', true));
        return $resultReturn;
    }

    /*
     * @author tuanbm
     * Ham gui email toi nguoi dung
     * @static
     * @param: $to: Email toi nguoi nhan
     * @param $title: tieu de email
     * @param $body: noi dung email
     * $return (The number of sent emails)
     * 0: la khong co email nao duoc gui di
     * 1: la gui email thanh cong
     * -1: la co loi gui email
     */

    public static function SendEmail($to, $title, $body) {
        try {
            $mailer = sfContext::getInstance()->getMailer();
            $from = sfConfig::get('app_email_from','dragon.new.no1@gmail.com');
            $message = $mailer->compose();
            $message->setSubject($title);
            $message->setTo($to);
            $message->setFrom($from);
            $message->setBody($body, 'text/html'); //text/plain
            //      $result = $mailer->composeAndSend($from, $to, $title, $body);
            $result = $mailer->send($message);
            return $result;
        } catch (exception $e) {
            //ghi log gui that bai
            $logger = VtHelper::getLogger4Php('all');
            $logger->info(sprintf('[SEND MAIL ERROR] %s | params: {"to":"%s","$title":"%s"}', $e->getMessage(), $to,$title));
            return 0;
        }
    }

    /*
     * * Date: 2014/04/17
     * @author tuanbm
     * Ham get Image Thumbnail, de tra ve duong dan tuyet doi (Anh Thumbnail)
     * @static
     * return: link day du ca http://server/media/....
     * EX: $folderThumb="thumbnail",$width=150,$height=150,$configDefaultImage = "app_url_media_default_image"
     */

    private static function createImageThumbnail($imageName, $folderThumbName, $width, $height, $configDefaultImage = "app_url_media_default_image") {
        try {
            //tuanbm: thu check xem Main Image co ton tai khong, neu ton tai thi generate ra anh thumbnail (Cai nay Do Keeng migrate ve)
            $full_path_file = sfConfig::get("app_upload_media_images") . "/" . $folderThumbName . "/" . $imageName;
            $originalImage = sfConfig::get("app_upload_media_images") . "/" . $imageName;
            if (is_file($originalImage)) {
                $file_name = basename($full_path_file); //test.jpg
                $folderThumb = str_replace($file_name, "", $full_path_file); //duong dan file
                if (!is_dir($folderThumb)) {
                    @mkdir($folderThumb, 0777, true);
                }
                //neu ton tai $originalImage thi generate no ra anh thumbnail
                if ($height == 0) {
                    $thumbnail = new sfThumbnail($width, $height, true, true, 60);
                } else {
                    $thumbnail = new sfThumbnail($width, $height, false, true, 60);
                }

                $thumbnail->loadFile($originalImage);
                $thumbnail->save($full_path_file, 'image/jpeg');
                return sfConfig::get('app_url_media_images') . "/" . $folderThumbName . "/" . $imageName;
            }
            return sfConfig::get($configDefaultImage);
        } catch (Exception $ex) {
            return sfConfig::get($configDefaultImage);
        }
    }

    /*
     * Date: 2014/04/17
     * @author tuanbm
     * Ham get Image Thumbnail, de tra ve duong dan tuyet doi (Anh Thumbnail)
     * @static
     * return: link day du ca http://server/media/....

     */

    public static function generateStructurePath($uniqueFileName) {
        //$uiq = uniqid(1,true);
        //$fileName = hash('sha1',$uiq);
        $mash = 255;
        $hashCode = crc32($uniqueFileName); //md5(serialize($fileName));
        $firstDir = $hashCode & $mash;
        $firstDir = vsprintf("%02x", $firstDir);
        $secondDir = ($hashCode >> 8) & $mash;
        $secondDir = vsprintf("%02x", $secondDir);
        $thirdDir = ($hashCode >> 4) & $mash;
        $thirdDir = vsprintf("%02x", $thirdDir);
        return $firstDir . "/" . $secondDir . "/" . $thirdDir;
    }

    public static function getUrlImagePathThumb($imageName, $width = 200, $height = 200, $configDefaultImage = "app_url_media_default_image") {
        try {
            if (strlen($imageName) == 0) {

                return VtHelper::getThumbUrl(sfConfig::get($configDefaultImage), $width, $height);
            } else {
                //them 1 doan code check exits file, neu ko ton tai thi cung hidden di
                //u01/apps/imuzik/cms-web/web/uploads/images
                $imageName = ltrim($imageName, "/");
                $folderThumbnail = "thumbnail" . $width . "x" . $height;
                $filename = sfConfig::get("app_upload_media_images") . "/" . $folderThumbnail . "/" . $imageName;
                if (is_file($filename)) {
                    return sfConfig::get('app_url_media_images') . "/" . $folderThumbnail . "/" . $imageName;
                } else {
                    return self::createImageThumbnail($imageName, $folderThumbnail, $width, $height, $configDefaultImage);
                    //return sfConfig::get($configDefaultImage);
                }
            }
        } catch (Exception $e) {
            return VtHelper::getThumbUrl(sfConfig::get($configDefaultImage), $width, $height);
        }
    }

    /*
     * @author tuanbm
     * Ham get Image Thumbnail, de tra ve duong dan tuyet doi (Anh Thumbnail)
     * @static
     * return: link day du ca http://server/media/....
     */

    public static function getUrlImagePathBigThumb($objectStr, $imageName, $configDefaultImage = "app_url_media_default_image", $is_keeng = 0) {
        try {
            if (strlen($imageName) == 0) {
                return sfConfig::get($configDefaultImage);
            } else {
                if ($is_keeng == 1) {
                    //http://media2.keeng.vn/medias
                    //return "http://media2.keeng.vn/".$imageName;
                    $pos = strrpos($imageName, "/medias2/");
                    if ($pos === 0) {
                        return sfConfig::get("app_url_media2_images_keeng", "http://media2.keeng.vn") . "/medias/" . substr($imageName, 8, strlen($imageName) - 8);
                    } else {
                        return sfConfig::get("app_url_media_images_keeng", "http://media.keeng.vn") . $imageName;
                    }
                } else {
                    //them 1 doan code check exits file, neu ko ton tai thi cung hidden di
                    //u01/apps/imuzik/cms-web/web/uploads/images
                    $imageName = ltrim($imageName, "/");
                    $filename = sfConfig::get("app_upload_media_images") . "/" . $objectStr . "/thumbnail940/" . $imageName;
                    if (is_file($filename)) {
                        return sfConfig::get('app_url_media_images') . "/" . $objectStr . "/thumbnail940/" . $imageName;
                    } else {
                        //tuanbm: thu check xem Main Image co ton tai khong, neu ton tai thi generate ra anh thumbnail (Cai nay Do Keeng migrate ve)
                        return self::createImageThumbnail($objectStr, $imageName, "thumbnail400", 940, 450, $configDefaultImage);
                        //return sfConfig::get($configDefaultImage);
                    }
                }
                //return sfConfig::get('app_url_media_images') . "/" . $objectStr . "/thumbnail/" . $imageName;
            }
        } catch (Exception $e) {
            /* rethrow it */
            return sfConfig::get($configDefaultImage);
        }
    }

    /*
     * @author tuanbm
     * Ham get Image Thumbnail, de tra ve duong dan tuyet doi (Anh Thumbnail)
     * @static
     * return: link day du ca http://server/media/....
     */

    public static function getUrlImagePathMediumThumb($objectStr, $imageName, $configDefaultImage = "app_url_media_default_image", $is_keeng = 0) {
        try {
            if (strlen($imageName) == 0) {
                return sfConfig::get($configDefaultImage);
            } else {
                if ($is_keeng == 1) {
                    $pos = strrpos($imageName, "/medias2/");
                    if ($pos === 0) {
                        return sfConfig::get("app_url_media2_images_keeng", "http://media2.keeng.vn") . "/medias/" . substr($imageName, 8, strlen($imageName) - 8);
                    } else {
                        return sfConfig::get("app_url_media_images_keeng", "http://media.keeng.vn") . $imageName;
                    }
                } else {
                    //them 1 doan code check exits file, neu ko ton tai thi cung hidden di
                    //u01/apps/imuzik/cms-web/web/uploads/images
                    $imageName = ltrim($imageName, "/");
                    //tungtd2 sua check file ton tai file trong thu muc upload cua web chu khong check theo keeng
                    $filename = sfConfig::get("app_upload_media_images") . "/" . $objectStr . "/thumbnail440/" . $imageName;
                    $root = sfConfig::get('sf_root_dir') . '/web';
                    if (is_file($root . $filename)) {
                        return sfConfig::get('app_upload_media_images') . "/" . $objectStr . "/thumbnail440/" . $imageName;
                    } else {
                        //tuanbm: thu check xem Main Image co ton tai khong, neu ton tai thi generate ra anh thumbnail (Cai nay Do Keeng migrate ve)
                        return self::createImageThumbnail($objectStr, $imageName, "thumbnail440", 440, 310, $configDefaultImage);
                        //return sfConfig::get($configDefaultImage);
                    }
                }

                //return sfConfig::get('app_url_media_images') . "/" . $objectStr . "/thumbnail/" . $imageName;
            }
        } catch (Exception $e) {
            /* rethrow it */
            return sfConfig::get($configDefaultImage);
        }
    }

    public static function getFullDirectoryImageFile($imageName) {
        return sfConfig::get('sf_web_dir') . $imageName;
    }

    /**
     * @author hoangl
     * Ham loai bo tat ca cac the html
     * @static
     * @param       $str - Xau can loai bo tag
     * @param array $tags - Mang cac tag se strip, vi du: array('a', 'b')
     * @param bool  $stripContent
     * @return mixed|string
     */
    public static function encodeOutput($string, $force = true) {
        if (sfConfig::get('sf_escaping_strategy')
            && sfConfig::get('sf_escaping_method') == "ESC_SPECIALCHARS" && $force == false
        ) {
            return $string;
        } else {
            return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
            //        return htmlentities($string, ENT_QUOTES, 'UTF-8');
        }
    }

    /**
     * @author tuanbm2
     * Ham loai bo tat ca cac the html mac dinh loai bo array('script', 'iframe', 'noscript')
     * @static
     * @param       $str - Xau can loai bo tag
     * @param array $tags - Mang cac tag se strip, vi du: array('a', 'b')
     * @param bool  $stripContent
     * @example: echo VtHelper::strip_html_default_tags($article->getBody())
     * @return mixed|string
     */
    public static function strip_html_default_tags($str) {
        return VtHelper::strip_html_tags($str, array('script', 'iframe', 'noscript'));
    }

  public static function strip_html_tags_and_decode($str) {
    $str = htmlspecialchars_decode($str);
    $config = HTMLPurifier_Config::createDefault();
    $config->set('HTML.MaxImgLength', null);
    $config->set('CSS.MaxImgLength', null);
    $purifier = new HTMLPurifier($config);
    $clean_html = $purifier->purify($str);
    return $clean_html;
  }

    /**
     * @author hoangl
     * Ham loai bo tat ca cac the html
     * @static
     * @param       $str - Xau can loai bo tag
     * @param array $tags - Mang cac tag se strip, vi du: array('a', 'b')
     * @param bool  $stripContent
     * @example: <?php echo VtHelper::strip_html_tags($article->getBody(), array('script', 'iframe', 'noscript'))?>
     * @return mixed|string
     */
    public static function strip_html_tags($str, $tags = array(), $stripContent = false) {
        if (empty($tags)) {
            $tags = array("br/", "hr/", "!--...--", '!doctype', 'a', 'abbr', 'address', 'area', 'article', 'aside', 'audio', 'b', 'base', 'bb', 'bdo', 'blockquote', 'body', 'br', 'button', 'canvas', 'caption', 'cite', 'code', 'col', 'colgroup', 'command', 'datagrid', 'datalist', 'dd', 'del', 'details', 'dfn', 'div', 'dl', 'dt', 'em', 'embed', 'eventsource', 'fieldset', 'figcaption', 'figure', 'footer', 'form', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'head', 'header', 'hgroup', 'hr', 'html', 'i', 'iframe', 'img', 'input', 'ins', 'kbd', 'keygen', 'label', 'legend', 'li', 'link', 'mark', 'map', 'menu', 'meta', 'meter', 'nav', 'noscript', 'object', 'ol', 'optgroup', 'option', 'output', 'p', 'param', 'pre', 'progress', 'q', 'ruby', 'rp', 'rt', 'samp', 'script', 'section', 'select', 'small', 'source', 'span', 'strong', 'style', 'sub', 'summary', 'sup', 'table', 'tbody', 'td', 'textarea', 'tfoot', 'th', 'thead', 'time', 'title', 'tr', 'ul', 'var', 'video', 'wbr');
        }
        $content = '';
        if (!is_array($tags)) {
            $tags = (strpos($str, '>') !== false ? explode('>', str_replace('<', '', $tags)) : array($tags));
            if (end($tags) == '')
                array_pop($tags);
        }
        foreach ($tags as $tag) {
            if ($stripContent)
                $content = '(.+</' . $tag . '(>|\s[^>]*>)|)';
            $str = preg_replace('#</?' . $tag . '(>|\s[^>]*>)' . $content . '#is', '', $str);
        }

        $str = trim($str, ' ');

        return $str;
    }

    public static function strip_html_tags_no_script($str) {
        $str = VtHelper::strip_html_tags($str, array('script', 'iframe'));
        return $str;
    }

    //tuanbm ham xu ly convert cac ky tu dac biet @#|
    public static function replaceSpecialCharacterForXml($value) {
        //chu y: bat buoc phai tuan theo thu tu convert #, @ |
        //    $value = str_replace("#","&#35;",$value);
        //    $value = str_replace("@","&#64;",$value);
        //    $value = str_replace("|","&#124;",$value);
        //Su dung Ma Mo Rong ASCII de thay the ® ¶ ©
        return $value;
    }

    //huynq28 format number
    //format: 'x.yyy.zzz' default
    public static function formatNumber($number, $delimiter = ".") {
        return number_format($number, 0, $delimiter, $delimiter);
    }

    public static function generateString($length = 8) {

        $string = "";
        $possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ";

        $maxlength = strlen($possible);

        if ($length > $maxlength) {
            $length = $maxlength;
        }
        // set up a counter for how many characters are in
        $i = 0;
        // add random characters until $length is reached
        while ($i < $length) {
            // pick a random character from the possible ones
            $char = substr($possible, mt_rand(0, $maxlength - 1), 1);
            // have we already used this character?
            if (!strstr($string, $char)) {
                // no, so it's OK to add it onto the end of whatever we've already got...
                $string .= $char;

                $i++;
            }
        }
        return $string;
    }

    /**
     * Lay link anh thumbnail<br />
     * Vi du su dung:<br />
     * <img src="<?php VtHelper::getThumbUrl('/medias/2011/06/15/abc.jpg', 90, 60); ?>" />
     * @param string $source /medias/2011/06/15/abc.jpg (nam trong thu muc web!)
     * @param int    $width
     * @param int    $height
     * @return string /medias/2011/06/15/thumbs/abc_90_60.jpg
     */
    public static function getThumbUrl($source, $width = null, $height = null, $type = '') {
        $defaultImage = sfConfig::get('app_url_media_default_image');
        $source = self::getUrlImagePath($type, $source);
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
            if ($app == 'front') {
                $dir = '/cache' . '/f_' . substr($source, 1, $pos);
            } else if ($app == 'mobile') {
                $dir = '/cache' . '/m_' . substr($source, 1, $pos);
            } else if ($app == 'admin') {
                $dir = '/cache' . '/a_' . substr($source, 1, $pos);
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
            #return false;
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

        if (!is_dir($mediasDir . $dir))
            mkdir($mediasDir . $dir, 0777, true);
        $thumbnail->save($fullThumbPath, 'image/jpeg');
        return (file_exists(sfConfig::get('sf_web_dir') . $dir . $thumbName)) ? $dir . $thumbName : $defaultImage;
    }

    /**
     * Ham tra ve duong dan theo ngay thang nam hoac theo duong dan truyen vao
     * @author NamDT5
     * @created on 29/09/2012
     * @param string $path
     */
    public static function generatePath($path = '', $byDate = true) {
        if ($byDate) {
            if ($path)
                $folder = $path . '/' . date('Y') . '/' . date('m') . '/' . date('d') . "/";
            else
                $folder = date('Y') . '/' . date('m') . '/' . date('d') . "/";
        } else {
            $folder = $path . '/';
        }
        $fullDir = sfConfig::get('sf_web_dir') . $folder;
        if (!is_dir($fullDir)) {
            @mkdir($fullDir, 0777, true);
        }
        return $folder;
    }

    public static function formatDurationTime($duration, $delimiter = ':') {
        $seconds = $duration % 60;
        $minutes = floor($duration / 60);
        $hours = floor($duration / 3600);
        $seconds = str_pad($seconds, 2, "0", STR_PAD_LEFT);
        $minutes = str_pad($minutes, 2, "0", STR_PAD_LEFT) . $delimiter;
        if ($hours > 0) {
            $hours = str_pad($hours, 2, "0", STR_PAD_LEFT) . $delimiter;
        } else {
            $hours = '';
        }
        return "$hours$minutes$seconds";
    }

    /**
     * replace apostrophe
     * @param type $inputString
     * @return string
     */
    public static function replaceApostrophe($inputString) {
        if (!$inputString)
            return "";
        return str_replace("'", "\'", vtSecurity::decodeInput($inputString));
    }

    /**
     * Replace het cac ky tu dac biet
     * @param $str
     * @return mixed
     */
    public static function replaceSpecialChar($str) {
        $specialChar = array(
            unichr(160), //'\xA0',     // space
            # '\x60',     //
            # '\xB4',     //
            unichr(8216), // '\x2018',   // left single quotation mark
            unichr(8217), // '\x2019',   // right single quotation mark
            unichr(8220), // '\x201C',   // left double quotation mark
            unichr(8221), // '\x201D'    // right double quotation mark
            unichr(130), // baseline single quote
            unichr(145), // left single quote
            unichr(146), // right single quote)
            unichr(147), // right single quote)
            unichr(148), // right single quote)
        );
        $specialCharReplace = array(
            ' ', // space
            # '\x60',     //
            # '\xB4',     //
            "'", // left single quotation mark
            "'", // right single quotation mark
            '"', // left double quotation mark
            '"', // right double quotation mark
            ',', // baseline single quote
            "'", // 145
            "'", // 146
            '"', // 147
            '"', // 148
        );
        return str_replace($specialChar, $specialCharReplace, $str);
    }

    /**
     * author: thongnq1
     * han thuc hien kiem tra xem so thue bao co phai cua viettel khong
     */
    public static function checkViettelPhoneNumber($phoneNumber) {
        if (preg_match(self::VT_MSISDN_PATTERN, $phoneNumber)) {
            return true;
        }
        return false;
    }

    public static function datapost($URLServer, $postdata) {
        $agent = "Mozilla/5.0";
        $cURL_Session = curl_init();
        curl_setopt($cURL_Session, CURLOPT_URL, $URLServer);
        curl_setopt($cURL_Session, CURLOPT_USERAGENT, $agent);
        curl_setopt($cURL_Session, CURLOPT_POST, 1);
        curl_setopt($cURL_Session, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($cURL_Session, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($cURL_Session, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($cURL_Session);
        return $result;
    }

    /**
     * @author loilv4
     * Ham loai bo tat ca cac the html mac dinh loai bo array('script', 'iframe', 'noscript') va loai bo ca the 'p'
     */
    public static function strip_html_tags_and_decode_p($str) {
        //Ham nay chi duoc dung doi voi get du lieu hien thi sf_escaping_strategy = 1 va sf_escaping_method=ESC_SPECIALCHARS
        //tuyet doi ko dung de remove truoc khi save du lieu
        //do symfony tu dong encode HTML nen phai decode truoc khi remove Script
        $str = htmlspecialchars_decode($str); //co the dung ham $object->getSomething(ESC_RAW);//htmlspecialchars_decode($str, ENT_QUOTES);
        $str = VtHelper::strip_html_tags($str, array('script', 'iframe', 'noscript', 'embed', 'p'));
        return str_replace('<embed ', '', $str);
    }

    /**
     * Check datetime
     * @author HoangL
     * @param $dateTime
     * @return bool
     */
    public static function checkDateTime($dateTime) {
        if (preg_match("/^(\d{2})-(\d{2})-(\d{4}) ([01][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/", $dateTime, $matches)) {
            if (count($matches) >= 6 && checkdate($matches[2], $matches[1], $matches[3])) {
                return true;
            }
        }
        return false;
    }


    public static function startsWith($haystack, $needle) {
        return !strncmp($haystack, $needle, strlen($needle));
    }

    /**
     * @author ngoctv
     * Ham kiem tra mot ky tu hay mot chuoi co trong phan duoi cua chuoi khac hay khong, neu co thi tra ve true nguoc lai la false
     * @param type $haystack
     * @param type $needle
     * @return boolean
     */
    public static function endsWith($haystack, $needle) {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }
        return (substr($haystack, -$length) === $needle);
    }

    public static function translateQuery($str, $trim = true) {
        if ($str == null || $str == '')
            return $str;
        $str = $trim ? trim($str) : $str;
        $str = addcslashes($str, "\\%_");
        return $str;
    }

    public static function genPassword($length=6)
    {
        # first character is capitalize
        $pass =  chr(mt_rand(65,90));    // A-Z

        # rest are either 0-9 or a-z
        for($k=0; $k < $length - 1; $k++)
        {
            $probab = mt_rand(1,10);

            if($probab <= 8)   // a-z probability is 80%
                $pass .= chr(mt_rand(97,122));
            else            // 0-9 probability is 20%
                $pass .= chr(mt_rand(48, 57));
        }
        return $pass;
    }

    public static function generateCode($length = 8){
        return substr(md5(uniqid(mt_rand(), true)) , 0, $length);
    }

    /**
     * Ham kiem tra 2 khoang thoi gian co bi trung nhau khong
     * @author anhbhv
     * @created on 16/10/2014
     * @param $start_one
     * @param $end_one
     * @param $start_two
     * @param $end_two
     * @return int
     */
    public static function datesOverlap($start_one, $end_one, $start_two, $end_two)
    {
        $start_one = new DateTime($start_one);
        $end_one = new DateTime($end_one);
        $start_two = new DateTime($start_two);
        $end_two = new DateTime($end_two);
        if ($start_one <= $end_two && $end_one >= $start_two) { //If the dates overlap
            return min($end_one, $end_two)->diff(max($start_two, $start_one))->days + 1; //return how many days overlap
        }

        return 0; //Return 0 if there is no overlap
    }

    /**
     * Ham kiem tra bat dau cua mot chuoi co ton tai trong mang truyen vao ko
     * VD: $sourceString = 01654926551 , $sourceArray = array('097', '098', '0165') --> result = true
     * @author anhbhv
     * @param $sourceArray
     * @param $sourceString
     * @return bool
     */
    public static function checkBeginOfStringInArray($sourceArray, $sourceString){
        foreach ($sourceArray as $key => $search) {
            if (strpos($sourceString, $search) === 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * Ham tra ve cac thiet lap cua he thong
     * @author NamDT5
     * @created on Apr 21, 2011
     * @param $key - Key hoac 1 mang cac key can lay. Neu = null -> lay tat ca thiet lap he thong
     * @return array hoac string
     */
    public static function getSystemSetting($key = null, $useCache = true, $default = null)
    {
        if ($useCache) {
            $cache = new sfFileCache(array('cache_dir' => sfConfig::get('sf_cache_dir') . '/function'));
            $cache->setOption('lifetime', 86400);
            $fc = new sfFunctionCache($cache);
            $result = $fc->call('VtHelper::getSystemSetting', array('key' => $key, 'useCache' => false));
            return $result != null ? $result : $default;
        }

        $result = array();
        $query = SettingTable::getInstance()->createQuery()->select('code, content');
        $fetchOne = false;
        if (!empty($key)) {
            if (is_array($key)) {
                $query = $query->andWhereIn('code', $key);
            } else if (is_string($key)) {
                $query = $query->andWhere('code = ?', $key);
                $fetchOne = true;
            }
        }

        $pixConfig = $query->fetchArray();

        if (count($pixConfig)) {
            // Tra ve gia tri cua 1 config
            if ($fetchOne)
                return $pixConfig[0]['content'];
            // Tra ve 1 mang cac config
            foreach ($pixConfig as $config) {
                $result[$config['code']] = $config['content'];
            }
        } else
            return $default;
        return $result;
    }

    public static function remove_utf8_bom($text)
    {
        $bom = pack('H*','EFBBBF');
        $text = preg_replace("/^$bom/", '', $text);
        return $text;
    }

    /**
     * Ham tra ve so ngay giua 2 ngay truyen vao
     * @author anhbhv
     * @param $date1
     * @param $date2
     * @return mixed
     */
    public static function getNumOfDayBetweenTwoDate($date1, $date2){
        $date1 = new DateTime($date1);
        $date2 = new DateTime($date2);
        return $date2->diff($date1)->days + 1;
    }

    /**
     * @author: tuanbm2
     * @Description: Ham xu ly hide xxx o duoi SDT
     * @param $isdn
     * @return string
     */
    public static function getIsdnXXX($isdn){
        $length = strlen($isdn);
        $lengthCut = sfConfig::get("app_isdn_xxx",4);
        if($length>$lengthCut){
            $isdnXXX=substr($isdn,0,$length-$lengthCut).str_repeat("x",$lengthCut);
            return $isdnXXX;
        }
        return $isdn;
    }


    private static $configurator4php=null;
    private static $config4php=null;

    /**
     * @author: tuanbm2
     * @description: su dung khoi tao Log4PHP 1 lan cho request
     * @param $content
     * @param string $loggerName
     * @param string $type
     */
    public static function writeLog4Php($content,$loggerName = 'all',$type="info") {
        if(self::$config4php==null){
            self::$configurator4php = new LoggerConfiguratorDefault();
            self::$config4php = self::$configurator4php->parse(sfConfig::get('sf_config_dir').'/log4php.xml');
            Logger::configure(self::$config4php);
        }
        $logger = Logger::getLogger($loggerName);
        $logger->$type($content);
    }
    public static function getLogger4Php($loggerName) {
        if(self::$config4php==null){
            self::$configurator4php = new LoggerConfiguratorDefault();
            self::$config4php = self::$configurator4php->parse(sfConfig::get('sf_config_dir').'/log4php.xml');
            Logger::configure(self::$config4php);
        }
        $logger = Logger::getLogger($loggerName);
        return $logger;
    }

    public static function validateToken($token) {
        $baseForm = new BaseForm();
        return $baseForm->getCSRFToken() == $token;
    }

    public static function getPercentDisplay($value1, $value2){
        return ((1-round($value2/$value1,2))*100).'%';
    }

    public static function convertNumberToString($number){
      $no = round($number);
      $words = array('0' => '', '1' => 'một', '2' => 'hai',
        '3' => 'ba', '4' => 'bốn', '5' => 'năm', '6' => 'sáu',
        '7' => 'bảy', '8' => 'tám', '9' => 'chín',
        '10' => 'mười');
      if($no <= 10)
        return $words[$no];
      $digits_1 = strlen($no);
      $i = 0;
      $str = array();
      $digits = array('', 'trăm', 'nghìn', 'triệu', 'tỉ');
      while ($i < $digits_1) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += ($divider == 10) ? 1 : 2;
        if ($number) {
          $counter = count($str);
          if($number < 19)
            $str [] = " mười ".$words[$number];
          else
            $str [] = $words[floor($number / 10)]." mươi ".$words[$number % 10]. $digits[$counter];
        } else $str[] = null;
      }
      $str = array_reverse($str);
      return implode('', $str);
    }

  public static function convert_number_to_words($number) {

    $hyphen      = ' ';
    $conjunction = '  ';
    $separator   = ' ';
    $negative    = 'âm ';
    $decimal     = ' phẩy ';
    $dictionary  = array(
      0                   => 'Không',
      1                   => 'Một',
      2                   => 'Hai',
      3                   => 'Ba',
      4                   => 'Bốn',
      5                   => 'Năm',
      6                   => 'Sáu',
      7                   => 'Bảy',
      8                   => 'Tám',
      9                   => 'Chín',
      10                  => 'Mười',
      11                  => 'Mười một',
      12                  => 'Mười hai',
      13                  => 'Mười ba',
      14                  => 'Mười bốn',
      15                  => 'Mười năm',
      16                  => 'Mười sáu',
      17                  => 'Mười bảy',
      18                  => 'Mười tám',
      19                  => 'Mười chín',
      20                  => 'Hai mươi',
      30                  => 'Ba mươi',
      40                  => 'Bốn mươi',
      50                  => 'Năm mươi',
      60                  => 'Sáu mươi',
      70                  => 'Bảy mươi',
      80                  => 'Tám mươi',
      90                  => 'Chín mươi',
      100                 => 'trăm',
      1000                => 'ngàn',
      1000000             => 'triệu',
      1000000000          => 'tỷ',
      1000000000000       => 'nghìn tỷ',
      1000000000000000    => 'ngàn triệu triệu',
      1000000000000000000 => 'tỷ tỷ'
    );

    if (!is_numeric($number)) {
      return false;
    }

    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
// overflow
      trigger_error(
        'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
        E_USER_WARNING
      );
      return false;
    }

    if ($number < 0) {
      return $negative . self::convert_number_to_words(abs($number));
    }

    $fraction = null;

    if (strpos($number, '.') !== false) {
      list($number, $fraction) = explode('.', $number);
    }

    switch (true) {
      case $number < 21:
        $string = $dictionary[$number];
        break;
      case $number < 100:
        $tens   = ((int) ($number / 10)) * 10;
        $units  = $number % 10;
        $string = $dictionary[$tens];
        if ($units) {
          $string .= $hyphen . $dictionary[$units];
        }
        break;
      case $number < 1000:
        $hundreds  = $number / 100;
        $remainder = $number % 100;
        $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
        if ($remainder) {
          $string .= $conjunction . self::convert_number_to_words($remainder);
        }
        break;
      default:
        $baseUnit = pow(1000, floor(log($number, 1000)));
        $numBaseUnits = (int) ($number / $baseUnit);
        $remainder = $number % $baseUnit;
        $string = self::convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
        if ($remainder) {
          $string .= $remainder < 100 ? $conjunction : $separator;
          $string .= self::convert_number_to_words($remainder);
        }
        break;
    }

    if (null !== $fraction && is_numeric($fraction)) {
      $string .= $decimal;
      $words = array();
      foreach (str_split((string) $fraction) as $number) {
        $words[] = $dictionary[$number];
      }
      $string .= implode(' ', $words);
    }

    return $string;
  }

  public static function getClientIp() {
    if (isset($_SERVER['HTTP_CLIENT_IP']) && $_SERVER['HTTP_CLIENT_IP'])
      $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'])
      $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_X_FORWARDED']) && $_SERVER['HTTP_X_FORWARDED'])
      $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if (isset($_SERVER['HTTP_FORWARDED_FOR']) && $_SERVER['HTTP_FORWARDED_FOR'])
      $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_FORWARDED']) && $_SERVER['HTTP_FORWARDED'])
      $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'])
      $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
      $ipaddress = 'UNKNOWN';

    return $ipaddress;
  }

  public static function hideSimNumberOrder($sim){
      return substr_replace(substr_replace(substr_replace($sim,'x',2,1),'x',5,1),'x',8,1);
  }

    // xoa cache cho component
    // muon xoa het thi de $key la *
    public static function deleteCacheKeyOfComponent($module,$partial,$key, $apps = null)
    {
        $cacheKey = "@sf_cache_partial?module=".$module."&action=".$partial."&sf_cache_key=".$key;
        return self::deleteCacheKey($cacheKey, $apps);
    }


    // xoa cache cho page
    public static function deleteCacheKey($cacheKey,$apps = null)
    {
        if($apps){
            $currentApps = sfContext::getInstance()->getConfiguration()->getApplication();
            sfContext::switchTo($apps);
            $cache = new sfFileCache(array(
              'automatic_cleaning_factor' => 0,
              'prefix' => sfConfig::get('sf_app_dir').'/'.$apps.'/template:',
              'cache_dir' => sfConfig::get('sf_cache_dir').'/'.$apps.'/prod/template',
            ));
            $cacheManger = new sfViewCacheManager(sfContext::getInstance(),$cache);
            $cacheManger->remove($cacheKey);
            sfContext::switchTo($currentApps);
        }else
            sfContext::getInstance()->getViewCacheManager()->remove($cacheKey);

        return false;
    }

    public static function renderImg($source, $w = null, $h = null, $class = null, $configDefaultImage = "app_url_media_default_image") {
        try {
            if (strlen($source) == 0) {
                return sfConfig::get($configDefaultImage);
            } else {
                $filename = sfConfig::get("sf_upload_dir"). $source;
                if (is_file($filename)) {
                    return sfConfig::get('app_url_media') . self::getThumbUrl($source, $w, $h, $configDefaultImage);
                } else {
                    return sfConfig::get($configDefaultImage);
                }
            }
        } catch (Exception $e) {
            return sfConfig::get($configDefaultImage);
        }
    }

    public static function getUrlImagePath($imageName, $configDefaultImage = "app_url_media_default_image") {
        try {
            if (strlen($imageName) == 0) {
                return sfConfig::get($configDefaultImage);
            } else {
                $filename = sfConfig::get("sf_upload_dir") . $imageName;
                if (is_file($filename)) {
                    return sfConfig::get('app_url_media_images') . $imageName;
                } else {
                    return sfConfig::get($configDefaultImage);
                }
            }
        } catch (Exception $e) {
            return sfConfig::get($configDefaultImage);
        }
    }

    public static function checkCsrfToken($token, $secret){
        $form = new BaseForm();
        return $form->getCSRFToken($secret) == $token;
    }

    public static function setPager($query, $page, $modal, $maxPerPage){
      if($query) {
        $pages = ceil($query->count() / $maxPerPage);
        $pager = new sfDoctrinePager($modal, $maxPerPage);
        $pager->setQuery($query);
        $pager->setPage($page > $pages ? $pages : $page);
        $pager->init();
      }else{
        $pager = null;
      }
      return $pager;
    }
}

//@tungtd2
// function to call post webservice QTAN

/**
 * Chuyen doi ky tu ASCII ve dang chuan UNICODE
 * @author HoangL
 * @param        $unicode
 * @param string $encoding
 * @return string
 */
function unichr($unicode, $encoding = 'UTF-8') {
    return mb_convert_encoding("&#{$unicode};", $encoding, 'HTML-ENTITIES');
}

/**
 * Tra ve ma ASCII
 * @param        $string
 * @param string $encoding
 * @return mixed
 */
function uniord($string, $encoding = 'UTF-8') {
    $entity = mb_encode_numericentity($string, array(0x0, 0xffff, 0, 0xffff), $encoding);
    return preg_replace('`^&#([0-9]+);.*$`', '\\1', $entity);
}



