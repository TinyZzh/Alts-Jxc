<?php

namespace Jxc\Impl\Libs;

class DateUtil {

    /**
     * 获取当前时间
     * @return bool|string
     */
    public static function makeTime() {
        return date("Y-m-d H:i:s");
    }

    public static function specTime($timestamp = null) {
        return date("Y-m-d H:i:s", $timestamp);
    }

    /**
     * 获取当前日
     * @return int The day of the month
     * */
    public static function day() {
        return date("d");
    }

    /**
     * 获取日期
     * @param int $time 时间
     * @return bool|string
     */
    public static function dateTime($time = null) {
        if ($time) {
            return date("Y-m-d", $time);
        }
        return date("Y-m-d");
    }

    /**
     * 是否通过某时间
     * @param int $timeLastRefresh 上一次刷新时间【时间戳】
     * @param array $points 【时间点数组】
     * @param int $time_now 当前时间 【时间戳】
     * @return bool
     */
    public static function isThrowTime($timeLastRefresh, $points, $time_now = null) {
        $isCanRefresh = FALSE;
        if ($timeLastRefresh == null) {
            $isCanRefresh = TRUE;
        } else {
            if ($time_now == null) {
                $time_now = time();
            }
            if ($timeLastRefresh < $time_now) {
                $pCount = count($points);
                for ($i = 0; $i < $pCount; $i++) {
                    $str_time = is_numeric($points[$i]) ? $points[$i] . ':00:00' : $points[$i];
                    // 跨天
                    $date_last = date("Y-m-d", $timeLastRefresh);
                    if ($date_last != date("Y-m-d", $time_now)) {
                        $str_time = $date_last . ' ' . $str_time;
                    }
                    $point_time = strtotime($str_time);
                    if ($timeLastRefresh < $point_time && $time_now >= $point_time) {
                        $isCanRefresh = TRUE;
                        break;
                    }
                }
            }
        }
        return $isCanRefresh;
    }

    /**
     * 是否在当天某个时间段
     * @param array $points 时间段数组, 数组长度必须是2的整数倍
     * @param int $timestamp 时间(时间戳)
     * @param bool $isNumericPoint
     * @return bool
     */
    public static function isBetweenTime($points, $timestamp = 0, $isNumericPoint = false) {
        if ($timestamp == 0) {
            $timestamp = time();
        }
        $pCount = count($points);
        if ($isNumericPoint) {
            for ($i = 0; $i < $pCount; $i += 2) {
                $time_a = strtotime($points[$i].':00:00');
                $time_b = strtotime($points[$i + 1].':00:00');
                if (($timestamp < $time_a) || ($timestamp > $time_b)) {
                    return false;
                }
            }
        } else {
            for ($i = 0; $i < $pCount; $i += 2) {
                $time_a = strtotime($points[$i]);
                $time_b = strtotime($points[$i + 1]);
                if (($timestamp < $time_a) || ($timestamp > $time_b)) {
                    return false;
                }
            }
        }
        return true;
    }
}