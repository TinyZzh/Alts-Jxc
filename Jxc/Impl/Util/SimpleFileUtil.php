<?php
namespace Jxc\Impl\Util;
/**
 * Created by PhpStorm.
 * User: TinyZ
 * Date: 2015/6/25
 * Time: 23:13
 */
class SimpleFileUtil {

    /**
     * Load data file
     * @param string $filePath
     * @return null|string
     */
    public static function loadFile($filePath) {
        $fp = fopen($filePath, 'r');
        if ($fp != null) {
            $line = '';
            while (!feof($fp)) {
                $line .= trim(fgets($fp));
            }
            fclose($fp);
            return $line;
        }
        return null;
    }

    /**
     * Simple util load file
     * @param string $filePath The data file path
     * @return array|null
     */
    public static function loadByLine($filePath) {
        $fp = fopen($filePath, 'r');
        if ($fp != null) {
            $list = array();
            while (!feof($fp)) {
                $line = trim(fgets($fp));
                if ($line != '[' && $line != ']') {
                    array_push($list, json_decode(substr($line, 0, strlen($line) - 1), true));
                }
            }
            fclose($fp);
            return $list;
        }
        return null;
    }
}
/* End of file SimpleFileUtil.php */