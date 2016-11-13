<?php

namespace Jxc\Impl\Util;

/**
 * Global game utility
 */
final class GameUtil {

    /**
     * Generate unique id by platform_serverId tag and random numeric
     * @param string $tag
     * @return string Return a unique id. Example : pc_s1_83591_*************
     */
    public static function id($tag = null) {
        if ($tag == null) {
            $prefix = mt_rand(10000, 99999);
        } else {
            $prefix = $tag . '_' . mt_rand(10000, 99999);
        }
        return uniqid($prefix . '_');
    }

    /**
     * Verify is the request has params
     * @param array $request The command request
     * @param array $params All verify filed 's array
     * @return bool|string. Return false if the $request have all params defined in $params. otherwise return the missing fieldName
     */
    public static function verifyRequestParams($request, $params) {
        if (is_array($params)) {
            foreach ($params as $keyIndex => $fieldName) {
                if (!isset($request[$fieldName])) {
                    return $fieldName;
                }
            }
        }
        return false;
    }

    /**
     * 写入ini文件
     * @param $filename
     * @param $ini_data
     * @param int $flags
     * @return bool|int
     *
     * @modify TinyZ
     * @author triple-j
     * @link https://github.com/triple-j/PHP_Write_INI_File/blob/master/write_ini_file.php
     */
    public static function write_ini_file($filename, $ini_data, $flags = 0) {
        $ini_string = self::build_ini_string($ini_data);
        return $ini_string ? file_put_contents($filename, $ini_string, $flags) : false;
    }

    public static function build_ini_string($ini_data) {
        $ini_string = "";
        $add_newline = false;
        if (!is_array($ini_data) || empty($ini_data)) {
            return false;
        }
        // sort array making sure values not in sections are at the top
        foreach ($ini_data as $key => $val) {
            if (is_array($val)) {
                unset($ini_data[$key]);
                $ini_data[$key] = $val;
            }
        }
        // build ini string
        foreach ($ini_data as $key => $val) {
            if (is_array($val)) {
                // section
                if ($add_newline) {
                    $ini_string .= PHP_EOL;
                    $add_newline = false;
                }
                $ini_string .= "[$key]" . PHP_EOL;
                foreach ($val as $sub_key => $sub_val) {
                    if (is_array($sub_val)) {
                        // array
                        if (array_keys($sub_val) === range(0, count($sub_val) - 1)) {
                            // sequential array
                            foreach ($sub_val as $seq_val) {
                                $ini_string .= "{$sub_key}[] = " . (is_numeric($seq_val) ? $seq_val : "\"$seq_val\"") . PHP_EOL;
                            }
                        } else {
                            // associative array
                            foreach ($sub_val as $assoc_key => $assoc_val) {
                                $ini_string .= "{$sub_key}[{$assoc_key}] = " . (is_numeric($assoc_val) ? $assoc_val : "\"$assoc_val\"") . PHP_EOL;
                            }
                        }
                    } elseif (empty($sub_val)) {
                        // empty
                        $ini_string .= "$sub_key = " . PHP_EOL;
                    } else {
                        // string or number
                        $ini_string .= "$sub_key = " . (is_numeric($sub_val) ? $sub_val : "\"$sub_val\"") . PHP_EOL;
                    }
                }
            } else {
                // not in a section
                $ini_string .= "$key = " . (is_numeric($val) ? $val : "\"$val\"") . PHP_EOL;
            }
            $add_newline = true;
        }
        return $ini_string;
    }
}
/* End of file DataUtil.php */