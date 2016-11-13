<?php
namespace Jxc\Impl\Util;
/**
 * AES加密工具
 */
class AES {

    const AES_KEY = 'lingjingdnsg2015';

    const IV = 'lingjingdnsg2015';

    public static function encrypt($data, $key) {
//        $data = AES::padding($data);
        return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, self::IV));
    }

    public static function decrypt($data, $key) {
//        $data = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, base64_decode($data), MCRYPT_MODE_CBC, self::IV);
//        return AES::packing($data);
        return mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, base64_decode($data), MCRYPT_MODE_CBC, self::IV);
    }


//    public static function encrypt($data, $key, $algorithm = 'rijndael-128', $mode = 'cbc') {
//        $module = mcrypt_module_open($algorithm, '', $mode, '');
//        mcrypt_generic_init($module, $key, self::IV);
//        //Padding
//        $block = mcrypt_get_block_size($algorithm, $mode); //Get Block Size
//        $pad = $block - (strlen($data) % $block); //Compute how many characters need to pad
//        $data .= str_repeat(chr($pad), $pad); // After pad, the str length must be equal to block or its integer multiples
//        //Encrypt
//        $encrypted = mcrypt_generic($module, $data);
//        mcrypt_generic_deinit($module);
//        mcrypt_module_close($module);
//        return base64_encode($encrypted);
//    }
//
//    public static function decrypt($data, $key, $algorithm = 'rijndael-128', $mode = 'cbc') {
//        $module = mcrypt_module_open($algorithm, '', $mode, '');
//        mcrypt_generic_init($module, $key, self::IV);
//        //Decrypt
//        $data = mdecrypt_generic($module, base64_decode($data)); //Get original str
//        mcrypt_generic_deinit($module);
//        mcrypt_module_close($module);
//        //Depadding
//        $slast = ord(substr($data, -1)); //pad value and pad count
//        return substr($data, 0, strlen($data) - $slast);
//    }

    /**
     *
     * @param $data
     * @param string $algorithm
     * @param string $mode
     * @return string
     */
    public static function padding($data, $algorithm = 'rijndael-128', $mode = 'cbc'){
        $block = mcrypt_get_block_size($algorithm, $mode);
        $len = strlen($data);
        $padding = $block - ($len % $block);
        $data .= str_repeat(chr($padding),$padding);
        return $data;
    }

    public static function packing($data, $algorithm = 'rijndael-128', $mode = 'cbc'){
        $block = mcrypt_get_block_size($algorithm, $mode);
//        var_dump($block);
        $packing = ord($data{strlen($data) - 1});
//        var_dump($packing);
        if($packing and ($packing < $block)){
            for($P = strlen($data) - 1; $P >= strlen($data) - $packing; $P--){
                if(ord($data{$P}) != $packing){
                    $packing = 0;
                }
            }
        }
        return substr($data,0,strlen($data) - $packing);
    }
}
?>