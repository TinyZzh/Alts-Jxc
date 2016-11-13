<?php

namespace Jxc\Impl\Util;

/**
 * IP address
 */
final class IP {

    /**
     * Get client real ip address
     * */
    public static function clientIP() {
        $ip = getenv("REMOTE_ADDR");
        if (isset($_SERVER)) {
            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
                $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
            } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
                $ip = $_SERVER["HTTP_CLIENT_IP"];
            } else {
                $ip = $_SERVER["REMOTE_ADDR"];
            }
        } else {
            if (getenv("HTTP_X_FORWARDED_FOR")) {
                $ip = getenv("HTTP_X_FORWARDED_FOR");
            } else if (getenv("HTTP_CLIENT_IP")) {
                $ip = getenv("HTTP_CLIENT_IP");
            }
//            else {
//                $ip = getenv("REMOTE_ADDR");
//            }
        }
        return current(explode(',', $ip));
    }
}
/* End of file IP.php */