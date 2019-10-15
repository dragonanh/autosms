<?php

/*
 * @author noinh
 * read metadata file flv
 *
 */
class FLVMetaData
{

    private $buffer;
    private $metaData;
    public $fileName;
    private $typeFlagsAudio;
    private $typeFlagsVideo;

    /**
     * CONSTRUCTOR : initialize class members
     *
     * @param string $flv : flv file path
     */
    public function __construct($flv)
    {
        $this->fileName = $flv;
        $this->metaData = array(
            "duration" => null,
            "size" => null,
            "framerate" => null,
            "width" => null,
            "height" => null,
            "videodatarate" => null,
            "audiodatarate" => null,
            "audiodelay" => null,
            "audiosamplesize" => null,
            "audiosamplerate" => null,
            "audiocodecid" => null,
            "videocodecid" => null,
            "version" => null,
            "headersize" => 0
        );
    }

    /**
     * Gets metadata of FLV file
     *
     * @return array $this->metaData : matadata of FLV
     */
    public function getMetaData()
    {
        if (!file_exists($this->fileName)) {
            echo "Error! {$this->fileName} does not exist.";
            return false;
        }
        if (!is_readable($this->fileName)) {
            echo "Error! Could not read the file. Check the file permissions.";
            return false;
        }
        $f = @fopen($this->fileName, "rb");
        if (!$f) {
            echo "Try again to submit the file.";
            return;
        }
        $signature = fread($f, 3);
        if ($signature != "FLV") {
            echo "Error! Wrong file format.";
            // return false;
        }
        $this->metaData["version"] = ord(fread($f, 1));
        $this->metaData["size"] = filesize($this->fileName);
        $flags = ord(fread($f, 1));
        $flags = sprintf("%'04b", $flags);
        $this->typeFlagsAudio = substr($flags, 1, 1);
        $this->typeFlagsVideo = substr($flags, 3, 1);
        for ($i = 0; $i < 4; $i++) {
            $this->metaData["headersize"] += ord(fread($f, 1));
        }
        $this->buffer = fread($f, 400);
        fclose($f);
        if (strpos($this->buffer, "onMetaData") === false) {
            echo "Error! No MetaData Exists, try different file.";
            //return false;
        }
        foreach ($this->metaData as $k => $v) {
            $this->parseBuffer($k);
        }
        return $this->metaData;
    }

    /**
     * Takes a field name of metadata, retrieve it's value and set it in $this->metaData
     *
     * @param string $fieldName : matadata field name
     */
    private function parseBuffer($fieldName)
    {
        $fieldPos = strpos($this->buffer, $fieldName); //get the field position
        if ($fieldPos !== false) {
            $pos = $fieldPos + strlen($fieldName) + 1;
            $buffer = substr($this->buffer, $pos);
            $d = "";
            for ($i = 0; $i < 8; $i++) {
                $d .= sprintf("%08b", ord(substr($buffer, $i, 1)));
            }
            $total = self::bin2Double($d);
            $this->metaData[$fieldName] = $total;
        }
    }

    /**
     * Calculates double-precision value of given binary string
     * (IEEE Standard 754 - Floating Point Numbers)
     *
     * @param string binary data $strBin
     * @return Float calculated double-precision number
     */
    public static function bin2Double($strBin)
    {
        $sb = substr($strBin, 0, 1); // first bit is sign bit
        $exponent = substr($strBin, 1, 11); // 11 bits exponent
        $fraction = "1" . substr($strBin, 12, 52); //52 bits fraction (1.F)
        $s = pow(-1, bindec($sb));
        $dec = pow(2, (bindec($exponent) - 1023)); //Decode exponent
        if ($dec == 2047) {
            if ($fraction == 0) {
                if ($s == 0) {
                    echo "Infinity";
                } else {
                    echo "-Infinity";
                }
            } else {
                echo "NaN";
            }
        }
        if ($dec > 0 && $dec < 2047) {
            $t = 1;
            for ($i = 1; $i <= 53; $i++) {
                $t += ((int)substr($fraction, $i, 1)) * pow(2, -$i); //decode significand
            }
            $total = $s * $t * $dec;
            return $total;
        }
        return false;
    }
/*
 * @author: noinh
 * get duration movie
 * */
    public function getDurationFLV()
    {
        $this->getMetaData();

       return $duration = $this->metaData["duration"];

    }
}